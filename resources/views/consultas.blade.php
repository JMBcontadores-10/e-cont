<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultas</title>
    <link rel="shortcut icon" href="img/logo-contarapp-03.png" type="image/png" />
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
  <nav class="navbar navbar-default" role="navigation" style="background-color: #ffffff;">
    <form class="navbar-form navbar-left" action="/../modulos.php" method="post">
      <a class="navbar-brand" style="padding: 10px 70px;">
        <button class="b4"><img src="img/logo-contarapp-01.png" width="25%"></button>
      </a>
    </form>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown" style="padding: 10px 70px;">
          <form class="navbar-form navbar-left" action="/../index.php">
            <button class="button1" type="submit">Cerrar Sesión</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
</head>
<style>
    .inicio {
      border-radius: 5px;
      background-color: #f4f5f9;
      padding: 0px 10px;
      width: 1200px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 50px;
      //border-radius: 20px;
    }

    .button1 {
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

    .button2 {
      width: 150px;
      height: 30px;
      background-color: #ffffff;
      color: #000000;
      border: #0055FF 1px solid;
      border-radius: 4px;
      box-sizing: border-box;
      font-family: 'Work Sans', sans-serif;
      font-weight: normal;
    }

    .btn-linkj {
      background-color: #0055ff;
      border: none;
      color: white;
      padding: 5px 8px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 2px 1px;
      cursor: pointer;
      height: 50px;
    }

    .label1 {
      color: #0055FF;
      font-size: 18px;
      font-family: 'Work Sans', sans-serif;
    }

    .label2 {
      color: #0055FF;
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
    }

    .label3 {
      color: #000000;
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
      font-weight: normal;
    }

    label {
      font-size: 16px;
      font-family: 'Work Sans', sans-serif;
    }

    h1 {
      font-size: 26px;
      font-family: 'Work Sans', sans-serif;
    }

    h2 {
      font-size: 24px;
      font-family: 'Work Sans', sans-serif;
    }

    h3 {
      font-size: 21px;
      font-family: 'Work Sans', sans-serif;
    }

    h4 {
      font-size: 18px;
      font-family: 'Work Sans', sans-serif;
    }

    h5 {
      font-size: 16px;
      font-family: 'Work Sans', sans-serif;
    }

    .p1 {
      font-size: 14px;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .p2 {
      font-size: 12px;
      font-family: 'Work Sans', sans-serif;
      font-weight: normal;
    }

    .p3 {
      font-size: 18px;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .hr1 {
      border: none;
      border-left: 1px solid hsla(200, 10%, 50%, 100);
      height: 30vh;
      width: 1px;
    }

    .b3 {
      background-color: #f4f5f9;
      color: #0055FF;
      font-size: 14px;
      border: none;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
    }

    .b4 {
      background-color: #ffffff;
      border: none;
      text-align: left;
      cursor: pointer;
    }
    </style>
<body style="background-color: #f4f5f9">
    <div class="inicio" align="center">
        <form class="navbar-form navbar-left" action="{{ url('/') }}" >
          <button class="b3"> << Regresar</button>
        </form>

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown" style="padding: 10px 20px;">
            <label class="label1"> Descargas</label>
          </li>
        </ul>
        <hr style="border-color:black; width:100%">
        <div align="left">
          <label class="label1"> Sesión de:</label>
          <h1>{{Auth::user()->nombre}}</h1>
          <label>{{Auth::user()->RFC}}</label>
        </div>
        <hr style="border-color:black; width:100%">

    <div class="row">
        <div class="col-md-4">
          <form action="{{url('historial')}}">
          <input class="btn-linkj" type="submit" value="Historial de consultas"><br>
          </form>
        </div>
        <div class="col-md-4">
          <form method="post" href="{{url('/views/graficas.blade.php')}}">
          <input class="btn-linkj" type="submit" value="Estadisticas Recibidas"><br>
          </form>
        </div>
        <div class="col-md-4">
          <input class="btn-linkj" type="submit" value="Estadisticas Emitidas"><br>
          </form>
        </div>
    </div>
    <div align="left">
        <form name="formulario_consultas" action="formconsultas">
          {{csrf_field()}}
          &nbsp;<label class="label1" for="consultas"> Consultas </label><br>
          <h4>
          <p>
          &nbsp;<input type="radio" required name="tipodes" value="Recibidas"> Consulta de Recibidas
          &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" required name="tipodes" value="Emitidas"> Consulta de Emitidas
          </p>
          <br>
          <p>Tipo:
          <select name="TipoFac">
            <option value="Factura">Ingreso/Egreso</option>
            <option value="nomina">Nómina</option>
            <option value="pago">Pago</option>
          </select>
        </p>
          <br><br>
            <label for=pwd> Eliga el Periodo: </label>
            <input type=date name=fecha1 min=2020-01-01 > a
            &nbsp;<input type=date name=fecha2 min=2020-01-01 >
          </h4>
        </div>
          <input class="btn-linkj" type="submit" value="Enviar"><br>
          <br>
          <br>
          <br>
          <h4 align="center" style="font-size: 12px; font-weight: bold;">CONTARAPP 2021 | JMB Contadores</h4>
        </form>
</div>
</body>
</html>
