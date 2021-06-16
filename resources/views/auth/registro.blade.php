@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registro Clientes') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('registro-store') }}">
                        @csrf

                        {{-- RFC y password 1 --}}
                        <div class="form-group row">
                            <label for="RFC" class="col-md-4 col-form-label text-md-right">{{ __('RFC') }}</label>

                            <div class="col-md-6">
                                <input id="RFC" type="text" class="form-control @error('RFC') is-invalid @enderror"
                                    name="RFC" value="{{ old('RFC') }}"  autocomplete="RFC" autofocus>

                                @error('RFC')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text"
                                    class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- RFC y password 2 --}}
                        <div class="form-group row">
                            <label for="RFC2" class="col-md-4 col-form-label text-md-right">{{ __('RFC') }}</label>

                            <div class="col-md-6">
                                <input id="RFC2" type="text" class="form-control @error('RFC2') is-invalid @enderror"
                                    name="RFC2" value="{{ old('RFC') }}"  autocomplete="RFC2" autofocus>

                                @error('RFC2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password2"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password2" type="text"
                                    class="form-control @error('password2') is-invalid @enderror" name="password2">

                                @error('password2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- RFC y password 3 --}}
                        <div class="form-group row">
                            <label for="RFC3" class="col-md-4 col-form-label text-md-right">{{ __('RFC') }}</label>

                            <div class="col-md-6">
                                <input id="RFC3" type="text" class="form-control @error('RFC3') is-invalid @enderror"
                                    name="RFC3" value="{{ old('RFC3') }}"  autocomplete="RFC3" autofocus>

                                @error('RFC3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password3"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password3" type="text"
                                    class="form-control @error('password3') is-invalid @enderror" name="password3">

                                @error('password3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- RFC y password 4 --}}
                        <div class="form-group row">
                            <label for="RFC4" class="col-md-4 col-form-label text-md-right">{{ __('RFC') }}</label>

                            <div class="col-md-6">
                                <input id="RFC4" type="text" class="form-control @error('RFC4') is-invalid @enderror"
                                    name="RFC4" value="{{ old('RFC4') }}"  autocomplete="RFC4" autofocus>

                                @error('RFC4')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password4"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password4" type="text"
                                    class="form-control @error('password4') is-invalid @enderror" name="password4">

                                @error('password4')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- RFC y password 5 --}}
                        <div class="form-group row">
                            <label for="RFC5" class="col-md-4 col-form-label text-md-right">{{ __('RFC') }}</label>

                            <div class="col-md-6">
                                <input id="RFC5" type="text" class="form-control @error('RFC5') is-invalid @enderror"
                                    name="RFC5" value="{{ old('RFC5') }}"  autocomplete="RFC5" autofocus>

                                @error('RFC5')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password5"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password5" type="text"
                                    class="form-control @error('password5') is-invalid @enderror" name="password5">

                                @error('password5')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
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
