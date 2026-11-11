
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ELCinema')</title>

    <style>

        /* =====================================
           GLOBAL
        ===================================== */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;

            display: flex;
            flex-direction: column;

            font-family: Arial, Helvetica, sans-serif;

            background: #080a10;
            color: #ffffff;
        }

        a {
            -webkit-tap-highlight-color: transparent;
        }


        /* =====================================
           NAVBAR ELCINEMA - DESAIN AWAL
        ===================================== */

        .elcinema-navbar {
            width: 100%;
            height: 90px;
            min-height: 90px;

            padding: 0 7%;

            background: #11141e;
            border-bottom: 1px solid #303747;

            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 25px;

            position: relative;
            z-index: 100;
        }


        /* LOGO */

        .navbar-logo {
            color: #ef3045;

            font-size: 34px;
            font-weight: 900;

            text-decoration: none;
            letter-spacing: -1.5px;

            flex-shrink: 0;
        }

        .navbar-logo:hover {
            color: #ff5967;
        }


        /* MENU */

        .navbar-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;

            gap: 25px;

            flex-wrap: nowrap;
        }

        .navbar-menu > a {
            color: #ffffff;

            text-decoration: none;

            font-size: 14px;
            font-weight: 600;

            white-space: nowrap;

            transition: .2s;
        }

        .navbar-menu > a:hover {
            color: #ff5967;
        }


        /* REGISTER */

        .navbar-register {
            padding: 11px 18px;

            background: #e7283b;

            border-radius: 7px;

            color: white !important;
        }

        .navbar-register:hover {
            background: #bd1d31;
        }


        /* NAMA USER */

        .navbar-user {
            color: #ff8290;

            font-size: 12px;
            font-weight: bold;

            white-space: nowrap;
        }


        /* LOGOUT */

        .logout-form {
            margin: 0;
            padding: 0;

            display: inline-flex;
            flex-shrink: 0;
        }

        .navbar-logout {
            padding: 11px 18px;

            background: #e7283b;

            border: none;
            border-radius: 7px;

            color: #ffffff;

            font-size: 12px;
            font-weight: bold;

            cursor: pointer;

            transition: .2s;
        }

        .navbar-logout:hover {
            background: #bd1d31;
        }


        /* =====================================
           MAIN CONTENT
        ===================================== */

        .site-main {
            flex: 1;
            width: 100%;
            min-height: 500px;
        }


        /* =====================================
           FOOTER ELCINEMA
        ===================================== */

        .elcinema-footer {
            width: 100%;

            padding: 25px 20px;

            background: #11141e;

            border-top: 1px solid #252b39;

            text-align: center;

            color: #929cac;

            font-size: 14px;

            margin-top: auto;
        }


        /* =====================================
           RESPONSIVE TABLET
        ===================================== */

        @media(max-width: 1100px) {

            .elcinema-navbar {
                padding: 0 4%;
                gap: 15px;
            }

            .navbar-logo {
                font-size: 29px;
            }

            .navbar-menu {
                gap: 16px;
            }

            .navbar-menu > a {
                font-size: 12px;
            }

        }


        /* =====================================
           RESPONSIVE MOBILE
        ===================================== */

        @media(max-width: 800px) {

            .elcinema-navbar {
                height: auto;
                min-height: auto;

                padding: 20px 5%;

                align-items: flex-start;
                flex-direction: column;

                gap: 20px;
            }

            .navbar-logo {
                font-size: 30px;
            }

            .navbar-menu {
                width: 100%;

                justify-content: flex-start;

                flex-wrap: wrap;

                gap: 15px;
            }

        }


        @media(max-width: 480px) {

            .navbar-menu {
                gap: 12px;
            }

            .navbar-menu > a {
                font-size: 11px;
            }

            .navbar-logout {
                padding: 9px 13px;
                font-size: 10px;
            }

            .navbar-user {
                font-size: 10px;
            }

            .elcinema-footer {
                font-size: 11px;
                padding: 20px 12px;
            }

        }

    </style>

    @stack('styles')

</head>


<body>


    <!-- =====================================
         NAVBAR
    ====================================== -->

    <nav class="elcinema-navbar">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="navbar-logo">
            ELCINEMA
        </a>


        <!-- MENU -->
        <div class="navbar-menu">

            <a href="{{ route('home') }}">
                Home
            </a>

            <a href="{{ route('home') }}#film">
                Film
            </a>


            @guest

                <!-- PENGUNJUNG -->
                <a href="{{ route('login') }}">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="navbar-register">
                    Register
                </a>

            @endguest


            @auth

                @if(auth()->user()->isAdmin())

                    <!-- ADMIN -->
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard Admin
                    </a>

                    <a href="{{ route('admin.movies.index') }}">
                        Kelola Film
                    </a>

                    <a href="{{ route('admin.screenings.index') }}">
                        Jadwal
                    </a>

                    <a href="{{ route('admin.bookings.index') }}">
                        Booking
                    </a>

                @elseif(auth()->user()->isCustomer())

                    <!-- CUSTOMER -->
                    <a href="{{ route('customer.dashboard') }}">
                        Dashboard Saya
                    </a>

                @endif


                <!-- USER -->
                <span class="navbar-user">
                    {{ auth()->user()->name }}
                </span>


                <!-- LOGOUT -->
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="logout-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="navbar-logout"
                    >
                        Logout
                    </button>

                </form>

            @endauth

        </div>

    </nav>


    <!-- =====================================
         HALAMAN UTAMA
    ====================================== -->

    <main class="site-main">

        @yield('content')

    </main>


    <!-- =====================================
         FOOTER
    ====================================== -->

    <footer class="elcinema-footer">

        © {{ date('Y') }} ELCinema. All Rights Reserved.

    </footer>


    @stack('scripts')


</body>
</html>
