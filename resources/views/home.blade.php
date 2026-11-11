
@extends('layouts.app')

@section('title', 'ELCinema - Premium Cinema Experience')

@section('content')

@php
    $featuredMovie = $featuredMovie
        ?? $nowShowing->first()
        ?? $comingSoon->first();
@endphp

<div class="home-page">

    <!-- =================================
         HERO BANNER
    ================================== -->

    <section class="hero-section">

        <div class="hero-background"></div>

        <div class="hero-content">

            <div class="hero-label">
                <span></span>
                WELCOME TO ELCINEMA
            </div>

            <h1>
                Experience The<br>
                <span>Magic of Cinema.</span>
            </h1>

            <p>
                Nikmati pengalaman menonton film terbaik
                dengan kualitas hiburan yang luar biasa.
                Temukan film favoritmu dan pesan tiket
                bioskop dengan mudah di ELCinema.
            </p>

            <div class="hero-buttons">

                @if($featuredMovie)

                    <a
                        href="{{ route('movies.detail', $featuredMovie->id) }}"
                        class="primary-hero-button"
                    >
                        <span>▶</span>
                        LIHAT FILM UNGGULAN
                    </a>

                @endif

                <a href="#film" class="secondary-hero-button">
                    JELAJAHI FILM
                    <span>↓</span>
                </a>

            </div>

            <div class="hero-features">

                <div>
                    <strong>{{ $totalMovies }}</strong>
                    <small>FILM TERSEDIA</small>
                </div>

                <div>
                    <strong>{{ $nowShowing->count() }}</strong>
                    <small>NOW SHOWING</small>
                </div>

                <div>
                    <strong>{{ $comingSoon->count() }}</strong>
                    <small>COMING SOON</small>
                </div>

            </div>

        </div>


        <!-- POSTER UNGGULAN -->
        @if($featuredMovie)

            <div class="hero-poster-area">

                <div class="hero-poster-glow"></div>

                <div class="hero-poster">

                    @if($featuredMovie->poster_display_url)

                        <img
                            src="{{ $featuredMovie->poster_display_url }}"
                            alt="{{ $featuredMovie->title }}"
                        >

                    @else

                        <div class="poster-fallback">
                            <span>🎬</span>
                            <strong>{{ $featuredMovie->title }}</strong>
                        </div>

                    @endif

                </div>

                <div class="featured-label">

                    <span>✦</span>

                    <div>
                        <small>FEATURED MOVIE</small>

                        <strong>{{ $featuredMovie->title }}</strong>
                    </div>

                </div>

            </div>

        @endif

    </section>


    <!-- =================================
         NOW SHOWING
    ================================== -->

    <section class="movie-section" id="film">

        <div class="section-header">

            <div>

                <span class="section-eyebrow">
                    ELCINEMA MOVIE COLLECTION
                </span>

                <h2>Now <span>Showing</span></h2>

                <p>
                    Pilihan film terbaik yang sedang tayang di ELCinema.
                </p>

            </div>

            <div class="section-count">
                {{ $nowShowing->count() }} FILM
            </div>

        </div>


        <div class="movie-grid">

            @forelse($nowShowing as $movie)

                <article class="movie-card">

                    <!-- POSTER -->
                    <a
                        href="{{ route('movies.detail', $movie->id) }}"
                        class="movie-poster"
                    >

                        @if($movie->poster_display_url)

                            <img
                                src="{{ $movie->poster_display_url }}"
                                alt="{{ $movie->title }}"
                                loading="lazy"
                            >

                        @else

                            <div class="poster-fallback">
                                <span>🎬</span>
                                <strong>{{ $movie->title }}</strong>
                            </div>

                        @endif

                        <span class="movie-status">
                            NOW SHOWING
                        </span>

                        @if($movie->rating)
                            <span class="movie-rating">
                                ★ {{ number_format((float)$movie->rating, 1) }}
                            </span>
                        @endif

                        <span class="poster-overlay"></span>

                    </a>


                    <!-- INFORMASI FILM -->
                    <div class="movie-information">

                        <div class="movie-title-row">

                            <h3>
                                {{ $movie->title }}
                            </h3>

                        </div>

                        <p class="movie-genre">
                            {{ $movie->genre }}
                        </p>

                        <div class="movie-metadata">

                            <span>
                                ◷ {{ $movie->duration }} menit
                            </span>

                            <span>
                                {{ $movie->age_rating }}
                            </span>

                        </div>

                        <div class="movie-price-row">

                            <div>
                                <small>HARGA TIKET MULAI</small>

                                <strong>
                                    Rp{{ number_format(
                                        (float)$movie->ticket_price,
                                        0, ',', '.'
                                    ) }}
                                </strong>
                            </div>

                        </div>

                        <a
                            href="{{ route('movies.detail', $movie->id) }}"
                            class="movie-detail-button"
                        >
                            LIHAT DETAIL
                            <span>→</span>
                        </a>

                    </div>

                </article>

            @empty

                <div class="empty-movies">

                    <span>🎬</span>

                    <h3>Belum Ada Film Tayang</h3>

                    <p>
                        Film yang sedang tayang akan ditampilkan di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    <!-- =================================
         COMING SOON
    ================================== -->

    <section class="movie-section coming-section">

        <div class="section-header">

            <div>

                <span class="section-eyebrow">
                    COMING SOON COLLECTION
                </span>

                <h2>Coming <span>Soon</span></h2>

                <p>
                    Film menarik yang akan segera hadir di layar ELCinema.
                </p>

            </div>

            <div class="section-count coming-count">
                {{ $comingSoon->count() }} FILM
            </div>

        </div>


        <div class="movie-grid">

            @forelse($comingSoon as $movie)

                <article class="movie-card coming-card">

                    <a
                        href="{{ route('movies.detail', $movie->id) }}"
                        class="movie-poster"
                    >

                        @if($movie->poster_display_url)

                            <img
                                src="{{ $movie->poster_display_url }}"
                                alt="{{ $movie->title }}"
                                loading="lazy"
                            >

                        @else

                            <div class="poster-fallback">
                                <span>🎬</span>
                                <strong>{{ $movie->title }}</strong>
                            </div>

                        @endif

                        <span class="movie-status coming-status">
                            COMING SOON
                        </span>

                        <span class="poster-overlay"></span>

                    </a>


                    <div class="movie-information">

                        <h3>{{ $movie->title }}</h3>

                        <p class="movie-genre">
                            {{ $movie->genre }}
                        </p>

                        <div class="movie-metadata">

                            <span>
                                ◷ {{ $movie->duration }} menit
                            </span>

                            <span>
                                {{ $movie->age_rating }}
                            </span>

                        </div>

                        <div class="release-information">

                            <small>TANGGAL RILIS</small>

                            <strong>
                                {{ $movie->release_date
                                    ? $movie->release_date->format('d M Y')
                                    : 'Segera diumumkan' }}
                            </strong>

                        </div>

                        <a
                            href="{{ route('movies.detail', $movie->id) }}"
                            class="movie-detail-button"
                        >
                            LIHAT DETAIL
                            <span>→</span>
                        </a>

                    </div>

                </article>

            @empty

                <div class="empty-movies">

                    <span>🍿</span>

                    <h3>Film Baru Segera Hadir</h3>

                    <p>
                        Nantikan koleksi film terbaru ELCinema.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    <!-- =================================
         PROMOTION BANNER
    ================================== -->

    <section class="cinema-promotion">

        <div class="promotion-content">

            <span>YOUR CINEMA EXPERIENCE</span>

            <h2>
                Your Next Favorite Movie<br>
                Is Waiting For You.
            </h2>

            <p>
                Pilih film favorit, tentukan jadwal,
                dan nikmati pengalaman bioskop bersama ELCinema.
            </p>

            <a href="#film">
                TEMUKAN FILM FAVORITMU →
            </a>

        </div>

        <div class="promotion-icon">
            <span>🎟️</span>
        </div>

    </section>


    <!-- =================================
         FOOTER CONTENT
    ================================== -->

    <div class="home-bottom">

        <strong>ELCINEMA</strong>

        <span>
            MOVIE ENTERTAINMENT EXPERIENCE
        </span>

    </div>

