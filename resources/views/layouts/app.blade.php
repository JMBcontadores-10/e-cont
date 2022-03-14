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
    <script  src="{{ asset('js/General.js') }}" defer > </script>

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
   {{-- <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> --}}
   <link href="{{ asset('css/seccionRelacionados.css') }}" rel="stylesheet" >
<link href="{{ asset('css/estilos_generales.css')}}" rel="stylesheet">
  {{--------- estilos para el dashbard  ---}}
  {{--------- estilos para el dashbard  ---}}

   <!-- BEGIN: Vendor CSS-->
   <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/vendors.min.css') }}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/charts/apexcharts.css') }}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/extensions/swiper.min.css') }}">
   <!-- END: Vendor CSS-->


       <!-- BEGIN: Vendor CSS-->
       <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/vendors.min.css')}}">
       <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
       <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
       <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
       <link rel="stylesheet"  href="{{ asset('css/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
       <!-- END: Vendor CSS-->

   <!-- BEGIN: Theme CSS-->
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/bootstrap.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/bootstrap-extended.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('app-assets/css/colors.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/components.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/themes/dark-layout.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/themes/semi-dark-layout.min.css')}}">
   <!-- END: Theme CSS-->


   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/pages/app-invoice.min.css')}}">
   <!-- END: Page CSS-->

   <!-- BEGIN: Page CSS-->
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/core/menu/menu-types/vertical-menu.min.css')}}">
   <link rel="stylesheet"  href="{{ asset('css/app-assets/css/pages/dashboard-ecommerce.min.css')}}">
   <!-- END: Page CSS-->

   <!-- BEGIN: Custom CSS-->
   <link rel="stylesheet"  href="{{ asset('css/assets/css/style.css')}}">
   <!-- END: Custom CSS-->

   <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
     {{--------- end estilos para el dashbard  ---}}





     {{--------- end estilos para el dashbard  ---}}


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
    <script src="{{ asset('js/es.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet" />

      {{-----------scripts dashboard --------}}


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('js/app-assets/vendors/js/vendors.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js') }}" defer></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.min.js') }}" defer></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('js/app-assets/js/scripts/configs/vertical-menu-light.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/js/core/app-menu.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/js/core/app.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/js/scripts/components.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/js/scripts/footer.min.js') }}" defer></script>
    <script src="{{ asset('js/app-assets/js/scripts/customizer.min.js') }}" defer></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('js/app-assets/js/scripts/pages/app-invoice.min.js') }}" defer></script>
    <!-- END: Page JS-->






      {{----------- end scripts dashboard    ---}}



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@stack('styles')

  <!-- estilos para PIKADAY calendario /para el uso de livewire input-date-->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
  <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<!-- cdn para push notifiaciones  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>


  <!-- Alertas Links -->
  <script>
        function alerta() {
            alert("¡En Construcción!");
        }
    </script>


</head>


<body class="vertical-layout vertical-menu-modern 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" >
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
    mostrarSaludo()
});
            </script>

@livewire('livewire-ui-modal')
<!--================ codigo body=======================================  -->

<!-- begin::preloader-->
<div id="page-loader"><span class="preloader-interior"></span></div>
<!-- end::preloader -->

@if(Auth::check())

auth()->user();

$tipo=auth()->user->tipo;
{{--$rfc=auth()->user()->RFC--}}
$empresa=auth()->user->tipo;

          @endif









