@extends('layouts.app')

<head>
    <title>Contarapp Not Found</title>
</head>

{{-- <style>
    @keyframes spinning {
        from {
            transform: rotate(0deg)
        }

        to {
            transform: rotate(360deg)
        }
    }

    .spin {
        animation-name: spinning;
        animation-duration: 3s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }


    .logo {
        width: 80px;
        height: 80px;

    }

    .msg404 {
        color: #0055FF;
        font-size: 85px;
    }

    .msg2 {
        color: black;
        font-size: 23px;
        font-family: 'Work Sans', sans-serif
    }

    .logo0 {
        width: 380px;
    }

    .container404 {
        margin-top: 100px;
        /* background-image: url('img/icons8-computer-160.png');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 500px; */
    }

</style> --}}

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
