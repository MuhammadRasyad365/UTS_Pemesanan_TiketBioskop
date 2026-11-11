<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        // Film yang sedang tayang.
        $nowShowing = Movie::where('status', 'now_showing')
            ->orderByDesc('rating')
            ->orderBy('title')
            ->get();

        // Film yang akan segera tayang.
        $comingSoon = Movie::where('status', 'coming_soon')
            ->orderBy('release_date')
            ->orderBy('title')
            ->get();

        // Film unggulan untuk banner utama.
        $featuredMovie = $nowShowing->first()
            ?? $comingSoon->first();

        // Statistik katalog.
        $totalMovies = Movie::count();

        return view('home', compact(
            'nowShowing',
            'comingSoon',
            'featuredMovie',
            'totalMovies'
        ));
    }
}
