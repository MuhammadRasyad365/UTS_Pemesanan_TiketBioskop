
@extends('layouts.app')

@section('title', 'Customer Dashboard - ELCinema')

@section('content')

<div class="dashboard-page">

    <div class="dashboard-heading">
        <span>ELCINEMA CUSTOMER AREA</span>

        <h1>Halo, {{ auth()->user()->name }}!</h1>

        <p>
            Selamat datang kembali. Nikmati pengalaman
            menonton bersama ELCinema.
        </p>
    </div>

    <div class="customer-banner">
        <div>
            <span>YOUR CINEMA EXPERIENCE</span>
            <h2>Temukan Film Favoritmu</h2>
            <p>Pesan tiket bioskop dengan mudah dan cepat.</p>

            <a href="{{ route('home') }}">
                LIHAT DAFTAR FILM →
            </a>
        </div>
        <div class="banner-icon">🎬</div>
    </div>

    <h2 class="section-heading">Riwayat Pemesanan Saya</h2>

    @forelse($bookings as $booking)

        <div class="customer-booking">

            <div class="booking-main">
                <span class="booking-label">BOOKING ID</span>

                <strong>{{ $booking->booking_code }}</strong>

                <h3>{{ $booking->screening->movie->title }}</h3>

                <p>
                    {{ $booking->screening->show_date->format('d M Y') }}
                    |
                    {{ substr($booking->screening->start_time, 0, 5) }}
                    WIB
                </p>

                <p>
                    Kursi:
                    {{ collect($booking->seat_codes_list)->join(', ') }}
                </p>
            </div>

            <div class="booking-side">

                <strong class="booking-total">
                    Rp{{ number_format(
                        (float)$booking->total_price,
                        0, ',', '.'
                    ) }}
                </strong>

                <span class="booking-status">
                    {{ strtoupper($booking->status) }}
                </span>

                @if($booking->status === 'confirmed')
                    <a href="{{ route('booking.ticket', $booking->id) }}">
                        LIHAT E-TICKET
                    </a>
                @endif

            </div>

        </div>

    @empty

        <div class="empty-bookings">
            <div>🎟️</div>
            <h3>Belum Ada Pemesanan</h3>
            <p>Kamu belum mempunyai transaksi tiket.</p>

            <a href="{{ route('home') }}">
                PESAN TIKET SEKARANG
            </a>
        </div>

    @endforelse

</div>

<style>
.dashboard-page {
    width:90%;
    max-width:1150px;
    margin:auto;
    padding:45px 0 70px;
    color:white;
}

.dashboard-heading span {
    color:#ff5967;
    letter-spacing:2px;
    font-size:11px;
    font-weight:bold;
}

.dashboard-heading h1 {
    font-size:32px;
    margin:12px 0;
}

.dashboard-heading p {
    color:#aab0bc;
    font-size:13px;
}

.customer-banner {
    margin:30px 0 40px;
    padding:35px;
    border-radius:15px;
    background:linear-gradient(110deg,#491a2b,#1d2940);
    border:1px solid #443143;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.customer-banner span {
    color:#ff8290;
    font-size:10px;
    letter-spacing:2px;
}

.customer-banner h2 {
    margin:12px 0;
    font-size:26px;
}

.customer-banner p {
    color:#c4c7d0;
    font-size:13px;
    margin-bottom:20px;
}

.customer-banner a,
.empty-bookings a {
    display:inline-block;
    color:white;
    background:#e7283b;
    text-decoration:none;
    padding:12px 17px;
    border-radius:7px;
    font-size:11px;
    font-weight:bold;
}

.banner-icon {
    font-size:65px;
}

.section-heading {
    font-size:21px;
    margin-bottom:20px;
}

.customer-booking {
    display:flex;
    justify-content:space-between;
    gap:20px;
    padding:24px;
    margin-bottom:15px;
    border-radius:12px;
    background:#171c29;
    border:1px solid #303747;
}

.booking-label {
    color:#ff5967;
    font-size:10px;
    letter-spacing:1px;
}

.booking-main > strong {
    display:block;
    color:#ff6876;
    margin-top:7px;
}

.booking-main h3 {
    margin:13px 0 8px;
}

.booking-main p {
    color:#aab0bc;
    margin-top:7px;
    font-size:12px;
}

.booking-side {
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    justify-content:center;
    gap:10px;
}

.booking-total {
    color:#ff6876;
}

.booking-status {
    color:#66dda0;
    font-size:10px;
}

.booking-side a {
    background:#e7283b;
    color:white;
    text-decoration:none;
    padding:10px 13px;
    border-radius:7px;
    font-size:10px;
    font-weight:bold;
}

.empty-bookings {
    background:#171c29;
    border:1px solid #303747;
    border-radius:12px;
    padding:45px 20px;
    text-align:center;
}

.empty-bookings div {
    font-size:45px;
}

.empty-bookings h3 {
    margin:15px 0;
}

.empty-bookings p {
    color:#aab0bc;
    font-size:13px;
    margin-bottom:20px;
}

@media(max-width:650px) {
    .customer-booking {
        flex-direction:column;
    }

    .booking-side {
        align-items:flex-start;
    }

    .customer-banner {
        padding:22px;
    }

    .customer-banner h2 {
        font-size:21px;
    }

    .banner-icon {
        font-size:38px;
    }
}
</style>

@endsection