</div>


<style>

/* =====================================
   HOME GLOBAL
===================================== */

.home-page {
    width:100%;
    max-width:1450px;
    margin:0 auto;
    padding:35px 5% 65px;
    color:#ffffff;
    font-family:Arial, Helvetica, sans-serif;
}

.home-page * {
    box-sizing:border-box;
}


/* =====================================
   HERO SECTION
===================================== */

.hero-section {
    min-height:450px;
    position:relative;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:30px;
    padding:45px 55px;
    overflow:hidden;
    border-radius:20px;

    background:linear-gradient(
        115deg,
        #451622,
        #241c30 55%,
        #151b2d
    );

    border:1px solid #4b2b3a;
    margin-bottom:65px;
}

.hero-background {
    position:absolute;
    width:420px;
    height:420px;
    right:15%;
    top:-200px;
    border-radius:50%;
    background:rgba(229,52,74,.10);
    filter:blur(20px);
}

.hero-content {
    position:relative;
    z-index:2;
    max-width:620px;
}

.hero-label {
    display:flex;
    gap:10px;
    align-items:center;
    color:#ff9aa4;
    font-weight:bold;
    font-size:10px;
    letter-spacing:2.5px;
}

.hero-label span {
    width:8px;
    height:8px;
    border-radius:50%;
    background:#ef3045;
    box-shadow:0 0 12px #ef3045;
}

