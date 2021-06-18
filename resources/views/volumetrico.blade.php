@extends('layouts.app')

@section('content')

    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    @endphp

    <div>
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
                  <label for="pwd"><b>Elige la fecha:</b></label><br>
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
                    <br>
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

@endsection
