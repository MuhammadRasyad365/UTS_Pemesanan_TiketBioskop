
@extends('layouts.app')

@section('title', 'Edit Film - Admin ELCinema')

@section('content')

<div class="admin-page">

    <span class="admin-eyebrow">MOVIE MANAGEMENT</span>

    <h1>Edit Film</h1>

    <p>Perbarui informasi {{ $movie->title }}.</p>

    <div style="margin-top:25px">
        @include('admin.movies._form')
    </div>

</div>

@endsection