<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
  <div class="navbar-wrapper">
    <div class="navbar-container content">
      <div class="navbar-collapse" id="navbar-mobile">
        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
          <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="javascript:void(0);"><i class="ficon bx bx-menu"></i></a></li>
          </ul>
          <ul class="nav navbar-nav bookmark-icons">
            <li class="nav-item d-none d-lg-block" onclick="alerta()"><a class="nav-link"  data-toggle="tooltip" data-placement="top" title="Email"><i class="ficon bx bx-envelope"></i></a></li>

            <li class="nav-item d-none d-lg-block" onclick="alerta()"><a class="nav-link"  data-toggle="tooltip" data-placement="top" title="Chat"><i class="ficon bx bx-chat"></i></a></li>

            <li class="nav-item d-none d-lg-block" onclick="alerta()" ><a class="nav-link"  data-toggle="tooltip" data-placement="top" title="Todo"><i class="ficon bx bx-check-circle"></i></a></li>

            <li class="nav-item d-none d-lg-block" onclick="alerta()"><a class="nav-link" data-toggle="tooltip" data-placement="top" title="Calendario"><i class="ficon bx bx-calendar-alt"></i></a></li>
          </ul>

          <ul class="nav navbar-nav">
            <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon bx bx-star warning"></i></a>
              <div class="bookmark-input search-input">
                <div class="bookmark-input-icon"><i class="bx bx-search primary"></i></div>
                <input class="form-control input" type="text" placeholder="Explorar..." tabindex="0" data-search="template-search">
                <ul class="search-list"></ul>
              </div>
            </li>
          </ul>
        </div>


        <ul class="nav navbar-nav float-right">

        <!--
          <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
            <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="javascript:void(0);" data-language="en"><i class="flag-icon flag-icon-us mr-50"></i> English</a><a class="dropdown-item" href="javascript:void(0);" data-language="fr"><i class="flag-icon flag-icon-fr mr-50"></i> French</a><a class="dropdown-item" href="javascript:void(0);" data-language="de"><i class="flag-icon flag-icon-de mr-50"></i> German</a><a class="dropdown-item" href="javascript:void(0);" data-language="pt"><i class="flag-icon flag-icon-pt mr-50"></i> Portuguese</a></div>
          </li>
        -->
          <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
          <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon bx bx-search"></i></a>
            <div class="search-input">
              <div class="search-input-icon"><i class="bx bx-search primary"></i></div>
              <input class="input" type="text" placeholder="Explorar..." tabindex="-1" data-search="template-search">
              <div class="search-input-close"><i class="bx bx-x"></i></div>
              <ul class="search-list"></ul>
            </div>
          </li>

<!--========NOTIFICATION SECTION=============== -->


<livewire:notification-secction>

    <!--========NOTIFICATION SECTION=============== -->

    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:void(0);" data-toggle="dropdown">
        <div class="user-nav d-sm-flex d-none"><span class="user-name">{{ucfirst(auth()->user()->nombre)}}</span><span class="user-status text-muted"><i style="color:#90EE90; box-shadow: 0 0 5px #4f9;" class="fas fa-circle"></i>&nbsp;Conectado</span></div><span><img class="round" src="img/user.png" alt="avatar" height="40" width="40"></span></a>
      <div class="dropdown-menu dropdown-menu-right pb-0"><a class="dropdown-item" href="page-user-profile.html"><i class="bx bx-user mr-50"></i> Editar Perfil</a><a class="dropdown-item" href="app-email.html"><i class="bx bx-envelope mr-50"></i> Email</a><a class="dropdown-item" href="app-todo.html"><i class="bx bx-check-square mr-50"></i>Tareas</a><a class="dropdown-item" href="app-chat.html"><i class="bx bx-message mr-50"></i>Chats</a>
        <div class="dropdown-divider mb-0"></div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <input class="dropdown-item" type="submit" value="Cerrar sesión">
      </form>

      </div>
    </li>
  </ul>
</div>
</div>
</div>
</nav>

