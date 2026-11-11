<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Screening;

class ScreeningSeeder extends Seeder
{
    public function run(): void
    {
        $movies = Movie::where('status', 'now_showing')
            ->orderBy('id')
            ->get();

        $showTimes = [
            '12:00:00',
            '15:00:00',
            '18:00:00',
        ];

        foreach ($movies as $index => $movie) {

            // Contoh setiap film memiliki studio sendiri.
            $studio = 'Studio ' . ($index + 1);

            for ($day = 1; $day <= 3; $day++) {

                $date = now()
                    ->addDays($day)
                    ->toDateString();

                foreach ($showTimes as $time) {

                    Screening::updateOrCreate(
                        [
                            'studio_name' => $studio,
                            'show_date' => $date,
                            'start_time' => $time,
                        ],
                        [
                            'movie_id' => $movie->id,
                            'ticket_price' => $movie->ticket_price,
                            'status' => 'available',
                        ]
                    );
                }
            }
        }
    }
}
