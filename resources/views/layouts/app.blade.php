<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/logo-contarapp-03.png') }}" type="image/png" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/code.js') }}" defer></script>
    <script src="{{ asset('js/numeros.js') }}" defer></script>
    <script  src="{{ asset('js/chequesytrasncontrol.js') }}" defer > </script>


    <script src="{{ asset('js/calendar.js') }}" defer></script>
    <script src="{{ asset('js/calendari.js')}}" defer></script>
    <script src="https://kit.fontawesome.com/4b9ba14b0f.js" crossorigin="anonymous"></script>

    <script src="{{ asset('js/fullcalendar.js') }}" defer></script>
    <script src="{{ asset('js/moment.min.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <!-- Datatable-->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>

    <!-- Fonts -->
    <link rel="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- google fonts / icons-->

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    {{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/seccionRelacionados.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/estilos_generales.css')}}" rel="stylesheet">




    <!--charts-->

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!--calendario-->
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />-->

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->

    <script src="{{ asset('js/moment.min.js') }}" defer></script>
    {{-- <script src="{{ asset('js/fullcalendar.js') }}" defer></script> --}}
    <script src="{{ asset('js/excel.js') }}" defer></script>
    <script src="{{ asset('js/es.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@stack('styles')

  <!-- estilos para PIKADAY calendario /para el uso de livewire input-date-->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
  <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<!-- cdn para push notifiaciones  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
</head>



<body>
    <!-- add before </body> cdn´s para filepond() validacion de archivos pdf/jpg etc.. -->
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<!-- add before </body> cdn´s para filepond() validacion de archivos pdf/jpg etc.. -->
<!-- CDN´S para validacion de tamaño filepond</body> -->
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<!-- CDN´S para validacion de tamaño filepond</body> -->


    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
   $(document).ready(function() {
    $('#page-loader').fadeOut(500);
});
            </script>
            <div id="page-loader"><span class="preloader-interior"></span></div>

    <div id="app">

        @if (Auth::check() && !Route::is('home', 'login', 'modules', 'log') && !Route::is('construccion'))

            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/modules') }}">
                        <img src="img/e-conta-logo-01.png" width="200px">
                    </a>
                    {{-- <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button> --}}

                    <div {{-- class="collapse navbar-collapse" --}} id="navbarSupportedContent">
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
                                <div>
                                    <a href="{{ route('logout') }}" class="nav-item button1"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <div>
                <nav class="navbar navbar-expand-lg navbar-light " style="background-color:; position: absolute; left:10%; ">
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                      <div class="navbar-nav">
                        @if (Session::get('tipoU') == '2')
                        <a class="nav-item nav-link" href="{{ url('descargasv2') }}">Descargas</a>
                        <a class="nav-item nav-link" href="{{ url('consultas') }}">Consultas</a>
                        @endif
                        <a class="nav-item nav-link" href="{{ url('construccion') }}">Expediente Digital</a>
                        <a class="nav-item nav-link" href="{{ url('volumetrico') }}">Control Volumétrico</a>
                        <a class="nav-item nav-link" href="{{ url('cuentasporpagar') }}">Cuentas por pagar</a>
                        <a class="nav-item nav-link" href="{{ url('cheques-transferencias') }}">Cheques y Transferencias</a>
                        <a class="nav-item nav-link" href="{{ url('construccion') }}">Expediente Fiscal</a>
                        <a class="nav-item nav-link" href="{{ url('construccion') }}">Nómina</a>
                        @if (Session::get('tipoU') == '2')
                        <a class="nav-item nav-link" href="{{ url('monitoreo') }}">Monitoreo</a>
                        <a class="nav-item nav-link" href="{{ url('auditoria') }}">Auditoría</a>
                        @endif
                      </div>
                    </div>
                  </nav>
            </div>
            <br>
            <br>

        @endif


        <main class="py-4">

            @if (Route::is('home', 'login', 'modules', 'log'))
                <div class="row justify-content-center mb-3">
                    <img src="{{ asset('img/logo-contarapp-01.png') }}" width="380px">
                </div>
            @endif

            @yield('content')



        </main>
    </div>

    @stack('calendario')

</body>
<footer style="margin-top: 20px;">
    <p class="row justify-content-center" style="font-size: 20px; font-weight: bold;">E-CONT {{ date('Y') }} |
        JMB Contadores</p>
</footer>
@stack('scripts')
</html>
