@extends('layouts.app')

@section('content')


    <div class="container">
      <div class="float-md-left">
        <a class="b3" href="{{ url()->previous() }}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Consultas</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div class="justify-content-start">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
        <hr style="border-color:black; width:100%;">
    </div>


            <div class="row">
                <div class="col-md-4">
                    <form action="{{ url('historial') }}">
                        <input class="btn-linkj" type="submit" value="Historial de consultas"><br>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="post" href="{{ url('/views/graficas.blade.php') }}">
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
                    {{ csrf_field() }}
                    &nbsp;<label class="label1" for="consultas"> Consultas </label><br>
                    <h4>
                        <p>
                            &nbsp;<input type="radio" required name="tipodes" value="Recibidas"> Consulta de Recibidas
                            &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" required name="tipodes" value="Emitidas"> Consulta
                            de Emitidas
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
                        <input type=date name=fecha1 min=2020-01-01> a
                        &nbsp;<input type=date name=fecha2 min=2020-01-01>
                    </h4>
            </div>
            <input class="btn-linkj" type="submit" value="Enviar"><br>

            </form>
        </div>
    </div>

    <div align="left">
        <form action="{{url('consultas1.blade.php')}}">
        
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

        </form>
</div>


@endsection
