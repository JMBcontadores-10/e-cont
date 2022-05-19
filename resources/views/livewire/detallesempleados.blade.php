<div>


    @php
use App\Models\XmlR;
use App\Models\MetadataR;
use App\Models\Cheques;
use App\Models\Notificaciones;

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
                    <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-user-group">Empleados</span></h6>
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
                <div class="tr table-body-cell">Prestamo <br>Infonavit(FD)</div>
                <div class="tr table-body-cell">Prestamo <br>Infonavit(CF)</div>
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

        @endphp
        @foreach ($colM as $i)


        <div class="resp-table-row"> {{----- incio row-----}}
            <div class="table-body-cell"> {{++$n}}</div>
           <div class="table-body-cell"> {{$i['Receptor.Nombre']}}</div>
           <div class="table-body-cell"> {{$i['Receptor.Rfc']}}</div>
           <div class="table-body-cell"> {{$i['Complemento.0.Nomina.Percepciones.Percepcion.0.ImporteGravado']}}</div>
           <div class="table-body-cell"> {{$i['Complemento.0.Nomina.TotalPercepciones']}}</div>
           <div class="table-body-cell"> FD</div>
           <div class="table-body-cell"> CF</div>
           <div class="table-body-cell"> {{$i['Complemento.0.Nomina.Deducciones.Deduccion.1.Importe']}}</div>
           <div class="table-body-cell"> {{$i['Complemento.0.Nomina.TotalDeducciones']}}</div>
           <div class="table-body-cell"> {{$i['Total']}}</div>
           <div class="table-body-cell"> {{$i['UUID']}}</div>

        </div>{{-----  fin table row-----}}


           @endforeach



    </div>
</div>









        </div><!--end modal body -->
    </div>
  </div>
</div>











</div>
