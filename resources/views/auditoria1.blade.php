@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="javascript:javascript:history.go(-1)">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Auditoría</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>

        <h1>Facturas: {{ $tipoer }}.</h1>
        <h1>Periodo del {{ $fecha1er }} al {{ $fecha2er }}.</h1>
        <br>
        <div class="row">
            @if ($rc)
                <div class="col-12 text-center">
                    <h2>Tabla de Reporte Completo</h2>
                </div>
            @else
                <div class="col-8 text-center">
                    <h2>Tabla de comparación</h2>
                </div>
                <div class="col-4 text-center">
                    <h2>Tabla de CFDIs faltantes</h2>
                </div>
            @endif
            <div class="{{ $rc ? 'col-12' : 'col-8' }}">
                <div style="overflow: auto">
                    <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-center align-middle">N°</th>
                                <th class="text-center align-middle">UUID</th>
                                <th class="text-center align-middle">Fecha Emisión</th>
                                <th class="text-center align-middle">Fecha Certificación SAT</th>
                                <th class="text-center align-middle">Fecha Cancelación</th>
                                <th class="text-center align-middle">Estado SAT Anterior</th>
                                <th class="text-center align-middle">Estado SAT Actual</th>
                                <th class="text-center align-middle">Estado cambiado</th>
                                <th class="text-center align-middle">Cheque vinculado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metadata as $me)


                                @php
                                    if ($me->estatus == '1') {
                                        $me->estatus = 'Vigente';
                                    } else {
                                        $me->estatus = 'Cancelado';
                                    }
                                    if ($tipoer == 'Emitidas') {
                                        $colM = DB::table('metadata_e')
                                            ->select('estado')
                                            ->where('folioFiscal', $me->uuid)
                                            ->first();
                                    } else {
                                        $colM = DB::table('metadata_r')
                                            ->select('estado', 'cheques_id')
                                            ->where('folioFiscal', $me->uuid)
                                            ->first();
                                    }
                                    if (isset($colM)) {
                                        $estadoM = $colM['estado'];
                                        if (isset($colM['cheques_id'])) {
                                            $cheques_id = $colM['cheques_id'];
                                        }else {
                                            $cheques_id = '-';
                                        }
                                    } else {
                                        $estadoM = 'X';
                                    }
                                @endphp
                                @if ($rc ? $estadoM != 'X' : $estadoM != 'X' && $me->estatus != $estadoM)
                                    <tr>
                                        <td class="text-center align-middle">{{ ++$n }}</td>
                                        <td class="text-center align-middle">{{ $me->uuid }}</td>
                                        <td class="text-center align-middle">{{ $me->fechaEmision }}</td>
                                        <td class="text-center align-middle">{{ $me->fechaCertificacionSat }}</td>
                                        <td class="text-center align-middle">{{ $me->fechaCancelacion }}</td>
                                        <td class="text-center align-middle">{{ $estadoM }}</td>
                                        <td class="text-center align-middle">{{ $me->estatus }}</td>
                                        <td class="text-center align-middle">
                                            @if ($me->estatus == $estadoM)
                                                <i class="far fa-check-circle fa-2x" style="color: green"></i>
                                            @else
                                                <i class="far fa-times-circle fa-2x" style="color: red"></i>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $cheques_id }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if (!$rc)
                <div class="col-4">
                    <div style="overflow: auto">
                        <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center align-middle">N°</th>
                                    <th class="text-center align-middle">UUID</th>
                                    <th class="text-center align-middle">Fecha Emisión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metadata2 as $me2)
                                    @php
                                        if ($me2->estatus == '1') {
                                            $me2->estatus = 'Vigente';
                                        } else {
                                            $me2->estatus = 'Cancelado';
                                        }
                                        if ($tipoer == 'Emitidas') {
                                            $colM = DB::table('metadata_e')
                                                ->select('estado')
                                                ->where('folioFiscal', $me2->uuid)
                                                ->first();
                                        } else {
                                            $colM = DB::table('metadata_r')
                                                ->select('estado')
                                                ->where('folioFiscal', $me2->uuid)
                                                ->first();
                                        }
                                        if (isset($colM)) {
                                            $estadoM = $colM['estado'];
                                        } else {
                                            $estadoM = 'X';
                                        }
                                    @endphp
                                    @if ($estadoM == 'X')
                                        <tr>
                                            <td class="text-center align-middle">{{ ++$m }}</td>
                                            <td class="text-center align-middle">{{ $me2->uuid }}</td>
                                            <td class="text-center align-middle">{{ $me2->fechaEmision }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
