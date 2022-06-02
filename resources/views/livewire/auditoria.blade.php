<div>{{----------------------DIV PRINCIPAL-------------}}
     <script src="{{ asset('js/auditoria.js') }}" defer></script>
     @php
         use App\Models\XmlR;
     @endphp

         <!-- TableExport js --->
    {{------Referencias: https://github.com/hhurz/tableExport.jquery.plugin
                         https://examples.bootstrap-table.com/#extensions/export.html
    ---------------}}

    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    @php

        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }

        $date=date('Y-m-d');
    @endphp


     {{-- Contenedor --}}
     <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">



        {{-- Creacion del modal --}}
        <div wire:ignore.self class="modal fade " id="listacdfi" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
            <div class="modal-content ">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="TitleRevisado"><span style="text-decoration: none;"
                            class="icons far fa-check-circle">Cfdi´s faltantes por descargar</span></h6>
                    <button id="BtnCloseRevi" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="example" class="{{ $class }}" style="width:100%">
                            {{-- Encabezado --}}
                            <thead>
                                <tr>
                                    <th>
                                        <span class="align-middle">UUID </span>
                                    </th>
                                    <th>Fecha emisión</th>
                                </tr>
                            </thead>

                    @empty(!$list)

                    @foreach ($list as $me2)
                    @php

                        if ($tipoer == 'Emitidas') {
                            $colM = DB::table('metadata_e')
                                ->select('estado')
                                ->where('folioFiscal', strtoupper($me2->uuid()))
                                ->first();
                        } else {
                            $colM = DB::table('metadata_r')
                                ->where('folioFiscal',  strtoupper($me2->uuid()))
                                ->first();
                        }
                        if (isset($colM)) {
                            $estadoM = $colM['estado'];
                        } else {
                            $estadoM = 'X';
                        }
                    @endphp
                     <tbody>
                    @if ($estadoM == 'X' )
                    @php $contador++; @endphp
                        <tr>

                            <td class="text-center align-middle">{{ strtoupper($me2->uuid()) }}</td>
                            <td class="text-center align-middle">{{ $me2->get('fechaEmision')}}</td>
                        </tr>
                    @endif
                     </tbody>

                @endforeach

                @endempty

            </table>
         </div>

                </div>
            </div>
        </div>
    </div>


                    {{--seccion de  Filtros --}}



{{-- Filtros de busqueda --}}

  {{-- Condicional para mostrar un listado de empresas --}}
  @empty(!$empresas)
  <label for="inputState">Empresa: {{ $empresa }}</label>
  <select wire:loading.attr="disabled" wire:model="rfcEmpresa" id="inputState1"
      class=" select form-control">
      <option value="">--Selecciona Empresa--</option>
      <?php $rfc = 0;
      $rS = 1;
      foreach ($empresas as $fila) {
          echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
      } ?>
  </select>

  <br>
@endempty

  <div class="form-inline mr-auto">



<form  wire:submit.prevent="consultar" >
    @csrf

    <select class="form-control" id="searchTerm" onchange="doSearch()">
        <option  value="" >--Filtra Estado--</option>
        <option value="cancelado" >Canceladas</option>
    </select>





  <select required class="form-control" wire:model.defer="tipoer" id="inputState1" wire:loading.attr="disabled"
   >
   <option  value="" >--Selecciona tipo--</option>
    <option value="Emitidas" >Emitidas</option>
    <option  value="Recibidas">Recibidas</option>
  </select>
&nbsp;&nbsp;

           {{-- Busqueda por mes --}}
        <input required class="form-control" type="date" min="2014-01-01"
        max={{ $date }} wire:model.defer="fecha_ini"  >
        &nbsp; A
        &nbsp;

    <input required class="form-control" type="date" min="2014-01-01"
    max={{ $date }} wire:model.defer="fecha_fin" />
    <button  type="submit"  class="btn btn-primary">Consultar</button>

</form>
&nbsp;&nbsp;



