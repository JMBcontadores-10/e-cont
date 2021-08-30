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
                <div class="col-md-12">
                    <form action="{{ url('historial') }}">
                        <input class="btn-linkj" type="submit" value="Historial de Descargas"><br>
                    </form>
                </div>
            </div>
            <br>
            <div>
                <form  method="POST" action="{{route('consultas1')}}">
                    @csrf

                    <br>
                        <p>
                            &nbsp;<input type="radio" required name="tipodes" value="Recibidas"> Consulta de Recibidas
                            &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" required name="tipodes" value="Emitidas"> Consulta
                            de Emitidas
                        </p>
                        <br>
                        <p>Tipo:
                            <input type="radio" required name="tipoFac" value="I"> Ingreso
                            <input type="radio" required name="tipoFac" value="E"> Egreso
                            <input type="radio" required name="tipoFac" value="P"> Pago
                            <input type="radio" required name="tipoFac" value="N"> Nómina
                        </p>
                        <br><br>
                        <label for=pwd> Elija el Periodo: </label>
                        <input type="date" name="fecha1" min="2020-01-01" required> a
                        &nbsp;<input type="date" name="fecha2" min="2020-01-01" required>
                        <input class="btn-linkj" type="submit" value="Enviar"><br>

            </form>
        </div>
        <br>
        <br>

    </div>




@endsection
