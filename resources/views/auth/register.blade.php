
@extends('layouts.app')

@section('title', 'Register - ELCinema')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">E</div>

        <h1>Create Account</h1>

        <p class="auth-subtitle">
            Daftar sekarang dan mulai pesan tiket bioskop favoritmu.
        </p>

        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">

            @csrf

            <div class="field">
                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    required
                    maxlength="255"
                >
            </div>

            <div class="field">
                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    required
                >
            </div>

            <div class="field">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                    minlength="8"
                >
            </div>

            <div class="field">
                <label>Konfirmasi Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                >
            </div>

            <div style="
                background:#202b3b;
                color:#aab0bc;
                font-size:12px;
                padding:13px;
                border-radius:8px;
                margin-bottom:20px;
            ">
                <strong style="color:#79d9a2">
                    CUSTOMER ACCOUNT
                </strong>

                <p style="margin-top:6px">
                    Pendaftaran ini otomatis membuat akun Customer.
                </p>
            </div>

            <button type="submit" class="auth-button">
                DAFTAR SEKARANG
            </button>

        </form>

        <div class="auth-divider"></div>

        <p class="register-text">
            Sudah mempunyai akun?
            <a href="{{ route('login') }}">Login sekarang</a>
        </p>

        <a href="{{ route('home') }}" class="back-home">
            ← Kembali ke Home
        </a>

    </div>

</div>

<style>
    .auth-wrapper {
        min-height:75vh;
        display:flex;
        justify-content:center;
        align-items:center;
        padding:40px 15px;
    }

    .auth-card {
        width:100%;
        max-width:440px;
        padding:38px;
        color:white;
        background:#171c29;
        border:1px solid #303747;
        border-radius:18px;
        box-shadow:0 15px 45px rgba(0,0,0,.25);
    }

    .auth-logo {
        width:55px;
        height:55px;
        background:#e7283b;
        color:white;
        display:grid;
        place-items:center;
        border-radius:13px;
        font-size:30px;
        font-weight:900;
        margin-bottom:22px;
    }

    .auth-card h1 {
        font-size:29px;
        margin-bottom:10px;
    }

    .auth-subtitle {
        color:#aab0bc;
        font-size:13px;
        line-height:1.7;
        margin-bottom:27px;
    }

    .field {
        margin-bottom:18px;
    }

    .field label {
        display:block;
        font-size:12px;
        font-weight:bold;
        margin-bottom:9px;
    }

    .field input {
        box-sizing:border-box;
        width:100%;
        padding:14px;
        background:#0e1420;
        border:1px solid #354052;
        border-radius:8px;
        color:white;
        outline:none;
    }

    .field input:focus {
        border-color:#ef3949;
    }

    .auth-button {
        width:100%;
        padding:15px;
        border:0;
        border-radius:8px;
        background:#e7283b;
        color:white;
        font-weight:bold;
        cursor:pointer;
    }

    .auth-divider {
        border-top:1px solid #343e50;
        margin:25px 0;
    }

    .register-text {
        color:#aab0bc;
        text-align:center;
        font-size:13px;
    }

    .register-text a {
        color:#ff5967;
        text-decoration:none;
        font-weight:bold;
    }

    .back-home {
        display:block;
        text-align:center;
        margin-top:22px;
        color:#aab0bc;
        font-size:12px;
        text-decoration:none;
    }

    .error-message {
        background:#481f2b;
        color:#ffb8c2;
        padding:13px;
        border-radius:8px;
        margin-bottom:20px;
        font-size:12px;
    }
</style>

@endsection
