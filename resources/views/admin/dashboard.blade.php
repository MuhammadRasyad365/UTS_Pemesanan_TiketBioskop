
@extends('layouts.app')

@section('title', 'Dashboard Admin - ELCinema')

@section('content')

<div class="admin-dashboard">

    <!-- =================================
         HEADER DASHBOARD
    ================================== -->

    <div class="dashboard-topbar">

        <div>
            <span class="dashboard-eyebrow">
                ELCINEMA MANAGEMENT SYSTEM
            </span>

            <h1>Dashboard Admin</h1>

            <p>
                Selamat datang kembali,
                <strong>{{ auth()->user()->name }}</strong>.
                Kelola seluruh operasional bioskop dari sini.
            </p>
        </div>

        <div class="dashboard-date">

            <span class="date-icon">▦</span>

            <div>
                <strong>{{ now()->format('d M Y') }}</strong>
                <small>Administrator Panel</small>
            </div>

        </div>

    </div>


    <!-- =================================
         WELCOME BANNER
    ================================== -->

    <section class="welcome-banner">

        <div class="welcome-content">

            <span class="welcome-label">
                WELCOME TO ELCINEMA ADMIN
            </span>

            <h2>
                Manage Your Cinema.<br>
                Create Great Experiences.
            </h2>

            <p>
                Pantau film, jadwal penayangan,
                transaksi pelanggan, dan aktivitas bioskop
                melalui satu dashboard.
            </p>

            <a href="{{ route('admin.bookings.index') }}"
               class="welcome-button">
                LIHAT TRANSAKSI
                <span>→</span>
            </a>

        </div>

        <div class="welcome-visual">
            <div class="visual-circle circle-one"></div>
            <div class="visual-circle circle-two"></div>
            <div class="cinema-icon">🎬</div>
            <span>ELCINEMA ORIGINAL EXPERIENCE</span>
        </div>

    </section>


    <!-- =================================
         STATISTIK
    ================================== -->

    <div class="section-heading">

        <div>
            <span>OVERVIEW</span>
            <h2>Statistik Bioskop</h2>
        </div>

        <span class="database-indicator">
            <i></i> DATABASE CONNECTED
        </span>

    </div>


    <div class="statistics-grid">

        <!-- TOTAL FILM -->
        <div class="statistics-card">

            <div class="statistics-card-top">
                <span class="statistics-icon red-icon">▣</span>
                <span class="statistics-label">TOTAL FILM</span>
            </div>

            <h2>{{ $totalMovies }}</h2>

            <p>Film terdaftar dalam katalog</p>

            <a href="{{ route('admin.movies.index') }}">
                Lihat semua film →
            </a>

        </div>


        <!-- TOTAL JADWAL -->
        <div class="statistics-card">

            <div class="statistics-card-top">
                <span class="statistics-icon blue-icon">◷</span>
                <span class="statistics-label">TOTAL JADWAL</span>
            </div>

            <h2>{{ $totalScreenings }}</h2>

            <p>Seluruh jadwal penayangan</p>

            <a href="{{ route('admin.screenings.index') }}">
                Kelola jadwal →
            </a>

        </div>


        <!-- TOTAL BOOKING -->
        <div class="statistics-card">

            <div class="statistics-card-top">
                <span class="statistics-icon purple-icon">▤</span>
                <span class="statistics-label">TOTAL BOOKING</span>
            </div>

            <h2>{{ $totalBookings }}</h2>

            <p>Seluruh transaksi pelanggan</p>

            <a href="{{ route('admin.bookings.index') }}">
                Lihat pemesanan →
            </a>

        </div>


        <!-- TOTAL CUSTOMER -->
        <div class="statistics-card">

            <div class="statistics-card-top">
                <span class="statistics-icon green-icon">♙</span>
                <span class="statistics-label">TOTAL CUSTOMER</span>
            </div>

            <h2>{{ $totalCustomers }}</h2>

            <p>Pengguna dengan role customer</p>

            <a href="{{ route('admin.bookings.index') }}">
                Lihat aktivitas →
            </a>

        </div>

    </div>


    <!-- =================================
         NILAI TRANSAKSI
    ================================== -->

    <section class="revenue-section">

        <div class="revenue-content">

            <div class="revenue-icon">
                Rp
            </div>

            <div>
                <span>NILAI BOOKING CONFIRMED</span>

                <h2>
                    Rp{{ number_format(
                        (float)$totalRevenue,
                        0,
                        ',',
                        '.'
                    ) }}
                </h2>

                <p>
                    Jumlah nilai transaksi dengan status confirmed.
                    Belum termasuk verifikasi pembayaran eksternal.
                </p>
            </div>

        </div>

        <a href="{{ route('admin.bookings.index') }}"
           class="revenue-button">
            DETAIL TRANSAKSI →
        </a>

    </section>


    <!-- =================================
         MENU PENGELOLAAN
    ================================== -->

    <div class="section-heading management-heading">

        <div>
            <span>MANAGEMENT CENTER</span>
            <h2>Menu Pengelolaan</h2>
        </div>

        <p>
            Akses fitur utama pengelolaan ELCinema.
        </p>

    </div>


    <div class="management-grid">

        <!-- KELOLA FILM -->
        <a href="{{ route('admin.movies.index') }}"
           class="management-card">

            <div class="management-icon red-icon">
                🎬
            </div>

            <div class="management-card-content">
                <h3>Kelola Film</h3>

                <p>
                    Tambah film, ubah judul, ganti poster,
                    sinopsis, rating, dan harga.
                </p>

                <span>KELOLA FILM →</span>
            </div>

        </a>


        <!-- KELOLA JADWAL -->
        <a href="{{ route('admin.screenings.index') }}"
           class="management-card">

            <div class="management-icon blue-icon">
                📅
            </div>

            <div class="management-card-content">
                <h3>Kelola Jadwal</h3>

                <p>
                    Atur tanggal, jam tayang, studio,
                    harga dan status penayangan.
                </p>

                <span>KELOLA JADWAL →</span>
            </div>

        </a>


        <!-- MANAJEMEN BOOKING -->
        <a href="{{ route('admin.bookings.index') }}"
           class="management-card">

            <div class="management-icon purple-icon">
                🎟️
            </div>

            <div class="management-card-content">
                <h3>Manajemen Booking</h3>

                <p>
                    Periksa transaksi, kursi, pelanggan,
                    dan status pemesanan tiket.
                </p>

                <span>KELOLA BOOKING →</span>
            </div>

        </a>


        <!-- CUSTOMER -->
        <a href="{{ route('admin.bookings.index') }}"
           class="management-card">

            <div class="management-icon green-icon">
                👥
            </div>

            <div class="management-card-content">
                <h3>Aktivitas Customer</h3>

                <p>
                    Pantau aktivitas pemesanan dan
                    transaksi seluruh pelanggan.
                </p>

                <span>LIHAT AKTIVITAS →</span>
            </div>

        </a>

    </div>


    <!-- =================================
         TRANSAKSI TERBARU
    ================================== -->

    <section class="recent-bookings">

        <div class="recent-heading">

            <div>
                <span>RECENT TRANSACTIONS</span>
                <h2>Pemesanan Terbaru</h2>
                <p>Aktivitas transaksi pelanggan ELCinema.</p>
            </div>

            <a href="{{ route('admin.bookings.index') }}">
                LIHAT SEMUA →
            </a>

        </div>


        <div class="recent-table-wrapper">

            <table class="recent-table">

                <thead>
                    <tr>
                        <th>KODE BOOKING</th>
                        <th>PELANGGAN</th>
                        <th>FILM</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($latestBookings as $booking)

                        <tr>

                            <td class="booking-code">
                                {{ $booking->booking_code }}
                            </td>

                            <td>
                                <div class="customer-name">
                                    {{ $booking->customer_name }}
                                </div>

                                <small>
                                    {{ $booking->customer_email }}
                                </small>
                            </td>

                            <td>
                                {{ $booking->screening?->movie?->title ?? '-' }}
                            </td>

                            <td class="table-price">
                                Rp{{ number_format(
                                    (float)$booking->total_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>

                                <span class="booking-status status-{{ $booking->status }}">
                                    {{ strtoupper($booking->status) }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('admin.bookings.show', $booking) }}"
                                    class="table-detail-button"
                                >
                                    DETAIL
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-booking">

                                    <span>🎟️</span>

                                    <strong>Belum Ada Transaksi</strong>

                                    <p>
                                        Pemesanan pelanggan akan muncul di sini.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>


    <!-- =================================
         FOOTER DASHBOARD
    ================================== -->

    <div class="dashboard-bottom">

        <span>
            ELCINEMA ADMIN CONTROL CENTER
        </span>

        <span>
            © {{ date('Y') }} ELCinema
        </span>

    </div>


</div>


<style>

/* =====================================
   DASHBOARD GLOBAL
===================================== */

.admin-dashboard {
    width:92%;
    max-width:1400px;
    margin:0 auto;
    padding:42px 0 55px;
    color:#ffffff;
    font-family:Arial, Helvetica, sans-serif;
}

.admin-dashboard * {
    box-sizing:border-box;
}


/* =====================================
   HEADER
===================================== */

.dashboard-topbar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.dashboard-eyebrow {
    color:#ff6575;
    font-size:10px;
    font-weight:bold;
    letter-spacing:2px;
}

.dashboard-topbar h1 {
    font-size:31px;
    margin:12px 0 9px;
}

.dashboard-topbar p {
    font-size:13px;
    color:#aab3c1;
    line-height:1.7;
}

.dashboard-topbar p strong {
    color:#ff7583;
}

.dashboard-date {
    display:flex;
    align-items:center;
    gap:13px;
    padding:13px 17px;
    background:#171d2a;
    border:1px solid #303a4b;
    border-radius:10px;
}

.date-icon {
    width:38px;
    height:38px;
    display:grid;
    place-items:center;
    background:#38202c;
    color:#ff7684;
    border-radius:8px;
    font-size:21px;
}

.dashboard-date div {
    display:flex;
    flex-direction:column;
    gap:5px;
}

.dashboard-date strong {
    font-size:12px;
}

.dashboard-date small {
    font-size:10px;
    color:#909bad;
}


/* =====================================
   WELCOME BANNER
===================================== */

.welcome-banner {
    position:relative;
    overflow:hidden;
    min-height:270px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    padding:36px 40px;
    margin-bottom:38px;

    background:linear-gradient(
        115deg,
        #471622 0%,
        #291c2e 53%,
        #151c30 100%
    );

    border:1px solid #542b3b;
    border-radius:16px;
}

.welcome-content {
    position:relative;
    z-index:2;
    max-width:650px;
}

.welcome-label {
    display:inline-block;
    color:#ff8793;
    font-size:10px;
    font-weight:bold;
    letter-spacing:2px;
}

.welcome-content h2 {
    font-size:31px;
    line-height:1.3;
    margin:15px 0;
}

.welcome-content p {
    max-width:550px;
    color:#d2c5ce;
    font-size:13px;
    line-height:1.8;
    margin-bottom:22px;
}

.welcome-button {
    display:inline-flex;
    align-items:center;
    gap:15px;
    padding:13px 19px;
    background:#e7283b;
    border-radius:7px;
    color:white;
    text-decoration:none;
    font-size:10px;
    font-weight:bold;
    transition:.2s;
}

.welcome-button:hover {
    background:#bd1930;
    transform:translateY(-2px);
}

.welcome-button span {
    font-size:16px;
}

.welcome-visual {
    position:relative;
    width:245px;
    height:200px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.visual-circle {
    position:absolute;
    border:1px solid rgba(255,111,129,.2);
    border-radius:50%;
}

.circle-one {
    width:180px;
    height:180px;
}

.circle-two {
    width:135px;
    height:135px;
}

.cinema-icon {
    position:relative;
    z-index:1;
    font-size:73px;
    filter:drop-shadow(0 10px 20px rgba(0,0,0,.35));
}

.welcome-visual > span {
    position:absolute;
    bottom:0;
    color:#e89ba5;
    font-size:8px;
    letter-spacing:2px;
}


/* =====================================
   SECTION HEADING
===================================== */

.section-heading {
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:15px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.section-heading > div > span,
.recent-heading > div > span {
    color:#ff6575;
    font-size:10px;
    font-weight:bold;
    letter-spacing:2px;
}

.section-heading h2 {
    font-size:21px;
    margin-top:9px;
}

.database-indicator {
    display:flex;
    align-items:center;
    gap:8px;
    color:#75dca0;
    background:#16392a;
    border:1px solid #285b40;
    border-radius:30px;
    padding:9px 12px;
    font-size:9px;
    font-weight:bold;
    letter-spacing:.7px;
}

.database-indicator i {
    width:7px;
    height:7px;
    background:#4cda8a;
    border-radius:50%;
}


/* =====================================
   STATISTICS
===================================== */

.statistics-grid {
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:17px;
    margin-bottom:27px;
}

.statistics-card {
    padding:21px;
    min-height:174px;
    background:linear-gradient(145deg,#1b2332,#151a27);
    border:1px solid #303a4b;
    border-radius:12px;
    transition:.2s;
}

.statistics-card:hover {
    transform:translateY(-3px);
    border-color:#75404c;
}

.statistics-card-top {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}

.statistics-icon {
    width:40px;
    height:40px;
    display:grid;
    place-items:center;
    border-radius:10px;
    font-size:22px;
}

.red-icon {
    color:#ff7887;
    background:#49222f;
}

.blue-icon {
    color:#8cbfff;
    background:#213b5b;
}

.purple-icon {
    color:#c49aff;
    background:#382954;
}

.green-icon {
    color:#78dca5;
    background:#193c2d;
}

.statistics-label {
    font-size:9px;
    color:#9ba6b7;
    font-weight:bold;
    letter-spacing:1px;
}

.statistics-card h2 {
    margin:18px 0 5px;
    font-size:31px;
    color:white;
}

.statistics-card p {
    color:#969fb0;
    font-size:11px;
}

.statistics-card > a {
    display:inline-block;
    margin-top:14px;
    color:#ff7482;
    font-size:10px;
    font-weight:bold;
    text-decoration:none;
}

.statistics-card > a:hover {
    color:#ffffff;
}


/* =====================================
   NILAI BOOKING
===================================== */

.revenue-section {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    padding:24px 27px;
    margin-bottom:40px;

    background:linear-gradient(105deg,#3f1a27,#242239);
    border:1px solid #653442;
    border-radius:13px;
}

.revenue-content {
    display:flex;
    align-items:center;
    gap:18px;
}

.revenue-icon {
    width:55px;
    height:55px;
    border-radius:12px;
    background:#632936;
    display:grid;
    place-items:center;
    color:#ff8490;
    font-size:21px;
    font-weight:bold;
}

.revenue-content > div:last-child > span {
    color:#e5aab2;
    font-size:10px;
    letter-spacing:1.5px;
    font-weight:bold;
}

.revenue-content h2 {
    margin:9px 0 5px;
    color:#ff7482;
    font-size:29px;
}

.revenue-content p {
    color:#bfadb8;
    font-size:10px;
    line-height:1.6;
}

.revenue-button {
    padding:12px 15px;
    background:#e7283b;
    color:white;
    border-radius:7px;
    text-decoration:none;
    font-size:10px;
    font-weight:bold;
    white-space:nowrap;
}


/* =====================================
   MANAGEMENT CARD
===================================== */

.management-heading {
    margin-bottom:20px;
}

.management-heading > p {
    color:#909bad;
    font-size:11px;
}

.management-grid {
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:16px;
    margin-bottom:40px;
}

.management-card {
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    padding:23px 20px;

    min-height:205px;

    background:#171d2a;
    border:1px solid #303a4b;
    border-radius:12px;

    color:white;
    text-decoration:none;

    transition:.22s;
}

.management-card:hover {
    transform:translateY(-5px);
    background:#202a3b;
    border-color:#e13d51;
    box-shadow:0 12px 26px rgba(0,0,0,.2);
}

.management-icon {
    width:46px;
    height:46px;
    display:grid;
    place-items:center;
    border-radius:11px;
    font-size:24px;
    margin-bottom:17px;
}

.management-card h3 {
    font-size:14px;
    margin-bottom:9px;
}

.management-card p {
    color:#a0aaba;
    font-size:11px;
    line-height:1.7;
    flex:1;
}

.management-card-content > span {
    color:#ff7886;
    font-size:9px;
    font-weight:bold;
    letter-spacing:.7px;
}


/* =====================================
   TRANSAKSI TERBARU
===================================== */

.recent-bookings {
    background:#171d2a;
    border:1px solid #303a4b;
    border-radius:13px;
    padding:24px;
    overflow:hidden;
}

.recent-heading {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:22px;
}

.recent-heading h2 {
    margin:9px 0;
    font-size:20px;
}

.recent-heading p {
    color:#98a2b2;
    font-size:11px;
}

.recent-heading > a {
    color:#ff7482;
    text-decoration:none;
    font-size:10px;
    font-weight:bold;
    white-space:nowrap;
}

.recent-table-wrapper {
    width:100%;
    overflow-x:auto;
}

.recent-table {
    width:100%;
    min-width:750px;
    border-collapse:collapse;
    text-align:left;
}

.recent-table th {
    padding:14px 12px;
    background:#252f40;
    color:#ff7d8a;
    font-size:9px;
    letter-spacing:.8px;
}

.recent-table td {
    padding:16px 12px;
    border-bottom:1px solid #303a4b;
    font-size:11px;
    color:#e6eaf0;
}

.recent-table tbody tr:hover {
    background:#202838;
}

.recent-table td small {
    display:block;
    margin-top:6px;
    color:#929cac;
    font-size:10px;
}

.booking-code {
    color:#ff7785 !important;
    font-weight:bold;
}

.customer-name {
    color:white;
    font-weight:bold;
}

.table-price {
    color:#ff7785 !important;
    font-weight:bold;
}

.booking-status {
    display:inline-block;
    padding:7px 9px;
    font-size:9px;
    font-weight:bold;
    border-radius:5px;
}

.status-confirmed {
    color:#86e4ad;
    background:#17432e;
}

.status-cancelled {
    color:#ffb5bf;
    background:#501f2c;
}

.status-used {
    color:#9cc8ff;
    background:#243d60;
}

.table-detail-button {
    display:inline-block;
    padding:8px 11px;
    border-radius:6px;
    background:#24598e;
    color:white;
    font-size:9px;
    font-weight:bold;
    text-decoration:none;
}

.empty-booking {
    padding:38px 15px;
    text-align:center;
}

.empty-booking span {
    font-size:35px;
}

.empty-booking strong {
    display:block;
    margin:12px 0;
    color:white;
}

.empty-booking p {
    font-size:11px;
    color:#929cac;
}


/* =====================================
   FOOTER DASHBOARD
===================================== */

.dashboard-bottom {
    display:flex;
    justify-content:space-between;
    gap:15px;
    flex-wrap:wrap;
    margin-top:25px;
    padding:17px 0;

    color:#747f91;
    border-top:1px solid #252e3c;

    font-size:9px;
    letter-spacing:1px;
}


/* =====================================
   RESPONSIVE
===================================== */

@media(max-width:1050px) {

    .statistics-grid {
        grid-template-columns:repeat(2,1fr);
    }

    .management-grid {
        grid-template-columns:repeat(2,1fr);
    }

    .welcome-visual {
        width:180px;
    }

}

@media(max-width:650px) {

    .admin-dashboard {
        width:94%;
        padding:28px 0 40px;
    }

    .dashboard-topbar h1 {
        font-size:25px;
    }

    .welcome-banner {
        padding:25px;
        min-height:auto;
    }

    .welcome-content h2 {
        font-size:23px;
    }

    .welcome-content p {
        font-size:11px;
    }

    .welcome-visual {
        display:none;
    }

    .statistics-grid {
        gap:10px;
    }

    .statistics-card {
        padding:15px;
        min-height:155px;
    }

    .statistics-card h2 {
        font-size:26px;
    }

    .statistics-label {
        font-size:8px;
    }

    .revenue-section {
        align-items:flex-start;
        flex-direction:column;
        padding:20px;
    }

    .revenue-content h2 {
        font-size:22px;
    }

    .management-grid {
        grid-template-columns:1fr;
    }

    .management-card {
        min-height:175px;
    }

    .recent-bookings {
        padding:15px;
    }

    .recent-heading {
        align-items:flex-start;
        flex-direction:column;
    }

}

</style>

@endsection
