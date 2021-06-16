@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 style="color:#0055ff;"> INICIO DE SESIÓN</h2>
                        </div>
                        <div class="row justify-content-center">
                            <h5 style="color:#0055ff;">_____________________________________________________</h5>
                        </div>
                        <br>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="RFC" class="col-md-4 col-form-label text-md-right">{{ __('RFC: ') }}</label>

                                <div class="col-md-6">
                                    <input placeholder="ej. ANSD2938HRT981" id="RFC" type="text"
                                        class="form-control @error('RFC') is-invalid @enderror" name="RFC"
                                        value="{{ old('RFC') }}" required autocomplete="RFC" autofocus>

                                    @error('RFC')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Contraseña: ') }}</label>

                                <div class="col-md-6">
                                    <input placeholder="**********" id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Enviar') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
