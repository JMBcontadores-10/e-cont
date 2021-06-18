@extends('layouts.app')

<head>
    <title>Contarapp Not Found</title>
</head>

@section('content')
    <div class="container mt-5 pt-5">
        <div class="alert alert-info text-center">
            <div class="row justify-content-center mb-3">
                <img src="{{ asset('img/logo-contarapp-01.png') }}" width="40%">
            </div>
            <h2 class="display-3">404</h2>
            <p class="display-5">Oops! Page not found</p>
        </div>
    </div>
@endsection
