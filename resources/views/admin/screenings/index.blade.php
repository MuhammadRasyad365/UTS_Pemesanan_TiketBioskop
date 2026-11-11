
@extends('layouts.app')

@section('title', 'Kelola Jadwal - ELCinema')

@section('content')

<div class="screening-page">

    <!-- HEADER -->
    <div class="page-header">

        <div class="header-content">

            <div class="eyebrow">
                <span class="eyebrow-dot"></span>
                ELCINEMA ADMIN MANAGEMENT
            </div>

            <h1>Kelola Jadwal Tayang</h1>

            <p>
                Kelola jadwal penayangan film, studio,
                waktu dan harga tiket bioskop.
            </p>

        </div>

        <a href="{{ route('admin.screenings.create') }}"
           class="add-button">

            <span>+</span>
            TAMBAH JADWAL

        </a>

    </div>


    <!-- NOTIFIKASI -->
    @if(session('success'))

        <div class="alert-box success">
            <span>✓</span>
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="alert-box error">
            <span>!</span>
            {{ session('error') }}
        </div>

    @endif

    @if($errors->any())

        <div class="alert-box error">

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

        </div>

    @endif


    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-info">
                <span>TOTAL JADWAL</span>

                <h2>{{ $screenings->total() }}</h2>

                <small>Seluruh data penayangan</small>
            </div>

            <div class="stat-icon red">
                <span>▦</span>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-info">
                <span>DITAMPILKAN</span>

                <h2>{{ $screenings->count() }}</h2>

                <small>Jadwal pada halaman ini</small>
            </div>

            <div class="stat-icon blue">
                <span>◷</span>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-info">
                <span>HALAMAN</span>

                <h2>{{ $screenings->currentPage() }}</h2>

                <small>
                    Dari {{ $screenings->lastPage() }} halaman
                </small>

            </div>

            <div class="stat-icon purple">
                <span>▤</span>
            </div>

        </div>

    </div>


    <!-- FILTER PENCARIAN -->
    <div class="filter-card">

        <div class="filter-heading">

            <div>
                <h3>Daftar Penayangan</h3>

                <p>
                    Cari dan filter jadwal bioskop.
                </p>
            </div>

            <span class="live-indicator">
                <i></i> DATABASE TERHUBUNG
            </span>

        </div>


        <form
            action="{{ route('admin.screenings.index') }}"
            method="GET"
            class="filter-form"
        >

            <div class="search-box">

                <span>⌕</span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul film atau nama studio..."
                >

            </div>


            <select name="status">

                <option value="">Semua Status</option>

                <option
                    value="available"
                    @selected(request('status') === 'available')
                >
                    Available
                </option>

                <option
                    value="cancelled"
                    @selected(request('status') === 'cancelled')
                >
                    Cancelled
                </option>

            </select>


            <button type="submit" class="search-button">
                CARI
            </button>


            <a
                href="{{ route('admin.screenings.index') }}"
                class="reset-button"
            >
                RESET
            </a>

        </form>

    </div>


    <!-- TABEL JADWAL -->
    <div class="table-card">

        <div class="table-top">

            <div>
                <h3>Data Jadwal Penayangan</h3>

                <p>
                    Menampilkan jadwal film ELCinema dari database.
                </p>
            </div>

            <span class="record-count">
                {{ $screenings->total() }} DATA
            </span>

        </div>


        <div class="table-wrapper">

            <table class="screening-table">

                <thead>

                    <tr>

                        <th>FILM</th>

                        <th>TANGGAL</th>

                        <th>JAM TAYANG</th>

                        <th>STUDIO</th>

                        <th>HARGA TIKET</th>

                        <th>BOOKING</th>

                        <th>STATUS</th>

                        <th>AKSI</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($screenings as $screening)

                        <tr>

                            <!-- FILM -->
                            <td>

                                <div class="film-information">

                                    <div class="film-icon">
                                        🎬
                                    </div>

                                    <div class="film-text">

                                        <strong>
                                            {{ $screening->movie->title }}
                                        </strong>

                                        <small>
                                            {{ $screening->movie->genre }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- TANGGAL -->
                            <td>

                                <div class="date-information">

                                    <strong>
                                        {{ $screening->show_date->format('d M Y') }}
                                    </strong>

                                    <small>
                                        {{ $screening->show_date->format('l') }}
                                    </small>

                                </div>

                            </td>


                            <!-- JAM -->
                            <td>

                                <div class="time-information">

                                    <strong>
                                        {{ substr($screening->start_time, 0, 5) }}
                                    </strong>

                                    @if($screening->end_time)

                                        <small>
                                            Sampai
                                            {{ substr($screening->end_time, 0, 5) }}
                                        </small>

                                    @endif

                                </div>

                            </td>


                            <!-- STUDIO -->
                            <td>

                                <span class="studio-badge">
                                    {{ $screening->studio_name }}
                                </span>

                            </td>


                            <!-- HARGA -->
                            <td>

                                <strong class="ticket-price">

                                    Rp{{ number_format(
                                        (float)$screening->ticket_price,
                                        0, ',', '.'
                                    ) }}

                                </strong>

                            </td>


                            <!-- BOOKING -->
                            <td>

                                <span class="booking-count">
                                    {{ $screening->bookings_count }}
                                </span>

                            </td>


                            <!-- STATUS -->
                            <td>

                                <span class="status-badge status-{{ $screening->status }}">

                                    <i></i>

                                    {{ strtoupper($screening->status) }}

                                </span>

                            </td>


                            <!-- AKSI -->
                            <td>

                                <div class="action-group">

                                    <a
                                        href="{{ route('admin.screenings.edit', $screening) }}"
                                        class="edit-button"
                                        title="Edit jadwal"
                                    >
                                        EDIT
                                    </a>


                                    @if($screening->bookings_count === 0)

                                        <form
                                            action="{{ route('admin.screenings.destroy', $screening) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus jadwal ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="delete-button"
                                            >
                                                HAPUS
                                            </button>

                                        </form>

                                    @else

                                        <span class="locked-button"
                                              title="Jadwal memiliki transaksi">
                                            TERKUNCI
                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">

                                    <span>📅</span>

                                    <h3>Jadwal Tidak Ditemukan</h3>

                                    <p>
                                        Belum ada jadwal yang sesuai
                                        dengan pencarian kamu.
                                    </p>

                                    <a href="{{ route('admin.screenings.create') }}">
                                        TAMBAH JADWAL
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- PAGINATION CUSTOM -->
        @if($screenings->hasPages())

            <div class="custom-pagination">

                <div class="pagination-info">

                    Menampilkan

                    <strong>{{ $screenings->firstItem() }}</strong>

                    sampai

                    <strong>{{ $screenings->lastItem() }}</strong>

                    dari

                    <strong>{{ $screenings->total() }}</strong>

                    jadwal

                </div>


                <div class="pagination-controls">

                    @if($screenings->onFirstPage())

                        <span class="page-button disabled">
                            ← Sebelumnya
                        </span>

                    @else

                        <a
                            href="{{ $screenings->previousPageUrl() }}"
                            class="page-button"
                        >
                            ← Sebelumnya
                        </a>

                    @endif


                    <span class="page-number">

                        {{ $screenings->currentPage() }}

                        /

                        {{ $screenings->lastPage() }}

                    </span>


                    @if($screenings->hasMorePages())

                        <a
                            href="{{ $screenings->nextPageUrl() }}"
                            class="page-button"
                        >
                            Berikutnya →
                        </a>

                    @else

                        <span class="page-button disabled">
                            Berikutnya →
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>


    <!-- FOOTER INFO -->
    <div class="page-footer-note">

        <span>ⓘ</span>

        Jadwal yang sudah memiliki transaksi tidak dapat
        dihapus untuk menjaga riwayat pemesanan pelanggan.

    </div>

</div>


<style>

/* ===================================
   GLOBAL HALAMAN
=================================== */

.screening-page {
    width:92%;
    max-width:1400px;
    margin:0 auto;
    padding:42px 0 65px;
    color:#ffffff;
    font-family:Arial, Helvetica, sans-serif;
    box-sizing:border-box;
}

.screening-page * {
    box-sizing:border-box;
}


/* ===================================
   HEADER
=================================== */

.page-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:25px;
    flex-wrap:wrap;
    margin-bottom:32px;
}

