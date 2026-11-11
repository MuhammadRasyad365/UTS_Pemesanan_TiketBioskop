
@extends('layouts.app')

@section('title', $movie->title . ' - ELCinema')

@section('content')

<div class="container" style="padding:45px 0">

    <a href="{{ route('home') }}"
       style="color:#ff5967;text-decoration:none">
       ← Kembali ke Katalog Film
    </a>

    <div style="
        display:grid;
        grid-template-columns:280px 1fr;
        gap:35px;
        margin-top:30px;
        align-items:start;
    ">

        <div style="
            min-height:400px;
            background:linear-gradient(145deg,#481722,#151b2c);
            border-radius:15px;
            display:flex;
            justify-content:center;
            align-items:center;
            overflow:hidden;
        ">

            @if($movie->poster_display_url)
                <img src="{{ $movie->poster_display_url }}"
                     alt="{{ $movie->title }}"
                     style="width:100%;height:400px;object-fit:cover">
            @else
                <div class="poster-placeholder">
                    {{ $movie->title }}
                </div>
            @endif

        </div>

        <div>

            <span style="color:#ff5967">
                ELCINEMA MOVIE DETAIL
            </span>

            <h1 style="font-size:38px;margin:15px 0">
                {{ $movie->title }}
            </h1>

            <p style="color:#aab0bc;margin-bottom:20px">
                {{ $movie->genre }}
                &nbsp; | &nbsp;
                {{ $movie->duration }} menit
                &nbsp; | &nbsp;
                {{ $movie->age_rating }}
            </p>

            <h3 style="margin-bottom:12px">
                Sinopsis Film
            </h3>

            <p style="color:#c4c7d0;line-height:1.8">
                {{ $movie->synopsis }}
            </p>

            <div style="
                display:flex;
                gap:35px;
                margin:30px 0;
                flex-wrap:wrap;
            ">

                <div>
                    <small style="color:#aab0bc">Rating</small>
                    <h2 style="color:#ff5967">
                        {{ number_format((float)$movie->rating,1) }}/10
                    </h2>
                </div>

                <div>
                    <small style="color:#aab0bc">Harga Tiket</small>
                    <h2 style="color:#ff5967">
                        Rp{{ number_format(
                            (float)$movie->ticket_price,
                            0,',','.'
                        ) }}
                    </h2>
                </div>

            </div>


<div style="
    background:#171c29;
    border:1px solid #303747;
    padding:22px;
    border-radius:12px;
    margin-top:25px;
">

    <h3 style="margin-bottom:20px">
        Jadwal Penayangan
    </h3>

    @forelse($movie->screenings as $screening)

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            flex-wrap:wrap;
            padding:15px;
            background:#222938;
            border-radius:9px;
            margin-bottom:12px;
        ">

            <div>
                <strong>
                    {{ $screening->show_date->format('d M Y') }}
                </strong>

                <p style="color:#aab0bc;margin-top:5px">
                    {{ substr($screening->start_time, 0, 5) }}
                    WIB | {{ $screening->studio_name }}
                </p>

                <p style="color:#ff5967;margin-top:5px">
                    Rp{{ number_format(
                        (float)$screening->ticket_price,
                        0, ',', '.'
                    ) }}
                </p>
            </div>
                <a
                    href="{{ route('booking.create', $screening->id) }}"
                    class="btn"
                    style="
                        background:#e50914;
                        color:#ffffff;
                        padding:12px 24px;
                        border-radius:8px;
                        text-decoration:none;
                        font-weight:600;
                        border:1px solid #ff3340;
                        display:inline-block;
                        transition:all 0.3s ease;
                    "
                >
                    🎟 Pilih Kursi
                </a>

        </div>

    @empty

        <p style="color:#aab0bc">
            Belum tersedia jadwal penayangan.
        </p>

    @endforelse

</div>


        </div>

    </div>

</div>

@endsection
