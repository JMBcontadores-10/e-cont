@extends('layouts.app')

<head>
    <title>Cheques y Transferencias Contarapp</title>
    
</head>

@section('content')
    <div class="container">

        <div class="float-md-left">
            <a class="b3" href="{{ url('cheques-transferencias') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Cheques y Transferencias</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>

        <div style="color:black;" ALIGN=center>
            @if ($editar)
                @if (!$subirArchivo)
                    <div id="alerta-archivo">
                        <h4>Este cheque/transferencia ya tiene un archivo. ¿Deseas subir uno nuevo?</h4>
                        <a id="alerta-archivo-si" class="btn btn-primary">Si</a>
                        <a id="alerta-archivo-no" class="btn btn-primary">No</a>
                        <br><br>
                    </div>
                @endif
                <div id="{{ !$subirArchivo ? 'form-editar' : '' }}" ALIGN="center">
                    <h1 style="color:#035CAB">Editar Cheque/Transferencia</h1><br>
                    <form enctype="multipart/form-data" action="{{ url('archivo-pagar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <input type="hidden" name="nombrec" value="{{ $nombrec }}">
                        Seleccione el tipo: &nbsp;
                        <select name="tipo">
                            <option {{ $tipo == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option {{ $tipo == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option {{ $tipo == 'Domiciliación' ? 'selected' : '' }}>Domiciliación</option>
                            <option {{ $tipo == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                        </select>
                        <br><br>#Cheque/#Transferencia: <input type=text required name="numCheque"
                            value="{{ $numCheque }}">
                        <br><br>Fecha <input type=date required name="fechaCheque" value="{{ $fechaCheque }}">
                        <br><br>Importe Cheque/Transferencia: <input id="number" required name="importeCheque"
                            value="{{ $importeCheque }}">
                        <br><br>Importe Total: <input type=text required readonly name="importeT"
                            value="{{ $importeT }}">
                        <br><br>Beneficiario: <input type=text required name="beneficiario" value="{{ $beneficiario }}">
                        <br><br>Tipo de operación: &nbsp;
                        <select name="tipoOperacion">
                            <option {{ $tipoOperacion == 'Impuestos' ? 'selected' : '' }}>Impuestos</option>
                            <option {{ $tipoOperacion == 'Nómina' ? 'selected' : '' }}>Nómina</option>
                            <option {{ $tipoOperacion == 'Gasto y/o compra' ? 'selected' : '' }}>Gasto y/o compra
                            </option>
                            <option {{ $tipoOperacion == 'Sin CFDI' ? 'selected' : '' }}>Sin CFDI</option>
                            <option {{ $tipoOperacion == 'Parcialidad' ? 'selected' : '' }}>Parcialidad</option>
                            <option {{ $tipoOperacion == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <br>
                        <br>
                        <div id="{{ !$subirArchivo ? 'subir-archivo' : '' }}">
                            <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                            <p> Subir Archivo (solo PDF): <input name="subir_archivo" type="file" accept=".pdf" />
                            </p>
                        </div>
                        <button class="btn btn-linkj">Registrar Cheque/Transferencia</button>

                    </form>
                </div>
            @else
                @if ($vincular)
                    <div>
                        <form action="{{ url('agregar-xml-cheque') }}" method="POST">
                            @csrf
                            <label for="selectCheque" class="text-bold">Selecciona un Cheque/Transferencia previo:</label>
                            <select name="selectCheque" id="selectCheque" class="ml-2">
                                <option value="0">#Fecha - Beneficiario - Monto</option>
                                @foreach ($colCheques as $i)
                                    <option value="{{ $i->_id }}">
                                        {{ $i->fecha }} - {{ $i->Beneficiario }} - ${{ $i->importecheque }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="totalXml" value="{{ $totalXml }}">
                            <input type="hidden" name="allcheck" value="{{ json_encode($allcheck, true) }}">
                            <input type="submit" class="btn btn-primary ml-2" value="Enviar Archivo">
                            <br><br>
                        </form>
                    </div>
                    <div id="alerta-crear">
                        <h4>¿Deseas crear un nuevo cheque / transferencia?</h4>
                        <a id="alerta-crear-si" class="btn btn-primary">Si</a>
                        <a id="alerta-crear-no" class="btn btn-primary">No</a>
                        <br><br>
                    </div>
                @endif
                <div id="{{ $vincular ? 'form-crear' : '' }}" ALIGN="center">
                    <h1 style="color:#035CAB">Añadir Cheque/Transferencia</h1><br>
                    <form enctype="multipart/form-data" action="{{ url('archivo-pagar') }}" method="POST">
                        @csrf
                        Seleccione el tipo: &nbsp;
                        <select name="tipo">
                            <option>Cheque</option>
                            <option>Transferencia</option>
                            <option>Domiciliación</option>
                            <option>Efectivo</option>
                        </select>
                        <br><br>#Cheque/#Transferencia: <input type=text required name="numCheque">
                        <br><br>Fecha <input type=date required name="fechaCheque">
                        <br><br>Importe Cheque/Transferencia: <input id="number" required name="importeCheque">
                        <br><br>Importe Total: <input type=text required readonly name="importeT"
                            {{-- value="{{ $vincular ? $totalXml : '0' }}" --}}
                            value="0">
                        <br><br>Beneficiario: <input type=text required name=" beneficiario">
                        <br><br>Tipo de operación: &nbsp;
                        <select name="tipoOperacion">
                            <option>Impuestos</option>
                            <option>Nómina</option>
                            <option>Gasto y/o compra</option>
                            <option>Sin CFDI</option>
                            <option>Parcialidad</option>
                            <option>Otro</option>
                        </select>
                        <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                        <br><br>
                        <p> Subir Archivo (solo PDF): <input name="subir_archivo" type="file" accept=".pdf" /></p><br>
                        <button class="btn btn-linkj">Registrar Cheque/Transferencia</button>
                    </form>
                </div>
            @endif
        </div>
    @endsection