.eyebrow {
    display:flex;
    align-items:center;
    gap:9px;
    color:#ff6574;
    font-size:11px;
    font-weight:bold;
    letter-spacing:2px;
}

.eyebrow-dot {
    width:8px;
    height:8px;
    background:#ee3047;
    border-radius:50%;
    box-shadow:0 0 10px rgba(238,48,71,.7);
}

.header-content h1 {
    margin:13px 0 9px;
    color:#ffffff;
    font-size:32px;
    font-weight:800;
}

.header-content p {
    font-size:14px;
    color:#aab2c0;
    line-height:1.6;
}

.add-button {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    background:linear-gradient(120deg,#f02c45,#b91631);
    color:white;
    padding:15px 21px;
    border-radius:9px;
    font-size:12px;
    font-weight:bold;
    text-decoration:none;
    transition:.2s;
    box-shadow:0 6px 20px rgba(231,40,59,.18);
}

.add-button span {
    font-size:20px;
    line-height:12px;
}

.add-button:hover {
    transform:translateY(-2px);
    box-shadow:0 9px 25px rgba(231,40,59,.35);
}


/* ===================================
   ALERT
=================================== */

.alert-box {
    padding:15px 18px;
    margin-bottom:22px;
    border-radius:9px;
    font-size:13px;
    line-height:1.7;
}

.alert-box.success {
    background:#153c2b;
    color:#8ce8b4;
    border:1px solid #286e4a;
}

.alert-box.error {
    background:#481f2b;
    color:#ffc1ca;
    border:1px solid #8b3b4d;
}


/* ===================================
   STATISTIK
=================================== */

.stats-grid {
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:18px;
    margin-bottom:25px;
}

.stat-card {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:23px;
    background:linear-gradient(140deg,#1b2231,#151a26);
    border:1px solid #30394a;
    border-radius:12px;
    min-height:120px;
}

.stat-info span {
    color:#a8b1c0;
    font-size:10px;
    letter-spacing:1.4px;
    font-weight:bold;
}

.stat-info h2 {
    font-size:29px;
    color:#ff6574;
    margin:11px 0 5px;
}

.stat-info small {
    font-size:11px;
    color:#858fa1;
}

.stat-icon {
    width:49px;
    height:49px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    font-size:24px;
}

.stat-icon.red {
    background:#48212c;
    color:#ff7685;
}

.stat-icon.blue {
    background:#1d3450;
    color:#84bdff;
}

.stat-icon.purple {
    background:#372650;
    color:#c99aff;
}


/* ===================================
   FILTER
=================================== */

.filter-card {
    background:#171c29;
    border:1px solid #303747;
    border-radius:12px;
    padding:24px;
    margin-bottom:25px;
}

.filter-heading {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.filter-heading h3 {
    font-size:17px;
    margin-bottom:7px;
}

.filter-heading p {
    color:#929cac;
    font-size:12px;
}

.live-indicator {
    color:#73d9a0;
    background:#183c2c;
    padding:8px 12px;
    border-radius:30px;
    font-size:9px;
    font-weight:bold;
    letter-spacing:.7px;
}

.live-indicator i {
    display:inline-block;
    width:7px;
    height:7px;
    border-radius:50%;
    background:#46d68c;
    margin-right:5px;
}

.filter-form {
    display:grid;
    grid-template-columns:minmax(200px,1fr) 180px 115px 90px;
    gap:11px;
}

.search-box {
    display:flex;
    align-items:center;
    gap:10px;
    padding:0 14px;
    background:#0f1520;
    border:1px solid #364052;
    border-radius:8px;
}

.search-box span {
    color:#8290a5;
    font-size:22px;
}

.search-box input {
    width:100%;
    min-width:0;
    padding:13px 0;
    border:0;
    outline:none;
    background:transparent;
    color:white;
    font-size:13px;
}

.search-box input::placeholder {
    color:#788498;
}

.filter-form select {
    min-width:0;
    width:100%;
    padding:13px;
    border:1px solid #364052;
    border-radius:8px;
    background:#0f1520;
    color:white;
    font-size:12px;
}

.search-button,
.reset-button {
    display:flex;
    align-items:center;
    justify-content:center;
    border:0;
    border-radius:8px;
    padding:12px;
    font-size:11px;
    font-weight:bold;
    text-decoration:none;
    cursor:pointer;
}

.search-button {
    background:#e7283b;
    color:white;
}

.search-button:hover {
    background:#bb192e;
}

.reset-button {
    background:#303b4e;
    color:white;
}


/* ===================================
   TABEL
=================================== */

.table-card {
    overflow:hidden;
    background:#171c29;
    border:1px solid #303747;
    border-radius:13px;
}

.table-top {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    padding:23px 24px;
}

.table-top h3 {
    font-size:17px;
    margin-bottom:7px;
}

.table-top p {
    color:#929cac;
    font-size:12px;
}

.record-count {
    color:#ff8793;
    background:#3a202c;
    border:1px solid #62313f;
    padding:9px 13px;
    border-radius:7px;
    font-size:10px;
    font-weight:bold;
}

.table-wrapper {
    width:100%;
    overflow-x:auto;
    padding:0 20px;
}

.screening-table {
    border-collapse:collapse;
    width:100%;
    min-width:1050px;
}

.screening-table thead {
    background:#252e3e;
}

.screening-table th {
    padding:17px 14px;
    color:#ff7482;
    font-size:10px;
    text-align:left;
    letter-spacing:.9px;
    font-weight:bold;
    white-space:nowrap;
}

.screening-table td {
    padding:17px 14px;
    border-bottom:1px solid #303849;
    color:#e5e9f0;
    font-size:12px;
    vertical-align:middle;
}

.screening-table tbody tr {
    transition:background .15s;
}

.screening-table tbody tr:hover {
    background:#202838;
}

.screening-table tbody tr:last-child td {
    border-bottom:0;
}


/* FILM */

.film-information {
    display:flex;
    gap:12px;
    align-items:center;
    min-width:180px;
}

.film-icon {
    width:39px;
    height:39px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    border-radius:8px;
    background:#34212b;
    font-size:18px;
}

.film-text {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.film-text strong {
    color:#ffffff;
    font-size:12px;
}

.film-text small {
    color:#929cac;
    font-size:10px;
}


/* TANGGAL DAN JAM */

.date-information,
.time-information {
    display:flex;
    flex-direction:column;
    gap:6px;
    white-space:nowrap;
}

.date-information strong,
.time-information strong {
    color:#e9edf4;
    font-size:12px;
}

.date-information small,
.time-information small {
    color:#8793a7;
    font-size:10px;
}


/* STUDIO */

.studio-badge {
    display:inline-block;
    padding:8px 11px;
    background:#25344a;
    color:#a9ceff;
    border:1px solid #344b69;
    border-radius:6px;
    font-size:11px;
    font-weight:bold;
    white-space:nowrap;
}


/* HARGA */

.ticket-price {
    color:#ff7280;
    font-size:12px;
    white-space:nowrap;
}


/* BOOKING */

.booking-count {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:30px;
    height:29px;
    padding:0 9px;
    background:#263b58;
    color:#9ec8ff;
    border-radius:7px;
    font-size:12px;
    font-weight:bold;
}


/* STATUS */

.status-badge {
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:8px 10px;
    border-radius:6px;
    font-size:9px;
    font-weight:bold;
    white-space:nowrap;
}

.status-badge i {
    width:6px;
    height:6px;
    display:inline-block;
    background:currentColor;
    border-radius:50%;
}

.status-available {
    background:#17432e;
    color:#83e5ab;
}

.status-cancelled {
    background:#501f2c;
    color:#ffb0bb;
}


/* AKSI */

.action-group {
    display:flex;
    align-items:center;
    gap:7px;
    white-space:nowrap;
}

.edit-button,
.delete-button,
.locked-button {
    padding:9px 11px;
    border:0;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:9px;
    font-weight:bold;
    cursor:pointer;
}

.edit-button {
    background:#245a91;
}

.edit-button:hover {
    background:#337dc0;
}

.delete-button {
    background:#a92b3e;
}

.delete-button:hover {
    background:#d5364c;
}

.locked-button {
    display:inline-block;
    background:#49351f;
    color:#e7c081;
    border:1px solid #73542c;
    cursor:not-allowed;
}


/* EMPTY STATE */

.empty-state {
    padding:60px 20px !important;
    text-align:center;
}

.empty-state > span {
    display:block;
    font-size:42px;
    margin-bottom:15px;
}

.empty-state h3 {
    font-size:17px;
    margin-bottom:9px;
}

.empty-state p {
    color:#929cac;
    font-size:12px;
    margin-bottom:22px;
}

.empty-state a {
    display:inline-block;
    padding:11px 17px;
    background:#e7283b;
    color:white;
    font-size:10px;
    font-weight:bold;
    text-decoration:none;
    border-radius:7px;
}


/* ===================================
   PAGINATION CUSTOM
=================================== */

.custom-pagination {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
    padding:22px 24px;
    background:#121722;
    border-top:1px solid #303747;
}

.pagination-info {
    color:#99a3b3;
    font-size:11px;
}

.pagination-info strong {
    color:white;
}

.pagination-controls {
    display:flex;
    align-items:center;
    gap:10px;
}

.page-button {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:10px 13px;
    background:#283449;
    border:1px solid #3a4b65;
    color:white;
    text-decoration:none;
    border-radius:7px;
    font-size:10px;
    font-weight:bold;
    transition:.2s;
}

.page-button:not(.disabled):hover {
    background:#e7283b;
    border-color:#e7283b;
}

.page-button.disabled {
    opacity:.35;
    cursor:not-allowed;
}

.page-number {
    color:#ff8290;
    font-size:12px;
    font-weight:bold;
    background:#31202c;
    padding:10px 15px;
    border-radius:7px;
}


/* ===================================
   CATATAN FOOTER
=================================== */

.page-footer-note {
    display:flex;
    gap:9px;
    align-items:flex-start;
    margin-top:19px;
    color:#929cac;
    font-size:11px;
    line-height:1.7;
}

.page-footer-note span {
    color:#ff9d66;
}


/* ===================================
   RESPONSIVE
=================================== */

@media(max-width:1000px) {

    .stats-grid {
        grid-template-columns:repeat(3,1fr);
        gap:10px;
    }

    .stat-card {
        padding:16px;
    }

    .filter-form {
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:650px) {

    .screening-page {
        width:94%;
        padding:28px 0 50px;
    }

    .page-header {
        align-items:flex-start;
        flex-direction:column;
    }

    .header-content h1 {
        font-size:25px;
    }

    .header-content p {
        font-size:12px;
    }

    .add-button {
        width:100%;
    }

    .stats-grid {
        grid-template-columns:1fr;
    }

    .stat-card {
        min-height:100px;
    }

    .filter-card {
        padding:16px;
    }

    .filter-form {
        grid-template-columns:1fr;
    }

    .table-top {
        padding:18px;
        align-items:flex-start;
    }

    .table-wrapper {
        padding:0 12px;
    }

    .custom-pagination {
        align-items:flex-start;
        flex-direction:column;
        padding:18px;
    }

    .pagination-controls {
        width:100%;
        justify-content:space-between;
    }

}

</style>

@endsection
