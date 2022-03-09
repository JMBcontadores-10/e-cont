@extends('layouts.app')

@php
use App\Models\XmlR;
@endphp

<head>
    <title>Cuentas por pagar Contarapp </title>
</head>

@section('content')
    <div class="container">
        <div class="float-md-left">
            <a class="b3" href="{{ url('/cheques-transferencias') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Descargas</p>
        </div>
        <br>
        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <h1 align="center">Cheque y/o transferencia para Multi-Cheques</h1>
            <hr style="border-color:black; width:100%;">
        </div>
    </div>

    <form action="{{ url('desvincular-cheque') }}" method="POST">
        @csrf
        <div class="mx-4" style="overflow: auto">
            <table class="table table-sm table-hover table-bordered mx-2">
                <thead>
                    <tr class="table-primary">
                        <th class="text-center align-middle" width="100">No.</th>
                        {{-- Si el cheque no está verificado se puede desvincular --}}

		@if ($verificado == 0)
                            <th  class="text-center align-middle" width="150">Desvincular CFDI <input type="checkbox" id="allcheck"
                                    name="allcheck" /></th>
                        @endif
                        {{-- <th class="text-center align-middle">RFC Emisor</th> --}}
                        {{-- <th class="text-center align-middle">Razón Social Emisor</th> --}}
                        <th class="text-center align-middle"width="200">UUID</th>
                        <th class="text-center align-middle"width="150">Fecha Emisión</th>
                        <th class="text-center align-middle" width="200">Emisor</th>
                        <th class="text-center align-middle"width="150">Concepto</th>
                        <th class="text-center align-middle"width="150">Folio</th>
                        <th class="text-center align-middle"width="150">Metodo - Pago</th>
                        <th class="text-center align-middle"width="150">Complemento</th>
                        <th class="text-center align-middle"width="150">Efecto</th>

		<th class="text-center align-middle"width="150">subtotal</th>

                        <th class="text-center align-middle"width="150">IVA</th>
                        <th class="text-center align-middle"width="150">Total</th>
                        <th class="text-center align-middle"width="150" >Estado</th>
                        <th class="text-center align-middle"width="150">Descargar</th>
                    </tr>
                </thead>
                <tbody class="buscar">
                    @php
                        $arrRfc = [];
		$a=[];
		$t=[];
		$egreso=[];
		$sub_Egreso=[];
		$iva_Egreso =[];
		$Iva=[];
		$Iv = [];
                    @endphp
                    @foreach ($colM as $i)
                        @php

                            $emisorRfc = $i->emisorRfc;
                            $arrRfc[] = $emisorRfc;
                            $emisorNombre = $i->emisorNombre;
                            $folioF = $i->folioFiscal;
                            $fechaE = $i->fechaEmision;
                            $efecto = $i->efecto;
                            $total = $i->total;
                            $estado = $i->estado;
                            if ($efecto == 'Egreso') {
                                $total = -1 * abs($total);
			  //$res_total = $total;

                           }
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ ++$n }}</td>
                            {{-- Si el cheque no está verificado se puede desvincular --}}
                            @if ($verificado == 0)
                                <td class="text-center align-middle allcheck">
                                    <div id="checkbox-group" class="checkbox-group">
                                        <input class="mis-checkboxes" tu-attr-precio='{{ $total }}' type="checkbox"
                                            id="allcheck" name="allcheck[]" value="{{ $folioF }}" />
                                    </div>
                                </td>
                            @endif
                            {{-- <td class="text-center align-middle">{{ $emisorRfc }}</td> --}}
                            {{-- <td class="text-center align-middle">{{ $emisorNombre }}</td> --}}
                            <td class="text-center align-middle">{{ $folioF }}</td>
                            <td class="text-center align-middle">{{ $fechaE }}</td>
                            <td class="text-center align-middle">{{ $emisorNombre }}</td>

                            @php
                                $anio = substr($fechaE, 0, 4);
                                $mes = (string) (int) substr($fechaE, 5, 2);
                                // Se asignan las rutas donde está almacenado el archivo
                                $rutaXml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/XML/$folioF.xml";
                                $rutaPdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$mes.$meses[$mes]/Recibidos/PDF/$folioF.pdf";
                                $nUR = 0;
                                $nCon = 0;
                                $totalX = 0;
                                // Se consulta la coincidencia de metadata con el contenido xml para obtener los campos faltantes
                                $colX = XmlR::where(['UUID' => $folioF])->get();

                              $su =0;
                                if (!$colX->isEmpty()) {


                                    foreach ($colX as $v) {

                                        $concepto = $v['Conceptos.Concepto'];
                                        $metodoPago = $v['MetodoPago'];
				$subtotal =$v['SubTotal'];





				//$iva=$v['Impuestos.TotalImpuestosTrasladados'];
				$total = $v['Total'];
				$iva=$v['Impuestos.Traslados.Traslado.0.Importe']; // Imprimir el IVA .16% "002"
                                        $folio = $v['Folio'];




				$var = (float) $v['SubTotal']; //Convertimos los datos de subtotal de string a flotante




				$vIva = (float) $v['Impuestos.Traslados.Traslado.0.Importe']; //Convertimos a flotante los datos extraidos de la base de datos




				$vTotal = (float) $v['Total']; //Convertimos los datos del Total de String a Flotante



                              if($efecto == "Traslado"){

                                   	$t=0;
                                        $egreso=0;
				$sub_Egreso=0;
				$a=0;
				$iva_Egreso =0;
				$Iva =0;
				$Iv = 0;

                                  }else{

  			if ($efecto == 'Ingreso') {

			$t[]=$vTotal; // array completo del Total de los ingresos
			$a [] = $var; //Array completo del subtotal de los ingresos
			$Iv [] = $vIva; //Array completo del Iva de todos los ingresos


			}

  		if ($efecto == 'Egreso') {

		$egreso[]=$vTotal;
		$sub_Egreso[] = $var;
		$iva_Egreso [] = $vIva;



		}

                          }// end else













                                         if ($efecto == 'Pago') {
                                            $docRel = $v['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                                            $metodoPago = '-';

                                            if (!isset($docRel)) {
                                                $docRel = $v['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado.IdDocumento'];
                                            }
                                        } elseif ($efecto == 'Egreso' or $efecto == 'Ingreso') {
                                            $docRel = $v['CfdiRelacionados.CfdiRelacionado'];
                                        }

			}




                                } else {
                                    $concepto = 'X';
                                    $metodoPago = 'X';
                                    $folio = 'X';
                                    $uuidRef = 'X';
                                }




                            @endphp

                            <td class="text-center align-middle">
                                @if (!$colX->isEmpty())
                                    @foreach ($concepto as $c)
                                        {{ ++$nCon }}. {{ $c['Descripcion'] }}<br>
                                    @endforeach
                                @else
                                    {{ $concepto }}
                                @endif









                            </td>
                            <td class="text-center align-middle">{{ $folio }}</td>
                            <td class="text-center align-middle">{{ $metodoPago }}</td>
                            <td class="text-center align-middle">
                                @if (!$colX->isEmpty())
                                    @if ($efecto == 'Pago')
                                        @if (is_array($docRel) || is_object($docRel))
                                            @foreach ($docRel as $d)
                                                {{ ++$nUR }}. {{ $d['IdDocumento'] }}<br>
                                            @endforeach
                                        @else
                                            {{ ++$nUR }}. {{ $docRel }}
                                        @endif
                                    @elseif ($efecto == 'Egreso' and !$docRel == null or $efecto == 'Ingreso' and !$docRel == null)
                                        @foreach ($docRel as $d)
                                            {{ ++$nUR }}. {{ $d['UUID'] }}<br>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                @else
                                    {{ $uuidRef }}
                                @endif
                            </td>


			@if($efecto =='Egreso')
                            <td class="text-center align-middle", style="background-color: rgb(255, 85, 85);">{{ $efecto }}</td>
			@elseif($efecto == 'Ingreso')
			<td class="text-center align-middle">{{ $efecto }}</td>
			@else
			<td class="text-center align-middle">{{ $efecto }}</td>
			@endif

			@if($efecto =='Egreso')
                           <td class="text-center align-middle", style="background-color: rgb(255, 85, 85);">${{ number_format($subtotal,2) }}</td>

			@elseif($efecto = 'Ingreso')
			<td class="text-center align-middle">${{ number_format($subtotal,2) }}</td>

			@else
			<td class="text-center align-middle">${{ number_format($subtotal,2) }}</td>

			@endif




			@if (empty($iva))
                            <td class="text-center align-middle">$ 0.0</td>
                            @elseif ($iva=="0.00")
                            <td class="text-center align-middle">$ 0.0</td>


			@elseif ($efecto =='Egreso')
                            <td class="text-center align-middle" style="background-color: rgb(255, 85, 85);"> ${{ $iva }}</td>
                            @else


                            <td class="text-center align-middle">${{ $iva }}</td>
                            @endif

			@if($efecto =='Egreso')

			<td class="text-center align-middle", style="background-color: rgb(255, 85, 85);">${{ number_format($total, 2) }}</td>
			@elseif($efecto = 'Ingreso')

			 <td class="text-center align-middle">${{ number_format($total, 2) }}</td>
			@else

			 <td class="text-center align-middle">${{ number_format($total, 2) }}</td>
			@endif



                            <td class="text-center align-middle">{{ $estado }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ $rutaXml }}" download="{{ $folioF }}.xml">
                                    <i class="fas fa-file-download fa-2x"></i>
                                </a>
                                <a href="{{ $rutaPdf }}" target="_blank">
                                    <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                </a>
                            </td>
                        </tr>



                    @endforeach
		@php


			$suma_subEgreso = array_sum($sub_Egreso); //Aqu� sumo el arreglo que contiene todos los egresos.

		  $suma_sub=array_sum($a) - $suma_subEgreso; // Aqu� hago la resta de los ingresos menos egresos para as� obtener el total


	            $sumaEgreso= array_sum($egreso); //Sumo el array completo del Total de los egresos
		 $suma_total = array_sum($t)-$sumaEgreso; // Aqu� hago la resta del array total de los egresos menos el array total de los ingresos

		$suma_Iva_Egreso = array_sum($iva_Egreso);
		$suma_iva = array_sum($Iv) - $suma_Iva_Egreso;

		@endphp


		@if ($verificado == 0)



			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center align-middle"><b> Total </b></td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_sub, 2)}}</b> </td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_iva, 2)}}</b> </td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_total, 2)}}</b> </td>
		@else
		<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center align-middle"><b>Total</b></td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_sub, 2)}} </b> </td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_iva, 2)}}</b> </td>
			<td class="text-center align-middle"><b> $ {{number_format($suma_total, 2)}}</b> </td>

		@endif


                </tbody>
            </table>
        </div>
        {{-- Si el cheque no está verificado se puede desvincular --}}
        @if ($verificado == 0)
            <div class="row justify-content-center mt-4">
                <input readonly name="cheques_id" type="hidden" value="{{ $id }}" />
                <input readonly id="total" name="totalXml" type="hidden" value="0" />
                <input id="vinct" type="submit" value="Desvincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </div>
        @endif
    </form>
    {{-- Si el cheque no está verificado permite visualizar el resto de CFDI recibidos pertenecientes a los RFC ya vinculados --}}
    @if ($verificado == 0)
        <div class="row d-flex justify-content-center mt-4">
            <form action="{{ url('detalles') }}">
                <input name='arrRfc' type="hidden" value="{{ json_encode(array_unique($arrRfc)) }}">
                <input type="submit" value="Ir a vincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </form>
        </div>
    @endif
@endsection
