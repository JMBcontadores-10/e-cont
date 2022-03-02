
<div>{{-----------main----------------}}



    @php
use App\Models\XmlR;
use App\Models\Cheques;

$class='';
        if(empty($class)){
           $class="table nowrap dataTable no-footer";

        }
@endphp
<script>
    window.addEventListener('cerrarFacturas', event => {

        $("[data-dismiss=modal]").trigger({ type: "click" });// cerrar modal por data-dismiss.:)

      });

        </script>


   <!-- Modal -->

     <div wire:ignore.self class="modal fade " id="facturasVinculadas{{$datos->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-comments">Facturas Vinculadas</span></h6>
                    <button id="mdlFa" type="button"  class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>



<div class="modal-body"><!--modal body -->
@if ($total==0)
<button disabled class="btn btn-secondary mr-1 mb-1" wire:click="desvincular()">Desvincular Cheques </button>
@else
<button class="btn btn-secondary mr-1 mb-1" wire:click="desvincular()">Desvincular Cheques </button>
@endif
    {{-- &nbsp; {{$total}} --}}
 <div wire:loading wire:target="desvincular" >
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
      <div></div>
      <div></div>

  </div>
  Desvinculando Cheque...
  </div>


  <a  class="btn btn-success shadow mr-1 mb-1" href="{{ url('exportar', ['facturas' => $datos->_id])}}">Exportar a Excel</a>
    <div id="resp-table">
        <div id="resp-table-body">
            <div class="resp-table-row"> {{----- incio row-----}}
                {{-- @if ($datos->verificado == 0)
                <div class=" tr table-body-cell">hi</div>
                 @endif --}}

                <div class=" tr table-body-cell">UUID</div>
                <div class="tr table-body-cell">Fecha Emisión</div>
                <div class="tr table-body-cell">Emisor</div>
                <div class="tr table-body-cell">Concepto</div>
                <div class="tr table-body-cell">Folio</div>
                <div class="tr table-body-cell">Metodo - Pago</div>
                <div class="tr table-body-cell">UUID2 relacionado</div>
                <div class="tr table-body-cell">Efecto</div>
                <div class="tr table-body-cell">subtotal</div>
                <div class="tr table-body-cell">IVA</div>
                <div class="tr table-body-cell">Total</div>
                <div class="tr table-body-cell">Estado</div>
                <div class="tr table-body-cell">Descargar</div>

            </div>{{-------fin row-------}}

            @php
            $arrRfc = [];
