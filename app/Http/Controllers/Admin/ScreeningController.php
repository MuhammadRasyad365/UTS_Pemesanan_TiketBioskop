<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    // 1. MENAMPILKAN SELURUH JADWAL
    public function index(Request $request)
    {

        $query = Screening::with('movie')
            ->withCount('bookings')
            ->orderBy('show_date')
            ->orderBy('start_time');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('movie', function ($movieQuery) use ($search) {
                    $movieQuery->where(
                        'title',
                        'like',
                        "%{$search}%"
                    );
                });

                $q->orWhere(
                    'studio_name',
                    'like',
                    "%{$search}%"
                );

            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $screenings = $query
            ->paginate(12)
            ->withQueryString();

        return view(
            'admin.screenings.index',
            compact('screenings')
        );
    }

    // 2. FORM TAMBAH JADWAL
    public function create()
    {
        $movies = Movie::orderBy('title')->get();

        $screening = new Screening([
            'ticket_price' => 50000,
            'status' => 'available',
        ]);

        return view(
            'admin.screenings.create',
            compact('movies', 'screening')
        );
    }

    // 3. MENYIMPAN JADWAL
    public function store(Request $request)
    {
        $data = $this->validateSchedule($request);

        Screening::create($data);

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    // 4. FORM EDIT JADWAL
    public function edit(Screening $screening)
    {
        if ($screening->bookings()->exists()) {
            return redirect()
                ->route('admin.screenings.index')
                ->with(
                    'error',
                    'Jadwal tidak dapat diedit karena sudah memiliki transaksi.'
                );
        }

        $movies = Movie::orderBy('title')->get();

        return view(
            'admin.screenings.edit',
            compact('screening', 'movies')
        );
    }

    // 5. MEMPERBARUI JADWAL
    public function update(
        Request $request,
        Screening $screening
    ) {
        if ($screening->bookings()->exists()) {
            return redirect()
                ->route('admin.screenings.index')
                ->with(
                    'error',
                    'Jadwal yang sudah memiliki transaksi tidak boleh diubah. Kelola transaksi pelanggan terlebih dahulu.'
                );
        }

        $data = $this->validateSchedule(
            $request,
            $screening->id
        );

        $screening->update($data);

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    // 6. MENGHAPUS JADWAL
    public function destroy(Screening $screening)
    {
        if ($screening->bookings()->exists()) {
            return back()->with(
                'error',
                'Jadwal tidak dapat dihapus karena sudah memiliki transaksi.'
            );
        }

        $screening->delete();

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    // 7. VALIDASI DATA DAN BENTROK JADWAL
    private function validateSchedule(
        Request $request,
        ?int $ignoreId = null
    ): array {

        $data = $request->validate([
            'movie_id' => [
                'required',
                'exists:movies,id',
            ],

            'studio_name' => [
                'required',
                'string',
                'max:50',
            ],

            'show_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'ticket_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:available,cancelled',
            ],
        ]);

        $movie = Movie::findOrFail($data['movie_id']);

        $start = Carbon::createFromFormat(
            'H:i',
            $data['start_time']
        );

        // Jika jam selesai kosong, hitung dari durasi film
        // ditambah 15 menit untuk pergantian penonton.
        if (!empty($data['end_time'])) {
            $end = Carbon::createFromFormat(
                'H:i',
                $data['end_time']
            );
        } else {
            $end = $start->copy()->addMinutes(
                (int) $movie->duration + 15
            );
        }

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'end_time' =>
                    'Jam selesai harus lebih besar dari jam mulai dan masih pada hari yang sama.',
            ]);
        }

        // Memeriksa jadwal lain di studio dan tanggal yang sama.
        $existingSchedules = Screening::with('movie')
            ->where('studio_name', $data['studio_name'])
            ->whereDate('show_date', $data['show_date'])
            ->when(
                $ignoreId,
                fn ($query) => $query->where('id', '!=', $ignoreId)
            )
            ->get();

        foreach ($existingSchedules as $existing) {
            $oldStart = Carbon::parse($existing->start_time);

            $oldEnd = $existing->end_time
                ? Carbon::parse($existing->end_time)
                : $oldStart->copy()->addMinutes(
                    (int) $existing->movie->duration + 15
                );

            $isOverlapping =
                $start->lessThan($oldEnd) &&
                $end->greaterThan($oldStart);

            if ($isOverlapping) {
                throw ValidationException::withMessages([
                    'start_time' =>
                        'Jadwal bertabrakan dengan penayangan lain di studio yang sama.',
                ]);
            }
        }

        $data['end_time'] = $end->format('H:i:s');

        return $data;
    }
}
