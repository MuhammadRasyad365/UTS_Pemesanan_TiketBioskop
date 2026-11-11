@extends('layouts.app')

@section('title', 'Kelola Film - Admin ELCinema')

@section('content')

<div class="admin-page">

    <div class="admin-topbar">
        <div>
            <span class="admin-eyebrow">ELCINEMA MANAGEMENT</span>
            <h1>Kelola Film</h1>
            <p>Tambah, edit, dan kelola katalog film bioskop.</p>
        </div>

        <a href="{{ route('admin.movies.create') }}" class="admin-button">
            + TAMBAH FILM
        </a>
    </div>

    @if(session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="admin-alert error">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" class="search-form">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari judul atau genre film..."
        >
        <button type="submit">CARI</button>
    </form>

    <div class="table-card">

        <div class="table-responsive">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>POSTER</th>
                        <th>FILM</th>
                        <th>GENRE</th>
                        <th>DURASI</th>
                        <th>HARGA</th>
                        <th>JADWAL</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($movies as $movie)

                        <tr>
                            <!-- Kolom Poster -->
                            <td>
                                @if($movie->poster_display_url)
                                    <img
                                        src="{{ $movie->poster_display_url }}"
                                        alt="{{ $movie->title }}"
                                        class="admin-poster-thumbnail"
                                    >
                                @else
                                    <div class="admin-poster-placeholder">
                                        🎬
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $movie->title }}</strong>
                            </td>

                            <td>{{ $movie->genre }}</td>

                            <td>{{ $movie->duration }} menit</td>

                            <td>
                                Rp{{ number_format(
                                    (float)$movie->ticket_price,
                                    0, ',', '.'
                                ) }}
                            </td>

                            <td>{{ $movie->screenings_count }}</td>

                            <td>
                                <span class="admin-status">
                                    {{ str_replace('_', ' ', strtoupper($movie->status)) }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <a
                                        href="{{ route('admin.movies.edit', $movie) }}"
                                        class="edit-link"
                                    >
                                        EDIT
                                    </a>

                                    <form
                                        action="{{ route('admin.movies.destroy', $movie) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus film ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="delete-button">
                                            HAPUS
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" style="text-align:center">
                                Belum ada data film.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-area">
            {{ $movies->links() }}
        </div>

    </div>

</div>

<style>
.admin-page {
    width: 92%;
    max-width: 1250px;
    margin: auto;
    padding: 40px 0 65px;
    color: white;
}

.admin-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.admin-eyebrow {
    color: #ff5967;
    font-size: 10px;
    letter-spacing: 2px;
    font-weight: bold;
}

.admin-page h1 {
    margin: 10px 0;
    font-size: 30px;
}

.admin-page p {
    color: #aab0bc;
    font-size: 13px;
}

.admin-button,
.search-form button {
    display: inline-block;
    padding: 13px 18px;
    color: white;
    background: #e7283b;
    border: 0;
    border-radius: 7px;
    text-decoration: none;
    font-size: 11px;
    font-weight: bold;
    cursor: pointer;
}

.admin-alert {
    padding: 14px;
    border-radius: 8px;
    margin-top: 20px;
    font-size: 12px;
}

.admin-alert.success {
    background: #17432e;
    color: #8ce8b4;
}

.admin-alert.error {
    background: #481f2b;
    color: #ffc1ca;
}

.search-form {
    display: flex;
    gap: 10px;
    margin: 25px 0;
}

.search-form input {
    flex: 1;
    min-width: 0;
    background: #171c29;
    border: 1px solid #303747;
    padding: 13px;
    border-radius: 7px;
    color: white;
}

.table-card {
    background: #171c29;
    border: 1px solid #303747;
    border-radius: 12px;
    padding: 18px;
}

.table-responsive {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    text-align: left;
}

.admin-table th {
    padding: 15px 12px;
    color: #ff7c89;
    background: #222b3b;
    font-size: 10px;
}

.admin-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #303747;
    font-size: 12px;
    vertical-align: middle;
}

/* Styling untuk Poster Thumbnail & Placeholder */
.admin-poster-thumbnail {
    display:block;
    width:55px;
    height:75px;
    object-fit:cover;
    border-radius:7px;
    border:1px solid #394355;
}

.admin-poster-placeholder {
    width:55px;
    height:75px;
    display:grid;
    place-items:center;
    border-radius:7px;
    background:#34212b;
    font-size:22px;
}

.admin-status {
    padding: 7px 9px;
    color: #79dfa6;
    background: #19432f;
    border-radius: 5px;
    font-size: 9px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.edit-link,
.delete-button {
    border: 0;
    border-radius: 5px;
    padding: 8px 12px;
    color: white;
    text-decoration: none;
    font-size: 9px;
    font-weight: bold;
    cursor: pointer;
}

.edit-link {
    background: #24598e;
}

.delete-button {
    background: #a62b3b;
}

.pagination-area {
    margin-top: 20px;
    color: white;
}

.pagination-area nav p {
    color: white;
}

@media(max-width: 600px) {
    .admin-page h1 {
        font-size: 25px;
    }

    .table-card {
        padding: 10px;
    }
}
</style>

@endsection