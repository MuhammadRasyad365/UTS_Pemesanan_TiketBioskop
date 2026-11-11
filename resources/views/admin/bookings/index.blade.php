
@extends('layouts.app')

@section('title', 'Manajemen Booking - ELCinema')

@section('content')

<div class="booking-admin">

    <!-- HEADER -->
    <div class="booking-heading">

        <div>
            <span>ELCINEMA / TRANSACTION MANAGEMENT</span>

            <h1>Manajemen Booking</h1>

            <p>
                Kelola seluruh pemesanan tiket pelanggan ELCinema.
            </p>
        </div>

        <div class="admin-label">
            ADMIN CONTROL PANEL
        </div>

    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="notification success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="notification error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notification error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- STATISTIK -->
    <div class="booking-stats">

        <div class="stat-card">
            <span>TOTAL BOOKING</span>
            <h2>{{ $totalBookings }}</h2>
            <small>Seluruh transaksi</small>
        </div>

        <div class="stat-card confirmed">
            <span>CONFIRMED</span>
            <h2>{{ $confirmedBookings }}</h2>
            <small>Booking aktif</small>
        </div>

        <div class="stat-card cancelled">
            <span>CANCELLED</span>
            <h2>{{ $cancelledBookings }}</h2>
            <small>Booking dibatalkan</small>
        </div>

        <div class="stat-card used">
            <span>USED</span>
            <h2>{{ $usedBookings }}</h2>
            <small>Tiket telah digunakan</small>
        </div>

    </div>

    <!-- FILTER -->
    <div class="filter-card">

        <form
            action="{{ route('admin.bookings.index') }}"
            method="GET"
            class="filter-form"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode booking, pelanggan, film..."
            >

            <select name="status">

                <option value="">Semua Status</option>

                <option
                    value="confirmed"
                    @selected(request('status') === 'confirmed')
                >
                    Confirmed
                </option>

                <option
                    value="cancelled"
                    @selected(request('status') === 'cancelled')
                >
                    Cancelled
                </option>

                <option
                    value="used"
                    @selected(request('status') === 'used')
                >
                    Used
                </option>

            </select>

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
            >

            <button type="submit">
                CARI
            </button>

            <a href="{{ route('admin.bookings.index') }}">
                RESET
            </a>

        </form>

    </div>

    <!-- TABEL BOOKING -->
    <div class="booking-table-card">

        <div class="table-heading">
            <h2>Daftar Transaksi Pelanggan</h2>

            <span>
                {{ $bookings->total() }} Transaksi
            </span>
        </div>

        <div class="table-responsive">

            <table class="booking-table">

                <thead>
                    <tr>
                        <th>KODE BOOKING</th>
                        <th>PELANGGAN</th>
                        <th>FILM</th>
                        <th>TANGGAL</th>
                        <th>KURSI</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bookings as $booking)

                        <tr>

                            <td class="booking-code-cell">
                                {{ $booking->booking_code }}
                            </td>

                            <td>
                                <strong>
                                    {{ $booking->customer_name }}
                                </strong>

                                <small>
                                    {{ $booking->customer_email }}
                                </small>
                            </td>

                            <td>
                                {{ $booking->screening->movie->title }}
                                <small>
                                    {{ $booking->screening->studio_name }}
                                </small>
                            </td>

                            <td>
                                {{ $booking->screening->show_date->format('d M Y') }}

                                <small>
                                    {{ substr($booking->screening->start_time, 0, 5) }} WIB
                                </small>
                            </td>

                            <td>
                                <div class="seat-list">
                                    @foreach($booking->seat_codes_list as $seatCode)
                                        <span>{{ $seatCode }}</span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="price-cell">
                                Rp{{ number_format(
                                    (float)$booking->total_price,
                                    0, ',', '.'
                                ) }}
                            </td>

                            <td>
                                <span class="status status-{{ $booking->status }}">
                                    {{ strtoupper($booking->status) }}
                                </span>
                            </td>

                            <td>
                                <a
                                    href="{{ route('admin.bookings.show', $booking) }}"
                                    class="detail-button"
                                >
                                    DETAIL
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="empty-state">
                                <span>🎟️</span>
                                <strong>Belum ada transaksi ditemukan</strong>
                                <p>Ubah pencarian atau filter booking.</p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">
            {{ $bookings->links() }}
        </div>

    </div>

</div>

<style>

.booking-admin {
    width:92%;
    max-width:1400px;
    margin:auto;
    padding:42px 0 70px;
    color:white;
}

