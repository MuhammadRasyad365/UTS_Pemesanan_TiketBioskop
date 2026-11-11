
@extends('layouts.app')

@section('title', 'Login - ELCinema')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">E</div>

        <h1>Welcome Back!</h1>

        <p class="auth-subtitle">
            Login untuk menikmati pengalaman menonton bersama ELCinema.
        </p>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">

            @csrf

            <div class="field">
                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="field">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit" class="auth-button">
                LOGIN SEKARANG
            </button>

        </form>

        <div class="auth-divider"></div>

        <p class="register-text">
            Belum mempunyai akun?
            <a href="{{ route('register') }}">Daftar sekarang</a>
        </p>

        <a href="{{ route('home') }}" class="back-home">
            ← Kembali ke Home
        </a>

    </div>

</div>

<style>

.auth-wrapper {
    min-height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 15px;
}

.auth-card {
    width: 100%;
    max-width: 440px;
    background: #171c29;
    border: 1px solid #303747;
    border-radius: 18px;
    padding: 38px;
    color: white;
    box-shadow: 0 15px 45px rgba(0,0,0,.25);
}

.auth-logo {
    width: 55px;
    height: 55px;
    display: grid;
    place-items: center;
    background: #e7283b;
    color: white;
    border-radius: 13px;
    font-size: 30px;
    font-weight: 900;
    margin-bottom: 22px;
}

.auth-card h1 {
    font-size: 29px;
    margin-bottom: 10px;
}

.auth-subtitle {
    color: #aab0bc;
    font-size: 13px;
    line-height: 1.7;
    margin-bottom: 27px;
}

.field {
    margin-bottom: 20px;
}

.field label {
    display: block;
    font-size: 12px;
    margin-bottom: 9px;
    font-weight: bold;
}

.field input {
    box-sizing: border-box;
    width: 100%;
    padding: 14px;
    color: white;
    background: #0e1420;
    border: 1px solid #354052;
    border-radius: 8px;
    outline: none;
}

.field input:focus {
    border-color: #ef3949;
}

.remember {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #aab0bc;
    font-size: 12px;
    margin-bottom: 22px;
}

.auth-button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 8px;
    background: #e7283b;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.auth-button:hover {
    background: #bd1729;
}

.auth-divider {
    border-top: 1px solid #343e50;
    margin: 25px 0;
}

.register-text {
    color: #aab0bc;
    text-align: center;
    font-size: 13px;
}

.register-text a {
    color: #ff5967;
    text-decoration: none;
    font-weight: bold;
}

.back-home {
    display: block;
    text-align: center;
    margin-top: 22px;
    color: #aab0bc;
    font-size: 12px;
    text-decoration: none;
}

.success-message,
.error-message {
    padding: 13px;
    border-radius: 8px;
    font-size: 12px;
    margin-bottom: 20px;
}

.success-message {
    background: #173c2c;
    color: #83e5ae;
}

.error-message {
    background: #481f2b;
    color: #ffb8c2;
}

</style>

@endsection
