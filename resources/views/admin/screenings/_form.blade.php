
@php
    $editing = $screening->exists;
@endphp

@if($errors->any())
    <div class="schedule-alert error">
        <strong>Periksa kembali data jadwal:</strong>

        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form
    action="{{ $editing
        ? route('admin.screenings.update', $screening)
        : route('admin.screenings.store') }}"
    method="POST"
    class="schedule-form"
>

    @csrf

    @if($editing)
        @method('PUT')
    @endif

    <div class="form-grid">

        <!-- FILM -->
        <div class="form-group full">
            <label for="movie_id">Pilih Film</label>

            <select name="movie_id" id="movie_id" required>

                <option value="">-- Pilih Film --</option>

                @foreach($movies as $movie)

                    <option
                        value="{{ $movie->id }}"
                        @selected(
                            (string)old('movie_id', $screening->movie_id)
                            === (string)$movie->id
                        )
                    >
                        {{ $movie->title }}
                    </option>

                @endforeach

            </select>
        </div>

        <!-- STUDIO -->
        <div class="form-group">
            <label for="studio_name">Nama Studio</label>

            <select name="studio_name" id="studio_name" required>

                <option value="">-- Pilih Studio --</option>

                @foreach(range(1, 5) as $number)

                    @php
                        $studio = 'Studio ' . $number;
                    @endphp

                    <option
                        value="{{ $studio }}"
                        @selected(
                            old('studio_name', $screening->studio_name)
                            === $studio
                        )
                    >
                        {{ $studio }}
                    </option>

                @endforeach

                @if(
                    $screening->exists &&
                    !in_array($screening->studio_name, array_map(
                        fn($n) => 'Studio '.$n,
                        range(1,5)
                    ))
                )
                    <option
                        value="{{ $screening->studio_name }}"
                        selected
                    >
                        {{ $screening->studio_name }}
                    </option>
                @endif

            </select>
        </div>

        <!-- TANGGAL -->
        <div class="form-group">
            <label for="show_date">Tanggal Tayang</label>

            <input
                type="date"
                id="show_date"
                name="show_date"
                value="{{ old(
                    'show_date',
                    $screening->show_date?->format('Y-m-d')
                ) }}"
                min="{{ today()->toDateString() }}"
                required
            >
        </div>

        <!-- JAM MULAI -->
        <div class="form-group">
            <label for="start_time">Jam Mulai</label>

            <input
                type="time"
                id="start_time"
                name="start_time"
                value="{{ old(
                    'start_time',
                    $screening->start_time
                        ? substr($screening->start_time, 0, 5)
                        : ''
                ) }}"
                required
            >
        </div>

        <!-- JAM SELESAI -->
        <div class="form-group">
            <label for="end_time">Jam Selesai</label>

            <input
                type="time"
                id="end_time"
                name="end_time"
                value="{{ old(
                    'end_time',
                    $screening->end_time
                        ? substr($screening->end_time, 0, 5)
                        : ''
                ) }}"
            >

            <small>
                Kosongkan untuk menghitung otomatis dari durasi film
                ditambah 15 menit.
            </small>
        </div>

        <!-- HARGA -->
        <div class="form-group">
            <label for="ticket_price">Harga Tiket (Rp)</label>

            <input
                type="number"
                name="ticket_price"
                id="ticket_price"
                min="0"
                step="1000"
                value="{{ old(
                    'ticket_price',
                    $screening->ticket_price ?? 50000
                ) }}"
                required
            >
        </div>

        <!-- STATUS -->
        <div class="form-group full">
            <label for="status">Status Penayangan</label>

            <select name="status" id="status" required>

                <option
                    value="available"
                    @selected(
                        old('status', $screening->status ?? 'available')
                        === 'available'
                    )
                >
                    Available - Dapat Dipesan
                </option>

                <option
                    value="cancelled"
                    @selected(
                        old('status', $screening->status ?? 'available')
                        === 'cancelled'
                    )
                >
                    Cancelled - Dibatalkan
                </option>

            </select>
        </div>

    </div>

    <div class="schedule-form-actions">

        <button type="submit" class="save-schedule">
            {{ $editing ? 'SIMPAN PERUBAHAN' : 'TAMBAH JADWAL' }}
        </button>

        <a
            href="{{ route('admin.screenings.index') }}"
            class="cancel-schedule"
        >
            BATAL
        </a>

    </div>

</form>

<style>

.schedule-form {
    background:#171c29;
    border:1px solid #303747;
    border-radius:14px;
    padding:28px;
    color:white;
}

.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:21px;
}

.form-group label {
    display:block;
    font-size:12px;
    font-weight:bold;
    margin-bottom:10px;
    color:#e8edf5;
}

.form-group input,
.form-group select {
    width:100%;
    box-sizing:border-box;
    padding:14px;
    background:#0e1420;
    color:white;
    border:1px solid #354052;
    border-radius:8px;
    outline:none;
}

.form-group input:focus,
.form-group select:focus {
    border-color:#ef3949;
    box-shadow:0 0 0 3px rgba(239,57,73,.1);
}

.form-group small {
    display:block;
    margin-top:8px;
    color:#929cac;
    font-size:10px;
    line-height:1.6;
}

.form-group.full {
    grid-column:1/-1;
}

.schedule-form-actions {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-top:28px;
}

.save-schedule,
.cancel-schedule {
    padding:14px 20px;
    border:none;
    border-radius:8px;
    color:white;
    font-weight:bold;
    font-size:11px;
    text-decoration:none;
    cursor:pointer;
}

.save-schedule {
    background:#e7283b;
}

.save-schedule:hover {
    background:#bb1c30;
}

.cancel-schedule {
    background:#394355;
}

.schedule-alert {
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
    font-size:12px;
}

.schedule-alert.error {
    background:#481f2b;
    border:1px solid #873a4c;
    color:#ffc1ca;
}

@media(max-width:600px) {
    .form-grid {
        grid-template-columns:1fr;
    }

    .form-group.full {
        grid-column:auto;
    }

    .schedule-form {
        padding:18px;
    }
}

</style>
