<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Control Volumétrico</title>
    <link rel="shortcut icon" href="img/logo-contarapp-03.png" type="image/png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="{{URL::asset('js/fullcalendar.js')}}"></script>
    <script src="{{URL::asset('js/es.js')}}"></script>
    <script src="{{URL::asset('js/main.js')}}"></script>
    <!-- Image and text -->
    <nav style="background-color: #ffffff; padding: 08px;">
      <form class="navbar-form navbar-left" action="/../modulos.php" method="post">
        <a class="navbar-brand" style="padding: 10px 70px;">
            <button class="b4"><img src="img/logo-contarapp-01.png" width="30%"></button>
        </a>
        </form>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav navbar-right" style="padding: 10px 70px;">
        <li class="dropdown">
          <form class="navbar-form navbar-left" action="/../index.php">
            <button class="button1" type="submit">Cerrar Sesión</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
</head>
<style>

    .inicio{
      border-radius: 5px;
      background-color: #f4f5f9;
      padding: 0px 10px;
      width: 1000px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 50px;
      //border-radius: 20px;
    }

    .button1{
      width: 130px;
      height: 30px;
      background-color: #ffffff;
      color: #000000;
      border: #0055FF 1px solid;
      border-radius: 4px;
      box-sizing: border-box;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .btn-linkj{
      background-color: #0055ff;
      border: none;
      color: white;
      padding: 5px 8px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 12px;
      margin: 2px 1px;
      cursor: pointer;
    }

    .b3{
      background-color: #f4f5f9;
      color: #0055FF;
      font-size: 14px;
      border: none;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .b4{
      background-color: #ffffff;
      border: none;
      text-align: left;
      cursor: pointer;
    }

    .label1{
      color: #0055FF;
      font-size: 18px;
      font-family: 'Work Sans', sans-serif;
    }

    .label2{
      color: #0055FF;
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
    }

    .label3{
      color: #000000;
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
      font-weight: normal;
    }

    label{
      font-size: 16px;
      font-family: 'Work Sans', sans-serif;
    }

    h1{
      font-size: 26px;
      font-family: 'Work Sans', sans-serif;
    }

    h2{
      font-size: 24px;
      font-family: 'Work Sans', sans-serif;
    }

    h3{
      font-size: 21px;
      font-family: 'Work Sans', sans-serif;
    }

    h4{
      font-size: 18px;
      font-family: 'Work Sans', sans-serif;
    }

    h5{
      font-size: 16px;
      font-family: 'Work Sans', sans-serif;
    }

    .p1{
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .p2{
      font-size: 12px;
      font-family: 'Work Sans', sans-serif;
      font-weight: normal;
    }

</style>
<body style="background-color: #f4f5f9;">
    <div class="inicio" align="center">
        <form class="navbar-form navbar-left" action="{{ url('/') }}">

          <button class="b3"><< Regresar</button>
        </form>

        <ul class="nav navbar-nav navbar-right">
        <li class="dropdown" style="padding: 10px 20px;">
          <label class="label2">Control Volumétrico</a></label>
        </li>
      </ul><br>
      <hr style="border-color:black; width:100%;">
      <div align="left">
          <label class="label1"> Sesión de: </label>
          <h1>{{Auth::user()->nombre}}</h1>
          <label>{{Auth::user()->RFC}}</label>
          <!--datos de la descarga-->
          <hr style="border-color:black; width:100%;">
      </div>

      <div class="container">
          <div class="row">
              <div class="col-3">
                  <form action="" method="post"></form>
                  <label for="pwd"><b>Elige la fecha:</b></label>
                  <input type=date name=id min=2020-01-01 required> &nbsp;
                  <br><br> <label>Acci&oacute;n a realizar: &nbsp;</label>

                  <select name="accion">
                      <option>Ingresar Datos</option>
                      <option>Editar Datos</option>
                      <option>Editar Cambio de Precio</option>
                  </select> &nbsp;

                  <br><br><input type="submit" value="Enviar" style="width: 90px; height: 35px;color:white; BORDER : #0055FF 1px solid; FONT-SIZE:13pt; background-color: #0055ff;">

                </form>
                <br>
                <br>
                <form action="" method="POST">
                    <h4 align="center"><b>Consulta hist&oacute;rica</b></h4>
                    <label for="pwd"><b>Elige la fecha:</b></label>
                    <input type="date" name="id1" min=2020-01-01 required>
                </form>
                <br>a<br>
                &nbsp;<input type="date" name="id2" min=2020-01-01 required>
                <br><br><input type="submit" value="Enviar" style="color:white; BORDER : #0055FF 1px solid; FONT-SIZE:13pt; background-color: #0055ff;">
              </div>
          </div>

      </div>
      <div class="col-8">

<div class="container">

<div id="calendar"></div>

  </div>


</div>
</div>


    </body>
    <footer>
        <br>
        <br>
        <br>
        <h5 align="center">CONTARAPP 2021 | JMB Contadores</h5>
    </footer>
    </html>