.hero-content h1 {
    font-size:45px;
    font-weight:900;
    line-height:1.16;
    margin:20px 0;
    letter-spacing:-1.4px;
}

.hero-content h1 span {
    color:#ff6576;
}

.hero-content > p {
    max-width:550px;
    font-size:13px;
    color:#d2c5ce;
    line-height:1.9;
}

.hero-buttons {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-top:28px;
}

.primary-hero-button,
.secondary-hero-button {
    padding:14px 18px;
    border-radius:8px;
    font-size:10px;
    font-weight:bold;
    text-decoration:none;
    transition:.2s;
}

.primary-hero-button {
    background:#e7283b;
    color:white;
}

.primary-hero-button span {
    margin-right:8px;
}

.primary-hero-button:hover {
    background:#bf1e33;
    transform:translateY(-2px);
}

.secondary-hero-button {
    border:1px solid #94717f;
    color:white;
}

.secondary-hero-button span {
    margin-left:9px;
}

.secondary-hero-button:hover {
    background:rgba(255,255,255,.10);
}

.hero-features {
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:35px;
    border-top:1px solid rgba(255,255,255,.15);
    margin-top:35px;
    padding-top:20px;
}

.hero-features > div {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.hero-features strong {
    font-size:21px;
    color:#ffffff;
}

.hero-features small {
    color:#b8a4b0;
    font-size:9px;
    letter-spacing:1px;
}


/* POSTER UNGGULAN */

.hero-poster-area {
    position:relative;
    z-index:2;
    width:260px;
    flex-shrink:0;
    margin-right:25px;
}

.hero-poster-glow {
    position:absolute;
    inset:5% -5%;
    border-radius:50%;
    background:rgba(230,39,60,.20);
    filter:blur(45px);
}

.hero-poster {
    position:relative;
    width:100%;
    aspect-ratio:2/3;
    max-height:335px;
    overflow:hidden;
    border:5px solid rgba(255,255,255,.12);
    border-radius:13px;
    box-shadow:0 20px 45px rgba(0,0,0,.4);
    transform:rotate(3deg);
}

.hero-poster img {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.hero-poster .poster-fallback {
    width:100%;
    height:100%;
    border-radius:0;
}

.featured-label {
    position:relative;
    display:flex;
    align-items:center;
    gap:12px;
    padding:13px;
    background:#171d2a;
    border:1px solid #424355;
    border-radius:9px;
    margin-top:18px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}

.featured-label > span {
    color:#ffbe66;
    font-size:22px;
}

.featured-label div {
    display:flex;
    flex-direction:column;
    gap:5px;
    min-width:0;
}

.featured-label small {
    color:#ff7886;
    font-size:8px;
    letter-spacing:1px;
}

.featured-label strong {
    font-size:11px;
    overflow-wrap:anywhere;
}


/* =====================================
   SECTION FILM
===================================== */

.movie-section {
    margin-bottom:60px;
    scroll-margin-top:30px;
}

.section-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:20px;
    margin-bottom:25px;
}

.section-eyebrow {
    color:#ff6576;
    font-size:10px;
    font-weight:bold;
    letter-spacing:2px;
}

.section-header h2 {
    font-size:29px;
    margin:9px 0;
    font-weight:800;
}

.section-header h2 span {
    color:#ff6274;
}

.section-header p {
    color:#929cac;
    font-size:12px;
    line-height:1.7;
}

.section-count {
    padding:10px 14px;
    color:#ff8792;
    background:#351e2a;
    border:1px solid #643344;
    border-radius:7px;
    font-size:9px;
    font-weight:bold;
    letter-spacing:1px;
}


/* =====================================
   GRID FILM
===================================== */

.movie-grid {
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:22px;
    align-items:stretch;
}

.movie-card {
    background:#171c29;
    border:1px solid #2d3545;
    border-radius:13px;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    transition:.25s;
}

.movie-card:hover {
    transform:translateY(-6px);
    border-color:#9a3546;
    box-shadow:0 15px 35px rgba(0,0,0,.3);
}


/* POSTER */

.movie-poster {
    position:relative;
    display:block;
    overflow:hidden;
    width:100%;
    aspect-ratio:2/3;
    background:linear-gradient(145deg,#421824,#151c2c);
}

.movie-poster > img {
    display:block;
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform .5s;
}

.movie-card:hover .movie-poster > img {
    transform:scale(1.05);
}

.poster-fallback {
    width:100%;
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    gap:20px;
    padding:20px;
    text-align:center;
    background:linear-gradient(145deg,#481b29,#171d30);
}

.poster-fallback span {
    font-size:45px;
}

.poster-fallback strong {
    color:white;
    font-size:19px;
    line-height:1.4;
}

.poster-overlay {
    position:absolute;
    inset:40% 0 0;
    background:linear-gradient(transparent,rgba(0,0,0,.45));
    pointer-events:none;
}


/* STATUS POSTER */

.movie-status {
    position:absolute;
    top:13px;
    left:13px;
    z-index:2;
    padding:7px 9px;
    border-radius:5px;
    background:#16442e;
    color:#91edb6;
    font-size:8px;
    letter-spacing:.6px;
    font-weight:bold;
}

.coming-status {
    color:#a9cbff;
    background:#203d63;
}


/* RATING POSTER */

.movie-rating {
    position:absolute;
    right:12px;
    top:13px;
    z-index:2;
    padding:7px 9px;
    border-radius:5px;
    background:rgba(12,16,25,.85);
    color:#ffda83;
    font-size:10px;
    font-weight:bold;
}


/* =====================================
   INFORMASI FILM
===================================== */

.movie-information {
    display:flex;
    flex-direction:column;
    flex:1;
    padding:18px;
}

.movie-information h3 {
    font-size:15px;
    color:#ffffff;
    line-height:1.5;
    margin:0;
    overflow-wrap:anywhere;
}

.movie-genre {
    color:#aab4c4;
    font-size:11px;
    line-height:1.6;
    margin:7px 0 14px;
}

.movie-metadata {
    display:flex;
    align-items:center;
    gap:9px;
    flex-wrap:wrap;
    margin-bottom:16px;
}

.movie-metadata span {
    padding:6px 8px;
    background:#222c3e;
    color:#b2bfd0;
    border-radius:5px;
    font-size:9px;
}

.movie-price-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:13px 0;
    border-top:1px solid #303849;
    margin-top:auto;
}

.movie-price-row > div {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.movie-price-row small {
    color:#8d99aa;
    font-size:8px;
    letter-spacing:.6px;
}

.movie-price-row strong {
    color:#ff6979;
    font-size:16px;
}


/* TANGGAL RILIS */

.release-information {
    display:flex;
    flex-direction:column;
    gap:6px;
    border-top:1px solid #303849;
    padding:13px 0;
    margin-top:auto;
}

.release-information small {
    color:#929cac;
    font-size:8px;
    letter-spacing:.8px;
}

.release-information strong {
    color:#b0cdf7;
    font-size:12px;
}


/* =====================================
   DETAIL BUTTON (TOMBOL PILIH/DETAIL)
===================================== */

.movie-detail-button {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 13px 16px;
    margin-top: 12px;
    border-radius: 8px;
    background: linear-gradient(135deg, #e7283b, #b51a2b);
    color: #ffffff;
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 1px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(231, 40, 59, 0.35);
    transition: all 0.3s ease;
}

.movie-detail-button:hover {
    background: linear-gradient(135deg, #ff3347, #d01f32);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 40, 59, 0.5);
}

.movie-detail-button span {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.movie-detail-button:hover span {
    transform: translateX(4px);
}


/* =====================================
   COMING SOON
===================================== */

.coming-section {
    padding-top:10px;
}

.coming-count {
    background:#1e2b41;
    border-color:#334e71;
    color:#a9ccff;
}


/* =====================================
   EMPTY STATE
===================================== */

.empty-movies {
    grid-column:1/-1;
    text-align:center;
    padding:50px 20px;
    background:#171c29;
    border:1px solid #303849;
    border-radius:13px;
}

.empty-movies span {
    font-size:43px;
}

.empty-movies h3 {
    margin:15px 0 8px;
    font-size:18px;
}

.empty-movies p {
    color:#929cac;
    font-size:12px;
}


/* =====================================
   PROMOTION
===================================== */

.cinema-promotion {
    position:relative;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    overflow:hidden;
    padding:35px 40px;
    margin:30px 0;
    border:1px solid #643544;
    border-radius:14px;
    background:linear-gradient(110deg,#411825,#202139);
}

.promotion-content {
    position:relative;
    z-index:2;
}

.promotion-content > span {
    color:#ff8792;
    font-size:9px;
    font-weight:bold;
    letter-spacing:2px;
}

.promotion-content h2 {
    font-size:25px;
    line-height:1.4;
    margin:12px 0;
}

.promotion-content p {
    color:#c1b4c0;
    font-size:12px;
    line-height:1.8;
    max-width:560px;
}

.promotion-content a {
    display:inline-block;
    padding:12px 16px;
    margin-top:18px;
    background:#e7283b;
    color:white;
    border-radius:7px;
    text-decoration:none;
    font-size:10px;
    font-weight:bold;
}

.promotion-icon {
    font-size:90px;
    opacity:.9;
    filter:drop-shadow(0 10px 18px rgba(0,0,0,.3));
}


/* =====================================
   HOME BOTTOM
===================================== */

.home-bottom {
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    padding:23px 0 0;
    margin-top:40px;
    border-top:1px solid #282f3d;
}

.home-bottom strong {
    color:#ef3045;
    font-size:19px;
    font-weight:900;
}

.home-bottom span {
    color:#7f8a9b;
    font-size:9px;
    letter-spacing:1.5px;
}


/* =====================================
   RESPONSIVE
===================================== */

@media(max-width:1100px) {

    .hero-section {
        padding:35px;
    }

    .hero-poster-area {
        width:210px;
        margin-right:5px;
    }

    .hero-content h1 {
        font-size:37px;
    }

    .movie-grid {
        grid-template-columns:repeat(3,minmax(0,1fr));
    }

}

@media(max-width:800px) {

    .home-page {
        padding:25px 4%;
    }

    .hero-section {
        min-height:auto;
    }

    .hero-poster-area {
        width:175px;
    }

    .hero-content h1 {
        font-size:30px;
    }

    .hero-visual {
        display:none;
    }

    .movie-grid {
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:15px;
    }

    .movie-information {
        padding:14px;
    }

    .movie-information h3 {
        font-size:13px;
    }

}

@media(max-width:550px) {

    .hero-section {
        padding:24px;
        flex-direction:column;
        align-items:flex-start;
    }

    .hero-content h1 {
        font-size:29px;
    }

    .hero-content > p {
        font-size:11px;
    }

    .hero-poster-area {
        width:160px;
        margin:15px auto 0;
        align-self:center;
    }

    .hero-poster {
        max-height:230px;
    }

    .hero-features {
        width:100%;
        justify-content:space-between;
        gap:10px;
    }

    .hero-features strong {
        font-size:18px;
    }

    .hero-features small {
        font-size:8px;
    }

    .section-header h2 {
        font-size:24px;
    }

    .section-header p {
        font-size:11px;
    }

    .movie-grid {
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:10px;
    }

    .movie-information {
        padding:11px;
    }

    .movie-information h3 {
        font-size:12px;
    }

    .movie-genre {
        font-size:10px;
    }

    .movie-price-row strong {
        font-size:13px;
    }

    .movie-detail-button {
        padding:10px 9px;
        font-size:9px;
    }

    .movie-metadata {
        gap:5px;
    }

    .movie-metadata span {
        font-size:8px;
        padding:5px;
    }

    .cinema-promotion {
        padding:25px;
    }

    .promotion-content h2 {
        font-size:21px;
    }

    .promotion-icon {
        display:none;
    }

}

</style>

@endsection
