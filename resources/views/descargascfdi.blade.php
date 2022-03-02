@extends('layouts.app')

<head>
    <title>Descargas CFDI SAT E-cont</title>
</head>

@section('content')

@php
$rfc = Auth::user()->RFC;
$nombre = Auth::user()->nombre;
$dtz = new DateTimeZone('America/Mexico_City');
$dt = new DateTime('now', $dtz);
$diaDescarga = $dt->format('Y-n-d');
@endphp

<div class="container">
    <div class="float-md-left">
        <a class="b3" href="{{ url()->previous() }}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Descargas</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div align="left">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
        <form method="POST" class="login-form">
            @csrf
            <input type="hidden" name="accion" value="login_fiel" />
            <div class="row">
                <div class="col-sm-3 form-group">
                    <!--<button type="submit" class="btn btn-success">Iniciar sesión</button>-->
                </div>
            </div>
        </form>
        <hr style="border-color:black; width:100%;">
    </div>







    
  

    @endsection









