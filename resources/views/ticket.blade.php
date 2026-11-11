
@extends('layouts.app')

@section('title', 'E-Ticket - ELCinema')

@section('content')

<div class="ticket-container">

    <div class="success-heading">
        <div class="success-icon">✓</div>
        <span>PEMESANAN BERHASIL</span>
        <h1>E-Ticket Anda Siap!</h1>
        <p>Simpan tiket ini dan tunjukkan kepada petugas bioskop.</p>
    </div>

    <div class="ticket-card">

        <div class="ticket-header">
            <div class="brand">
                <div class="brand-logo">E</div>
                <div>
                    <h2>ELCINEMA</h2>
                    <small>MOVIE EXPERIENCE</small>
                </div>
            </div>

            <div class="ticket-label">
                DIGITAL TICKET
                <strong>ADMIT ONE</strong>
            </div>
        </div>

        <div class="red-line"></div>

        <div class="movie-section">

            <div class="movie-poster">
                @if($booking->screening->movie->poster_url)
                    <img
                        src="{{ $booking->screening->movie->poster_url }}"
                        alt="{{ $booking->screening->movie->title }}"
                    >
                @else
                    <span>ELCINEMA</span>
                    <strong>{{ $booking->screening->movie->title }}</strong>
                @endif
            </div>

            <div class="movie-info">
                <small>YOUR MOVIE</small>

                <h1>{{ $booking->screening->movie->title }}</h1>

                <p>{{ $booking->screening->movie->genre }}</p>

                <span class="status">
                    ● {{ strtoupper($booking->status) }}
                </span>
            </div>

        </div>

        <div class="show-details">

            <div>
                <small>TANGGAL</small>
                <strong>
                    {{ $booking->screening->show_date->format('d M Y') }}
                </strong>
            </div>

            <div>
                <small>JAM TAYANG</small>
                <strong>
                    {{ substr($booking->screening->start_time, 0, 5) }} WIB
                </strong>
            </div>

            <div>
                <small>STUDIO</small>
                <strong>{{ $booking->screening->studio_name }}</strong>
            </div>

            <div>
                <small>DURASI</small>
                <strong>{{ $booking->screening->movie->duration }} Menit</strong>
            </div>

        </div>

        <div class="dashed-line"></div>

        <div class="ticket-bottom">

            <div class="customer-info">

                <small class="section-label">BOOKING INFORMATION</small>

                <div class="info-row">
                    <span>Nama Pemesan</span>
                    <strong>{{ $booking->customer_name }}</strong>
                </div>

                <div class="info-row">
                    <span>Email</span>
                    <strong>{{ $booking->customer_email }}</strong>
                </div>

                <div class="info-row">
                    <span>Nomor Telepon</span>
                    <strong>{{ $booking->customer_phone ?? '-' }}</strong>
                </div>

                <div class="seat-area">
                    <span>KURSI PILIHAN</span>

                    <div class="seat-list">
                        @foreach($booking->seat_codes_list as $seatCode)
                            <span class="seat-tag">
                                {{ $seatCode }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="booking-code">
                    <small>KODE BOOKING</small>
                    <h2>{{ $booking->booking_code }}</h2>
                </div>

            </div>

            <div class="qr-container">

                <div class="qr-box">
                    {!! $qrCode !!}
                </div>

                <strong>SCAN E-TICKET</strong>

                <p>Gunakan QR Code untuk verifikasi pemesanan.</p>

                <span>ELCINEMA VERIFIED BOOKING LINK</span>

            </div>

        </div>

        <div class="payment-total">

            <div>
                <small>TOTAL PEMESANAN</small>
                <h2>
                    Rp{{ number_format(
                        (float)$booking->total_price,
                        0, ',', '.'
                    ) }}
                </h2>
            </div>

            <div class="booking-status">
                STATUS BOOKING
                <strong>{{ strtoupper($booking->status) }}</strong>
            </div>

        </div>

        <div class="ticket-footer">
            <strong>THANK YOU FOR CHOOSING ELCINEMA</strong>
            <span>ELCINEMA · {{ $booking->id }}</span>
        </div>

    </div>

    <div class="ticket-buttons no-print">

        <button onclick="window.print()" class="btn-print">
            ▤ CETAK E-TICKET
        </button>

        <button onclick="copyBookingCode()" class="btn-copy">
            SALIN KODE BOOKING
        </button>

        <a href="{{ route('home') }}" class="btn-home">
            KEMBALI KE HOME
        </a>

    </div>

</div>

<style>

.ticket-container {
    max-width: 850px;
    width: 92%;
    margin: auto;
    padding: 45px 0 70px;
    color: #fff;
}

.success-heading {
    text-align: center;
    margin-bottom: 30px;
}

.success-icon {
    width: 60px;
    height: 60px;
    background: #174a32;
    color: #6ef1a2;
    border-radius: 50%;
    margin: 0 auto 15px;
    display: grid;
    place-items: center;
    font-size: 30px;
}

.success-heading > span {
    color: #ff5967;
    font-size: 11px;
    letter-spacing: 2px;
    font-weight: bold;
}

.success-heading h1 {
    font-size: 30px;
    margin: 10px 0;
}

.success-heading p {
    color: #aab0bc;
    font-size: 13px;
}

.ticket-card {
    background: #171c29;
    border: 1px solid #394152;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 18px 50px rgba(0,0,0,.25);
}

.ticket-header {
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand {
    display: flex;
    gap: 12px;
    align-items: center;
}

.brand-logo {
    width: 45px;
    height: 45px;
    background: #e7283b;
    border-radius: 10px;
    display: grid;
    place-items: center;
    font-size: 25px;
    font-weight: 900;
}

.brand h2 {
    color: #ff5967;
    font-size: 22px;
}

.brand small {
    color: #aab0bc;
    font-size: 9px;
    letter-spacing: 2px;
}

.ticket-label {
    text-align: right;
    color: #ff6876;
    font-size: 10px;
    letter-spacing: 2px;
}

.ticket-label strong {
    display: block;
    color: white;
    margin-top: 7px;
}

.red-line {
    height: 5px;
    background: linear-gradient(90deg,#e7283b,#ff7581,#71162d);
}

.movie-section {
    display: flex;
    gap: 24px;
    padding: 30px;
    align-items: center;
}

.movie-poster {
    width: 125px;
    height: 155px;
    flex-shrink: 0;
    background: linear-gradient(145deg,#531a2a,#171c29);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 12px;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-poster span {
    color: #ff5967;
    font-size: 9px;
    letter-spacing: 2px;
}

.movie-poster strong {
    font-size: 17px;
    margin-top: 10px;
}

.movie-info small,
.section-label {
    color: #ff5967;
    letter-spacing: 2px;
    font-size: 10px;
    font-weight: bold;
}

.movie-info h1 {
    font-size: 28px;
    margin: 10px 0;
}

.movie-info p {
    color: #aab0bc;
    font-size: 13px;
}

.status {
    display: inline-block;
    margin-top: 15px;
    padding: 7px 12px;
    background: #17432e;
    color: #68e8a0;
    border-radius: 20px;
    font-size: 10px;
    font-weight: bold;
}

.show-details {
    margin: 0 30px;
    background: #222b3b;
    padding: 20px;
    border-radius: 10px;
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 15px;
}

.show-details div {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.show-details small {
    color: #aab0bc;
    font-size: 9px;
    letter-spacing: 1px;
}

.show-details strong {
    font-size: 13px;
}

.dashed-line {
    margin: 30px 0;
    border-top: 2px dashed #485166;
}

.ticket-bottom {
    display: grid;
    grid-template-columns: 1fr 215px;
    gap: 25px;
    padding: 0 30px 30px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #303747;
    font-size: 12px;
}

.info-row span {
    color: #aab0bc;
}

.info-row strong {
    text-align: right;
    overflow-wrap: anywhere;
}

.seat-area {
    margin-top: 18px;
}

.seat-area > span {
    color: #ff5967;
    font-size: 10px;
    font-weight: bold;
}

.seat-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.seat-list strong {
    background: #253f64;
    color: #a3d0ff;
    padding: 9px 13px;
    border-radius: 6px;
    font-size: 12px;
}

.booking-code {
    margin-top: 20px;
    padding: 15px;
    background: #291a25;
    border: 1px dashed #a34759;
    border-radius: 9px;
}

.booking-code small {
    color: #aab0bc;
    font-size: 9px;
    letter-spacing: 1px;
}

.booking-code h2 {
    color: #ff6876;
    margin-top: 9px;
    font-size: 18px;
    overflow-wrap: anywhere;
}

.qr-container {
    background: white;
    color: #161923;
    border-radius: 12px;
    padding: 14px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    align-self: start;
}

.qr-box {
    width: 100%;
    display: flex;
    justify-content: center;
}

.qr-box svg {
    max-width: 100%;
    height: auto;
    display: block;
}

.qr-container > strong {
    font-size: 12px;
    margin-top: 12px;
}

.qr-container p {
    color: #687080;
    font-size: 10px;
    line-height: 1.5;
    margin-top: 7px;
}

.qr-container > span {
    color: #15965a;
    font-size: 8px;
    font-weight: bold;
    margin-top: 10px;
    letter-spacing: 1px;
}

.payment-total {
    padding: 22px 30px;
    background: #291820;
    border-top: 1px solid #743442;
    border-bottom: 1px solid #743442;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.payment-total small {
    color: #d4adb5;
    font-size: 10px;
    letter-spacing: 1px;
}

.payment-total h2 {
    color: #ff6876;
    margin-top: 8px;
    font-size: 26px;
}

.booking-status {
    color: #d4adb5;
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 10px;
    text-align: right;
}

.booking-status strong {
    color: #6ef1a2;
    font-size: 13px;
}

.ticket-footer {
    display: flex;
    justify-content: space-between;
    padding: 22px 30px;
    gap: 12px;
}

.ticket-footer strong {
    color: #ff6876;
    font-size: 10px;
    letter-spacing: 1px;
}

.ticket-footer span {
    color: #aab0bc;
    font-size: 10px;
}

.ticket-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 25px;
}

.ticket-buttons button,
.ticket-buttons a {
    border: 0;
    text-decoration: none;
    padding: 14px 20px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    font-size: 11px;
    cursor: pointer;
}

.btn-print {
    background: #e7283b;
}

.btn-print:hover {
    background: #bd1729;
}

.btn-copy {
    background: #283449;
}

.btn-home {
    background: #394252;
}

@media(max-width:600px) {

    .ticket-header {
        padding: 20px;
    }

    .movie-section {
        padding: 20px;
        gap: 15px;
    }

    .movie-poster {
        width: 90px;
        height: 125px;
    }

    .movie-info h1 {
        font-size: 21px;
    }

    .show-details {
        margin: 0 15px;
        grid-template-columns: repeat(2,1fr);
    }

    .ticket-bottom {
        grid-template-columns: 1fr;
        padding: 0 20px 25px;
    }

    .qr-container {
        width: 215px;
        margin: auto;
    }

    .payment-total {
        padding: 20px;
    }

    .payment-total h2 {
        font-size: 20px;
    }

    .ticket-footer {
        flex-direction: column;
        padding: 20px;
    }
}

@page {
    size: A4;
    margin: 10mm;
}

@media print {

    body {
        background: white !important;
        color: #111 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    nav, footer, .no-print,
    .success-heading, .ticket-buttons {
        display: none !important;
    }

    .ticket-container {
        max-width: none;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .ticket-card {
        background: white !important;
        color: #111 !important;
        border: 1px solid #ccc;
        box-shadow: none;
        break-inside: avoid;
    }

    .show-details {
        background: #f0f2f5 !important;
    }

    .movie-info h1,
    .show-details strong,
    .info-row strong {
        color: #111 !important;
    }

    .qr-container {
        border: 1px solid #ddd;
    }
}

</style>

<script>
function copyBookingCode() {

    const code = @json($booking->booking_code);

    if (navigator.clipboard && window.isSecureContext) {

        navigator.clipboard.writeText(code)
            .then(() => alert('Kode booking berhasil disalin.'))
            .catch(() => alert('Gagal menyalin kode booking.'));

    } else {
        alert('Kode booking: ' + code);
    }
}
</script>

@endsection
