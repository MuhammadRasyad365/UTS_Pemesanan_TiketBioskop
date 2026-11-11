<?php

namespace App\Http\Controllers;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with([
                'screening.movie',
                'seats',
            ])
            ->latest()
            ->get();

        return view('customer.dashboard', compact('bookings'));
    }
}