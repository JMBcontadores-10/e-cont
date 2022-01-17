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
    <!--<link href="{{ asset('css/styles.css') }}" rel="stylesheet">-->
    <link href="{{ asset('css/seccionRelacionados.css') }}" rel="stylesheet" >
<link href="{{ asset('css/estilos_generales.css')}}" rel="stylesheet">
  {{--------- estilos para el dashbard  ---}}
    <link href="{{asset('css/vendors/bundle.css')}}" rel="stylesheet">
    <link href="{{asset('css/vendors/datepicker/daterangepicker.css')}}" rel="stylesheet">
       <link href="{{asset('css/vendors/vmap/jqvmap.min.css')}}" rel="stylesheet">
          <link href="{{asset('css/assets/css/app.min.css')}}" rel="stylesheet">
     {{--------- end estilos para el dashbard  ---}}

 



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

      {{-----------scripts dashboard --------}}
  <!-- Plugin scripts -->
<script src="{{asset('js/vendors/bundle.js')}}" defer></script>

<!-- Chartjs -->
<script src="{{asset('js/vendors/charts/chartjs/chart.min.js')}}"defer></script>

<!-- Apex chart -->
<script src="{{asset('js/vendors/charts/apex/apexcharts.min.js')}}"defer></script>

<!-- Circle progress -->
<script src="{{asset('js/vendors/circle-progress/circle-progress.min.js')}}"defer></script>

<!-- Peity -->
<script src="{{asset('js/vendors/charts/peity/jquery.peity.min.js')}}"defer></script>
<script src="{{asset('js/assets/js/examples/charts/peity.js')}}"defer></script>

<!-- Datepicker -->
<script src="{{asset('js/vendors/datepicker/daterangepicker.js')}}"defer></script>

<!-- Slick -->
<script src="{{asset('js/vendors/slick/slick.min.js')}}"></script>

<!-- Vamp -->
<script src="{{asset('js/vendors/vmap/jquery.vmap.min.js')}}"defer></script>
<script src="{{asset('js/vendors/vmap/maps/jquery.vmap.usa.js')}}"defer></script>
<script src="{{asset('js/assets/js/examples/vmap.js')}}"defer></script>

<!-- Dashboard scripts -->
<script src="{{asset('js/assets/js/examples/dashboard.js')}}" defer></script>
<div class="colors"> <!-- To use theme colors with Javascript -->
    <div class="bg-primary"></div>
    <div class="bg-primary-bright"></div>
    <div class="bg-secondary"></div>
    <div class="bg-secondary-bright"></div>
    <div class="bg-info"></div>
    <div class="bg-info-bright"></div>
    <div class="bg-success"></div>
    <div class="bg-success-bright"></div>
    <div class="bg-danger"></div>
    <div class="bg-danger-bright"></div>
    <div class="bg-warning"></div>
    <div class="bg-warning-bright"></div>
</div>

<!-- App scripts -->
<script src="{{asset('js/assets/js/app.min.js')}}" defer></script>
      
      {{----------- end scripts dashboard    ---}}



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


<!--================ codigo body=======================================  -->
<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->


