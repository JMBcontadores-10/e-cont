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
    <script src="{{ asset('js/code.js')}}" defer></script>
    <script src="{{ asset('js/numeros.js')}}" defer></script>
    {{-- <script src="{{ asset('js/jquery-3.1.1.min.js')}}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <!--calendario-->
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />-->

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
    <link  href="{{ asset ('css/fullcalendar.css') }}" rel="stylesheet" />
    <script src="{{ asset ('js/moment.min.js')}}" defer></script>
    <script src="{{ asset ('js/fullcalendar.js')}}" defer></script>
    <script src="{{ asset ('js/es.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="{{ asset ('css/toastr.min.css')}}" rel="stylesheet" />

</head>

<body>
    <div id="app">
        @if (Auth::check() && !Route::is('home'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="img/logo-contarapp-01.png" width="200px">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                                {{-- @if (Route::has('login'))
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
                                @endif --}}
                            @else

                                <div class="dropdown-menu-right">
                                    <a href="{{ route('logout') }}" class="nav-item button1" onclick="event.preventDefault();
                                                                      document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>


                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif


        <main class="py-4">
            @if (Route::is('home', 'login'))
            <div class="row justify-content-center mb-3">
                <img src="{{ asset('img/logo-contarapp-01.png') }}" width="30%">
            </div>
        @endif
            @yield('content')
        </main>
    </div>

    @stack('calendario')
</body>
<footer style="margin-top: 50px;">
    <p class="row justify-content-center" style="font-size: 20px; font-weight: bold;">CONTARAPP {{date('Y')}} | JMB Contadores</p>
</footer>
</html>