<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div style="background-color:#397ac4;"  class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item mr-auto" id="Inicio" ><a class="navbar-brand" href="{{ url('modules') }}">
          <div class="brand-logo">

          <img src="img/logo-contarapp-03.png" alt="" class= "logo"> </img>

          </div>
          <h2 class="brand-text mb-0" style="color:#FFFFFF">E-Cont</h2></a></li>
      <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a></li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">

    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">

      <li class=" nav-item"><a href="index.html"><i class="#" data-icon="desktop"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Módulos</span></a>
        <ul class="menu-content">

          <li id="chequesytransferencias"><a class="d-flex align-items-center" href="{{ url('chequesytransferencias') }}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Cheques y Transferencias</span></a>
          </li>
          <li id="cuentasporpagar"><a class="d-flex align-items-center" href="{{ url('cuentasporpagar') }}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Cuentas por Pagar</span></a>
          </li>
          <li id="descargasv2"><a class="d-flex align-items-center" href="{{ url('descargasv2') }}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Descargas</span></a>
          </li>
          <li id="Contruccion"><a class="d-flex align-items-center" onclick="alerta()"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Nóminas</span></a>
          </li>
          <li id="Contruccion"><a class="d-flex align-items-center" onclick="alerta()" href=""><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Expediente Digital</span></a>
          </li>
          <li id="volumetrico"><a class="d-flex align-items-center" href="{{('volumetrico')}}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Control Volumetrico</span></a>
          </li>
          <li id="Contruccion"><a class="d-flex align-items-center" onclick="alerta()"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Expediente Fiscal</span></a>
          </li>
          <li id="auditoria"><a class="d-flex align-items-center" href="{{('auditoria')}}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Auditoria</span></a>
          </li>
          <li id="consultas"><a class="d-flex align-items-center" href="{{('consultas')}}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Consultas</span></a>
          </li>
          <li id="monitoreo"><a class="d-flex align-items-center" href="{{('monitoreo')}}"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Analytics">Monitoreo de facturación</span></a>
          </li>
        </ul>

      </li>

        <!--
      <li class=" navigation-header text-truncate"><span data-i18n="Apps">Apps</span>
      </li>
      <li class=" nav-item"><a href="app-email.html"><i class="#" data-icon="envelope-pull"></i><span class="menu-title text-truncate" data-i18n="Email">Email</span></a>
      </li>
      <li class=" nav-item"><a href="app-chat.html"><i class="#" data-icon="comments"></i><span class="menu-title text-truncate" data-i18n="Chat">Chat</span></a>
      </li>
      <li class=" nav-item"><a href="app-todo.html"><i class="#" data-icon="check-alt"></i><span class="menu-title text-truncate" data-i18n="Todo">Todo</span></a>
      </li>
      <li class=" nav-item"><a href="app-calendar.html"><i class="#" data-icon="calendar"></i><span class="menu-title text-truncate" data-i18n="Calendar">Calendar</span></a>
      </li>
      <li class=" nav-item"><a href="app-kanban.html"><i class="#" data-icon="grid"></i><span class="menu-title text-truncate" data-i18n="Kanban">Kanban</span></a>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="notebook"></i><span class="menu-title text-truncate" data-i18n="Invoice">Invoice</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="app-invoice-list.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Invoice List">Invoice List</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="app-invoice.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Invoice">Invoice</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="app-invoice-edit.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Invoice Edit">Invoice Edit</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="app-invoice-add.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Invoice Add">Invoice Add</span></a>
          </li>
        </ul>
      </li>





      <li class=" nav-item"><a href="app-file-manager.html"><i class="#" data-icon="save"></i><span class="menu-title text-truncate" data-i18n="File Manager">File Manager</span></a>
      </li>

      <li class=" nav-item"><a href="#"><i class="#" data-icon="users"></i><span class="menu-title text-truncate" data-i18n="User">User</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="app-users-list.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="List">List</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="app-users-view.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="View">View</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="app-users-edit.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Edit">Edit</span></a>
          </li>
        </ul>
      </li>

                    -->

      <!--
      <li class=" navigation-header text-truncate"><span data-i18n="UI Elements">UI Elements</span>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="retweet"></i><span class="menu-title text-truncate" data-i18n="Content">Content</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="content-grid.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Grid">Grid</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="content-typography.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Typography">Typography</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="content-text-utilities.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Text Utilities">Text Utilities</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="content-syntax-highlighter.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Syntax Highlighter">Syntax Highlighter</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="content-helper-classes.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Helper Classes">Helper Classes</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="colors.html"><i class="#" data-icon="drop"></i><span class="menu-title text-truncate" data-i18n="Colors">Colors</span></a>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="bulb"></i><span class="menu-title text-truncate" data-i18n="Icons">Icons</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="icons-livicons.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="LivIcons">LivIcons</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="icons-boxicons.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="BoxIcons">BoxIcons</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="square"></i><span class="menu-title text-truncate" data-i18n="Card">Card</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="card-basic.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Basic">Basic</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="card-actions.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Card Actions">Card Actions</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="widgets.html"><i class="#" data-icon="thumbnails-small"></i><span class="menu-title text-truncate" data-i18n="Widgets">Widgets</span><span class="badge badge-light-primary badge-pill badge-round float-right ml-auto">New</span></a>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="box-add"></i><span class="menu-title text-truncate" data-i18n="Components">Components</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="component-alerts.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Alerts">Alerts</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-buttons-basic.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Buttons">Buttons</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-breadcrumbs.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Breadcrumbs">Breadcrumbs</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-carousel.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Carousel">Carousel</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-collapse.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Collapse">Collapse</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-dropdowns.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Dropdowns">Dropdowns</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-list-group.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="List Group">List Group</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-modals.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Modals">Modals</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-pagination.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Pagination">Pagination</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-navbar.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Navbar">Navbar</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-tabs-component.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Tabs Component">Tabs Component</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-pills-component.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Pills Component">Pills Component</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-tooltips.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Tooltips">Tooltips</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-popovers.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Popovers">Popovers</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-badges.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Badges">Badges</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-pill-badges.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Pill Badges">Pill Badges</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-progress.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Progress">Progress</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-media-objects.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Media Objects">Media Objects</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-spinner.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Spinner">Spinner</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="component-bs-toast.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Toasts">Toasts</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="briefcase"></i><span class="menu-title text-truncate" data-i18n="Extra Components">Extra Components</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="ex-component-avatar.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Avatar">Avatar</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="ex-component-chips.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Chips">Chips</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="ex-component-divider.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Divider">Divider</span></a>
          </li>
        </ul>
      </li>

                    -->
        <!--
      <li class=" navigation-header text-truncate"><span data-i18n="Forms &amp; Tables">Forms &amp; Tables</span>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="check"></i><span class="menu-title text-truncate" data-i18n="Form Elements">Form Elements</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="form-inputs.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Input">Input</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-input-groups.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Input Groups">Input Groups</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-number-input.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Number Input">Number Input</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-select.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Select">Select</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-radio.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Radio">Radio</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-checkbox.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Checkbox">Checkbox</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-switch.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Switch">Switch</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-textarea.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Textarea">Textarea</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-quill-editor.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Quill Editor">Quill Editor</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-file-uploader.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="File Uploader">File Uploader</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="form-date-time-picker.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Date &amp; Time Picker">Date &amp; Time Picker</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="form-layout.html"><i class="#" data-icon="settings"></i><span class="menu-title text-truncate" data-i18n="Form Layout">Form Layout</span></a>
      </li>
      <li class=" nav-item"><a href="form-wizard.html"><i class="#" data-icon="priority-low"></i><span class="menu-title text-truncate" data-i18n="Form Wizard">Form Wizard</span></a>
      </li>
      <li class=" nav-item"><a href="form-validation.html"><i class="#" data-icon="check-alt"></i><span class="menu-title text-truncate" data-i18n="Form Validation">Form Validation</span></a>
      </li>
      <li class=" nav-item"><a href="form-repeater.html"><i class="#" data-icon="priority-low"></i><span class="menu-title text-truncate" data-i18n="Form Repeater">Form Repeater</span></a>
      </li>
      <li class=" nav-item"><a href="table.html"><i class="#" data-icon="thumbnails-big"></i><span class="menu-title text-truncate" data-i18n="Table">Table</span></a>
      </li>
      <li class=" nav-item"><a href="table-extended.html"><i class="#" data-icon="thumbnails-small"></i><span class="menu-title text-truncate" data-i18n="Table extended">Table extended</span></a>
      </li>
      <li class=" nav-item"><a href="table-datatable.html"><i class="#" data-icon="morph-map"></i><span class="menu-title text-truncate" data-i18n="Datatable">Datatable</span></a>
      </li>
                    -->

        <!--
      <li class=" navigation-header text-truncate"><span data-i18n="Pages">Pages</span>
      </li>
      <li class=" nav-item"><a href="page-user-profile.html"><i class="#" data-icon="user"></i><span class="menu-title text-truncate" data-i18n="User Profile">User Profile</span></a>
      </li>


      <li class=" nav-item"><a href="page-faq.html"><i class="#" data-icon="question-alt"></i><span class="menu-title text-truncate" data-i18n="FAQ">FAQ</span></a>
      </li>
      <li class=" nav-item"><a href="page-knowledge-base.html"><i class="#" data-icon="info-alt"></i><span class="menu-title text-truncate" data-i18n="Knowledge Base">Knowledge Base</span></a>
      </li>
      <li class=" nav-item"><a href="page-search.html"><i class="#" data-icon="search"></i><span class="menu-title text-truncate" data-i18n="Search">Search</span></a>
      </li>
      <li class=" nav-item"><a href="page-account-settings.html"><i class="#" data-icon="wrench"></i><span class="menu-title text-truncate" data-i18n="Account Settings">Account Settings</span></a>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="unlink"></i><span class="menu-title text-truncate" data-i18n="Starter kit">Starter kit</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/starter-kit/ltr/vertical-menu-template/sk-layout-1-column.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="1 column">1 column</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/starter-kit/ltr/vertical-menu-template/sk-layout-2-columns.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="2 columns">2 columns</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/starter-kit/ltr/vertical-menu-template/sk-layout-fixed-navbar.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Fixed navbar">Fixed navbar</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/starter-kit/ltr/vertical-menu-template/sk-layout-fixed.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Fixed layout">Fixed layout</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/starter-kit/ltr/vertical-menu-template/sk-layout-static.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Static layout">Static layout</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="unlock"></i><span class="menu-title text-truncate" data-i18n="Authentication">Authentication</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="auth-login.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Login">Login</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="auth-register.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Register">Register</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="auth-forgot-password.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Forgot Password">Forgot Password</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="auth-reset-password.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Reset Password">Reset Password</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="auth-lock-screen.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Lock Screen">Lock Screen</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="share"></i><span class="menu-title text-truncate" data-i18n="Miscellaneous">Miscellaneous</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="page-coming-soon.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Coming Soon">Coming Soon</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="#"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Error">Error</span></a>
            <ul class="menu-content">
              <li><a class="d-flex align-items-center" href="error-404.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="404">404</span></a>
              </li>
              <li><a class="d-flex align-items-center" href="error-500.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="500">500</span></a>
              </li>
            </ul>
          </li>
          <li><a class="d-flex align-items-center" href="page-not-authorized.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Not Authorized">Not Authorized</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="page-maintenance.html" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Maintenance">Maintenance</span></a>
          </li>
        </ul>
      </li>
      <li class=" navigation-header text-truncate"><span data-i18n="Charts &amp; Maps">Charts &amp; Maps</span>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="pie-chart"></i><span class="menu-title text-truncate" data-i18n="Charts">Charts</span><span class="badge badge-pill badge-round badge-light-info float-right mr-50 ml-auto">3</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="chart-apex.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Apex">Apex</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="chart-chartjs.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Chartjs">Chartjs</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="chart-chartist.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Chartist">Chartist</span></a>
          </li>
        </ul>
      </li>
      <li class=" nav-item"><a href="maps-leaflet.html"><i class="#" data-icon="map"></i><span class="menu-title text-truncate" data-i18n="Leaflet Maps">Leaflet Maps</span></a>
      </li>
      <li class=" navigation-header text-truncate"><span data-i18n="Extensions">Extensions</span>
      </li>
      <li class=" nav-item"><a href="ext-component-sweet-alerts.html"><i class="#" data-icon="warning-alt"></i><span class="menu-title text-truncate" data-i18n="Sweet Alert">Sweet Alert</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-toastr.html"><i class="#" data-icon="morph-map"></i><span class="menu-title text-truncate" data-i18n="Toastr">Toastr</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-noui-slider.html"><i class="#" data-icon="settings"></i><span class="menu-title text-truncate" data-i18n="NoUi Slider">NoUi Slider</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-drag-drop.html"><i class="#" data-icon="priority-high"></i><span class="menu-title text-truncate" data-i18n="Drag &amp; Drop">Drag &amp; Drop</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-tour.html"><i class="#" data-icon="paper-plane"></i><span class="menu-title text-truncate" data-i18n="Tour">Tour</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-swiper.html"><i class="#" data-icon="morph-orientation-tablet"></i><span class="menu-title text-truncate" data-i18n="Swiper">Swiper</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-treeview.html"><i class="#" data-icon="morph-sort-alt"></i><span class="menu-title text-truncate" data-i18n="Treeview">Treeview</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-block-ui.html"><i class="#" data-icon="expand"></i><span class="menu-title text-truncate" data-i18n="Block-UI">Block-UI</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-media-player.html"><i class="#" data-icon="music"></i><span class="menu-title text-truncate" data-i18n="Media Player">Media Player</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-miscellaneous.html"><i class="#" data-icon="loader-10"></i><span class="menu-title text-truncate" data-i18n="Miscellaneous">Miscellaneous</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-i18n.html"><i class="#" data-icon="globe"></i><span class="menu-title text-truncate" data-i18n="i18n">i18n</span></a>
      </li>
      <li class=" nav-item"><a href="ext-component-ratings.html"><i class="#" data-icon="star"></i><span class="menu-title text-truncate" data-i18n="Ratings">Ratings</span></a>
      </li>
      <li class=" navigation-header text-truncate"><span data-i18n="Others">Others</span>
      </li>
      <li class=" nav-item"><a href="#"><i class="#" data-icon="morph-menu-arrow-bottom"></i><span class="menu-title text-truncate" data-i18n="Menu Levels">Menu Levels</span></a>
        <ul class="menu-content">
          <li><a class="d-flex align-items-center" href="#"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Second Level">Second Level</span></a>
          </li>
          <li><a class="d-flex align-items-center" href="#"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Second Level">Second Level</span></a>
            <ul class="menu-content">
              <li><a class="d-flex align-items-center" href="#"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Third Level">Third Level</span></a>
              </li>
              <li><a class="d-flex align-items-center" href="#"><i class="bx bx-right-arrow-alt"></i><span class="menu-item text-truncate" data-i18n="Third Level">Third Level</span></a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <li class="disabled nav-item"><a href="#"><i class="#" data-icon="morph-preview"></i><span class="menu-title text-truncate" data-i18n="Disabled Menu">Disabled Menu</span></a>
      </li>
      <li class=" navigation-header text-truncate"><span data-i18n="Support">Support</span>
      </li>
      <li class=" nav-item"><a href="https://pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/documentation" target="_blank"><i class="#" data-icon="morph-folder"></i><span class="menu-title text-truncate" data-i18n="Documentation">Documentation</span></a>
      </li>
      <li class=" nav-item"><a href="https://pixinvent.ticksy.com/" target="_blank"><i class="#" data-icon="help"></i><span class="menu-title text-truncate" data-i18n="Raise Support">Raise Support</span></a>
      </li>

      -->

    </ul>
  </div>