$a=[];
$t=[];
$egreso=[];
$sub_Egreso=[];
$iva_Egreso =[];
$Iva=[];
$Iv = [];
$n=0;
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

            <div class="resp-table-row"> {{----- incio row-----}}
                 {{-- Si el cheque no está verificado se puede desvincular --}}
                 {{-- @if ($datos->verificado == 0)
                 <div class="table-body-cell">hi</div>
             @endif
                 --}}

                <div class="table-body-cell">
                     {{-- Si el cheque no está verificado se puede desvincular --}}
                     @if ($datos->verificado == 0)

                         <div id="checkbox-group" class="checkbox-group">
                             <input wire:model="checkedDesvincular" class="mis-checkboxes" tu-attr-precio='{{ $total }}' type="checkbox"
                                 id="allcheck" value="{{ $folioF }}" />
                         </div>

                 @endif

                    {{ $folioF }}</div>
                <div class="table-body-cell">{{ $fechaE }}</div>
                <div class="table-body-cell">{{ $emisorNombre }}</div>

                @php
                $dateValue = strtotime($datos->fecha);
                // $anio = date('Y',$dateValue);
                $mes=date('m',$dateValue);
                $fecha=date('Y-m-d');
                $rfc=$datos->rfc;

               $espa=new Cheques();

               $numero = (string) (int) substr($fechaE, 5, 2);
               $mesNombre = (string) (int) substr($fechaE, 5, 2);
               $anio = (string) (int) substr($fechaE, 0, 4);
               $mees=$espa->fecha_es($mesNombre);


               // Se asignan las rutas donde está almacenado el archivo
               $rutaXml = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$numero.$mees/Recibidos/XML/$folioF.xml";
               $rutaPdf = "storage/contarappv1_descargas/$rfc/$anio/Descargas/$numero.$mees/Recibidos/PDF/$folioF.pdf";
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

                       }elseif ($efecto == 'Egreso' or $efecto == 'Ingreso') {
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

<div class="table-body-cell">
               @if (!$colX->isEmpty())
                 @foreach ($concepto as $c)
                    {{ ++$nCon }}.{{Str::limit($c['Descripcion'],25); }} <br>

                @endforeach
                {{-- {{Str::limit($concepto[0]['Descripcion'],25); }} --}}

               @else
                 {{ Str::limit( $concepto,25);}}
               @endif

</div>

             <div class="table-body-cell">{{ $folio }}</div>
             <div class="table-body-cell">{{ $metodoPago }}</div>
             <div class="table-body-cell">  @if (!$colX->isEmpty())
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
        </div>

        @if($efecto =='Egreso')

        <div class="table-body-cell" style="color: rgb(255, 85, 85);">{{ $efecto }}</div>
			@elseif($efecto = 'Ingreso')
            <div class="table-body-cell">{{ $efecto }}</div>
			@else
            <div class="table-body-cell">{{ $efecto }}</div>
			@endif

			@if($efecto =='Egreso')
            <div class="table-body-cell" style="color: rgb(255, 85, 85);">${{ number_format($subtotal,2) }}</div>

			@elseif($efecto = 'Ingreso')

            <div class="table-body-cell">${{ number_format($subtotal,2) }}</div>

			@else
            <div class="table-body-cell">${{ number_format($subtotal,2) }}</div>

			@endif

            @if (empty($iva))
            <div class="table-body-cell">$ 0.0</div>
                            @elseif ($iva=="0.00")
                            <div class="table-body-cell">$ 0.0</div>


			@elseif ($efecto =='Egreso')
            <div class="table-body-cell" style="color: rgb(255, 85, 85);"> ${{ $iva }}</div>
                            @else


                            <div class="table-body-cell">${{ $iva }}</div>
                            @endif

			@if($efecto =='Egreso')

			<div class="table-body-cell" style="color: rgb(255, 85, 85);">${{ number_format($total, 2) }}</div>
			@elseif($efecto = 'Ingreso')

            <div class="table-body-cell">${{ number_format($total, 2) }}</div>
			@else

            <div class="table-body-cell">${{ number_format($total, 2) }}</div>
			@endif
            <div class="table-body-cell">{{ $estado }}</div>
                <div class="table-body-cell">
                <a href="{{ $rutaXml }}" download="{{ $folioF }}.xml">
                    <i class="fas fa-file-download fa-2x"></i>
                </a>
                <a href="{{ $rutaPdf }}" target="_blank">
                    <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                </a>
            </div>


            </div>{{-------fin row-------}}
@endforeach
@php


			$suma_subEgreso = array_sum($sub_Egreso); //Aqu� sumo el arreglo que contiene todos los egresos.

		  $suma_sub=array_sum($a) - $suma_subEgreso; // Aqu� hago la resta de los ingresos menos egresos para as� obtener el total


	            $sumaEgreso= array_sum($egreso); //Sumo el array completo del Total de los egresos
		 $suma_total = array_sum($t)-$sumaEgreso; // Aqu� hago la resta del array total de los egresos menos el array total de los ingresos

		$suma_Iva_Egreso = array_sum($iva_Egreso);
		$suma_iva = array_sum($Iv) - $suma_Iva_Egreso;

		@endphp


		@if ($datos->verificado == 0)



        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>




        <div class="table-body-cell"><b> Total </b></div>
			<div class="table-body-cell"><b> $ {{number_format($suma_sub, 2)}}</b> </div>
                <div class="table-body-cell"><b> $ {{number_format($suma_iva, 2)}}</b> </div>
                    <div class="table-body-cell"><b> $ {{number_format($suma_total, 2)}}</b> </div>
		@else

        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>
        <div class="table-body-cell"></div>

        <div class="table-body-cell"><b>Total</b></div>
			<div class="table-body-cell"><b> $ {{number_format($suma_sub, 2)}} </b> </div>
                <div class="table-body-cell"><b> $ {{number_format($suma_iva, 2)}}</b> </div>
                    <div class="table-body-cell"><b> $ {{number_format($suma_total, 2)}}</b> </div>

		@endif



        </div>
        {{-- Si el cheque no está verificado se puede desvincular --}}
        {{-- @if ($datos->verificado == 0)
            <div class="row justify-content-center mt-4">
                <input readonly name="cheques_id" type="hidden" value="{{ $datos->_id }}" />
                <input readonly id="total" name="totalXml" type="hidden" value="0" />
                <input id="vinct" type="submit" value="Desvincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </div>
        @endif
    </form> --}}
    {{-- Si el cheque no está verificado permite visualizar el resto de CFDI recibidos pertenecientes a los RFC ya vinculados --}}
    {{-- @if ($datos->verificado == 0)
        <div class="row d-flex justify-content-center mt-4">
            <form action="{{ url('detalles') }}">
                <input name='arrRfc' type="hidden" value="{{ json_encode(array_unique($arrRfc)) }}">
                <input type="submit" value="Ir a vincular Cheque/Transferencia"
                    style="color:#0055ff; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #FFFFFF">
            </form>
        </div>
    @endif --}}






        </div>
    </div>









        </div><!--end modal body -->
    </div>
  </div>
</div>






</div>{{-----------main----------------}}

