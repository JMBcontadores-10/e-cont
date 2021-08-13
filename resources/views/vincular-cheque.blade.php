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
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Seleccione el tipo:</p>
                            </div>
                            <div class="col-4">
                                <select name="tipo" class="form-control">
                                    <option {{ $tipo == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option {{ $tipo == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                    <option {{ $tipo == 'Domiciliación' ? 'selected' : '' }}>Domiciliación</option>
                                    <option {{ $tipo == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">#Cheque/#Transferencia:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required name="numCheque"
                                    value="{{ $numCheque }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Fecha:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=date required name="fechaCheque"
                                    value="{{ $fechaCheque }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Importe Cheque/Transferencia:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" id="number" required name="importeCheque"
                                    value="{{ $importeCheque }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Importe Total:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required readonly name="importeT"
                                    value="{{ $importeT }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Beneficiario:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required name="beneficiario"
                                    value="{{ $beneficiario }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Tipo de operación:</p>
                            </div>
                            <div class="col-4">
                                <select class="form-control" name="tipoOperacion">
                                    <option {{ $tipoOperacion == 'Impuestos' ? 'selected' : '' }}>Impuestos</option>
                                    <option {{ $tipoOperacion == 'Nómina' ? 'selected' : '' }}>Nómina</option>
                                    <option {{ $tipoOperacion == 'Gasto y/o compra' ? 'selected' : '' }}>Gasto y/o compra
                                    </option>
                                    <option {{ $tipoOperacion == 'Sin CFDI' ? 'selected' : '' }}>Sin CFDI</option>
                                    <option {{ $tipoOperacion == 'Parcialidad' ? 'selected' : '' }}>Parcialidad</option>
                                    <option {{ $tipoOperacion == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div id="{{ !$subirArchivo ? 'subir-archivo' : '' }}" class="mainbox2 row mt-3">
                            <div class="col-6 d-flex justify-content-end">
                                <p class="pf">Subir Archivo (solo PDF):</p>
                            </div>
                            <div class="col-4">
                                <input name="subir_archivo" type="file" accept=".pdf" />
                            </div>
                        </div>
                        <div id="{{ !$subirArchivo ? 'doc-relacionados' : '' }}" class="mainbox2 row mt-3">
                            <div class="col-6 d-flex justify-content-end">
                                <p class="pf">Documentos adicionales (solo PDF):</p>
                            </div>
                            <div class="col-4">
                                <input name="doc_relacionados[]" type="file" accept=".pdf" multiple />
                            </div>
                        </div>
                        <button class="btn btn-linkj mt-3">Actualizar Cheque/Transferencia</button>
                    </form>
                </div>
            @else
                @if ($vincular)
                    <div>
                        <form action="{{ url('agregar-xml-cheque') }}" method="POST">
                            @csrf
                            <label for="selectCheque" class="text-bold">Selecciona un Cheque/Transferencia previo:</label>
                            <select name="selectCheque" id="selectCheque" class="ml-2">
                                <option value="0">#Fecha - #Cheque - Beneficiario - Monto</option>
                                @foreach ($colCheques as $i)
                                    <option value="{{ $i->_id }}">
                                        {{ $i->fecha }} - {{ $i->numcheque }} - {{ $i->Beneficiario }} -
                                        ${{ number_format($i->importecheque, 2) }}
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
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Seleccione el tipo:</p>
                            </div>
                            <div class="col-4">
                                <select name="tipo" class="form-control">
                                    <option>Cheque</option>
                                    <option>Transferencia</option>
                                    <option>Domiciliación</option>
                                    <option>Efectivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">#Cheque/#Transferencia:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required name="numCheque">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Fecha:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=date required name="fechaCheque">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Importe Cheque/Transferencia:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" id="number" required name="importeCheque">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Importe Total:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required readonly name="importeT"
                                    value="{{ $vincular ? $totalXml : '0' }}">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Beneficiario:</p>
                            </div>
                            <div class="col-4">
                                <input class="form-control" type=text required name="beneficiario">
                            </div>
                        </div>
                        <div class="mainbox2 row">
                            <div class="col-6 d-flex justify-content-end mt-2">
                                <p class="pf">Tipo de operación:</p>
                            </div>
                            <div class="col-4">
                                <select class="form-control" name="tipoOperacion">
                                    <option>Impuestos</option>
                                    <option>Nómina</option>
                                    <option>Gasto y/o compra</option>
                                    <option>Sin CFDI</option>
                                    <option>Parcialidad</option>
                                    <option>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="mainbox2 row mt-3">
                            <div class="col-6 d-flex justify-content-end">
                                <p class="pf">Subir Archivo (solo PDF):</p>
                            </div>
                            <div class="col-4">
                                <input name="subir_archivo" type="file" accept=".pdf" />
                            </div>
                        </div>
                        <div class="mainbox2 row mt-3">
                            <div class="col-6 d-flex justify-content-end">
                                <p class="pf">Documentos adicionales (solo PDF):</p>
                            </div>
                            <div class="col-4">
                                <input name="doc_relacionados[]" type="file" accept=".pdf" multiple />
                            </div>
                        </div>
                        @if ($vincular)
                            <input type="hidden" name="allcheck" value="{{ json_encode($allcheck, true) }}">
                        @endif
                        <button class="btn btn-linkj mt-3">Registrar Cheque/Transferencia</button>
                    </form>
                </div>
            @endif
        </div>
    @endsection
