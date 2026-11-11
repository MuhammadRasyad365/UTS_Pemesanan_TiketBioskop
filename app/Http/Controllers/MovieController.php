<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    public function show($id)
    {
        $movie = Movie::with([
            'screenings' => function ($query) {
                $query->whereDate('show_date', '>=', today())
                    ->where('status', 'available')
                    ->orderBy('show_date')
                    ->orderBy('start_time');
            }
        ])->findOrFail($id);

        return view('detail', compact('movie'));
    }
}