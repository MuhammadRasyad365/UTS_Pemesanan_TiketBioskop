
@extends('layouts.app')

@section('title', 'Detail Booking - ELCinema')

@section('content')

<div class="booking-detail-page">

    <a href="{{ route('admin.bookings.index') }}"
       class="back-link">
        ← Kembali ke Manajemen Booking
    </a>

    <div class="detail-header">

        <div>
            <span>ELCINEMA / BOOKING DETAIL</span>
            <h1>Detail Pemesanan</h1>
            <p>Periksa informasi transaksi pelanggan.</p>
        </div>

        <span class="status status-{{ $booking->status }}">
            {{ strtoupper($booking->status) }}
        </span>

    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="notice success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="notice error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notice error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- KODE BOOKING -->
    <div class="booking-code-card">

        <div>
            <span>KODE BOOKING</span>

            <h2>{{ $booking->booking_code }}</h2>
        </div>

        <div class="code-icon">
            🎟️
        </div>

    </div>

    <div class="detail-layout">

        <!-- INFORMASI TRANSAKSI -->
        <div class="detail-main">

            <!-- INFORMASI CUSTOMER -->
            <section class="detail-card">

                <div class="card-heading">
                    <span>01</span>
                    <h2>Informasi Pelanggan</h2>
                </div>

                <div class="information-grid">

                    <div>
                        <small>Nama Lengkap</small>
                        <strong>{{ $booking->customer_name }}</strong>
                    </div>

                    <div>
                        <small>Email</small>
                        <strong>{{ $booking->customer_email }}</strong>
                    </div>

                    <div>
                        <small>Nomor Telepon</small>
                        <strong>{{ $booking->customer_phone ?? '-' }}</strong>
                    </div>

                    <div>
                        <small>Akun Terdaftar</small>
                        <strong>
                            {{ $booking->user?->role
                                ? strtoupper($booking->user->role)
                                : 'BOOKING LAMA' }}
                        </strong>
                    </div>

                </div>

            </section>

            <!-- INFORMASI FILM -->
            <section class="detail-card">

                <div class="card-heading">
                    <span>02</span>
                    <h2>Informasi Penayangan</h2>
                </div>

                <h3 class="movie-title">
                    {{ $booking->screening->movie->title }}
                </h3>

                <p class="movie-genre">
                    {{ $booking->screening->movie->genre }}
                </p>

                <div class="information-grid">

                    <div>
                        <small>Tanggal Tayang</small>
                        <strong>
                            {{ $booking->screening->show_date->format('d M Y') }}
                        </strong>
                    </div>

                    <div>
                        <small>Jam Tayang</small>
                        <strong>
                            {{ substr($booking->screening->start_time, 0, 5) }}
                            WIB
                        </strong>
                    </div>

                    <div>
                        <small>Studio</small>
                        <strong>
                            {{ $booking->screening->studio_name }}
                        </strong>
                    </div>

                    <div>
                        <small>Harga Per Kursi</small>
                        <strong>
                            Rp{{ number_format(
                                (float)$booking->screening->ticket_price,
                                0, ',', '.'
                            ) }}
                        </strong>
                    </div>

                </div>

            </section>

            <!-- KURSI -->
            <section class="detail-card">

                <div class="card-heading">
                    <span>03</span>
                    <h2>Informasi Kursi</h2>
                </div>

                <div class="seat-tags">

                    @forelse($booking->seat_codes_list as $seatCode)

                        <span>{{ $seatCode }}</span>

                    @empty

                        <small>Data kursi tidak tersedia.</small>

                    @endforelse

                </div>

                <p class="seat-description">
                    Jumlah tiket: {{ count($booking->seat_codes_list) }}
                </p>

            </section>

        </div>

        <!-- SIDEBAR STATUS -->
        <aside class="detail-sidebar">

            <section class="detail-card">

                <div class="card-heading">
                    <span>04</span>
                    <h2>Ringkasan Transaksi</h2>
                </div>

                <div class="summary-row">
                    <span>Kode Booking</span>
                    <strong>{{ $booking->booking_code }}</strong>
                </div>

                <div class="summary-row">
                    <span>Jumlah Kursi</span>
                    <strong>
                        {{ count($booking->seat_codes_list) }} Tiket
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Tanggal Pemesanan</span>
                    <strong>
                        {{ $booking->booked_at
                            ? $booking->booked_at->format('d M Y H:i')
                            : '-' }}
                    </strong>
                </div>

                <div class="total-box">
                    <span>TOTAL PEMESANAN</span>

                    <h2>
                        Rp{{ number_format(
                            (float)$booking->total_price,
                            0, ',', '.'
                        ) }}
                    </h2>
                </div>

            </section>

            <!-- KELOLA STATUS -->
            <section class="detail-card">

                <div class="card-heading">
                    <span>05</span>
                    <h2>Kelola Status</h2>
                </div>

                @if($booking->status === 'used')

                    <div class="used-notice">
                        ✓ Tiket sudah digunakan dan tidak dapat diubah kembali.
                    </div>

                @else

                    <form
                        action="{{ route('admin.bookings.status', $booking) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <label for="status">
                            Status Pemesanan
                        </label>

                        <select name="status" id="status" required>

                            @if($booking->status === 'confirmed')

                                <option
                                    value="confirmed"
                                    selected
                                >
                                    Confirmed
                                </option>

                                <option value="cancelled">
                                    Cancelled
                                </option>

                                <option value="used">
                                    Used
                                </option>

                            @elseif($booking->status === 'cancelled')

                                <option
                                    value="cancelled"
                                    selected
                                >
                                    Cancelled
                                </option>

                                <option value="confirmed">
                                    Aktifkan Kembali
                                </option>

                            @endif

                        </select>

                        <button type="submit" class="update-status">
                            SIMPAN PERUBAHAN
                        </button>

                    </form>

                @endif

                <div class="status-information">

                    <small>Keterangan:</small>

                    <p>
                        Confirmed: Pesanan telah dikonfirmasi.
                    </p>

                    <p>
                        Cancelled: Pesanan dibatalkan dan kursi dilepaskan.
                    </p>

                    <p>
                        Used: Tiket telah digunakan untuk masuk studio.
                    </p>

                </div>

            </section>

        </aside>

    </div>

    <!-- TINDAKAN -->
    <div class="bottom-actions">

        @if(in_array($booking->status, ['confirmed', 'used']))

            <a
                href="{{ route('booking.ticket', $booking) }}"
                class="ticket-action"
            >
                LIHAT E-TICKET
            </a>

        @endif

        @if($booking->status === 'cancelled')

            <form
                action="{{ route('admin.bookings.destroy', $booking) }}"
                method="POST"
                onsubmit="return confirm('Apakah kamu yakin ingin menghapus transaksi ini secara permanen?')"
            >
                @csrf
                @method('DELETE')

                <button class="delete-action">
                    HAPUS TRANSAKSI
                </button>

            </form>

        @endif

        <a
            href="{{ route('admin.bookings.index') }}"
            class="back-button"
        >
            KEMBALI KE DAFTAR
        </a>

    </div>

