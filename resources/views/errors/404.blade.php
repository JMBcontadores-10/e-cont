@extends('layouts.app')

<head>
    <title>Contarapp Not Found</title>
</head>

@section('content')
    <div class="container">
        <div class="container404">
            <div class="row d-flex justify-content-center">
                <img src="{{ asset('img/logo-contarapp-01.png') }}" class="logo0">
            </div>
            <div class="row d-flex justify-content-center align-top">
                <div class="msg404">4</div>
                <img class="logo spin mt-4" src="{{ asset('img/logo-contarapp-03.png') }}">
                <div class="msg404">4</div>
            </div>
            <div class="row d-flex justify-content-center msg404-2">
                <div>Página no encontrada.</div>
            </div>
        </div>
    </div>
@endsection
