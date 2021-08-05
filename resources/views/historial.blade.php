@extends('layouts.app')

@php
    use App\Models\CalendarioE;
    use App\Models\CalendarioR;
    
@endphp

@section('content')

<div class="container">
    <div class="float-md-left">
        <a class="b3" href="{{ url()->previous() }}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Historial de Descargas</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div class="justify-content-start">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
        <hr style="border-color:black; width:100%;">
    </div>

    <div class="input-group">
        <span class="input-group-text">Buscar</span>
        <input id="filtrar" type="text" class="form-control" placeholder="Buscar proveedor">
        {{-- <a href="#bottom" class="btn btn-primary ml-2">Ir abajo</a> --}}
    </div><br>

    <div class="row">
        <div class="col-5">
            <h3 class="text-center">Historial Emitidos</h3>
            <table class="table table-stripered">
                <tr>
                    <th>#</th> 
                    <th>RFC</th>
                    <th>Fecha</th>
                    <th>Tipo de Factura</th>
                    <th>Total Descargados</th>
                    <th>Total Error</th> 
                </tr>
                <tbody class="buscar">
                    @foreach ($col as $i)
                    <tr>
                        <td>{{++$n}}</td>
                        <td>{{$rfc}}</td>
                        <td>{{$i['fechaDescarga']}}</td>
                        <td>Emitidos</td>
                        <td>{{$i['descargasEmitidos']}}</td>
                            @php
                                $error= $i['erroresEmitidos']
                            @endphp
                        @if ($error >0)
                            <td style="color: #F10E04">{{$error}}</td>
                        @else
                            <td>{{$error}}</td>
                        @endif
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="col-2">

        </div>
        <div class="col-5">

            <h3 class="text-center">Historial Recibidos</h3>
            <table class="table table-stripered">
                <tr>
                    <th>#</th> 
                    <th>RFC</th>
                    <th>Fecha</th>
                    <th>Tipo de Factura</th>
                    <th>Total Descargados</th>
                    <th>Total Error</th> 
                </tr>
                @php
                    $rfc = Auth::user()->RFC;
                    $n=0;
                    // $tXml=0;
                    // $tTabla=0;

                    $colr = DB::table('calendario_r')
                        ->select('fechaDescarga', 'rfc', 'descargasRecibidos', 'erroresRecibidos')
                        ->where('rfc', $rfc)
                        ->orderBy('fechaDescarga', 'asc')
                        ->get();

                @endphp

                <tbody class="buscar">
                    @foreach ($colr as $r)
                    <tr>
                        <td>{{++$n}}</td>
                        <td>{{$rfc}}</td>
                        <td>{{$r['fechaDescarga']}}</td>
                        <td>Recibidos</td>
                        <td>{{$r['descargasRecibidos']}}</td>
                        @php
                        $error= $r['erroresRecibidos']
                        @endphp
                        @if ($error >0)
                            <td style="color: #F10E04">{{$error}}</td>
                        @else
                            <td>{{$error}}</td>
                        @endif
                    </tr>
                    @endforeach


                </tbody>
            </table>

        </div>

    </div>


</div>

@endsection
