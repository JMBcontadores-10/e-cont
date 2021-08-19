@extends('layouts.app')

<head>
    <title>Contarapp Not Found</title>
</head>

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <img src="{{ asset('img/logo-contarapp-01.png') }}" class="logo0">
        </div>
        <div class="row d-flex justify-content-center">
            <img src="{{ asset('img/construccion2.png') }}" style="width: 300px">
            {{-- <img src="{{ asset('img/PAGINA.png') }}" style="width: 500px"> --}}
        </div>
        <div class="row d-flex justify-content-center msg404-2">
            <div>Módulo en construcción.</div>
        </div>
        <div class="row d-flex justify-content-center mt-2">
            <a href="{{ url()->previous() }}" class="btn btn-primary">Regresar</a>
        </div>
    </div>
@endsection
