<div>


    @php
use App\Models\XmlR;
use App\Models\MetadataR;
use App\Models\Cheques;
use App\Models\Notificaciones;
use App\Http\Livewire\DetallesEmpleados;

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

     <div wire:ignore.self class="modal fade " id="detallesEmpleados{{$folio}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons  fas fa-user"> &nbsp;Empleados</span></h6>
                    <button id="mdlFa" type="button"  class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>



<div class="modal-body" style="color:#545252;"><!--modal body -->


    {{-- &nbsp; {{$total}} --}}
 <div wire:loading wire:target="desvincular" >
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
      <div></div>
      <div></div>

  </div>
  Desvinculando Cheque...
  </div>


    <div id="resp-table">
        <div id="resp-table-body">
            <div class="resp-table-row"> {{----- incio row-----}}
                {{-- @if ($datos->verificado == 0)
                <div class=" tr table-body-cell">hi</div>
                 @endif --}}

                <div class=" tr table-body-cell">No</div>
                <div class="tr table-body-cell">Empleado</div>
                <div class="tr table-body-cell">RFC</div>
                <div class="tr table-body-cell">Sueldo</div>
                <div class="tr table-body-cell">Total <br>Percepciones</div>
                <div class="tr table-body-cell">Préstamo <br>Infonavit</div>

                <div class="tr table-body-cell">ISR</div>
                <div class="tr table-body-cell">Total deducciones</div>
                <div class="tr table-body-cell">Total Neto</div>
                <div class="tr table-body-cell">UUID</div>

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
$funcion =new DetallesEmpleados;
$funcion2 =new DetallesEmpleados;
$sumaSueldo=0;
$sumTotalpercepciones=0;
$sumaPrestamoInfonavit=0;
$sumaIsr=0;
$sumTotalDeducciones=0;
$sumTotalNeto=0;
        @endphp
        @foreach ($colM as $i)


@php

    //    $numero = (string) (int) substr($i['Complemento.0.Nomina.FechaFinalPago'], 5, 2);
    //    $dateValue = strtotime($i['Complemento.0.Nomina.FechaFinalPago']);//obtener la fecha
    $numero = (string) (int) substr($i['Fecha'], 5, 2);
    $dateValue = strtotime($i['Fecha']);//obtener la fecha
        $mesfPago = date('m',$dateValue);// obtener el mes
        $espa=new Cheques();// se crea objeto para obtener la funcion meses español en modelo cheques
        $mes=$espa->fecha_es($mesfPago);// se obtiene mes y se convierte en español



    $rutaXml = "storage/contarappv1_descargas/".$i['Emisor.Rfc']."/".$anio."/Descargas/".$numero.".".$mes."/Emitidos/XML/".$i['UUID'].".xml";
    $rutaPdf = "storage/contarappv1_descargas/".$i['Emisor.Rfc']."/".$anio."/Descargas/".$numero.".".$mes."/Emitidos/PDF/".$i['UUID'].".pdf";
         @endphp
        <div class="resp-table-row"> {{----- incio row-----}}
            <div class="table-body-cell"> {{++$n}}</div>
           <div class="table-body-cell"> {{$i['Receptor.Nombre']}}</div>
           <div class="table-body-cell"> {{$i['Receptor.Rfc']}}</div>
           <div class="table-body-cell">
               @php $sumaSueldo+= $i['Complemento.0.Nomina.Percepciones.Percepcion.0.ImporteGravado'];  @endphp
            {{ number_format($i['Complemento.0.Nomina.Percepciones.Percepcion.0.ImporteGravado'],2)}}</div>
           <div class="table-body-cell">
               @php $sumTotalpercepciones+=$i['Complemento.0.Nomina.TotalPercepciones'];  @endphp
            {{ number_format($i['Complemento.0.Nomina.TotalPercepciones'],2)}}</div>
           <div class="table-body-cell">
                @php
                if(is_numeric($funcion-> deducciones("FD",$i['UUID']))){
                $sumaPrestamoInfonavit+=$funcion-> deducciones("FD",$i['UUID']);
                }else{  }

                    echo    $funcion-> deducciones("FD",$i['UUID']); @endphp
                    </div>
           <div class="table-body-cell">@php
                if(is_numeric($funcion->deducciones("ISR",$i['UUID'],$i['Complemento.0.Nomina.TipoNomina']))){
                    $sumaIsr+= $funcion->deducciones("ISR",$i['UUID'],$i['Complemento.0.Nomina.TipoNomina']);
                }else{  }
           echo    $funcion->deducciones("ISR",$i['UUID'],$i['Complemento.0.Nomina.TipoNomina']); @endphp</div>
           <div class="table-body-cell">
            @php $sumTotalDeducciones+=$i['Complemento.0.Nomina.TotalDeducciones'];  @endphp
            {{ number_format($i['Complemento.0.Nomina.TotalDeducciones'],2)}}</div>
           <div class="table-body-cell">
            @php $sumTotalNeto+=$i['Total'];  @endphp
                {{ number_format($i['Total'],2)}}</div>
           <div class="table-body-cell"> {{$i['UUID']}}</div>
           <div class="table-body-cell">
            <a href="{{ $rutaXml }}" download="{{ $i['UUID'] }}.xml">
                <i class="fas fa-file-download fa-2x"></i>
            </a>
            <a href="{{ $rutaPdf }}" target="_blank">
                <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
            </a>
        </div>

     </div>{{-----  fin table row-----}}


           @endforeach

           <div class="table-body-cell"> </div>
           <div class="table-body-cell"> </div>
           <div class="table-body-cell"><strong>Total </strong> </div>
           <div class="table-body-cell"><strong>${{$sumaSueldo}}</strong> </div>
           <div class="table-body-cell"><strong>${{$sumTotalpercepciones}}</strong> </div>
           <div class="table-body-cell"><strong>${{$sumaPrestamoInfonavit}}</strong> </div>

           <div class="table-body-cell"><strong>${{ $sumaIsr}}</strong></div>
           <div class="table-body-cell"><strong>${{$sumTotalDeducciones}}</strong> </div>
           <div class="table-body-cell"><strong>${{$sumTotalNeto}}</strong> </div>
           <div class="table-body-cell"> </div>
           <div class="table-body-cell">

        </div>

    </div>
</div>









        </div><!--end modal body -->
    </div>
  </div>
</div>











</div>