</div>

<style>

.booking-detail-page {
    width:92%;
    max-width:1200px;
    margin:auto;
    padding:40px 0 70px;
    color:white;
}

.back-link {
    color:#ff6876;
    text-decoration:none;
    font-size:12px;
}

.detail-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin:28px 0;
}

.detail-header > div > span {
    color:#ff5967;
    font-size:10px;
    letter-spacing:2px;
    font-weight:bold;
}

.detail-header h1 {
    font-size:30px;
    margin:10px 0;
}

.detail-header p {
    color:#aab0bc;
    font-size:13px;
}

.status {
    padding:9px 13px;
    border-radius:6px;
    font-size:10px;
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

.notice {
    margin-bottom:20px;
    padding:14px;
    border-radius:8px;
    font-size:12px;
}

.notice.success {
    color:#8ce8b4;
    background:#17432e;
}

.notice.error {
    color:#ffc1ca;
    background:#481f2b;
}

/* KODE BOOKING */

.booking-code-card {
    padding:24px;
    margin-bottom:25px;
    border-radius:12px;
    background:linear-gradient(110deg,#491a2b,#202a40);
    border:1px solid #713c4e;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.booking-code-card span {
    color:#ff9aa4;
    font-size:10px;
    letter-spacing:2px;
}

.booking-code-card h2 {
    color:#ff6876;
    font-size:24px;
    margin-top:10px;
    overflow-wrap:anywhere;
}

.code-icon {
    font-size:37px;
}

/* LAYOUT */

.detail-layout {
    display:grid;
    grid-template-columns:1.4fr .9fr;
    gap:20px;
    align-items:start;
}

.detail-main,
.detail-sidebar {
    display:flex;
    flex-direction:column;
    gap:18px;
}

.detail-card {
    background:#171c29;
    border:1px solid #303747;
    border-radius:12px;
    padding:23px;
}

.card-heading {
    display:flex;
    align-items:center;
    gap:11px;
    margin-bottom:23px;
}

.card-heading > span {
    width:30px;
    height:30px;
    border-radius:7px;
    background:#401e2a;
    color:#ff7684;
    display:grid;
    place-items:center;
    font-size:10px;
    font-weight:bold;
}

.card-heading h2 {
    font-size:16px;
}

/* INFORMASI */

.information-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:23px;
}

.information-grid > div {
    display:flex;
    flex-direction:column;
    gap:8px;
    min-width:0;
}

.information-grid small {
    color:#929cac;
    font-size:11px;
}

.information-grid strong {
    font-size:12px;
    overflow-wrap:anywhere;
}

.movie-title {
    font-size:21px;
    margin-bottom:8px;
}

.movie-genre {
    font-size:12px;
    color:#aab0bc;
    margin-bottom:22px;
}

/* SEAT */

.seat-tags {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.seat-tags span {
    background:#253f64;
    color:#a3d0ff;
    padding:10px 15px;
    border-radius:6px;
    font-weight:bold;
    font-size:12px;
}

.seat-description {
    color:#aab0bc;
    font-size:11px;
    margin-top:17px;
}

/* RINGKASAN */

.summary-row {
    display:flex;
    justify-content:space-between;
    gap:12px;
    padding:12px 0;
    border-bottom:1px solid #303747;
    font-size:11px;
}

.summary-row span {
    color:#929cac;
}

.summary-row strong {
    text-align:right;
    overflow-wrap:anywhere;
}

.total-box {
    margin-top:20px;
    background:#291820;
    padding:18px;
    border-radius:9px;
    border:1px solid #693040;
}

.total-box span {
    color:#d4aeb5;
    font-size:10px;
    letter-spacing:1px;
}

.total-box h2 {
    color:#ff6876;
    font-size:24px;
    margin-top:9px;
}

/* KELOLA STATUS */

.detail-card form label {
    display:block;
    font-size:11px;
    font-weight:bold;
    margin-bottom:10px;
}

.detail-card form select {
    box-sizing:border-box;
    width:100%;
    padding:13px;
    background:#0e1420;
    color:white;
    border:1px solid #354052;
    border-radius:7px;
}

.update-status {
    width:100%;
    padding:13px;
    border:0;
    background:#e7283b;
    color:white;
    border-radius:7px;
    font-size:10px;
    font-weight:bold;
    margin-top:13px;
    cursor:pointer;
}

.status-information {
    border-top:1px solid #303747;
    margin-top:20px;
    padding-top:16px;
}

.status-information small {
    color:#ff7885;
    font-weight:bold;
}

.status-information p {
    color:#aab0bc;
    font-size:10px;
    margin-top:9px;
    line-height:1.6;
}

.used-notice {
    padding:14px;
    border-radius:8px;
    background:#243d60;
    color:#9cc8ff;
    line-height:1.6;
    font-size:12px;
}

/* AKSI */

.bottom-actions {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-top:25px;
}

.ticket-action,
.back-button,
.delete-action {
    border:0;
    padding:13px 18px;
    border-radius:7px;
    color:white;
    text-decoration:none;
    font-size:10px;
    font-weight:bold;
    cursor:pointer;
}

.ticket-action {
    background:#24598e;
}

.back-button {
    background:#394355;
}

.delete-action {
    background:#a62b3b;
}

@media(max-width:800px) {
    .detail-layout {
        grid-template-columns:1fr;
    }
}

@media(max-width:550px) {
    .detail-header {
        align-items:flex-start;
        flex-direction:column;
    }

    .detail-header h1 {
        font-size:25px;
    }

    .booking-code-card h2 {
        font-size:18px;
    }

    .detail-card {
        padding:18px;
    }
}

</style>

@endsection
