<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MovieController extends Controller
{
    // =====================================
    // 1. DAFTAR FILM
    // =====================================

    public function index(Request $request)
    {
        $query = Movie::withCount('screenings')->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $movies = $query->paginate(10)->withQueryString();

        return view('admin.movies.index', compact('movies'));
    }


    // =====================================
    // 2. FORM TAMBAH FILM
    // =====================================

    public function create()
    {
        $movie = new Movie();

        return view('admin.movies.create', compact('movie'));
    }


    // =====================================
    // 3. SIMPAN FILM BARU
    // =====================================

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $poster = $data['poster'] ?? null;

        unset($data['poster']);

        // Membuat slug unik.
        $data['slug'] = $this->uniqueSlug($data['title']);

        // Upload poster dari komputer.
        if ($poster) {

            $data['poster_url'] = $poster->store(
                'posters',
                'public'
            );

        } else {

            // Alternatif menggunakan URL gambar.
            $data['poster_url'] = $data['poster_url'] ?? null;
        }

        Movie::create($data);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film dan poster berhasil ditambahkan.');
    }


    // =====================================
    // 4. FORM EDIT FILM
    // =====================================

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }


    // =====================================
    // 5. UPDATE FILM
    // =====================================

    public function update(Request $request, Movie $movie)
    {
        $data = $this->validatedData($request);

        $poster = $data['poster'] ?? null;

        unset($data['poster']);

        $oldPoster = $movie->poster_url;

        // Memperbarui slug hanya jika judul berubah.
        if ($movie->title !== $data['title']) {

            $data['slug'] = $this->uniqueSlug(
                $data['title'],
                $movie->id
            );
        }

        // Pilihan pertama: upload gambar baru.
        if ($poster) {

            $data['poster_url'] = $poster->store(
                'posters',
                'public'
            );

        }

        // Pilihan kedua: mengganti poster menggunakan URL.
        elseif ($request->filled('poster_url')) {

            $data['poster_url'] = $request->input('poster_url');

        }

        // Pilihan ketiga: menghapus poster.
        elseif ($request->boolean('remove_poster')) {

            $data['poster_url'] = null;

        }

        // Jika tidak ada perubahan poster,
        // pertahankan gambar lama.
        else {

            unset($data['poster_url']);

        }

        $movie->update($data);

        // Menghapus file lama setelah gambar baru berhasil disimpan.
        if (
            $oldPoster !== $movie->poster_url
        ) {
            $this->deleteLocalPoster($oldPoster);
        }

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film berhasil diperbarui.');
    }


    // =====================================
    // 6. HAPUS FILM
    // =====================================

    public function destroy(Movie $movie)
    {
        // Film yang sudah memiliki jadwal tidak dihapus.
        if ($movie->screenings()->exists()) {

            return back()->with(
                'error',
                'Film tidak dapat dihapus karena sudah memiliki jadwal penayangan.'
            );
        }

        $oldPoster = $movie->poster_url;

        $movie->delete();

        // Menghapus file poster lokal jika ada.
        $this->deleteLocalPoster($oldPoster);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film dan poster berhasil dihapus.');
    }


    // =====================================
    // 7. VALIDASI INPUT
    // =====================================

    private function validatedData(Request $request): array
    {
        return $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'genre' => [
                'required',
                'string',
                'max:100',
            ],

            'duration' => [
                'required',
                'integer',
                'min:1',
            ],

            'age_rating' => [
                'required',
                'string',
                'max:10',
            ],

            'synopsis' => [
                'nullable',
                'string',
            ],

            // Upload gambar dari komputer.
            'poster' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            // Alternatif poster menggunakan URL.
            'poster_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'release_date' => [
                'nullable',
                'date',
            ],

            'rating' => [
                'nullable',
                'numeric',
                'min:0',
                'max:10',
            ],

            'ticket_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'now_showing',
                    'coming_soon',
                    'ended',
                ]),
            ],

            'remove_poster' => [
                'nullable',
                'boolean',
            ],

        ]);
    }


    // =====================================
    // 8. MEMBUAT SLUG UNIK
    // =====================================

    private function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {

        $base = Str::slug($title) ?: 'film';

        $slug = $base;

        $counter = 1;

        while (
            Movie::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {

            $slug = $base . '-' . $counter++;

        }

        return $slug;
    }


    // =====================================
    // 9. MENGHAPUS POSTER LOKAL
    // =====================================

    private function deleteLocalPoster(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Jangan menghapus gambar dari URL eksternal.
        if (
            Str::startsWith($path, [
                'http://',
                'https://',
            ])
        ) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
