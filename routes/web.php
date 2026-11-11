<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ScreeningController as AdminScreeningController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// ========================================
// HALAMAN UMUM
// ========================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/film/{id}', [MovieController::class, 'show'])
    ->name('movies.detail');

// QR verification menggunakan signed URL.
Route::get('/ticket/verify/{code}', [
    BookingController::class,
    'verify'
])->middleware('signed')
  ->name('ticket.verify');


// ========================================
// LOGIN DAN REGISTER
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.store');

    Route::get('/register', [
        AuthController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        AuthController::class,
        'register'
    ])->name('register.store');

});


// ========================================
// HALAMAN PENGGUNA YANG SUDAH LOGIN
// ========================================

Route::middleware('auth')->group(function () {

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');


    // CUSTOMER SAJA
    Route::middleware('role:customer')->group(function () {

        Route::get('/dashboard', [
            CustomerDashboardController::class,
            'index'
        ])->name('customer.dashboard');

        Route::get('/booking/{screening}', [
            BookingController::class,
            'create'
        ])->name('booking.create');

        Route::post('/booking', [
            BookingController::class,
            'store'
        ])->name('booking.store');

    });


    // ADMIN SAJA
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            // Dashboard Admin
            Route::get('/dashboard', [
                AdminDashboardController::class,
                'index'
            ])->name('dashboard');

            // CRUD FILM
            Route::resource('movies', AdminMovieController::class)
                ->except(['show']);

            // CRUD JADWAL
            Route::resource('screenings', AdminScreeningController::class)
                ->except(['show']);

            // MANAJEMEN BOOKING ADMIN (Duplikasi dihapus)
            Route::get('/bookings', [
                AdminBookingController::class,
                'index'
            ])->name('bookings.index');

            Route::get('/bookings/{booking}', [
                AdminBookingController::class,
                'show'
            ])->name('bookings.show');

            Route::patch('/bookings/{booking}/status', [
                AdminBookingController::class,
                'updateStatus'
            ])->name('bookings.status');

            Route::delete('/bookings/{booking}', [
                AdminBookingController::class,
                'destroy'
            ])->name('bookings.destroy');

        });


    // E-TICKET UNTUK PEMILIK BOOKING ATAU ADMIN
    Route::get('/ticket/{booking}', [
        BookingController::class,
        'ticket'
    ])->name('booking.ticket');

});