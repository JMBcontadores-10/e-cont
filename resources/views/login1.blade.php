@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 style="color:#0055ff;"> INICIO DE SESIÓN</h2>
                            <br>
                        </div>
                        <div class="row justify-content-center">

                            <h5 style="color:#0055ff;">_____________________________________________________</h5>
                        </div>
                        <br>

                        <form method="POST" action="{{ route('home') }}">
                            @csrf
                            <div id="login1">
                                <input type="hidden" value="2" name="tipo">
                                <div class="form-group row">
                                    <label for="rfcC"
                                        class="col-md-4 col-form-label text-md-right">{{ __('RFC: ') }}</label>

                                    <div class="col-md-6">
                                        <input placeholder="ej. ANSD2938HRT981" id="rfcC" type="text" class="form-control"
                                            name="rfcC" value="{{ old('rfcC') }}" required>


                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="passC"
                                        class="col-md-4 col-form-label text-md-right">{{ __('Contraseña: ') }}</label>

                                    <div class="col-md-6">
                                        <input placeholder="**********" id="passC" type="password" class="form-control"
                                            name="passC" required>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Enviar') }}
                                    </button>
                                </div>
                        </form>

                    </div>

                        <div id="entrar">
                            <div class="form-group row">

                            <div class="col-12">
                                <a onclick="showLogin1()" class="btn btn-primary" style="margin-left: 250px;">
                                {{ __('Entrar como contador') }}
                            </a>
                        </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                            <form action="{{ route('home') }}" method="POST" style="margin-left: 235px;">
                                @csrf
                                <input type="hidden" value="3" name="tipo" >
                                <button onclick="variableSesion()" type="submit" class="btn btn-primary">
                                    {{ __('Entrar como secretaria') }}
                                </button>
                            </form>
                        </div>


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
