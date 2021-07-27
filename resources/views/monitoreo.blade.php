@extends('layouts.app')

@php
 use App\Models\MetadataE;   
@endphp

@section('content')

<div class="container">
    <div class="float-md-left">
        <a class="b3" href="{{ url()->previous() }}">
            << Regresar</a>
    </div>
    <div class="float-md-right">
        <p class="label2">Cuentas por pagar</p>
    </div>
    <br>
    <hr style="border-color:black; width:100%;">
    <div class="justify-content-start">
        <label class="label1" style="font-weight: bold"> Sesión de: </label>
        <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
        <hr style="border-color:black; width:100%;">
    </div>
    <div class="row" style="justify-content: center;">
        
        @php
            $hoy = date("d-M-Y");
            $ayer = date("d-M-Y", strtotime($hoy."- 1 days"));
        @endphp
        <br>
        <h1>Facturación @php
             echo $ayer;
        @endphp</h1>
    </div>
    <br>
    <div class="row">
        <a class="" href="{{ url('/') }}">
            <img src="img/pdf.png" width="30px">
        </a>
        <a class="" href="{{ url('/') }}">
            <img src="img/excel.png" width="30px" style="margin-left: 30px;">
        </a>
    </div>
    <br>
    <div class="row">
        <div class="col-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Facturas</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                
                @for ($i = 0; $i <24; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td></td>
                    <td></td>   
                </tr>
                @endfor
                </tbody>
            </table>

        </div>
        <div class="col-5">
    
        </div>

        <div class="col-5">
            <table class="table table-striped">
                <tr>
                    <th>RFC</th>
                    <th>Razón Social</th>
                    <th># Facturas Emitidas</th>
                    <th>Monto</th>
                    <th>Ver</th>
                </tr>
                @foreach ($col as $i)
                @php
                    $sum=0;
                    $nXml = 0;
                @endphp
                <tr>
                    <td>{{$i['receptorRfc']}}</td>
                    <td>{{$i['receptorNombre']}}</td>
                    @php
                        $sum = $sum + $i['total'];
                    @endphp
                    <td></td>
                    <td>{{$sum}}</td>
                    <td>
                        <form action="detallesfactura" method="POST">
                            @csrf
                            <input type="hidden" name="emisorRfc" value="{{ $i['receptorRfc'] }}">
                            <input type="hidden" name="emisorNombre" value="{{ $i['receptorNombre'] }}">
                            <input type=submit value=Ver>
                        </form>
                    </td>
                @endforeach

            </table>

        </div>

    </div>
</div>

@endsection