<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Booking;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalMovies = Movie::count();

        $totalScreenings = Screening::count();

        $totalBookings = Booking::count();

        $totalCustomers = User::where(
            'role',
            'customer'
        )->count();

        $totalRevenue = Booking::where(
            'status',
            'confirmed'
        )->sum('total_price');

        $latestBookings = Booking::with([
            'user',
            'screening.movie',
            'seats',
        ])
        ->latest()
        ->take(10)
        ->get();

        return view('admin.dashboard', compact(
            'totalMovies',
            'totalScreenings',
            'totalBookings',
            'totalCustomers',
            'totalRevenue',
            'latestBookings'
        ));
    }
}
