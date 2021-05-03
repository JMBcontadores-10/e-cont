<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-contarapp-03.png') }}" type="image/png" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style>
    input[type=text],
    select {
        width: 300px;
        padding: 12px 20px;
        margin-left: auto;
        margin-right: auto;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* input[type=submit] {

        width: 180px;
        color: #0055ff;
        border-style: ridge;
        border-color: #0055ff;
        background-color: white;
        padding: 14px 20px;
        margin-left: 10px;
        border-radius: 4px;
        cursor: pointer;

    }

    input[type=submit]:hover {
        background-color: #0055ff;
        color: white;
    } */

    .cerrarSesion {

        width: 180px;
        font-size: 10pt;
        color: #0055ff;
        border: #0055ff solid 1px;
        background-color: white;
        padding: 14px 20px;
        margin-left: 10px;
        border-radius: 4px;
        cursor: pointer;

    }

    .cerrarSesion:hover {
        background-color: #0055ff;
        color: white;
    }

    .btnModulo {
        border-radius: 10px 10px 10px 10px;
        color: white;
        font-size: 10pt;
        background-color: #0055ff;

    }

    .inicio {
        border-radius: 5px;
        background-color: #ffffff;
        padding: 20px;
        width: 1000px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
        border-radius: 20px;
    }

    h1,
    h2,
    h3,
    h4,
    h6,
    input {
        font-family: 'Work Sans', sans-serif;
    }

    button {
        width: 200px;
        background-color: #ffffff;
        color: #0055ff;
        padding: 14px 20px;
        margin: 0 auto;
        border: none;
        cursor: pointer;
        box-sizing: border-box;

    }

</style>
<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif

                            @if (Route::has('registro'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('registro') }}">{{ __('Registro') }}</a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <main class="py-4">
            <div class="row justify-content-center"><img src="{{ asset('img/logo-contarapp-01.png') }}" width="30%">
            </div>
            @yield('content')
        </main>
    </div>
</body>
<footer style="margin-top: 50px;">
    <p class="row justify-content-center" style="font-size: 20px; font-weight: bold;">CONTARAPP 2021 | JMB Contadores</p>
</footer>
</html>
