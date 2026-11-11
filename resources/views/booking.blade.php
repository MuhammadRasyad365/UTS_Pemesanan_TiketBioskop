
@extends('layouts.app')

@section('title', 'Pilih Kursi - ELCinema')

@section('content')

@php
    $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
@endphp

<div class="booking-page">

    <!-- HEADER PEMESANAN -->
    <div class="booking-header">
        <div>
            <a href="{{ route('movies.detail', $screening->movie_id) }}"
               class="back-link">
                <span>←</span> Kembali ke Detail Film
            </a>

            <div class="page-label">ELCINEMA BOOKING SYSTEM</div>

            <h1>Pilih Kursi Anda</h1>

            <p class="header-description">
                Pilih kursi favoritmu dan nikmati pengalaman
                menonton film bersama ELCinema.
            </p>
        </div>

        <div class="booking-badge">
            <span class="badge-dot"></span>
            BOOKING ONLINE
        </div>
    </div>

    <!-- INFORMASI FILM -->
    <div class="movie-information">

        <div class="movie-icon">
            🎬
        </div>

        <div class="movie-description">
            <span class="movie-label">FILM YANG DIPILIH</span>

            <h2>{{ $screening->movie->title }}</h2>

            <p>{{ $screening->movie->genre }}</p>
        </div>

        <div class="movie-detail-item">
            <span>TANGGAL</span>
            <strong>
                {{ $screening->show_date->format('d M Y') }}
            </strong>
        </div>

        <div class="movie-detail-item">
            <span>JAM TAYANG</span>
            <strong>
                {{ substr($screening->start_time, 0, 5) }} WIB
            </strong>
        </div>

        <div class="movie-detail-item">
            <span>STUDIO</span>
            <strong>{{ $screening->studio_name }}</strong>
        </div>

        <div class="movie-detail-item ticket-price-info">
            <span>HARGA TIKET</span>
            <strong>
                Rp{{ number_format(
                    (float) $screening->ticket_price,
                    0, ',', '.'
                ) }}
            </strong>
        </div>

    </div>

    <!-- ERROR VALIDASI -->
    @if($errors->any())
        <div class="error-box">
            <strong>Terjadi kesalahan!</strong>

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- FORM PEMESANAN -->
    <form action="{{ route('booking.store') }}"
          method="POST"
          id="bookingForm">

        @csrf

        <input type="hidden"
               name="screening_id"
               value="{{ $screening->id }}">

        <div class="booking-layout">

            <!-- BAGIAN PEMILIHAN KURSI -->
            <section class="seat-panel">

                <div class="panel-heading">
                    <div>
                        <span class="section-label">STEP 01</span>
                        <h2>Pilih Kursi</h2>
                        <p>Pilih tempat duduk terbaik untukmu.</p>
                    </div>

                    <div class="seat-count">
                        <span id="seatCount">0</span>/8 Kursi
                    </div>
                </div>

                <!-- KETERANGAN KURSI -->
                <div class="seat-legend">

                    <div class="legend-item">
                        <span class="legend-color available"></span>
                        <span>Tersedia</span>
                    </div>

                    <div class="legend-item">
                        <span class="legend-color occupied"></span>
                        <span>Terisi</span>
                    </div>

                    <div class="legend-item">
                        <span class="legend-color selected"></span>
                        <span>Dipilih</span>
                    </div>

                </div>

                <!-- LAYAR BIOSKOP -->
                <div class="cinema-screen-area">

                    <div class="screen-light"></div>

                    <div class="cinema-screen">
                        LAYAR BIOSKOP
                    </div>

                    <p>FRONT SCREEN</p>

                </div>

                <!-- DENAH KURSI -->
                <div class="seat-map">

                    @foreach($rows as $row)

                        <div class="seat-row">

                            <div class="row-label">{{ $row }}</div>

                            @foreach(range(1, 8) as $number)

                                @php
                                    $seatCode = $row . $number;

                                    $isBooked = in_array(
                                        $seatCode,
                                        $bookedSeats,
                                        true
                                    );
                                @endphp

                                <button
                                    type="button"
                                    class="seat {{ $isBooked ? 'seat-booked' : 'seat-available' }}"
                                    data-seat="{{ $seatCode }}"
                                    aria-label="Kursi {{ $seatCode }}"
                                    {{ $isBooked ? 'disabled' : '' }}
                                >
                                    {{ $number }}
                                </button>

                            @endforeach

                            <div class="row-label">{{ $row }}</div>

                        </div>

                    @endforeach

                </div>

                <div class="seat-note">
                    <span>ⓘ</span>
                    Klik kursi yang berwarna hijau untuk memilih.
                    Kursi merah tidak dapat dipesan.
                </div>

            </section>

            <!-- BAGIAN RINGKASAN DAN FORM -->
            <aside class="booking-sidebar">

                <!-- RINGKASAN KURSI -->
                <div class="summary-card">

                    <span class="section-label">STEP 02</span>

                    <h2>Ringkasan Pesanan</h2>

                    <div class="summary-divider"></div>

                    <div class="summary-row">
                        <span>Film</span>

                        <strong>
                            {{ $screening->movie->title }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Tanggal</span>

                        <strong>
                            {{ $screening->show_date->format('d/m/Y') }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Jam</span>

                        <strong>
                            {{ substr($screening->start_time, 0, 5) }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Harga / Kursi</span>

                        <strong>
                            Rp{{ number_format(
                                (float) $screening->ticket_price,
                                0, ',', '.'
                            ) }}
                        </strong>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="selected-seat-heading">
                        Kursi Dipilih
                    </div>

                    <div id="selectedSeatList"
                         class="selected-seat-list">

                        <span class="empty-seat">
                            Belum ada kursi dipilih
                        </span>

                    </div>

                    <div class="summary-divider"></div>

                    <div class="total-section">

                        <span>Total Pembayaran</span>

                        <h2 id="totalPrice">Rp0</h2>

                    </div>

                </div>

                <!-- FORM DATA PELANGGAN -->
                <div class="customer-card">

                    <span class="section-label">STEP 03</span>

                    <h2>Data Pemesan</h2>

                    <p class="form-description">
                        Isi identitas untuk menyelesaikan pemesanan.
                    </p>

                    <div class="form-group">

                        <label for="customer_name">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            placeholder="Masukkan nama lengkap"
                            maxlength="255"
                            autocomplete="name"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="customer_email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="customer_email"
                            name="customer_email"
                            value="{{ old('customer_email') }}"
                            placeholder="contoh@email.com"
                            maxlength="255"
                            autocomplete="email"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="customer_phone">
                            Nomor Telepon
                        </label>

                        <input
                            type="tel"
                            id="customer_phone"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            placeholder="08xxxxxxxxxx"
                            maxlength="25"
                            autocomplete="tel"
                        >

                    </div>

                    <div class="secure-info">
                        <span>🔒</span>
                        Data pemesanan akan disimpan secara aman
                        di database ELCinema.
                    </div>

                    <!-- INPUT KURSI OTOMATIS -->
                    <div id="selectedSeatInputs"></div>

                    <button type="submit"
                            class="confirm-button"
                            id="confirmButton">

                        <span>Konfirmasi Pemesanan</span>

                        <span class="button-arrow">→</span>

                    </button>

                    <p class="confirmation-note">
                        Pastikan data dan pilihan kursi sudah benar.
                    </p>

                </div>

            </aside>

        </div>

    </form>

</div>

<!-- CSS -->
<style>

.booking-page {
    max-width: 1250px;
    width: 90%;
    margin: auto;
    padding: 45px 0 70px;
    color: #ffffff;
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.back-link {
    color: #ff5967;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 24px;
    font-size: 14px;
}

.back-link:hover {
    color: #ffffff;
}

.page-label,
.section-label {
    color: #ff5967;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
}

.booking-header h1 {
    font-size: 36px;
    margin: 10px 0;
}

.header-description {
    color: #aab0bc;
    font-size: 14px;
}

.booking-badge {
    padding: 11px 17px;
    background: #25151d;
    color: #ff7180;
    border: 1px solid #642b37;
    border-radius: 30px;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 1px;
    white-space: nowrap;
}

.badge-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #e7283b;
    border-radius: 50%;
    margin-right: 7px;
}

/* INFORMASI FILM */

.movie-information {
    background: linear-gradient(110deg, #191e2c, #211722);
    border: 1px solid #303747;
    border-radius: 15px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 23px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.movie-icon {
    background: #421923;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 27px;
}

.movie-description {
    flex: 1;
    min-width: 150px;
}

.movie-label {
    font-size: 10px;
    color: #ff5967;
    letter-spacing: 1.5px;
}

.movie-description h2 {
    margin: 7px 0;
    font-size: 20px;
}

.movie-description p {
    color: #aab0bc;
    font-size: 12px;
}

.movie-detail-item {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 100px;
}

.movie-detail-item span {
    color: #939baa;
    font-size: 10px;
    letter-spacing: 1px;
}

.movie-detail-item strong {
    font-size: 13px;
}

.ticket-price-info strong {
    color: #ff5967;
}

/* LAYOUT BOOKING */

.booking-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(310px, 0.85fr);
    gap: 25px;
    align-items: start;
}

.seat-panel,
.summary-card,
.customer-card {
    background: #141925;
    border: 1px solid #2a3243;
    border-radius: 15px;
}

.seat-panel {
    padding: 28px;
}

.panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.panel-heading h2,
.summary-card h2,
.customer-card h2 {
    font-size: 21px;
    margin: 8px 0;
}

.panel-heading p,
.form-description {
    color: #929cac;
    font-size: 13px;
}

.seat-count {
    padding: 10px 14px;
    background: #252d3d;
    color: #cbd5e1;
    border-radius: 9px;
    font-size: 12px;
}

/* KETERANGAN KURSI */

.seat-legend {
    display: flex;
    justify-content: center;
    gap: 24px;
    margin: 35px 0 30px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #c0c7d3;
    font-size: 12px;
}

.legend-color {
    width: 13px;
    height: 13px;
    border-radius: 4px;
}

.legend-color.available {
    background: #27844d;
}

.legend-color.occupied {
    background: #c52d40;
}

.legend-color.selected {
    background: #1677d2;
}

/* LAYAR BIOSKOP */

.cinema-screen-area {
    text-align: center;
    margin: 0 auto 40px;
    width: 85%;
}

.screen-light {
    height: 4px;
    background: #ff5967;
    border-radius: 50%;
    box-shadow: 0 0 18px #ff5967;
    margin-bottom: 5px;
}

.cinema-screen {
    padding: 14px;
    background: linear-gradient(
        to bottom,
        #354052,
        #252b3a
    );
    color: #e0e6f0;
    font-size: 11px;
    letter-spacing: 4px;
    font-weight: bold;
    border-radius: 0 0 50% 50%;
    transform: perspective(150px) rotateX(-5deg);
}

.cinema-screen-area p {
    color: #737d91;
    font-size: 9px;
    margin-top: 13px;
    letter-spacing: 2px;
}

/* DENAH KURSI */

.seat-map {
    display: flex;
    flex-direction: column;
    gap: 17px;
    width: 100%;
    max-width: 520px;
    margin: auto;
}

.seat-row {
    display: grid;
    grid-template-columns: 20px repeat(8, minmax(0, 1fr)) 20px;
    gap: 8px;
    align-items: center;
}

.row-label {
    color: #ff6674;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
}

.seat {
    width: 100%;
    aspect-ratio: 1 / 0.9;
    border: none;
    border-radius: 7px 7px 4px 4px;
    color: #ffffff;
    font-weight: bold;
    font-size: 12px;
    transition: 0.2s;
}

.seat-available {
    background: #27844d;
    cursor: pointer;
    box-shadow: 0 3px 0 #155d34;
}

.seat-available:hover {
    background: #35b86b;
    transform: translateY(-3px);
}

.seat-booked {
    background: #b52c3b;
    cursor: not-allowed;
    opacity: 0.8;
    box-shadow: 0 3px 0 #781d2b;
}

.seat-selected {
    background: #1677d2 !important;
    box-shadow: 0 3px 0 #0b4c91 !important;
    outline: 2px solid #90c8ff;
    transform: translateY(-3px);
}

.seat-note {
    background: #1b2433;
    padding: 13px;
    margin-top: 32px;
    border-radius: 8px;
    color: #aab0bc;
    font-size: 11px;
    line-height: 1.6;
}

/* SIDEBAR */

.booking-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.summary-card,
.customer-card {
    padding: 24px;
}

.summary-divider {
    height: 1px;
    background: #303747;
    margin: 19px 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin: 15px 0;
    font-size: 12px;
}

.summary-row span {
    color: #929cac;
}

.summary-row strong {
    color: #e8edf5;
    text-align: right;
}

.selected-seat-heading {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 12px;
}

.selected-seat-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.selected-seat-tag {
    background: #173d68;
    color: #94caff;
    border: 1px solid #2868a9;
    padding: 8px 12px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: bold;
}

.empty-seat {
    color: #7e899a;
    font-size: 12px;
}

.total-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.total-section span {
    color: #aab0bc;
    font-size: 13px;
}

.total-section h2 {
    color: #ff5967;
    font-size: 23px;
    margin: 0;
}

/* FORM PELANGGAN */

.form-description {
    margin-bottom: 22px;
    line-height: 1.6;
}

.form-group {
    margin-bottom: 19px;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 9px;
    color: #e2e7ef;
}

.form-group input {
    width: 100%;
    padding: 13px 14px;
    background: #0d121c;
    color: white;
    border: 1px solid #343d4e;
    border-radius: 8px;
    outline: none;
    font-size: 13px;
    transition: 0.2s;
}

.form-group input:focus {
    border-color: #ef3949;
    box-shadow: 0 0 0 3px rgba(239, 57, 73, 0.12);
}

.form-group input::placeholder {
    color: #667085;
}

.secure-info {
    display: flex;
    gap: 8px;
    color: #9ca7b7;
    background: #1c2635;
    padding: 12px;
    border-radius: 8px;
    font-size: 10px;
    line-height: 1.6;
    margin-bottom: 20px;
}

.confirm-button {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 9px;
    color: white;
    background: linear-gradient(100deg, #e7283b, #a91631);
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.2s;
}

.confirm-button:hover {
    background: linear-gradient(100deg, #ff4052, #c61a3a);
    transform: translateY(-2px);
}

.button-arrow {
    font-size: 20px;
}

.confirmation-note {
    color: #788397;
    font-size: 10px;
    text-align: center;
    margin-top: 14px;
}

.error-box {
    background: #451d29;
    color: #ffd4da;
    padding: 16px;
    margin-bottom: 22px;
    border: 1px solid #943448;
    border-radius: 9px;
    font-size: 13px;
}

.error-box p {
    margin-top: 7px;
}

/* RESPONSIVE */

@media(max-width: 950px) {
    .booking-layout {
        grid-template-columns: 1fr;
    }

    .booking-sidebar {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 650px) {
    .booking-page {
        width: 94%;
        padding-top: 25px;
    }

    .booking-header {
        align-items: flex-start;
    }

    .booking-header h1 {
        font-size: 28px;
    }

    .booking-badge {
        font-size: 9px;
        padding: 9px;
    }

    .movie-information {
        gap: 15px;
    }

    .movie-description {
        min-width: calc(100% - 85px);
    }

    .movie-detail-item {
        flex: 1 1 40%;
    }

    .seat-panel {
        padding: 17px 10px;
    }

    .seat-row {
        gap: 5px;
        grid-template-columns: 15px repeat(8, minmax(0, 1fr)) 15px;
    }

    .seat {
        font-size: 10px;
    }

    .booking-sidebar {
        display: flex;
    }

    .summary-card,
    .customer-card {
        padding: 20px;
    }
}

</style>

<!-- JAVASCRIPT PEMILIHAN KURSI -->
<script>

document.addEventListener('DOMContentLoaded', function () {

    const seats = document.querySelectorAll(
        '.seat-available'
    );

    const selectedSeats = new Set();

    const ticketPrice = Number(
        @json((float) $screening->ticket_price)
    );

    const oldSeats = @json(old('seat_codes', []));

    const selectedSeatList =
        document.getElementById('selectedSeatList');

    const selectedSeatInputs =
        document.getElementById('selectedSeatInputs');

    const totalPrice =
        document.getElementById('totalPrice');

    const seatCount =
        document.getElementById('seatCount');

    const confirmButton =
        document.getElementById('confirmButton');

    const currency = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    });

    // Memilih kembali kursi setelah validasi form gagal.
    if (Array.isArray(oldSeats)) {

        oldSeats.forEach(function (code) {

            const seat = Array.from(seats).find(
                item => item.dataset.seat === code
            );

            if (seat && selectedSeats.size < 8) {
                selectedSeats.add(code);
                seat.classList.add('seat-selected');
            }
        });
    }

    function updateBooking() {

        const seatList = Array.from(selectedSeats);

        seatCount.textContent = seatList.length;

        // Menampilkan daftar kursi yang dipilih.
        selectedSeatList.innerHTML = '';

        if (seatList.length === 0) {

            selectedSeatList.innerHTML =
                '<span class="empty-seat">Belum ada kursi dipilih</span>';

        } else {

            seatList.forEach(function (code) {

                const tag = document.createElement('span');

                tag.className = 'selected-seat-tag';
                tag.textContent = code;

                selectedSeatList.appendChild(tag);
            });
        }

        // Menghitung total pembayaran.
        const total = seatList.length * ticketPrice;

        totalPrice.textContent = currency.format(total);

        // Membuat input seat_codes[] secara otomatis.
        selectedSeatInputs.innerHTML = '';

        seatList.forEach(function (code) {

            const input = document.createElement('input');

            input.type = 'hidden';
            input.name = 'seat_codes[]';
            input.value = code;

            selectedSeatInputs.appendChild(input);
        });

        // Mengaktifkan tombol hanya jika minimal satu kursi dipilih.
        confirmButton.disabled = seatList.length === 0;

        confirmButton.style.opacity =
            seatList.length === 0 ? '0.5' : '1';
    }

    // Event klik untuk masing-masing kursi.
    seats.forEach(function (seat) {

        seat.addEventListener('click', function () {

            const code = seat.dataset.seat;

            if (selectedSeats.has(code)) {

                selectedSeats.delete(code);

                seat.classList.remove('seat-selected');

            } else {

                if (selectedSeats.size >= 8) {

                    alert(
                        'Maksimal pemesanan adalah 8 kursi.'
                    );

                    return;
                }

                selectedSeats.add(code);

                seat.classList.add('seat-selected');
            }

            updateBooking();
        });

    });

    // Validasi sebelum mengirim form.
    document.getElementById('bookingForm')
        .addEventListener('submit', function (event) {

            if (selectedSeats.size === 0) {

                event.preventDefault();

                alert('Silakan pilih minimal satu kursi.');

                return;
            }

        });

    updateBooking();

});

</script>

@endsection
