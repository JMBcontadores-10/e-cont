@extends('layouts.app')

@php
use App\Models\MetadataR;
use App\Models\ListaNegra;
@endphp

<head>
    <title>Cheques y Transferencias Contarapp</title>
    
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url()->previous() }}">
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

        <div ALIGN="center" style="color:#035CAB">
            <h1>Añadir Cheque/Transferencia</h1><br><br>
        </div>
        <div style="color:black;" ALIGN=center>
            <form enctype="multipart/form-data" action="{{ url('archivo-pagar') }}" method="POST">
                @csrf
                {{-- <input type=hidden name=a value=0>
                <input type=hidden name=c value="<?php echo $a; ?>">
                <input type=hidden name=idche value="<?php echo $idche; ?>">
                <input type=hidden name=password value="<?php echo $pass1; ?>">
                <input type=hidden name=passauto value="000">
                <input type=hidden name=previo value=<?php echo '00'; ?>> --}}
                {{-- <br>Seleccione el tipo: &nbsp;
                <select name="tipo">
                    <option>Cheque</option>
                    <option>Transferencia</option>
                    <option>Domiciliación</option>
                    <option>Efectivo</option>
                </select>
                <br><br>#Cheque/#Transferencia: <input type=text required name="numCheque">
                <br><br>Fecha <input type=date required name="fechaCheque">
                <br><br>Importe Cheque/Transferencia: <input id="number" required name="importeCheque">
                <br><br>Importe Total: <input type=text required readonly name="importeT" value=0.00>
                <br><br>Beneficiario: <input type=text required name="beneficiario">
                <br><br>Tipo de operación: &nbsp;
                <select name="tipoCheque">
                    <option>Impuestos</option>
                    <option>Nómina</option>
                    <option>Gasto y/o compra</option>
                    <option>Sin CFDI</option>
                    <option>Parcialidad</option>
                    <option>Otro</option>
                </select> --}}

                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                <br><br>
                <p> Subir Archivo (solo PDF): <input name="subir_archivo" type="file" accept=".pdf" /></p><br>
                <button class="btn btn-linkj">Registrar Cheque/Transferencia</button>
            </form>

        </div>
    @endsection
