
@extends('layouts.app')

@section('title', 'Tambah Jadwal - ELCinema')

@section('content')

<div class="schedule-admin-page">

    <a href="{{ route('admin.screenings.index') }}"
       class="back-link">
        ← Kembali ke Daftar Jadwal
    </a>

    <div class="page-heading">

        <span>ELCINEMA / SCREENING MANAGEMENT</span>

        <h1>Tambah Jadwal Tayang</h1>

        <p>
            Buat jadwal penayangan film untuk studio bioskop.
        </p>

    </div>

    @include('admin.screenings._form')

</div>

<style>

.schedule-admin-page {
    width:92%;
    max-width:950px;
    margin:auto;
    padding:40px 0 70px;
    color:white;
}

.back-link {
    color:#ff6876;
    text-decoration:none;
    font-size:13px;
}

.page-heading {
    margin:28px 0;
}

.page-heading span {
    color:#ff6876;
    letter-spacing:2px;
    font-size:10px;
    font-weight:bold;
}

.page-heading h1 {
    font-size:30px;
    margin:12px 0;
}

.page-heading p {
    font-size:13px;
    color:#aab0bc;
}

</style>

@endsection
