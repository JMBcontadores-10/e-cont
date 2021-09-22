@extends('layouts.app')

<head>
    <title>Cheques y Transferencias Contarapp</title>
</head>
@php
use App\Models\Cheques;
@endphp

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/modules') }}">
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

        <div class="row justify-content-end">
            <div class="col-sm-7">
                <form class="form-inline">
                    <label class="pf" for="mes">Seleccione el periodo: </label>
                    <div class="form-group">
                        <select class="form-control m-2" id="mes" name="mes" >
                            <option value="00">Todos</option>
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="anio" name="anio">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary ml-2">Consultar</button>
                    </div>
                </form>
            </div>
            <div>
                <form action="{{ url('vincular-cheque') }}" method="POST">
                    @csrf
                    <button class="button2">Registrar Cheque/Transferencia</button>
                </form>
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar palabra clave">
        </div>
        <br>
    </div>
    <div class="mx-4" style="overflow: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr class="table-primary">
                    <th class="text-center align-middle">No.</th>
                    <th class="text-center align-middle">Fecha cheque</th>
                    <th class="text-center align-middle">Núm cheque o transferencia</th>
                    <th class="text-center align-middle">Beneficiario</th>
                    <th class="text-center align-middle">Tipo de operación</th>
                    <th class="text-center align-middle">Tipo</th>
                    <th class="text-center align-middle">Total</th>
                    <th class="text-center align-middle">Total CFDI</th>
                    <th class="text-center align-middle">Por comprobar</th>
                    @if (Session::get('tipoU') == '2')
                        <th class="text-center align-middle">Ajuste</th>
                    @endif
                    <th class="text-center align-middle">PDF cheque o transferencia</th>
                    <th class="text-center align-middle">Documentos adicionales</th>
                    <th class="text-center align-middle">Acciones</th>
                    @if (Session::get('tipoU') == '2')
                        <th class="text-center align-middle" colspan="2">Contabilizado</th>
                    @endif
                    <th class="text-center align-middle">Comentarios</th>
                    <th class="text-center align-middle">Cheque id</th>
                </tr>
            </thead>
            <tbody class="buscar">
                @foreach ($colCheques as $i)
                    @php
                        $editar = true;
                        $id = $i['_id'];
                        $tipo = $i['tipomov'];
                        $fecha = $i['fecha'];
                        $numCheque = $i['numcheque'];
                        $beneficiario = $i['Beneficiario'];
                        $importeC = $i['importecheque'];
                        $sumaxml = $i['importexml'];
                        $ajuste = $i['ajuste'];
                        $verificado = $i['verificado'];
                        $faltaxml = $i['faltaxml'];
                        $contabilizado = $i['conta'];
                        $pendiente = $i['pendi'];

                        $tipoO = $i['tipoopera'];
                        if ($tipoO == 'Impuestos' or $tipoO == 'Parcialidad') {
                            $diferencia = 0;
                        } else {
                            $diferencia = $importeC - abs($sumaxml);
                            $diferencia = $diferencia - $ajuste;
                        }
                        if ($diferencia > 1 or $diferencia < -1) {
                            $diferenciaP = 0;
                        } else {
                            $diferenciaP = 1;
                        }
                        $diferencia = number_format($diferencia, 2);

                        $nombreCheque = $i['nombrec'];
                        if ($nombreCheque == '0') {
                            $subirArchivo = true;
                            $nombreChequeP = 0;
                        } else {
                            $subirArchivo = false;
                            $nombreChequeP = 1;
                        }

                        $rutaArchivo = $rutaDescarga . $nombreCheque;
                        if (!empty($i['doc_relacionados'])) {
                            $docAdi = $i['doc_relacionados'];
                        }

                        $revisado_fecha = $i['revisado_fecha'];
                        $contabilizado_fecha = $i['contabilizado_fecha'];
                        $poliza = $i['poliza'];
                        $comentario = $i['comentario'];
                    @endphp
                    <tr class="CellWithComment">
                        <td class="text-center align-middle">{{ ++$n }}</td>
                        <td class="text-center align-middle CellWithComment">
                            {{ $fecha }}
                            @if (isset($comentario) && $verificado == 0)
                                <span class="CellComment">{{ $comentario }}</span>
                            @endif
                        </td>
                        <td class="text-center align-middle">{{ $numCheque }}</td>
                        <td class="text-center align-middle">{{ $beneficiario }}</td>
                        <td class="text-center align-middle">{{ $tipoO }}</td>
                        <td class="text-center align-middle">{{ $tipo }}</td>
                        <td class="text-center align-middle">${{ number_format($importeC, 2) }}</td>
                        <td class="text-center align-middle">${{ number_format($sumaxml, 2) }}</td>
                        <td class="text-center align-middle">${{ $diferencia }}</td>
                        @if (Session::get('tipoU') == '2')
                            <td class="text-center align-middle CellWithComment">
                                ${{ $ajuste }}
                                @if ($verificado == 0)
                                    <form action="{{ url('cheques-transferencias') }}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" step="any" name="ajuste" style="width: 66px">
                                        <input class="mt-2" type="submit" value="Ajustar">
                                    </form>
                                @endif
                            </td>
                        @endif
                        <td class="text-center align-middle">
                            @if ($nombreCheque == '0')
                                <i class="far fa-times-circle fa-2x" style="color: rgb(255, 44, 44)"></i>
                            @else
                                <a id="rutArc" href="{{ $rutaArchivo }}" target="_blank">
                                    <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                </a>
                                <br>
                                    <form action="{{ url('borrarArchivo') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" style="width: 100px;">Borrar</button>
                                    </form>


                            @endif
                        </td>

                        <td class="text-center align-middle">
                            @if (empty($i['doc_relacionados']))
                                <i class="far fa-times-circle fa-2x" style="color: rgb(255, 44, 44)"></i>
                            @else
                                @if (!$docAdi['0'] == '')
                                    <select id="{{ "docs-adicionales$n" }}" name="docs-adicionales"
                                        class="form-control mb-2">
                                        @foreach ($docAdi as $d)
                                            @php
                                                $newstring = ltrim(stristr($d, '-'), '-');
                                            @endphp
                                            <option value="{{ $d }}">{{ $newstring }}</option>
                                        @endforeach
                                    </select>
                                    <input id="ruta-adicionales" name="ruta-adicionales" type="hidden"
                                        value="{{ $rutaDescarga . 'Documentos_Relacionados/' }}">
                                     <input id="{{ $n }}" onclick="verAdicional(this.id)" type="submit"
                                        value="Ver">
                                @else
                                    <i class="far fa-times-circle fa-2x" style="color: rgb(255, 44, 44)"></i>
                                @endif
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            <div class="row align-items-center">
                                @if ($faltaxml != 0)
                                    <div class="col align-self-center">
                                        <form action="{{ url('detallesCT') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="verificado" value="{{ $verificado }}">
                                            <button type="submit" class="fabutton">
                                                <i class="fas fa-eye fa-lg mt-3" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if ($verificado == 0)
                                    <div class="col align-self-center">
                                        <form action="{{ url('vincular-cheque') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="editar" value="{{ $editar }}">
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                                            <input type="hidden" name="numCheque" value="{{ $numCheque }}">
                                            <input type="hidden" name="fechaCheque" value="{{ $fecha }}">
                                            <input type="hidden" name="importeCheque" value="{{ $importeC }}">
                                            <input type="hidden" name="importeT" value="{{ $sumaxml }}">
                                            <input type="hidden" name="beneficiario" value="{{ $beneficiario }}">
                                            <input type="hidden" name="tipoOperacion" value="{{ $tipoO }}">
                                            <input type="hidden" name="subirArchivo" value="{{ $subirArchivo }}">
                                            <input type="hidden" name="nombrec" value="{{ $nombreCheque }}">
                                            <button type="submit" class="fabutton">
                                                <i class="fas fa-edit fa-lg" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                @if ($verificado == 0)
                                    <div class="col align-self-center">
                                        <form action="{{ url('delete-cheque') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="hidden" name="rutaArchivo" value="{{ $rutaArchivo }}">
                                            <button
                                                onclick="return confirm('¿Seguro que deseas eliminar el cheque/transferencia?')"
                                                type="submit" class="fabutton">
                                                <i class="fas fa-trash-alt fa-lg" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>
                                    </div>

                                @endif
                            </div>
                        </td>
                        @if (Session::get('tipoU') == '2')
                            <td class="text-center align-middle">
                                <div class="mx-1">
                                    @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                        @php
                                            Cheques::find($id)->update(['pendi' => 1]);
                                        @endphp
                                        <div class="row d-flex justify-content-center">
                                            <span class="fa-stack mb-2">
                                                <i class="fas fa-circle fa-stack-1x fa-lg mt-1"
                                                    style="color: rgb(8, 8, 8)"></i>
                                                <i class="fas fa-exclamation-triangle fa-stack-1x fa-2x"
                                                    style="color: rgb(240, 229, 73)"></i>
                                            </span>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <input type="submit" name="Pendientes" value="Pendientes"
                                                onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }})">
                                        </div>
                                    @elseif ($verificado == 0 )
                                        <form action="{{ url('cheques-transferencias') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="checkbox" name="revisado" required class="mb-2">
                                            Revisado
                                            <input type="submit" name="Aceptar" value="Aceptar">
                                        </form>
                                    @else
                                        <i class="far fa-check-circle fa-2x" style="color: green"></i>
                                        @if (isset($revisado_fecha))
                                            <div class="mt-1">{{ $revisado_fecha }}</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="text-center align-middle" style="width: 150px">
                                <div class="mx-1">
                                    @if ($verificado == 1 and $contabilizado == 0)
                                        <form action="{{ url('cheques-transferencias') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="checkbox" name="conta" required class="mt-4">
                                            Contabilizado
                                            <div>
                                                Póliza:
                                                <input type="text" name="poliza" size="2" required class="mt-1 mb-2">
                                            </div>
                                            <input type="submit" name="Aceptar" value="Aceptar">
                                        </form>
                                    @elseif ($verificado == 1 and $contabilizado == 1)
                                        <img src="{{ asset('img/CONTABILIZADO.png') }}" alt="" style="width: 40PX">
                                        @if (isset($contabilizado_fecha))
                                            <div class="mt-1">{{ $contabilizado_fecha }}</div>
                                        @endif
                                        @if (isset($poliza))
                                            <div class="mt-1">Póliza: {{ $poliza }}</div>
                                        @endif
                                    @else
                                        <img src="{{ asset('img/espera.png') }}" alt="">
                                    @endif
                                </div>
                            </td>
                        @endif
                        <td class="text-center align-middle">
                            <div class="mx-1">
                                @if ($verificado != 0)
                                    @if (isset($comentario))
                                        {{ $comentario }}
                                    @else
                                        -
                                    @endif
                                @else
                                    <form action="{{ url('cheques-transferencias') }}" method="POST">
                                        @csrf
                                        <input type="hidden" id="id" name="id" value="{{ $id }}">
                                        <textarea name="comentario" cols="20" rows="2" class="mb-2"></textarea>
                                        <input type="submit" value="Aceptar">
                                    </form>
                                @endif
                            </div>
                        </td>
                        <td class="text-center align-middle">{{ $id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="ml-4 mt-3">
        {{ $colCheques->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
    </div>
@endsection
