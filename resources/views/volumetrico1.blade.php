@extends('layouts.app')

@section('content')

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
        @if ($accion == 'Ingresar Datos')
            <br>
            <h2 align="center">Ingreso de datos del día <b>{{ $fech1 }}</b></h2>
            <form enctype="multipart/form-data" action="{{ route('insertaDatos') }}" method="POST">
                @csrf
                <input type=hidden name="rfc" value={{ Auth::user()->RFC }}>
                <input type=hidden name="fech1" value={{ $fech1 }}>
                <table class="table">
                    <thead>
                        <tr class="table-primary">
                            @php
                                $diesel = DB::table('clientes')
                                    ->select('diesel')
                                    ->where('RFC', Auth::user()->RFC)
                                    ->get();
                                foreach ($diesel as $di) {
                                    $die = $di['diesel'];
                                }

                            @endphp
                            <th scope="col"></th>
                            <th scope="col">Magna</th>
                            <th scope="col">Premium</th>
                            @if ($die == '1')
                                <th scope="col">Diesel</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Inventario Inicial</th>
                            @php
                                $invAyer = DB::table('volumetrico')
                                    ->select('aM', 'aP', 'aD', 'num')
                                    ->where('fech1', $fechaFv)
                                    ->get();
                                $cIA = $invAyer->count();
                                foreach ($invAyer as $iAyer) {
                                    $iaM = $iAyer['aM'];
                                    $iaP = $iAyer['aP'];
                                    $iaD = $iAyer['aD'];
                                }
                            @endphp
                            @if ($cIA > 0)
                                <td><input class="form-control" type=text name="invIniM" id="invIniM" value={{ $iaM }} ></td>
                                <td><input class="form-control" type=text name="invIniP" id="invIniP" value={{ $iaP }} ></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="invIniD" id="invIniD" value={{ $iaD }} ></td>
                                @endif
                            @else
                                <td><input class="form-control" type=text name="invIniM" id="invIniM" value="" ></td>
                                <td><input class="form-control" type=text name="invIniP" id="invIniP" value="" ></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="invIniD" id="invIniD" value="" ></td>
                                @endif

                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Compras</th>
                            <td><input class="form-control" type=text name="comprasM" id="comprasM" value="" ></td>
                            <td><input class="form-control" type=text name="comprasP" id="comprasP" value="" ></td>
                            @if ($die == '1')
                                <td><input class="form-control" type=text name="comprasD" id="comprasD" value="" ></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Litros Vendidos</th>
                            <td><input class="form-control" type=text name="ventasM" id="ventasM" value="" ></td>
                            <td><input class="form-control" type=text name="ventasP" id="ventasP" value="" ></td>
                            @if ($die == '1')
                                <td><input class="form-control" type=text name="ventasD" id ="ventasD" value=""></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Precio Venta</th>
                            <td><input class="form-control" type=text name="pventaM" value=""></td>
                            <td><input class="form-control" type=text name="pventaP" value=""></td>
                            @if ($die == '1')
                                <td><input class="form-control" type=text name="pventaD" value=""></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Inventario Real (Autostick)</th>
                            <td><input class="form-control" type=text name="autoM" id="autoM" value="" ></td>
                            <td><input class="form-control" type=text name="autoP" id="autoP" value="" ></td>
                            @if ($die == '1')
                                <td><input class="form-control" type=text name="autoD" id="autoD" value="" ></td>
                            @endif
                        </tr>
                        <tr id="invd">
                            <th scope="row">Inventario Determinado</th>
                                <td><input class="form-control" id="invDeterM" name="invDeterM" readonly="true" type="text" value="" ></td>
                                <td><input class="form-control" id="invDeterP" name="invDeterP" readonly="true" type=text value="" ></td>
                                @if ($die == '1')
                                <td><input class="form-control" id="invDeterD" name="invDeterD" readonly="true" type=text value="" ></td>
                                @endif
                            </tr>
                        <tr id= "invAuto">
                            <th>Diferencia o Merma</th>
                            <td><input class="form-control" id="mermaM" name="mermaM" readonly="true" type="text" value="" ></td>
                            <td><input class="form-control" id="mermaP" name="mermaP" readonly="true" type="text" value="" ></td>
                            @if ($die == '1')
                            <td><input class="form-control" id="mermaD" name="mermaD" readonly="true" type="text" value="" ></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <br>
                @php
                    $archivo = DB::table('volumetrico')
                                    ->select('archivoVol')
                                    ->where('RFC', Auth::user()->RFC)
                                    ->where('fech1', $fech1)
                                    ->get();

                    if ($archivo > '0') {
                        foreach ($archivo as $a) {
                            $archivoV = $a['archivoVol'];
                        }
                    }


                @endphp
                @if ($archivoV != 'null')
                    <h1>Ya existe archivo de este día</h1>
                    <a id="rutArc" href="{{ $archivoV }}" target="_blank">
                        <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                    </a>
                @else
                <div class="row">
                    <div class="col-3">
                        <p class="pf">Subir Archivo (solo PDF):</p>
                    </div>
                    <div class="col-3">
                        <input id="archivoVol" name="archivoVol" type="file" accept=".pdf" />
                    </div>
                </div>

                @endif
                <br>

                <div class="row justify-content center">
                    <a onclick="inventario()" class="btn btn-primary" style="margin-left: 250px;">
                        {{ __('Inventario Determinado') }}
                    </a>
                    <a onclick="merma()" class="btn btn-primary" style="margin-left: 250px;">
                        {{ __('Merma') }}
                    </a>
                </div>

                <div class="row justify-content center">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Enviar') }}
                    </button>
                </div>
            </form>

        @elseif ($accion == 'Editar Datos')
            <h3>Edición de datos de {{ $fech1 }}</h3>
            @php
                $dies = DB::table('clientes')
                    ->select('diesel')
                    ->where('RFC', Auth::user()->RFC)
                    ->get();
                foreach ($dies as $di) {
                    $die = $di['diesel'];
                }
                $diesel = DB::table('volumetrico')
                    ->where('RFC', Auth::user()->RFC)
                    ->where('fech1', $fech1)
                    ->get();
                $count = $diesel->count();

                foreach ($diesel as $di) {
                    $num = $di['num'];
                    $idV = $di['idV'];
                    $iM = $di['iiM'];
                    $iP = $di['iiP'];
                    $iD = $di['iiD'];
                    $cM = $di['cM'];
                    $cP = $di['cP'];
                    $cD = $di['cD'];
                    $vM = $di['vM'];
                    $vP = $di['vP'];
                    $vD = $di['vD'];
                    $aM = $di['aM'];
                    $aP = $di['aP'];
                    $aD = $di['aD'];
                    $pM = $di['pM'];
                    $pP = $di['pP'];
                    $pD = $di['pD'];
                }
            @endphp
            @if ($count > '0')

                <form action="{{ route('updateDatos') }}" method="POST">
                    @csrf
                    <input type=hidden name="idV" value={{ $idV }}>
                    <input type=hidden name="rfc" value={{ Auth::user()->RFC }}>
                    <input type=hidden name="fech1" value={{ $fech1 }}>
                    <input type=hidden name="num" value={{ $num }}>
                    <table class="table">
                        <thead>
                            <tr class="table-primary">

                                <th scope="col"></th>
                                <th scope="col">Magna</th>
                                <th scope="col">Premium</th>
                                @if ($die == '1')
                                    <th scope="col">Diesel</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Inventario Inicial</th>
                                <td><input class="form-control" type=text name="invIniM" id= "invIniM" value="{{ $iM }}">
                                </td>
                                <td><input class="form-control" type=text name="invIniP" id="invIniP" value="{{ $iP }}">
                                </td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="invIniD" id="invIniD" value="{{ $iD }}">
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Compras</th>
                                <td><input class="form-control" type=text name="comprasM" id="comprasM" value="{{ $cM }}">
                                </td>
                                <td><input class="form-control" type=text name="comprasP" id="comprasP" value="{{ $cP }}">
                                </td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="comprasD" id="comprasD"
                                            value="{{ $cD }}"></td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Litros Vendidos</th>
                                <td><input class="form-control" type=text name="ventasM" id="ventasM" value="{{ $vM }}">
                                </td>
                                <td><input class="form-control" type=text name="ventasP" id="ventasP" value="{{ $vP }}">
                                </td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="ventasD" id="ventasD" value="{{ $vD }}">
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Precio Venta</th>
                                <td><input class="form-control" type=text name="pventaM" value="{{ $pM }}">
                                </td>
                                <td><input class="form-control" type=text name="pventaP" value="{{ $pP }}">
                                </td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="pventaD" value="{{ $pD }}">
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Inventario Real (Autostick)</th>
                                <td><input class="form-control" type=text name="autoM" value="{{ $aM }}"></td>
                                <td><input class="form-control" type=text name="autoP" value="{{ $aP }}"></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="autoD" value="{{ $aD }}">
                                    </td>
                                @endif
                            </tr>
                            <tr id="invd">
                                <th scope="row">Inventario Determinado</th>

                                    <td><input class="form-control" id="invDeterM" name="invDeterM" readonly="true" type="text" value=""></td>
                                    <td><input class="form-control" id="invDeterP" name="invDeterP" readonly="true" type=text name="P" value=""></td>
                                    <td><input class="form-control" id="invDeterD" name="invDeterD" readonly="true" type=text name="D" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-12">
                        <a onclick="inventario()" class="btn btn-primary" style="margin-left: 250px;">
                            {{ __('Inventario Determinado') }}
                        </a>
                    </div>
                    <div class="row justify-content center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Enviar') }}
                        </button>
                    </div>
                </form>
            @endif

        @else
            @php
                $diesel1 = DB::table('volumetrico')
                    ->where('RFC', Auth::user()->RFC)
                    ->where('fech1', $fech1)
                    ->get();
                $count1 = $diesel1->count();
            @endphp
            @if ($count1 == '1')
                <h1>Editar precio de {{ $fech1 }}</h1>
                <form action="{{ route('updatePrecio') }}" method="POST">
                    @csrf
                    <input type=hidden name="rfc" value={{ Auth::user()->RFC }}>
                    <input type=hidden name="fech1" value={{ $fech1 }}>
                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                                @php
                                    $diesel = DB::table('clientes')
                                        ->select('diesel')
                                        ->where('RFC', Auth::user()->RFC)
                                        ->get();
                                    foreach ($diesel as $di) {
                                        $die = $di['diesel'];
                                    }
                                @endphp
                                <th scope="col"></th>
                                <th scope="col">Magna</th>
                                <th scope="col">Premium</th>
                                @if ($die == '1')
                                    <th scope="col">Diesel</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Inventario Inicial</th>
                                @php
                                    $invAyer = DB::table('volumetrico')
                                        ->select('aM', 'aP', 'aD')
                                        ->where('fech1', $fech1)
                                        ->get();
                                    $cIA = $invAyer->count();
                                    foreach ($invAyer as $iAyer) {
                                        $iaM = $iAyer['aM'];
                                        $iaP = $iAyer['aP'];
                                        $iaD = $iAyer['aD'];
                                    }
                                @endphp
                                @if ($cIA > 0)
                                    <td><input class="form-control" type=text name="invIniM" id="invIniM" value={{ $iaM }}>
                                    </td>
                                    <td><input class="form-control" type=text name="invIniP"  id="invIniP"value={{ $iaP }}>
                                    </td>
                                    @if ($die == '1')
                                        <td><input class="form-control" type=text name="invIniD" id="invIniD"
                                                value={{ $iaD }}></td>
                                    @endif
                                @else
                                    <td><input class="form-control" type=text name="invIniM" id="invIniM" value=""></td>
                                    <td><input class="form-control" type=text name="invIniP" id="invIniP" value=""></td>
                                    @if ($die == '1')
                                        <td><input class="form-control" type=text name="invIniD" id="invIniD" value=""></td>
                                    @endif

                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Compras</th>
                                <td><input class="form-control" type=text name="comprasM" id="comprasM" value=""></td>
                                <td><input class="form-control" type=text name="comprasP" id="comprasP" value=""></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="comprasD" id="comprasD" value=""></td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Litros Vendidos</th>
                                <td><input class="form-control" type=text name="ventasM" id="ventasM" value=""></td>
                                <td><input class="form-control" type=text name="ventasP" id="ventasP" value=""></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="ventasD" id="ventasD" value=""></td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Precio Venta</th>
                                <td><input class="form-control" type=text name="pventaM" value=""></td>
                                <td><input class="form-control" type=text name="pventaP" value=""></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="pventaD" value=""></td>
                                @endif
                            </tr>
                            <tr>
                                <th scope="row">Inventario Real (Autostick)</th>
                                <td><input class="form-control" type=text name="autoM" value=""></td>
                                <td><input class="form-control" type=text name="autoP" value=""></td>
                                @if ($die == '1')
                                    <td><input class="form-control" type=text name="autoD" value=""></td>
                                @endif
                            </tr>
                            <tr id="invd">
                                <th scope="row">Inventario Determinado</th>

                                    <td><input class="form-control" id="invDeterM" name="invDeterM" readonly="true" type="text" value=""></td>
                                    <td><input class="form-control" id="invDeterP" name="invDeterP" readonly="true" type=text value=""></td>
                                    <td><input class="form-control" id="invDeterD" name="invDeterD" readonly="true" type=text value=""></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="col-12">
                        <a onclick="inventario()" class="btn btn-primary" style="margin-left: 250px;">
                            {{ __('Inventario Determinado') }}
                        </a>
                    </div>

                    <div class="row justify-content center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Enviar') }}
                        </button>
                    </div>
                </form>


            @else
                <div class="d-flex justify-content-center">
                    <h1>Primero es necesario ingresar datos de:</h1>
                </div>
                <div class="d-flex justify-content-center">
                    <h1>{{ $fech1 }}</h1>
                </div>
                <div class="d-flex justify-content-center">
                    <img src="img/alerta1.png" alt="" width="200px" height="200px">
                </div>


            @endif

        @endif
    </div>

@endsection