<!-- begin::header -->
<div class="header">

    <div>
        <ul class="navbar-nav">

            <!-- begin::navigation-toggler -->
            <li class="nav-item navigation-toggler">
                <a href="#" class="nav-link" title="Hide navigation">
                    <i data-feather="arrow-left"></i>
                </a>
            </li>
            <li class="nav-item navigation-toggler mobile-toggler">
                <a href="#" class="nav-link" title="Show navigation">
                    <i data-feather="menu"></i>
                </a>
            </li>
            <!-- end::navigation-toggler -->

            <li class="nav-item">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Create</a>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">User</a>
                    <a href="#" class="dropdown-item">Category</a>
                    <a href="#" class="dropdown-item">Product</a>
                    <a href="#" class="dropdown-item">Report</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Apps</a>
                <div class="dropdown-menu dropdown-menu-big">
                    <div class="p-3">
                        <div class="row row-xs">
                            <div class="col-6">
                                <a href="chat.html">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="message-circle"></i>
                                        <div class="mt-2">Chat</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="inbox.html">
                                    <div class="p-3 border-radius-1 border text-center mb-3">
                                        <i class="width-23 height-23" data-feather="mail"></i>
                                        <div class="mt-2">Mail</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="app-todo.html">
                                    <div class="p-3 border-radius-1 border text-center">
                                        <i class="width-23 height-23" data-feather="check-circle"></i>
                                        <div class="mt-2">Todo</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="file-manager.html">
                                    <div class="p-3 border-radius-1 border text-center">
                                        <i class="width-23 height-23" data-feather="file"></i>
                                        <div class="mt-2">File Manager</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div>
        <ul class="navbar-nav">

            <li class="nav-item">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img width="18" class="mr-2" src="img/assets/media/image/flags/261-china.png" alt="flag"> China
                </a>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">
                        <img width="18" src="img/assets/media/image/flags/003-tanzania.png" class="mr-2" alt="flag">
                        Tanzania
                    </a>
                    <a href="#" class="dropdown-item">
                        <img width="18" src="img/assets/media/image/flags/262-united-kingdom.png" class="mr-2"
                             alt="flag"> United Kingdom
                    </a>
                    <a href="#" class="dropdown-item">
                        <img width="18" src="img/assets/media/image/flags/013-tunisia.png" class="mr-2" alt="flag">
                        Tunisia
                    </a>
                    <a href="#" class="dropdown-item">
                        <img width="18" src="img/assets/media/image/flags/044-spain.png" class="mr-2" alt="flag"> Spain
                    </a>
                </div>
            </li>

            <!-- begin::header search -->
            <li class="nav-item">
                <a href="#" class="nav-link" title="Search" data-toggle="dropdown">
                    <i data-feather="search"></i>
                </a>
                <div class="dropdown-menu p-2 dropdown-menu-right">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <div class="input-group-prepend">
                                <button class="btn" type="button">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- end::header search -->

            <!-- begin::header minimize/maximize -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" title="Fullscreen" data-toggle="fullscreen">
                    <i class="maximize" data-feather="maximize"></i>
                    <i class="minimize" data-feather="minimize"></i>
                </a>
            </li>
            <!-- end::header minimize/maximize -->

            <!-- begin::header messages dropdown -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link nav-link-notify" title="Chats" data-toggle="dropdown">
                    <i data-feather="message-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="img/assets/media/image/image1.jpg">
                        <h6 class="mb-0">Chats</h6>
                        <small class="font-size-11 opacity-7">2 unread chats</small>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                            <li>
                                <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="img/assets/media/image/user/man_avatar1.jpg"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Herbie Pallatina
                                            <i title="Mark as read" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-circle-o font-size-11"></i>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2">02:30 PM</span>
                                            <span>Have you madimage</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                   class="list-group-item d-flex align-items-center hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="img/assets/media/image/user/women_avatar5.jpg"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Andrei Miners
                                            <i title="Mark as read" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-circle-o font-size-11"></i>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2">08:36 PM</span>
                                            <span>I have a meetinimage</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="text-divider small pb-2 pl-3 pt-3">
                                <span>Old chats</span>
                            </li>
                            <li>
                                <a href="#"
                                   class="list-group-item d-flex align-items-center hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="img/assets/media/image/user/man_avatar3.jpg"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Kevin added
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2">11:09 PM</span>
                                            <span>Have you madimage</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="img/assets/media/image/user/man_avatar2.jpg"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Eugenio Carnelley
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2">Yesterday</span>
                                            <span>I have a meetinimage</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                   class="list-group-item d-flex align-items-center hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                            <img src="img/assets/media/image/user/women_avatar1.jpg"
                                                 class="rounded-circle" alt="user">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Neely Ferdinand
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <div class="small text-muted">
                                            <span class="mr-2">Yesterday</span>
                                            <span>I have a meetinimage</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="p-2 text-right">
                        <ul class="list-inline small">
                            <li class="list-inline-item">
                                <a href="#">Mark All Read</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <!-- end::header messages dropdown -->

            <!-- begin::header notification dropdown -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link nav-link-notify" title="Notifications" data-toggle="dropdown">
                    <i data-feather="bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="img/assets/media/image/image1.jpg">
                        <h6 class="mb-0">Notifications</h6>
                        <small class="font-size-11 opacity-7">1 unread notifications</small>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                            <li>
                                <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-success-bright text-success rounded-circle">
                                                    <i class="ti-user"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            New customer registered
                                            <i title="Mark as read" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-circle-o font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">20 min ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="text-divider small pb-2 pl-3 pt-3">
                                <span>Old notifications</span>
                            </li>
                            <li>
                                <a href="#" class="list-group-item d-flex hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-warning-bright text-warning rounded-circle">
                                                    <i class="ti-package"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            New Order Recieved
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">45 sec ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                   class="list-group-item d-flex align-items-center hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-danger-bright text-danger rounded-circle">
                                                    <i class="ti-server"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Server Limit Reached!
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">55 sec ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                   class="list-group-item d-flex align-items-center hide-show-toggler">
                                    <div>
                                        <figure class="avatar avatar-sm m-r-15">
                                                <span class="avatar-title bg-info-bright text-info rounded-circle">
                                                    <i class="ti-layers"></i>
                                                </span>
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 line-height-20 d-flex justify-content-between">
                                            Apps are ready for update
                                            <i title="Mark as unread" data-toggle="tooltip"
                                               class="hide-show-toggler-item fa fa-check font-size-11"></i>
                                        </p>
                                        <span class="text-muted small">Yesterday</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="p-2 text-right">
                        <ul class="list-inline small">
                            <li class="list-inline-item">
                                <a href="#">Mark All Read</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <!-- end::header notification dropdown -->

            <!-- begin::user menu -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" title="User menu" data-toggle="dropdown">
                    <i data-feather="settings"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-4 text-center d-flex justify-content-between"
                         data-backround-image="img/assets/media/image/image1.jpg">
                        <h6 class="mb-0">Settings</h6>
                    </div>
                    <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                    <label class="custom-control-label" for="customSwitch1">Allow notifications.</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2">Hide user requests</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" checked>
                                    <label class="custom-control-label" for="customSwitch3">Speed up demands</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch4" checked>
                                    <label class="custom-control-label" for="customSwitch4">Hide menus</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch5">
                                    <label class="custom-control-label" for="customSwitch5">Remember next visits</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch6">
                                    <label class="custom-control-label" for="customSwitch6">Enable report generation.</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <!-- end::user menu -->
        </ul>

        <!-- begin::mobile header toggler -->
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item header-toggler">
                <a href="#" class="nav-link">
                    <i data-feather="arrow-down"></i>
                </a>
            </li>
        </ul>
        <!-- end::mobile header toggler -->
    </div>

