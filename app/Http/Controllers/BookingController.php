<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    // =====================================
    // 1. MENAMPILKAN HALAMAN PEMILIHAN KURSI
    // =====================================

    public function create(Screening $screening)
    {
        abort_unless(
            $screening->status === 'available',
            404
        );

        $screening->load('movie');

        $bookedSeats = BookingSeat::where(
            'screening_id',
            $screening->id
        )
        ->whereHas('booking', function ($query) {
            $query->whereIn('status', [
                'confirmed',
                'used',
            ]);
        })
        ->pluck('seat_code')
        ->all();

        return view('booking', compact(
            'screening',
            'bookedSeats'
        ));
    }


    // =====================================
    // 2. MENYIMPAN DATA PEMESANAN
    // =====================================

    public function store(Request $request)
    {
        $data = $request->validate([

            'screening_id' => [
                'required',
                'exists:screenings,id'
            ],

            'customer_name' => [
                'required',
                'string',
                'max:255'
            ],

            'customer_email' => [
                'required',
                'email',
                'max:255'
            ],

            'customer_phone' => [
                'nullable',
                'string',
                'max:25'
            ],

            'seat_codes' => [
                'required',
                'array',
                'min:1',
                'max:8'
            ],

            'seat_codes.*' => [
                'required',
                'string',
                'distinct',
                'regex:/^[A-F][1-8]$/'
            ],

        ]);

        $booking = DB::transaction(function () use ($data) {

            // Mengunci jadwal selama transaksi berlangsung.
            $screening = Screening::whereKey(
                $data['screening_id']
            )->lockForUpdate()->firstOrFail();

            if ($screening->status !== 'available') {
                throw ValidationException::withMessages([
                    'screening_id' => 'Jadwal tidak tersedia.'
                ]);
            }

            $seatCodes = array_values(
                array_unique($data['seat_codes'])
            );

            // Memeriksa kursi yang sudah dipesan (Diperbaiki: duplikasi query dihapus).
            $occupiedSeats = BookingSeat::where(
                'screening_id',
                $screening->id
            )
            ->whereIn('seat_code', $seatCodes)
            ->pluck('seat_code')
            ->all();

            if (count($occupiedSeats) > 0) {
                throw ValidationException::withMessages([
                    'seat_codes' =>
                        'Kursi sudah dipesan: ' .
                        implode(', ', $occupiedSeats)
                ]);
            }

            // Menghitung total pembayaran.
            $totalPrice =
                (float) $screening->ticket_price *
                count($seatCodes);

            // Membuat data booking.
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'screening_id' => $screening->id,
                'booking_code' => 'ELC-' .
                    now()->format('YmdHis') . '-' .
                    Str::upper(Str::random(5)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'total_price' => $totalPrice,
                'seat_codes' => $seatCodes,
                'status' => 'confirmed',
                'booked_at' => now(),
            ]);
            
            // Menyimpan kursi yang dipilih.
            foreach ($seatCodes as $seatCode) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'screening_id' => $screening->id,
                    'seat_code' => $seatCode,
                ]);
            }

            return $booking;

        });

        // Mengarahkan pelanggan ke E-Ticket.
        return redirect()->route(
            'booking.ticket',
            $booking->id
        );
    }


    // =====================================
    // 3. MENAMPILKAN E-TICKET DAN QR CODE
    // =====================================

        public function ticket(Booking $booking)
        {
            $user = auth()->user();

            abort_unless(
                $user &&
                (
                    $user->isAdmin() ||
                    (int) $booking->user_id === (int) $user->id
                ),
                403
            );

        $booking->load([
            'screening.movie',
            'seats',
        ]);

        // Membuat URL verifikasi bertanda tangan.
        $verifyUrl = URL::temporarySignedRoute(
            'ticket.verify',
            now()->addYears(5),
            [
                'code' => $booking->booking_code,
            ]
        );

        // Membuat QR Code dalam format SVG.
        $qrCode = QrCode::format('svg')
            ->size(220)
            ->margin(1)
            ->generate($verifyUrl);

        return view('ticket', compact(
            'booking',
            'qrCode',
            'verifyUrl'
        ));
    }


    // =====================================
    // 4. VERIFIKASI QR CODE
    // =====================================

    public function verify($code)
    {
        $booking = Booking::with([
            'screening.movie',
            'seats',
        ])
        ->where('booking_code', $code)
        ->firstOrFail();

        return view('ticket-verify', compact(
            'booking'
        ));
    }

}