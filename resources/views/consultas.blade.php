@extends('layouts.app')

@section('content')

    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    @endphp

    <div class="container">
        <div>
            <form class="navbar-form navbar-left" action="{{ url('/') }}">
                <button class="b3">
                    << Regresar</button>
            </form>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown" style="padding: 10px 20px;">
                    <label class="label1"> Consultas</label>
                </li>
            </ul>
            <hr style="border-color:black; width:100%">
            <div align="left">
                <label class="label1"> Sesión de:</label>
                <h1>{{ Auth::user()->nombre }}</h1>
                <label>{{ Auth::user()->RFC }}</label>
            </div>
            <hr style="border-color:black; width:100%">
            <div class="row">
                <div class="col-md-3">
                    <form action="{{ url('ingreso') }}">
                        <input class="btn-linkj" type="submit" value="Ingreso Consultas"><br>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ url('egreso') }}">
                        <input class="btn-linkj" type="submit" value="Egreso Consultas"><br>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ url('nomina') }}">
                        <input class="btn-linkj" type="submit" value="Nómina Consultas"><br>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ url('pago') }}">
                        <input class="btn-linkj" type="submit" value="Pago Consultas"><br>
                    </form>
                </div>
            </div>
            <br>
            <br>

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

@endsection
