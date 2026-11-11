
@extends('layouts.app')

@section('title', 'Tambah Film - Admin ELCinema')

@section('content')

<div class="admin-page">

    <span class="admin-eyebrow">MOVIE MANAGEMENT</span>

    <h1>Tambah Film Baru</h1>

    <p>Masukkan informasi film yang akan ditambahkan ke katalog.</p>

    <div style="margin-top:25px">
        @include('admin.movies._form')
    </div>

</div>

@endsection
