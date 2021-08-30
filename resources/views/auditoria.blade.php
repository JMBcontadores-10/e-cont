@extends('layouts.app')

@section('content')

<div class="container">
    <div class="float-md-left">
        <a class="b3" href="{{url()->previous()}}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Auditoría</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div class="justify-content-start">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
        <hr style="border-color:black; width:100%;">
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form  method="POST" action="{{route('auditoria1')}}">
                        @csrf
                    <h2>Selecciona tipo de factura</h2>
                    <div class="form-check">
                        <input class="form-check-input" type="radio"  id="exampleRadios1" name="tipoer" value="Emitidas" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Emitidas
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio"  id="exampleRadios2" name="tipoer" value="Recibidas">
                        <label class="form-check-label" for="exampleRadios2">
                          Recibidas
                        </label>
                      </div>
                      <br>
                      <h2>Selecciona periodo</h2>
                      <input type="date" name="fecha1er" min="2020-01-01" required> a
                        &nbsp;<input type="date" name="fecha2er" min="2020-01-01" required>


                    <input class="btn-linkj" type="submit" value="Enviar"><br>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
