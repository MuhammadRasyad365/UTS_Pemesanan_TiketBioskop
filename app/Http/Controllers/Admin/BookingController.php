<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    // ======================================
    // 1. DAFTAR SELURUH TRANSAKSI
    // ======================================

    public function index(Request $request)
    {
        $query = Booking::with([
            'user',
            'screening.movie',
            'seats',
        ])->latest();

        // Pencarian
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'customer_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'customer_email',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'screening.movie',
                    function ($movieQuery) use ($search) {
                        $movieQuery->where(
                            'title',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal transaksi
        if ($request->filled('date')) {
            $query->whereDate(
                'booked_at',
                $request->date
            );
        }

        $bookings = $query
            ->paginate(15)
            ->withQueryString();

        // Statistik seluruh transaksi.
        $totalBookings = Booking::count();

        $confirmedBookings = Booking::where(
            'status',
            'confirmed'
        )->count();

        $cancelledBookings = Booking::where(
            'status',
            'cancelled'
        )->count();

        $usedBookings = Booking::where(
            'status',
            'used'
        )->count();

        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'confirmedBookings',
            'cancelledBookings',
            'usedBookings'
        ));
    }


    // ======================================
    // 2. DETAIL TRANSAKSI
    // ======================================

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'screening.movie',
            'seats',
        ]);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }


    // ======================================
    // 3. MEMPERBARUI STATUS BOOKING
    // ======================================

    public function updateStatus(
        Request $request,
        Booking $booking
    ) {

        $data = $request->validate([
            'status' => [
                'required',
                'in:confirmed,cancelled,used',
            ],
        ]);

        DB::transaction(function () use ($booking, $data) {

            // Urutan penguncian mengikuti proses booking:
            // jadwal lebih dahulu, kemudian transaksi.
            $screening = Screening::whereKey(
                $booking->screening_id
            )->lockForUpdate()->firstOrFail();

            $lockedBooking = Booking::whereKey(
                $booking->id
            )->lockForUpdate()->firstOrFail();

            $currentStatus = $lockedBooking->status;
            $newStatus = $data['status'];

            // Tidak perlu memproses status yang sama.
            if ($currentStatus === $newStatus) {
                return;
            }

            // Tiket yang sudah digunakan tidak dapat
            // dibatalkan atau dikembalikan menjadi confirmed.
            if ($currentStatus === 'used') {
                throw ValidationException::withMessages([
                    'status' =>
                        'Tiket yang sudah digunakan tidak dapat diubah kembali.',
                ]);
            }

            // ----------------------------------
            // A. MENANDAI TIKET SUDAH DIGUNAKAN
            // ----------------------------------

            if ($newStatus === 'used') {

                if ($currentStatus !== 'confirmed') {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hanya booking confirmed yang dapat ditandai used.',
                    ]);
                }

                $lockedBooking->update([
                    'status' => 'used',
                ]);

                return;
            }

            // ----------------------------------
            // B. MEMBATALKAN BOOKING
            // ----------------------------------

            if ($newStatus === 'cancelled') {

                if ($currentStatus !== 'confirmed') {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hanya booking confirmed yang dapat dibatalkan.',
                    ]);
                }

                // Pastikan salinan kursi tersimpan.
                $seatCodes = $lockedBooking->seat_codes_list;

                // Tandai transaksi dibatalkan.
                $lockedBooking->update([
                    'seat_codes' => $seatCodes,
                    'status' => 'cancelled',
                ]);

                // Melepaskan kursi agar dapat dipesan kembali.
                BookingSeat::where(
                    'booking_id',
                    $lockedBooking->id
                )->delete();

                return;
            }

            // ----------------------------------
            // C. MENGAKTIFKAN KEMBALI BOOKING
            // ----------------------------------

            if ($newStatus === 'confirmed') {

                if ($currentStatus !== 'cancelled') {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Hanya booking cancelled yang dapat diaktifkan kembali.',
                    ]);
                }

                if ($screening->status !== 'available') {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Jadwal penayangan tidak tersedia.',
                    ]);
                }

                $seatCodes = $lockedBooking->seat_codes_list;

                if (empty($seatCodes)) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Riwayat kursi tidak ditemukan.',
                    ]);
                }

                // Memeriksa apakah kursi sudah dipesan
                // oleh transaksi lain.
                $conflict = BookingSeat::where(
                    'screening_id',
                    $screening->id
                )
                ->whereIn('seat_code', $seatCodes)
                ->exists();

                if ($conflict) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Sebagian kursi sudah dipesan pelanggan lain.',
                    ]);
                }

                // Mengembalikan kursi ke transaksi.
                foreach ($seatCodes as $seatCode) {

                    BookingSeat::create([
                        'booking_id' => $lockedBooking->id,
                        'screening_id' => $screening->id,
                        'seat_code' => $seatCode,
                    ]);
                }

                $lockedBooking->update([
                    'status' => 'confirmed',
                    'seat_codes' => $seatCodes,
                ]);
            }

        });

        return back()->with(
            'success',
            'Status transaksi berhasil diperbarui.'
        );
    }


    // ======================================
    // 4. MENGHAPUS TRANSAKSI YANG DIBATALKAN
    // ======================================

    public function destroy(Booking $booking)
    {
        if ($booking->status !== 'cancelled') {
            return back()->with(
                'error',
                'Hanya transaksi cancelled yang dapat dihapus.'
            );
        }

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with(
                'success',
                'Data transaksi berhasil dihapus.'
            );
    }
}
