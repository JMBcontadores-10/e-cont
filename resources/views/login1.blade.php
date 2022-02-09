
<!DOCTYPE html>

<html >
  <!-- BEGIN: Head-->


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="E-cont">
    <title>E-cont asistente contable</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.html">
    <link rel="shortcut icon" type="image/x-icon" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

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



  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu-modern 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><!-- lock screen starts -->
<section class="row flexbox-container">
    <div class="col-xl-7 col-10">
        <div class="card bg-authentication mb-0">
            <div class="row m-0">
                <!-- left lock screen section -->
                <div class="col-md-6 col-12 px-0">
                    <div class="card disable-rounded-right mb-0 p-2">
                        <div class="card-header pb-1">
                            <div class="card-title">
                                <h4 class="text-center mb-2">INICIO DE SESIÓN</h4>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('home') }}">
                                @csrf
                                <div class="form-group mb-50">
                                    <label class="text-bold-600" for="exampleInputEmail1">Usuario / RFC</label>
                                    <input placeholder="ej. ANSD2938HRT981" id="rfcC" type="text" class="form-control"
                                            name="rfcC" value="{{ old('rfcC') }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="text-bold-600" for="exampleInputPassword1">Password</label>
                                    <input placeholder="**********" id="passC" type="password" class="form-control"
                                    name="passC" required>
                                </div>
                                <div class="text-center mb-1"><a href="auth-login.html" class="card-link"><small>
                                            ¿Olvidaste tu contraseña?</small></a></div>
                                <button type="submit" class="btn btn-primary glow position-relative w-100">Entrar<i
                                        id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                            </form>


                            
                        </div>

                        


                    </div>
                </div>
                <!-- right image section -->
                <div class="col-md-6 d-md-block d-none text-center align-self-center">
                    <img class="img-fluid" src="img/logo1.png" alt="branding logo"
                        width="350">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- lock screen ends -->

        </div>
      </div>
    </div>
    <!-- END: Content-->


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
    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

  </body>
  <!-- END: Body-->

<!-- Mirrored from www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/html/ltr/vertical-menu-template/auth-lock-screen.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 17 Jan 2022 18:16:58 GMT -->
</html>



{{---
    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center" >
                            <h2 style="color:#0055ff;"> INICIO DE SESIÓN</h2>
                            <br>
                        </div>
                        <div class="row justify-content-center">

                            <h5 style="color:#0055ff;">_____________________________________________________</h5>
                        </div>
                        <br>


                        <form method="POST" action="{{ route('home') }}">

                            @csrf
                            <div id="login1">
                                <div class="row justify-content-center">
                                    <h4>Ingrese los datos del contador:</h4>
                                </div>
                                <div class="form-group row">

                                    <label for="rfcC"
                                        class="col-md-4 col-form-label text-md-right">{{ __('RFC: ') }}</label>

                                    <div class="col-md-6">
                                        <input placeholder="ej. ANSD2938HRT981" id="rfcC" type="text" class="form-control"
                                            name="rfcC" value="{{ old('rfcC') }}" required>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="passC"
                                        class="col-md-4 col-form-label text-md-right">{{ __('Contraseña: ') }}</label>

                                    <div class="col-md-6">
                                        <input placeholder="**********" id="passC" type="password" class="form-control"
                                            name="passC" required>
                                    </div>


                                </div>


                                <div class="form-group row">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Enviar') }}
                                    </button>
                                </div>
                        </form>

                    </div>

                    <div id="entrar"  >
                        <div class="form-group row">

                            <div class="col-12" >

                                <a  onclick="showLogin1()" class=" btn btn-primary" style="margin-left: 250px; width: 200px; display: flex; justify-content: center;">
                                 {{ __('Contador') }}
                                </a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <form action="{{ route('home') }}" method="POST" >
                                    @csrf
                                    <input type="hidden" value="3" name="tipo">
                                    <button onclick="variableSesion()" type="submit" class="btn btn-primary " style=" width: 200px; display: flex; justify-content: center;">
                                           {{ __('Empresa') }}
                                    </button>
                                </form>
                            </div>


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

--}}

