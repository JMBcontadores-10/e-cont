@extends('layouts.app')

@section('content')

    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    @endphp

    <div class="container">
      <div class="float-md-left">
        <a class="b3" href="{{ url()->previous() }}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Control Volumétrico</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div class="justify-content-start">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
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

          <div class="col-8">

           <br>
           <br>

            <div id="calender"></div>

            {{-- aqui falta completar --}}
            <div class="modal fade" id="saveEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>

                        <div class="modal-body">

                            <button id="removeEvent" class="btn btn-sm btn-danger float-right"><i class="fa fa-trash"></i></button>
                            <br>

                            <label class="d-block"><input type="checkbox" id="allDay">A partir de:</label>
                            <input type="time" id="time" class="form-control mb-3 d-none">

                            <input type="text" id="title" class="form-control">
                            <textarea id="content" class="form-control"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                            <button id="addEvent" class="btn btn-success"><i class="fa fa-plus"></i>Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
      </div>


@endsection