<a {{ $active }}  data-toggle="modal" data-backdrop="static"
data-keyboard="false" data-target="#listacdfi"    class="btn btn-info notification">
    <span>Cfdi´s faltantes</span>
    <span class="badge">{{$contador}}</span>
  </a>




  </div>
  <br>

  <div {{ $active }}  class="btn-group btn-group-sm" role="group" aria-label="Basic example">
    <button type="button" class="btn btn-success" onclick="exportReportToExcel('{{$empresa}}')">Excel</button>
    <button type="button" class="btn btn-danger"  onclick="exportReportToPdf('{{$empresa}}')">Pdf</button>

  </div>

<style>
 .notification {



  position: relative;
  display: inline-block;
  border-radius: 2px;
}


.notification .badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 5px 10px;
  border-radius: 30%;
  background: #609ED5;
  color: white;
}
</style>
          {{-- Busqueda por texto-}}


  </div>

                    </div>
                    {{-- fin seccion de  Filtros --}}
                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    {{-- Boton de filtrado --}}
                    <div class="action-dropdown-btn d-none">
                        <div class="dropdown invoice-filter-action">
                            <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter Invoice
                            </button>
                        </div>
                    </div>

                    {{-- {{var_dump($list)}} --}}
                    {{-- Tabla de contenido --}}
                    <div class="table-responsive">
                        <table id="datos" class="{{ $class }}" style="width:100%">
                            {{-- Encabezado --}}
                            <thead>
                                <tr>
                                    <th>
                                        <span class="align-middle">UUID </span>
                                    </th>
                                    <th>Fecha emisión</th>

                                    <th>Cancelación</th>
                                    <th>Estado <br> Sat Anterior</th>
                                    <th>Estado <br> Sat Actual</th>
                                    <th>Estado <br> Cambiado</th>
                                    <th>Cheque  <br>vinculado</th>

                                </tr>
                            </thead>

                        {{---------  INCIO DEL FOR   -----}}

                            @empty(!$list)
                            <tbody>




                        @foreach ($list as $cfdi)
                        @php
                                  $UUID=strtoupper($cfdi->uuid());

                                    if ($tipoer == 'Emitidas') {
                                        $colM = DB::table('metadata_e')
                                            ->select('estado')
                                            ->where('folioFiscal',$UUID)
                                            ->first();
                                    } else {
                                        $colM = DB::table('metadata_r')
                                            ->where('folioFiscal', $UUID)
                                            ->first();
                                    }
                                    if (isset($colM)) {
                                        $estadoM = $colM['estado'];

                                    } else {
                                        $estadoM = 'X';
                                    }
                         @endphp






                          @if ( $estadoM != 'X'  )
                          <tr>


                              <td class="text-center align-middle">{{strtoupper($cfdi->uuid()) }}</td>
                              <td class="text-center align-middle">{{ date('d-m-Y',strtotime($cfdi->get('fechaEmision')))  }}</td>

                              <td class="text-center align-middle">{{ $cfdi->get('fechaProcesoCancelacion') }}</td>
                              <td class="text-center align-middle">{{ $estadoM }}</td>
                              <td class="text-center align-middle">{{ $cfdi->get('estadoComprobante') }}</td>
                              <td class="text-center align-middle">
                                  @if ($cfdi->get('estadoComprobante') == $estadoM)
                                      <i class="far fa-check-circle fa-2x" style="color: green"></i>
                                  @else
                                      <i class="far fa-times-circle fa-2x" style="color: red"></i>
                                  @endif
                              </td>
                              <td class="text-center align-middle">
                                  @if (!empty($colM['cheques_id']))
                                        @if (is_array($colM['cheques_id']))
                                      @foreach ($colM['cheques_id'] as $ids )

                                      {{$ids}}<br>

                                      @endforeach

                                      @else
                                     {{$colM['cheques_id']}}

                                     @endif
                                 @else

                                     -
                                  @endif

                              </td>


                          </tr>



                        @endif

                             @endforeach
                            </tbody>

                             @endempty
                       {{---------  FIN  DEL FOR   -----}}
                        </table>

                    </div>

                </section>








            </div>
        </div>
    </div>





</div>{{----------------------DIV PRINCIPAL-------------}}
