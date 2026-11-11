
@php
    $editing = $movie->exists;

    $externalPoster = $movie->poster_url &&
        \Illuminate\Support\Str::startsWith(
            $movie->poster_url,
            ['http://', 'https://']
        )
        ? $movie->poster_url
        : '';
@endphp

@if($errors->any())
    <div class="movie-alert error">
        <strong>Periksa kembali formulir film.</strong>

        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form
    action="{{ $editing
        ? route('admin.movies.update', $movie)
        : route('admin.movies.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="movieForm"
    class="movie-admin-form"
>

    @csrf

    @if($editing)
        @method('PUT')
    @endif

    <div class="form-section-title">
        <span>01</span>
        INFORMASI FILM
    </div>

    <div class="movie-form-grid">

        <!-- JUDUL -->
        <div class="form-group full">
            <label for="title">Judul Film *</label>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $movie->title) }}"
                placeholder="Masukkan judul film"
                maxlength="255"
                required
            >
        </div>

        <!-- GENRE -->
        <div class="form-group">
            <label for="genre">Genre Film *</label>

            <input
                type="text"
                id="genre"
                name="genre"
                value="{{ old('genre', $movie->genre) }}"
                placeholder="Action, Adventure, Drama"
                required
            >
        </div>

        <!-- DURASI -->
        <div class="form-group">
            <label for="duration">Durasi Film (Menit) *</label>

            <input
                type="number"
                id="duration"
                name="duration"
                value="{{ old('duration', $movie->duration) }}"
                min="1"
                placeholder="120"
                required
            >
        </div>

        <!-- KLASIFIKASI -->
        <div class="form-group">
            <label for="age_rating">Klasifikasi Usia *</label>

            <select name="age_rating" id="age_rating" required>

                @foreach(['SU', '13+', '17+', '21+'] as $age)

                    <option
                        value="{{ $age }}"
                        @selected(
                            old('age_rating', $movie->age_rating) === $age
                        )
                    >
                        {{ $age }}
                    </option>

                @endforeach

            </select>
        </div>

        <!-- TANGGAL RILIS -->
        <div class="form-group">
            <label for="release_date">Tanggal Rilis</label>

            <input
                type="date"
                id="release_date"
                name="release_date"
                value="{{ old(
                    'release_date',
                    $movie->release_date
                        ? \Illuminate\Support\Carbon::parse($movie->release_date)->format('Y-m-d')
                        : ''
                ) }}"
            >
        </div>

    </div>


    <!-- POSTER FILM -->
    <div class="form-section-title poster-section-title">
        <span>02</span>
        GAMBAR POSTER FILM
    </div>

    <div class="poster-upload-layout">

        <div class="poster-preview-area">

            <div class="poster-preview-box" id="posterPreviewBox">

                <img
                    id="posterPreview"
                    src="{{ $movie->poster_display_url }}"
                    alt="Preview poster film"
                    @if(!$movie->poster_display_url) hidden @endif
                >

                <div
                    class="poster-placeholder"
                    id="posterPlaceholder"
                    @if($movie->poster_display_url) hidden @endif
                >
                    <span>🎬</span>
                    <strong>PREVIEW POSTER</strong>
                    <small>Belum ada gambar</small>
                </div>

            </div>

            <span class="preview-caption">
                PREVIEW POSTER
            </span>

        </div>

        <div class="poster-controls">

            <label for="poster" class="upload-label">
                <span>↑</span>
                PILIH GAMBAR DARI KOMPUTER
            </label>

            <input
                type="file"
                id="poster"
                name="poster"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                hidden
            >

            <div id="selectedFileName" class="file-name">
                Belum ada gambar yang dipilih.
            </div>

            <div class="upload-information">
                <strong>Format gambar yang didukung:</strong>

                <p>JPG, JPEG, PNG dan WEBP.</p>

                <p>Maksimal ukuran gambar: 4 MB.</p>

                <p>Rasio poster yang disarankan: 2:3.</p>
            </div>

            <div class="form-group url-group">

                <label for="poster_url">
                    Atau gunakan URL gambar (opsional)
                </label>

                <input
                    type="url"
                    name="poster_url"
                    id="poster_url"
                    value="{{ old('poster_url', $externalPoster) }}"
                    placeholder="https://contoh.com/poster.jpg"
                >

            </div>

            @if($editing && $movie->poster_url)

                <label class="remove-poster-option">

                    <input
                        type="checkbox"
                        name="remove_poster"
                        value="1"
                        @checked(old('remove_poster'))
                    >

                    Hapus poster yang sedang digunakan.

                </label>

            @endif

            <p class="upload-note">
                Mengunggah gambar baru akan menggantikan poster lama.
                Jika tidak ada gambar baru, poster lama tetap digunakan.
            </p>

        </div>

    </div>


    <!-- SINOPSIS -->
    <div class="form-section-title">
        <span>03</span>
        DESKRIPSI FILM
    </div>

    <div class="form-group full">

        <label for="synopsis">Sinopsis Film</label>

        <textarea
            name="synopsis"
            id="synopsis"
            rows="6"
            placeholder="Tuliskan sinopsis atau ringkasan cerita film..."
        >{{ old('synopsis', $movie->synopsis) }}</textarea>

    </div>


    <!-- RATING DAN HARGA -->
    <div class="form-section-title">
        <span>04</span>
        RATING DAN HARGA
    </div>

    <div class="movie-form-grid">

        <div class="form-group">

            <label for="rating">Rating Film (0–10)</label>

            <input
                type="number"
                id="rating"
                name="rating"
                min="0"
                max="10"
                step="0.1"
                value="{{ old('rating', $movie->rating) }}"
                placeholder="8.5"
            >

        </div>

        <div class="form-group">

            <label for="ticket_price">
                Harga Tiket Dasar (Rp) *
            </label>

            <input
                type="number"
                id="ticket_price"
                name="ticket_price"
                min="0"
                step="1000"
                value="{{ old(
                    'ticket_price',
                    $movie->ticket_price ?? 50000
                ) }}"
                placeholder="50000"
                required
            >

        </div>

    </div>


    <!-- STATUS FILM -->
    <div class="form-section-title">
        <span>05</span>
        STATUS PENAYANGAN
    </div>

    <div class="form-group full">

        <label for="status">Status Film *</label>

        <select name="status" id="status" required>

            <option
                value="now_showing"
                @selected(
                    old('status', $movie->status ?? 'now_showing')
                    === 'now_showing'
                )
            >
                NOW SHOWING - Sedang Tayang
            </option>

            <option
                value="coming_soon"
                @selected(
                    old('status', $movie->status)
                    === 'coming_soon'
                )
            >
                COMING SOON - Segera Tayang
            </option>

            <option
                value="ended"
                @selected(
                    old('status', $movie->status)
                    === 'ended'
                )
            >
                ENDED - Tidak Ditayangkan
            </option>

        </select>

    </div>


    <!-- BUTTON -->
    <div class="movie-form-actions">

        <button type="submit" class="save-movie-button">

            {{ $editing
                ? 'SIMPAN PERUBAHAN FILM'
                : 'TAMBAHKAN FILM SEKARANG' }}

        </button>

        <a
            href="{{ route('admin.movies.index') }}"
            class="cancel-movie-button"
        >
            BATAL
        </a>

    </div>