.booking-heading {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.booking-heading > div:first-child > span {
    color:#ff5967;
    font-size:10px;
    font-weight:bold;
    letter-spacing:2px;
}

.booking-heading h1 {
    font-size:30px;
    margin:12px 0;
}

.booking-heading p {
    color:#aab0bc;
    font-size:13px;
}

.admin-label {
    padding:12px 16px;
    color:#ff8792;
    border:1px solid #693340;
    background:#2a1923;
    border-radius:30px;
    font-size:10px;
    font-weight:bold;
}

/* NOTIFIKASI */

.notification {
    margin-top:22px;
    padding:14px;
    border-radius:8px;
    font-size:12px;
}

.notification.success {
    color:#8ce8b4;
    background:#17432e;
    border:1px solid #256b48;
}

.notification.error {
    color:#ffc1ca;
    background:#481f2b;
    border:1px solid #873a4c;
}

/* STATISTIK */

.booking-stats {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin:30px 0;
}

.stat-card {
    padding:22px;
    background:#171c29;
    border:1px solid #303747;
    border-radius:11px;
}

.stat-card span {
    color:#aab0bc;
    font-size:10px;
    letter-spacing:1px;
}

.stat-card h2 {
    font-size:29px;
    margin:11px 0;
}

.stat-card small {
    color:#929cac;
    font-size:10px;
}

.stat-card.confirmed h2 {
    color:#57d797;
}

.stat-card.cancelled h2 {
    color:#ff7585;
}

.stat-card.used h2 {
    color:#80baff;
}

/* FILTER */

.filter-card {
    background:#171c29;
    border:1px solid #303747;
    border-radius:11px;
    padding:17px;
    margin-bottom:22px;
}

.filter-form {
    display:grid;
    grid-template-columns:1fr 160px 170px auto auto;
    gap:10px;
}

.filter-form input,
.filter-form select {
    width:100%;
    min-width:0;
    box-sizing:border-box;
    background:#0e1420;
    color:white;
    border:1px solid #354052;
    padding:12px;
    border-radius:7px;
}

.filter-form button,
.filter-form a {
    text-decoration:none;
    text-align:center;
    padding:12px 16px;
    border:0;
    border-radius:7px;
    color:white;
    font-size:10px;
    font-weight:bold;
    cursor:pointer;
}

.filter-form button {
    background:#e7283b;
}

.filter-form a {
    background:#394355;
}

/* TABEL */

.booking-table-card {
    padding:18px;
    border-radius:12px;
    border:1px solid #303747;
    background:#171c29;
}

.table-heading {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:20px;
}

.table-heading h2 {
    font-size:18px;
}

.table-heading span {
    font-size:11px;
    color:#aab0bc;
}

.table-responsive {
    overflow-x:auto;
}

.booking-table {
    width:100%;
    min-width:1150px;
    border-collapse:collapse;
}

.booking-table th {
    background:#222b3b;
    color:#ff7c89;
    text-align:left;
    padding:15px 12px;
    font-size:10px;
    letter-spacing:.6px;
}

.booking-table td {
    padding:15px 12px;
    border-bottom:1px solid #303747;
    font-size:11px;
}

.booking-table td small {
    display:block;
    margin-top:6px;
    color:#929cac;
    font-size:10px;
}

.booking-code-cell {
    color:#ff7885;
    font-weight:bold;
}

.price-cell {
    color:#ff7885;
    font-weight:bold;
}

.seat-list {
    display:flex;
    flex-wrap:wrap;
    gap:5px;
}

.seat-list span {
    background:#253f64;
    color:#a3d0ff;
    padding:5px 7px;
    border-radius:4px;
    font-size:9px;
    font-weight:bold;
}

.status {
    display:inline-block;
    border-radius:5px;
    padding:7px 9px;
    font-size:9px;
    font-weight:bold;
}

.status-confirmed {
    background:#17432e;
    color:#8ce8b4;
}

.status-cancelled {
    background:#501f2c;
    color:#ffb0bb;
}

.status-used {
    background:#243d60;
    color:#9cc8ff;
}

.detail-button {
    display:inline-block;
    background:#24598e;
    color:white;
    padding:9px 12px;
    border-radius:5px;
    text-decoration:none;
    font-size:9px;
    font-weight:bold;
}

.empty-state {
    padding:50px !important;
    text-align:center;
}

.empty-state span {
    display:block;
    font-size:32px;
    margin-bottom:12px;
}

.empty-state strong {
    display:block;
}

.empty-state p {
    color:#929cac;
    margin-top:8px;
}

.pagination {
    margin-top:22px;
}

@media(max-width:900px) {
    .booking-stats {
        grid-template-columns:repeat(2,1fr);
    }

    .filter-form {
        grid-template-columns:1fr 1fr;
    }
}

@media(max-width:550px) {
    .booking-admin {
        width:94%;
    }

    .booking-heading h1 {
        font-size:25px;
    }

    .booking-stats {
        gap:9px;
    }

    .stat-card {
        padding:15px;
    }

    .filter-form {
        grid-template-columns:1fr;
    }

    .booking-table-card {
        padding:10px;
    }
}

</style>

@endsection