</div>
<!-- END: Main Menu-->









@yield('content');

@livewireScripts



 <!-- BEGIN: Customizer-->
 <!--
 <div class="customizer d-none d-md-block"><a class="customizer-toggle" href="javascript:void(0);"><i class="bx bx-cog bx bx-spin white"></i></a><div class="customizer-content p-2">
    <h4 class="text-uppercase mb-0">Theme Customizer</h4>
    <small>Customize & Preview in Real Time</small>
    <a href="javascript:void(0)" class="customizer-close">
          <i class="bx bx-x"></i>
    </a>
    <hr>

    <h5 class="mt-1">Theme Layout</h5>
    <div class="theme-layouts">
      <div class="d-flex justify-content-start">
        <div class="mx-50">
          <fieldset>
            <div class="radio">
              <input type="radio" name="layoutOptions" value="false" id="radio-light" class="layout-name" data-layout=""
                checked>
              <label for="radio-light">Light</label>
            </div>
          </fieldset>
        </div>
        <div class="mx-50">
          <fieldset>
            <div class="radio">
              <input type="radio" name="layoutOptions" value="false" id="radio-dark" class="layout-name"
                data-layout="dark-layout">
              <label for="radio-dark">Dark</label>
            </div>
          </fieldset>
        </div>
        <div class="mx-50">
          <fieldset>
            <div class="radio">
              <input type="radio" name="layoutOptions" value="false" id="radio-semi-dark" class="layout-name"
                data-layout="semi-dark-layout">
              <label for="radio-semi-dark">Semi Dark</label>
            </div>
          </fieldset>
        </div>
      </div>
    </div>

    <hr>


    <div id="customizer-theme-colors">
      <h5>Menu Colors</h5>
      <ul class="list-inline unstyled-list">
        <li class="color-box bg-primary selected" data-color="theme-primary"></li>
        <li class="color-box bg-success" data-color="theme-success"></li>
        <li class="color-box bg-danger" data-color="theme-danger"></li>
        <li class="color-box bg-info" data-color="theme-info"></li>
        <li class="color-box bg-warning" data-color="theme-warning"></li>
        <li class="color-box bg-dark" data-color="theme-dark"></li>
      </ul>
      <hr>
    </div>



    <div id="menu-icon-animation">
      <div class="d-flex justify-content-between align-items-center">
        <div class="icon-animation-title">
          <h5 class="pt-25">Icon Animation</h5>
        </div>
        <div class="icon-animation-switch">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" checked id="icon-animation-switch">
            <label class="custom-control-label" for="icon-animation-switch"></label>
          </div>
        </div>
      </div>
      <hr>
    </div>



    <div class="collapse-sidebar d-flex justify-content-between align-items-center">
      <div class="collapse-option-title">
        <h5 class="pt-25">Collapse Menu</h5>
      </div>
      <div class="collapse-option-switch">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="collapse-sidebar-switch">
          <label class="custom-control-label" for="collapse-sidebar-switch"></label>
        </div>
      </div>
    </div>

    <hr>


    <div id="customizer-navbar-colors">
      <h5>Navbar Colors</h5>
      <ul class="list-inline unstyled-list">
        <li class="color-box bg-white border selected" data-navbar-default=""></li>
        <li class="color-box bg-primary" data-navbar-color="bg-primary"></li>
        <li class="color-box bg-success" data-navbar-color="bg-success"></li>
        <li class="color-box bg-danger" data-navbar-color="bg-danger"></li>
        <li class="color-box bg-info" data-navbar-color="bg-info"></li>
        <li class="color-box bg-warning" data-navbar-color="bg-warning"></li>
        <li class="color-box bg-dark" data-navbar-color="bg-dark"></li>
      </ul>
      <small><strong>Note :</strong> This option with work only on sticky navbar when you scroll page.</small>
      <hr>
    </div>



    <h5>Navbar Type</h5>
    <div class="navbar-type d-flex justify-content-start">
      <div class="hidden-ele mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="navbarType" value="false" id="navbar-hidden">
            <label for="navbar-hidden">Hidden</label>
          </div>
        </fieldset>
      </div>
      <div class="mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="navbarType" value="false" id="navbar-static">
            <label for="navbar-static">Static</label>
          </div>
        </fieldset>
      </div>
      <div class="mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="navbarType" value="false" id="navbar-sticky" checked>
            <label for="navbar-sticky">Fixed</label>
          </div>
        </fieldset>
      </div>
    </div>
    <hr>

    <h5>Footer Type</h5>
    <div class="footer-type d-flex justify-content-start">
      <div class="mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="footerType" value="false" id="footer-hidden">
            <label for="footer-hidden">Hidden</label>
          </div>
        </fieldset>
      </div>
      <div class="mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="footerType" value="false" id="footer-static" checked>
            <label for="footer-static">Static</label>
          </div>
        </fieldset>
      </div>
      <div class="mx-50">
        <fieldset>
          <div class="radio">
            <input type="radio" name="footerType" value="false" id="footer-sticky">
            <label for="footer-sticky" class="">Sticky</label>
          </div>
        </fieldset>
      </div>
    </div>

    <hr>


    <div class="card-shadow d-flex justify-content-between align-items-center py-25">
      <div class="hide-scroll-title">
        <h5 class="pt-25">Card Shadow</h5>
      </div>
      <div class="card-shadow-switch">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" checked id="card-shadow-switch">
          <label class="custom-control-label" for="card-shadow-switch"></label>
        </div>
      </div>
    </div>

    <hr>


    <div class="hide-scroll-to-top d-flex justify-content-between align-items-center py-25">
      <div class="hide-scroll-title">
        <h5 class="pt-25">Hide Scroll To Top</h5>
      </div>
      <div class="hide-scroll-top-switch">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="hide-scroll-top-switch">
          <label class="custom-control-label" for="hide-scroll-top-switch"></label>
        </div>
      </div>
    </div>



  </div>

      </div>



                    -->


  <div class="widget-chat widget-chat-demo d-none">
    <div class="card mb-0">
      <div class="card-header border-bottom p-0">
        <div class="media m-75">
          <a href="JavaScript:void(0);">
            <div class="avatar mr-75">
              <img src="../../../app-assets/images/portrait/small/avatar-s-2.jpg" alt="avtar images" width="32"
                height="32">
              <span class="avatar-status-online"></span>
            </div>
          </a>
          <div class="media-body">
            <h6 class="media-heading mb-0 pt-25"><a href="javaScript:void(0);">Kiara Cruiser</a></h6>
            <span class="text-muted font-small-3">Active</span>
          </div>
        </div>
        <div class="heading-elements">
          <i class="bx bx-x widget-chat-close float-right my-auto cursor-pointer"></i>
        </div>
      </div>
      <div class="card-body widget-chat-container widget-chat-demo-scroll">
        <div class="chat-content">
          <div class="badge badge-pill badge-light-secondary my-1">today</div>
          <div class="chat">
            <div class="chat-body">
              <div class="chat-message">
                <p>How can we help? 😄</p>
                <span class="chat-time">7:45 AM</span>
              </div>
            </div>
          </div>
          <div class="chat chat-left">
            <div class="chat-body">
              <div class="chat-message">
                <p>Hey John, I am looking for the best admin template.</p>
                <p>Could you please help me to find it out? 🤔</p>
                <span class="chat-time">7:50 AM</span>
              </div>
            </div>
          </div>
          <div class="chat">
            <div class="chat-body">
              <div class="chat-message">
                <p>Stack admin is the responsive bootstrap 4 admin template.</p>
                <span class="chat-time">8:01 AM</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer border-top p-1">
        <form class="d-flex" onsubmit="widgetChatMessageDemo();" action="javascript:void(0);">
          <input type="text" class="form-control chat-message-demo mr-75" placeholder="Type here...">
          <button type="submit" class="btn btn-primary glow px-1"><i class="bx bx-paper-plane"></i></button>
        </form>
      </div>
    </div>
  </div>
  <!-- widget chat demo ends -->

      </div>
      <div class="sidenav-overlay"></div>
      <div class="drag-target"></div>

      <!-- BEGIN: Footer-->
   {{-- <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-left d-inline-block">{{ date('Y') }} &copy; E-Cont</span><span class="float-right d-sm-inline-block d-none">Creado por<a class="text-uppercase" href="https://1.envato.market/pixinvent_portfolio" target="_blank">JMB CONTADORES</a></span>
          <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
        </p>
      </footer>--}}
      <!-- END: Footer-->


</body>
<livewire:notification-content>
<footer style="margin-top: 20px;">
  {{--  <p class="row justify-content-center" style="font-size: 20px; font-weight: bold;">E-CONT {{ date('Y') }} |
        JMB Contadores</p>--}}
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




<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '487ea2552280af7093a6dd6c9277382bc43b4b39';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