</form>


<style>

/* FORM */

.movie-admin-form {
    padding:30px;
    background:#171c29;
    border:1px solid #303747;
    border-radius:14px;
    color:white;
}

.movie-form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-section-title {
    display:flex;
    align-items:center;
    gap:12px;
    margin:30px 0 20px;
    padding-bottom:14px;
    border-bottom:1px solid #303747;
    color:#ff7180;
    font-size:11px;
    font-weight:bold;
    letter-spacing:1.5px;
}

.form-section-title:first-of-type {
    margin-top:0;
}

.form-section-title span {
    width:29px;
    height:29px;
    background:#40202c;
    border-radius:7px;
    display:grid;
    place-items:center;
    color:#ff8290;
}

.form-group {
    margin-bottom:20px;
    min-width:0;
}

.form-group.full {
    grid-column:1/-1;
}

.form-group label {
    display:block;
    font-size:12px;
    font-weight:bold;
    margin-bottom:10px;
    color:#e6ebf3;
}

.form-group input,
.form-group select,
.form-group textarea {
    box-sizing:border-box;
    width:100%;
    padding:14px;
    color:white;
    background:#0e1420;
    border:1px solid #354052;
    border-radius:8px;
    outline:none;
    font-size:13px;
    font-family:inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color:#e7283b;
    box-shadow:0 0 0 3px rgba(231,40,59,.10);
}

.form-group textarea {
    resize:vertical;
    line-height:1.7;
}

/* POSTER UPLOAD */

.poster-upload-layout {
    display:grid;
    grid-template-columns:220px 1fr;
    gap:30px;
    padding:20px;
    background:#202838;
    border:1px solid #354052;
    border-radius:12px;
}

.poster-preview-area {
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:12px;
}

.poster-preview-box {
    width:190px;
    aspect-ratio:2 / 3;
    background:#0d1420;
    border:1px dashed #536078;
    border-radius:10px;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
}

.poster-preview-box img {
    display:block;
    width:100%;
    height:100%;
    object-fit:cover;
}