</div>
<!-- end::header -->

<!-- begin::main -->
<div id="main">

    <!-- begin::navigation -->
    <div class="navigation">

        <div class="navigation-menu-tab">
            <div>
                <div class="navigation-menu-tab-header" data-toggle="tooltip" title="Roxana Roussell" data-placement="right">
                    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                        <figure class="avatar avatar-sm">
                            <img src="img/assets/media/image/user/women_avatar1.jpg" class="rounded-circle" alt="avatar">
                        </figure>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                        <div class="p-3 text-center" data-backround-image="img/assets/media/image/image1.jpg">
                            <figure class="avatar mb-3">
                                <img src="img/assets/media/image/user/women_avatar1.jpg" class="rounded-circle" alt="image">
                            </figure>
                            <h6 class="d-flex align-items-center justify-content-center">
                                Roxana Roussell
                                <a href="#" class="btn btn-primary btn-sm ml-2" data-toggle="tooltip" title="Edit profile">
                                    <i data-feather="edit-2"></i>
                                </a>
                            </h6>
                            <small>Balance: <strong>$105</strong></small>
                        </div>
                        <div class="dropdown-menu-body">
                            <div class="border-bottom p-4">
                                <h6 class="text-uppercase font-size-11 d-flex justify-content-between">
                                    Storage
                                    <span>%25</span>
                                </h6>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 35%;"
                                         aria-valuenow="35"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item">Profile</a>
                                <a href="#" class="list-group-item d-flex">
                                    Followers <span class="text-muted ml-auto">214</span>
                                </a>
                                <a href="#" class="list-group-item d-flex">
                                    Inbox <span class="text-muted ml-auto">18</span>
                                </a>
                                <a href="#" class="list-group-item" data-sidebar-target="#settings">Billing</a>
                                <a href="#" class="list-group-item" data-sidebar-target="#settings">Need help?</a>
                                <a href="#" class="list-group-item text-danger" data-sidebar-target="#settings">Sign Out!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-grow-1">
                <ul>
                    <li>
                        <a class="active" href="#" data-toggle="tooltip" data-placement="right" title="Dashboards"
                           data-nav-target="#dashboards">
                            <i data-feather="bar-chart-2"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Apps" data-nav-target="#apps">
                            <i data-feather="command"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="UI Elements"
                           data-nav-target="#elements">
                            <i data-feather="layers"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Pages" data-nav-target="#pages">
                            <i data-feather="copy"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <ul>
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Settings">
                            <i data-feather="settings"></i>
                        </a>
                    </li>
                    <li>
                        <a href="login.html" data-toggle="tooltip" data-placement="right" title="Logout">
                            <i data-feather="log-out"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- begin::navigation menu -->
        <div class="navigation-menu-body">

            <!-- begin::navigation-logo -->
            <div>
                <div id="navigation-logo">
                    <a href="index-2.html">
                        <img class="logo" style=" height:30px;"; src="img/e-conta-logo-01.png" alt="logo">
                        <img class="logo-light" src="img/e-conta-logo-01.png" alt="light logo">
                    </a>
                </div>
            </div>
            <!-- end::navigation-logo -->

            <div class="navigation-menu-group">

                <div class="open" id="dashboards">
                    <ul>
                        <li class="navigation-divider">Dashboards</li>
                        <li><a class="active" href="index-2.html">CRM System</a></li>
                        <li><a href="dashboard-two.html">Ecommerce <span class="badge badge-danger">2</span></a></li>
                        <li><a href="dashboard-three.html">Analytics</a></li>
                        <li><a href="dashboard-four.html">Project Management</a></li>
                        <li><a href="dashboard-five.html">Helpdesk Management</a></li>
                        <li class="navigation-divider">Contacts</li>
                        <li>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item d-flex align-items-center">
                                    <div>
                                        <div class="avatar avatar-sm m-r-10">
                                            <img src="img/assets/media/image/user/man_avatar1.jpg" class="rounded-circle" alt="image">
                                        </div>
                                    </div>
                                    <span>Valentine Maton</span>
                                </a>
                                <a href="#" class="list-group-item d-flex align-items-center">
                                    <div>
                                        <div class="avatar avatar-sm m-r-10">
                                            <img src="img/assets/media/image/user/women_avatar2.jpg" class="rounded-circle" alt="image">
                                        </div>
                                    </div>
                                    <span>Holmes Cherryman</span>
                                </a>
                                <a href="#" class="list-group-item d-flex align-items-center">
                                    <div>
                                        <div class="avatar avatar-sm m-r-10">
                                            <img src="img/assets/media/image/user/women_avatar4.jpg" class="rounded-circle" alt="image">
                                        </div>
                                    </div>
                                    <span>Kenneth Hune</span>
                                </a>
                            </div>
                        </li>
                        <li class="navigation-divider">Followers</li>
                        <li>
                            <div class="avatar-group ml-4">
                                <a href="#" class="avatar">
                                    <span class="avatar-title bg-success rounded-circle">E</span>
                                </a>
                                <a href="#" class="avatar">
                                    <img src="img/assets/media/image/user/women_avatar5.jpg" class="rounded-circle" alt="avatar">
                                </a>
                                <a href="#" class="avatar">
                                    <img src="img/assets/media/image/user/women_avatar2.jpg" class="rounded-circle" alt="avatar">
                                </a>
                                <a href="#" class="avatar">
                                    <span class="avatar-title bg-info rounded-circle">C</span>
                                </a>
                                <a href="#" class="avatar">
                                    <span class="avatar-title bg-dark rounded-circle">+30</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="apps">
                    <ul>
                        <li class="navigation-divider">Web Apps</li>
                        <li>
                            <a href="chat.html">
                                <span>Chat</span>
                                <span class="badge badge-danger">5</span>
                            </a>
                        </li>
                        <li>
                            <a href="inbox.html">
                                <span>Mail</span>
                            </a>
                        </li>
                        <li>
                            <a href="app-todo.html">
                                <span>Todo</span>
                                <span class="badge badge-warning">2</span>
                            </a>
                        </li>
                        <li>
                            <a href="file-manager.html">
                                <span>File Manager</span>
                            </a>
                        </li>
                        <li>
                            <a href="calendar.html">
                                <span>Calendar</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="elements">
                    <ul>
                        <li class="navigation-divider">UI Elements</li>
                        <li>
                            <a href="#">Basic</a>
                            <ul>
                                <li><a href="alerts.html">Alert</a></li>
                                <li><a href="accordion.html">Accordion</a></li>
                                <li><a href="buttons.html">Buttons</a></li>
                                <li><a href="dropdown.html">Dropdown</a></li>
                                <li><a href="list-group.html">List Group</a></li>
                                <li><a href="pagination.html">Pagination</a></li>
                                <li><a href="typography.html">Typography</a></li>
                                <li><a href="media-object.html">Media Object</a></li>
                                <li><a href="progress.html">Progress</a></li>
                                <li><a href="modal.html">Modal</a></li>
                                <li><a href="spinners.html">Spinners</a></li>
                                <li><a href="navs.html">Navs</a></li>
                                <li><a href="tab.html">Tab</a></li>
                                <li><a href="tooltip.html">Tooltip</a></li>
                                <li><a href="popovers.html">Popovers</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Cards</a>
                            <ul>
                                <li><a href="basic-cards.html">Basic Cards </a></li>
                                <li><a href="image-cards.html">Image Cards </a></li>
                                <li><a href="card-scroll.html">Card Scroll </a></li>
                                <li><a href="other-cards.html">Others </a></li>
                            </ul>
                        </li>
                        <li><a href="avatar.html">Avatar</a></li>
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="colors.html">Colors</a></li>
                        <li>
                            <a href="#">Plugins</a>
                            <ul>
                                <li><a href="sweet-alert.html">Sweet Alert</a></li>
                                <li><a href="lightbox.html">Lightbox</a></li>
                                <li><a href="toast.html">Toast</a></li>
                                <li><a href="tour.html">Tour</a></li>
                                <li><a href="slick-slide.html">Slick Slide</a></li>
                                <li><a href="nestable.html">Nestable</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Forms</a>
                            <ul>
                                <li><a href="basic-form.html">Form Layouts</a></li>
                                <li><a href="custom-form.html">Custom Forms</a></li>
                                <li><a href="advanced-form.html">Advanced Form</a></li>
                                <li><a href="form-validation.html">Validation</a></li>
                                <li><a href="form-wizard.html">Wizard</a></li>
                                <li><a href="file-upload.html">File Upload</a></li>
                                <li><a href="datepicker.html">Datepicker</a></li>
                                <li><a href="timepicker.html">Timepicker</a></li>
                                <li><a href="colorpicker.html">Colorpicker</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Tables</a>
                            <ul>
                                <li><a href="tables.html">Basic Tables</a></li>
                                <li><a href="data-table.html">Datatable</a></li>
                                <li><a href="responsive-table.html">Responsive Tables</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Charts</a>
                            <ul>
                                <li><a href="apexchart.html">Apex</a></li>
                                <li><a href="chartjs.html">Chartjs</a></li>
                                <li><a href="justgage.html">Justgage</a></li>
                                <li><a href="morsis.html">Morsis</a></li>
                                <li><a href="peity.html">Peity</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Maps</a>
                            <ul>
                                <li><a href="google-map.html">Google</a></li>
                                <li><a href="vector-map.html">Vector</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div id="pages">
                    <ul>
                        <li class="navigation-divider">Pages</li>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="register.html">Register</a></li>
                        <li><a href="recover-password.html">Recovery Password</a></li>
                        <li><a href="lock-screen.html">Lock Screen</a></li>
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="invoice.html">Invoice</a></li>

                        <li><a href="pricing-table.html">Pricing Table</a></li>
                        <li><a href="search-result.html">Search Result</a></li>
                        <li>
                            <a href="#">Error Pages</a>
                            <ul>
                                <li><a href="404.html">404</a></li>
                                <li><a href="404-2.html">404 V2</a></li>
                                <li><a href="503.html">503</a></li>
                                <li><a href="mean-at-work.html">Mean at Work</a></li>
                            </ul>
                        </li>
                        <li><a href="blank-page.html">Starter Page</a></li>
                        <li>
                            <a href="#">Email Templates</a>
                            <ul>
                                <li><a href="email-template-basic.html">Basic</a></li>
                                <li><a href="email-template-alert.html">Alert</a></li>
                                <li><a href="email-template-billing.html">Billing</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Menu Level</a>
                            <ul>
                                <li>
                                    <a href="#">Menu Level</a>
                                    <ul>
                                        <li>
                                            <a href="#">Menu Level </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end::navigation menu -->

    </div>
    <!-- end::navigation -->

    <!-- begin::main-content -->
    <div class="main-content">

        <!-- begin::page-header -->
        <div class="page-header">
            <div class="container-fluid d-sm-flex justify-content-between">
                <h4>CRM System</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">CRM System</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end::page-header -->










@yield('content');


</body>
<footer style="margin-top: 20px;">
    <p class="row justify-content-center" style="font-size: 20px; font-weight: bold;">E-CONT {{ date('Y') }} |
        JMB Contadores</p>
</footer>
@stack('scripts')
</html>
 <!-- =============codigo body fin====================================== -->       
{{--



<div id="page-loader"><span class="preloader-interior"></span></div>

<div id="app">

    @if (Auth::check() && !Route::is('home', 'login', 'modules', 'log') && !Route::is('construccion'))

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/modules') }}">
                    <img src="img/e-conta-logo-01.png" width="200px">
                </a>

                --}}
                {{-- <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button> --}}

             {{--    <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                       {{-- @else
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

--}}



