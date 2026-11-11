<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'title' => 'Langit Terakhir',
                'slug' => 'langit-terakhir',
                'genre' => 'Drama, Adventure',
                'duration' => 120,
                'age_rating' => '13+',
                'synopsis' => 'Kisah perjalanan seorang pemuda dalam menemukan harapan dan arti kehidupan.',
                'poster_url' => null,
                'release_date' => '2026-11-01',
                'rating' => 8.5,
                'ticket_price' => 45000,
                'status' => 'now_showing',
            ],
            [
                'title' => 'Operasi Senja',
                'slug' => 'operasi-senja',
                'genre' => 'Action, Thriller',
                'duration' => 135,
                'age_rating' => '17+',
                'synopsis' => 'Seorang agen rahasia harus menghentikan operasi berbahaya sebelum terlambat.',
                'poster_url' => null,
                'release_date' => '2026-11-03',
                'rating' => 8.8,
                'ticket_price' => 50000,
                'status' => 'now_showing',
            ],
            [
                'title' => 'Suara dari Lorong',
                'slug' => 'suara-dari-lorong',
                'genre' => 'Horror, Mystery',
                'duration' => 110,
                'age_rating' => '17+',
                'synopsis' => 'Misteri suara aneh dari lorong tua membawa sekelompok mahasiswa pada kejadian mengerikan.',
                'poster_url' => null,
                'release_date' => '2026-11-05',
                'rating' => 8.2,
                'ticket_price' => 40000,
                'status' => 'now_showing',
            ],
            [
                'title' => 'Pulau Awan',
                'slug' => 'pulau-awan',
                'genre' => 'Animation, Adventure',
                'duration' => 100,
                'age_rating' => 'SU',
                'synopsis' => 'Petualangan anak-anak menjelajahi pulau ajaib di atas awan.',
                'poster_url' => null,
                'release_date' => '2026-11-07',
                'rating' => 9.0,
                'ticket_price' => 35000,
                'status' => 'now_showing',
            ],
            [
                'title' => 'Kode Merah',
                'slug' => 'kode-merah',
                'genre' => 'Sci-Fi, Action',
                'duration' => 125,
                'age_rating' => '13+',
                'synopsis' => 'Teknologi masa depan menjadi ancaman bagi kehidupan manusia.',
                'poster_url' => null,
                'release_date' => '2026-11-18',
                'rating' => 0,
                'ticket_price' => 50000,
                'status' => 'coming_soon',
            ],
            [
                'title' => 'Janji di Musim Hujan',
                'slug' => 'janji-di-musim-hujan',
                'genre' => 'Romance, Drama',
                'duration' => 115,
                'age_rating' => '13+',
                'synopsis' => 'Kisah dua insan yang kembali bertemu setelah bertahun-tahun berpisah.',
                'poster_url' => null,
                'release_date' => '2026-11-25',
                'rating' => 0,
                'ticket_price' => 40000,
                'status' => 'coming_soon',
            ],
        ];

        foreach ($movies as $movie) {
            Movie::updateOrCreate(
                ['slug' => $movie['slug']],
                $movie
            );
        }
    }
}
