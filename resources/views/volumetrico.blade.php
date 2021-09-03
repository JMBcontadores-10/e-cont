@extends('layouts.app')

@section('content')

    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    @endphp

    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url()->previous() }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Control Volumétrico</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-3">
                <form action="{{ route('volumetrico1') }}" method="POST">
                    @csrf
                    <h4><b>Elige la fecha</b></h4>
                    <input type="date" name="fech1" min="2020-01-01" required>
                    <br>
                    <br>
                    <h4><b>Acción a realizar:</b></h4>

                    <select name="accion">
                        <option>Ingresar Datos</option>
                        <option>Editar Datos</option>
                        <option>Editar Cambio de Precio</option>
                    </select>

                    <br><br>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Enviar') }}
                    </button>

                </form>
                <br>
                <br>
                <form action="{{ route('convolu') }}" method="POST">
                    @csrf
                    <h4><b>Consulta hist&oacute;rica</b></h4>
                    <h5>Elige la fecha:</h5>
                    <br>
                    <input type="date" name="id1" min=2020-01-01 required>

                    <h5>a</h5>
                    <input type="date" name="id2" min=2020-01-01 required>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Enviar') }}
                    </button>
                </form>
            </div>

            <div class="col-9">

                <br>
                <br>

                <div id="calender"></div>

                {{-- aqui falta completar --}}
                <div class="modal fade" id="saveEventModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <button id="removeEvent" class="btn btn-sm btn-danger float-right"><i
                                        class="fa fa-trash"></i></button>
                                <br>

                                <label class="d-block"><input type="checkbox" id="allDay">A partir
                                    de:</label>
                                <input type="time" id="time" class="form-control mb-3 d-none">

                                <input type="text" id="title" class="form-control">
                                <textarea id="content" class="form-control"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                <button id="addEvent" class="btn btn-success"><i class="fa fa-plus"></i>Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