.poster-placeholder {
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:12px;
    color:#9ba7b8;
}

.poster-placeholder span {
    font-size:42px;
}

.poster-placeholder strong {
    color:#ff7180;
    font-size:10px;
    letter-spacing:1px;
}

.poster-placeholder small {
    font-size:10px;
}

.preview-caption {
    font-size:10px;
    color:#aab0bc;
    letter-spacing:1px;
}

.poster-controls {
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    justify-content:center;
    gap:12px;
}

.upload-label {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    padding:14px 18px;
    background:#e7283b;
    border-radius:8px;
    font-size:11px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.upload-label:hover {
    background:#bc1b30;
}

.upload-label span {
    font-size:20px;
}

.file-name {
    color:#aab0bc;
    font-size:11px;
    overflow-wrap:anywhere;
}

.upload-information {
    padding:13px;
    width:100%;
    background:#141b29;
    border-radius:8px;
    font-size:11px;
    color:#929cac;
    line-height:1.8;
}

.upload-information strong {
    color:#ff8793;
}

.url-group {
    width:100%;
    margin:0;
}

.remove-poster-option {
    display:flex;
    align-items:center;
    gap:9px;
    color:#ff9eaa;
    font-size:11px;
    cursor:pointer;
}

.remove-poster-option input {
    accent-color:#e7283b;
}

.upload-note {
    color:#929cac;
    font-size:10px;
    line-height:1.6;
}

.movie-form-actions {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    border-top:1px solid #303747;
    margin-top:25px;
    padding-top:25px;
}

.save-movie-button,
.cancel-movie-button {
    padding:15px 20px;
    border:0;
    border-radius:8px;
    text-decoration:none;
    color:white;
    font-size:11px;
    font-weight:bold;
    cursor:pointer;
}

.save-movie-button {
    background:#e7283b;
}

.save-movie-button:hover {
    background:#c21d32;
}

.cancel-movie-button {
    background:#394355;
}

.movie-alert {
    padding:15px;
    border-radius:8px;
    margin-bottom:22px;
    font-size:12px;
    line-height:1.7;
}

.movie-alert.error {
    color:#ffc1ca;
    background:#481f2b;
    border:1px solid #873a4c;
}

/* RESPONSIVE */

@media(max-width:650px) {

    .movie-admin-form {
        padding:18px;
    }

    .movie-form-grid {
        grid-template-columns:1fr;
    }

    .form-group.full {
        grid-column:auto;
    }

    .poster-upload-layout {
        grid-template-columns:1fr;
        padding:15px;
    }

    .poster-controls {
        width:100%;
    }

    .poster-preview-box {
        width:170px;
    }

    .upload-label {
        width:100%;
        box-sizing:border-box;
    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('poster');

    const urlInput = document.getElementById('poster_url');

    const preview = document.getElementById('posterPreview');

    const placeholder = document.getElementById('posterPlaceholder');

    const fileName = document.getElementById('selectedFileName');

    const removeCheckbox = document.querySelector(
        'input[name="remove_poster"]'
    );

    let previewObjectUrl = null;

    function showPreview(source) {

        if (!source) {
            preview.removeAttribute('src');
            preview.hidden = true;
            placeholder.hidden = false;
            return;
        }

        preview.src = source;
        preview.hidden = false;
        placeholder.hidden = true;
    }

    // Preview gambar dari komputer.
    fileInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {

            alert('Format gambar harus JPG, PNG, atau WEBP.');

            this.value = '';

            return;
        }

        if (file.size > 4 * 1024 * 1024) {

            alert('Ukuran gambar maksimal 4 MB.');

            this.value = '';

            return;
        }

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
        }

        previewObjectUrl = URL.createObjectURL(file);

        showPreview(previewObjectUrl);

        fileName.textContent = file.name;

        if (removeCheckbox) {
            removeCheckbox.checked = false;
        }

    });

    // Preview gambar dari URL.
    urlInput.addEventListener('change', function () {

        if (!this.value.trim() || fileInput.files.length > 0) {
            return;
        }

        showPreview(this.value.trim());

        fileName.textContent = 'Poster menggunakan URL gambar.';

        if (removeCheckbox) {
            removeCheckbox.checked = false;
        }

    });

    // Memilih hapus poster.
    if (removeCheckbox) {

        removeCheckbox.addEventListener('change', function () {

            if (this.checked) {

                fileInput.value = '';
                urlInput.value = '';

                if (previewObjectUrl) {
                    URL.revokeObjectURL(previewObjectUrl);
                    previewObjectUrl = null;
                }

                showPreview(null);

                fileName.textContent = 'Poster akan dihapus.';

            }

        });

    }

});

</script>
