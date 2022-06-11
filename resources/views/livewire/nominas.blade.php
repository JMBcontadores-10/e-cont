<div> {{-----din principal-----}}


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
   use App\Models\XmlE;
   use App\Models\MetadataE;
   use App\Models\Cheques;

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

  {{-- Filtros de busqueda --}}
  <div class="form-inline mr-auto">


 {{-- Busqueda por año --}}
 <label for="inputState">Año</label>
 <select wire:loading.attr="disabled" wire:model="anio" id="inputState2"
     class="select form-control">
     <?php foreach (array_reverse($anios) as $value) {
         echo '<option value="' . $value . '">' . $value . '</option>';
     } ?>
 </select>
 &nbsp;&nbsp;
{{-- Busqueda por mes --}}
  <label for="inputState">Mes</label>
  <select wire:model="mes" id="inputState1" wire:loading.attr="disabled"
      class=" select form-control">
      <option value="00">--Selecciona--</option>
      <?php foreach ($meses as $key => $value) {
          echo '<option value="' . $key . '">' . $value . '</option>';
      } ?>
  </select>
  &nbsp;&nbsp;


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
                                       <span class="align-middle">#Periodo</span>
                                   </th>
                                   <th>Periodo</th>
                                   <th>Fecha Pago   </th>
                                   <th>Raya</th>
                                   <th>Recibos de <br> Nómina </th>
                                   <th>Detalles</th>
                                   <th>Total pago</th>
                                   <th>ISR </th>
                                   <th>Asignar cheque</th>

                               </tr>
                           </thead>
                       {{---------  INCIO DEL FOR   -----}}

                           <tbody>
                           @foreach ($nominas as  $nom)
                                     @php

                 $metadata=MetadataE::
                  where('emisorRfc',$this->rfcEmpresa)
                ->where('estado','!=','Cancelado')
                ->where('efecto','Nómina')
                ->select('folioFiscal','cheques_id')
                ->project(['_id' => 0])
                ->get();

               foreach($metadata as $m){$cont[]=$m->folioFiscal;}


                $TotalPagado=DB::Table('xmlemitidos')
                ->whereIn('UUID',$cont)
                 ->where('Emisor.Rfc',$this->rfcEmpresa)
                 ->where('TipoDeComprobante','N')
                 ->where('Serie', $this->anio)
                ->where('Folio',$nom['Folio'])
                // ->select('Fecha','Complemento','Total')
                ->get()->sum('Total');


                $id=MetadataE::
                  where('emisorRfc',$this->rfcEmpresa)
                ->where('estado','!=','Cancelado')
                ->where('efecto','Nómina')
                ->whereNotNull('cheques_id')
                // ->project(['_id' => 0])
                ->get();



                foreach($id as $i){$ids[]=$i->folioFiscal;}

                    if(isset($ids)){
                $idd=XmlE::
                whereIn('UUID',$ids)
                 ->where('Emisor.Rfc',$this->rfcEmpresa)
                 ->where('TipoDeComprobante','N')
                 ->where('Serie', $this->anio)
                ->where('Folio',$nom['Folio'])
                // ->select('Fecha','Complemento','Total')
                ->get();
                  echo count($idd);

                foreach($idd as $c){
                $uuids[]=  $c['UUID']; }


                $metas=MetadataE::
                  where('folioFiscal',$uuids[0])

                ->first();
                 echo $metas['_id'];
                 echo $metas['folioFiscal'];



                }

                ##############################################
                $ISR=XmlE::
                whereIn('UUID',$cont)
                ->where('Emisor.Rfc',$this->rfcEmpresa)
                ->where('TipoDeComprobante','N')
                 ->where('Serie', $this->anio)
                ->where('Folio',$nom['Folio'])
                // ->select('Fecha','Complemento','Total')
                ->get()->sum('Complemento.0.Nomina.Deducciones.Deduccion.1.Importe');



                if (isset($nom['Complemento.0.Nomina.FechaFinalPago'])){
                $dateValue = strtotime($nom['Complemento.0.Nomina.FechaPago']);//obtener la fecha
                $mes = date('m',$dateValue);// obtener el mes
                $anio= date('Y',$dateValue);// obtener el año
                }else{

                $dateValue = strtotime($nom['Complemento.Nomina.FechaPago']);//obtener la fecha
                $mes = date('m',$dateValue);// obtener el mes
                $anio= date('Y',$dateValue);// obtener el año

                }

                                     @endphp

                            <tr>
                         <td class="text-center align-middle">
                             @if ($nom['Complemento.0.Nomina.TipoNomina'] == "E")
                             <a class="icon_basic">E</a>
                             @endif
                              {{$nom['Folio']}} </td>
                                     @if (isset($nom['Complemento.0.Nomina.FechaInicialPago']))
                                     @php  $fechaPago=$nom['Complemento.0.Nomina.FechaPago']  ;    @endphp
                        <td class="text-center align-middle">{{$nom['Complemento.0.Nomina.FechaInicialPago']}} al {{$nom['Complemento.0.Nomina.FechaFinalPago']}} </td>
                        <td class="text-center align-middle">{{$nom['Complemento.0.Nomina.FechaPago']}} </td>
                                     @else
                                     @php  $fechaPago=$nom['Complemento.Nomina.FechaPago']  ;    @endphp
                       <td class="text-center align-middle">{{$nom['Complemento.Nomina.FechaInicialPago']}} al {{$nom['Complemento.Nomina.FechaFinalPago']}} </td>
                       <td class="text-center align-middle">{{$nom['Complemento.Nomina.FechaPago']}} </td>
                       @endif
                          @php
                    /// validacion de existencia de arcivo para activar el icono con contenido/////
                    $ruta = "contarappv1_descargas/" .$this->rfcEmpresa. "/". $anio."/Nomina/Periodo".$nom['Folio'] . "/Raya/NominaPeriodo".$nom['Folio'].".pdf";
                    $rutaR = "contarappv1_descargas/" .$this->rfcEmpresa. "/". $anio."/Nomina/Periodo".$nom['Folio'] . "/RecibosNomina/RecibosPeriodo".$nom['Folio'].".pdf";

                       if(Storage::disk('public2')->exists($ruta)){
                     $clas="content_true";

                       }else{

                        $clas="icons";
                       }

                       if(Storage::disk('public2')->exists($rutaR)){
                     $clasR="content_true";

                       }else{

                        $clasR="icons";
                       }

                       @endphp


                         <td class="text-center align-middle">

                            <a  wire:loading.attr="hidden" class="{{ $clas }} fas fa-clipboard-list"
                            onclick="filepondRaya('{{$this->rfcEmpresa}}','{{$anio}}','{{$nom['Folio']}}')"
                             data-toggle="modal"data-backdrop="static"
                            data-target="#raya{{ $nom['Folio'] }}{{$fechaPago}}"></a>
                        </td>
                         <td class="text-center align-middle">

                            <a wire:loading.attr="hidden" class="{{$clasR}}  fas fa-file-invoice"
                            onclick="filepondRecibosNomina('{{$this->rfcEmpresa}}','{{$anio}}','{{$nom['Folio']}}')"
                             data-toggle="modal"data-backdrop="static"
                            data-target="#recibosnom{{ $nom['Folio'] }}{{$fechaPago}}"></a>

                        </td>
                         <td class="text-center align-middle">
                             <a wire:loading.attr="hidden" data-toggle="modal" data-controls-modal="#detallesEmpleados{{ $nom['Folio'] }}" data-backdrop="static"
                             data-keyboard="false" data-target="#detallesEmpleados{{ $nom['Folio'] }}"   class=" icons fas fa-eye"></a></td>
                         <td class="text-center align-middle">${{$TotalPagado}}</td>
                         <td class="text-center align-middle">${{$ISR}}</td>
                         <td class="text-center align-middle">


                            @if(isset($ids))

                            @if (count($idd)>0)
                         @php $clascheque="content_true" ;    @endphp
                         @else
                         @php $clascheque="icons" ;    @endphp
                            @endif

                            @php
                             $cheques= MetadataE::
            where('folioFiscal',$metas['folioFiscal'])
            ->first();

            foreach($cheques['cheques_id'] as $c){
               $ids[]=  $c; }

               $saldo=Cheques::
                whereIn('_id',$ids)
                //->select('Fecha','Complemento','Total')
                ->get()->sum('saldo');

               $TotalchequesAsociados=Cheques::
                whereIn('_id',$ids)
                //->select('Fecha','Complemento','Total')
                ->get()->sum('importecheque');
                $TotalchequesAsociados=$TotalchequesAsociados-$saldo;

                            // $TotalPagado

                 $granTotal = $TotalPagado-$TotalchequesAsociados;


                            @endphp

                        @if ($clascheque== "content_true" && $granTotal==0)

                        <a  wire:loading.attr="hidden"
                        wire:click="$emitTo('asignar-cheque','refresAsignar')"
                            {{-- wire:click="cheque('{{$metas['folioFiscal']}}')" --}}
                            name="14" id="{{$nom['Folio']}}"data-toggle="modal"data-controls-modal="#asingnarCheque"
                            class="{{$clascheque}} fas fa-money-check" data-target="#asignarCheque{{ $nom['Folio'] }}" >
                        </a>
                        @elseif($clascheque== "content_true" && $granTotal>0)
                        <a  wire:loading.attr="hidden" data-toggle="modal"
                            data-controls-modal="#asingnarCheque"
                            name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                            data-keyboard="false"
                            wire:click="$emitTo('asignar-cheque','refresAsignar')"
                            data-target="#asignarCheque{{ $nom['Folio'] }}" style="color:orange" class="icons fas fa-money-check">
                        </a>
                        @else
                        <a  wire:loading.attr="hidden" data-toggle="modal"
                            data-controls-modal="#asingnarCheque"
                            name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                            data-keyboard="false"
                            wire:click="$emitTo('asignar-cheque','refresAsignar')"
                            data-target="#asignarCheque{{ $nom['Folio'] }}" class="icons fas fa-money-check">
                        </a>

                        @endif

                        @else
                        <a  wire:loading.attr="hidden" data-toggle="modal"
                        data-controls-modal="#asingnarCheque"
                        name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                        data-keyboard="false"
                        wire:click="$emitTo('asignar-cheque','refresAsignar')"
                        data-target="#asignarCheque{{ $nom['Folio'] }}" class="icons fas fa-money-check">
                    </a>

                        @endif {{-----end if isset--}}


                       {{$TotalPagado}}
                        </td>

                            </tr>
                            {{-- <livewire:lista-raya :raya="$nom" :wire:key="'user-profile-one-'.$nom['Folio']"> --}}
                            @livewire('lista-raya', ['folio' => $nom['Folio'], 'RFC' =>$this->rfcEmpresa, 'fecha'=> $fechaPago ,'ruta'=>$ruta], key('user-profile-one-'.$nom['Folio'].$fechaPago))
                            @livewire('recibosnomina',['folio' => $nom['Folio'], 'RFC' =>$this->rfcEmpresa,'fecha'=> $fechaPago], key('user-profile-twoo-'.$nom['Folio'].$fechaPago))

                            @if(isset($idd)&&count($idd)!=0)
                            @livewire('asignar-cheque',['fecha'=>$nom['Complemento.0.Nomina.FechaFinalPago'],'asignarCheque' => $nom['Folio'],'RFC' =>$this->rfcEmpresa,'content'=> $clascheque, 'granTotal'=>$granTotal,'folioFiscal'=>$metas['folioFiscal']], key('user-profile-three-'.$nom['Folio'].$fechaPago))
                             @else
                          @livewire('asignar-cheque',['fecha'=>$nom['Complemento.0.Nomina.FechaFinalPago'],'asignarCheque' => $nom['Folio'],'RFC' =>$this->rfcEmpresa,'content'=> 'icons','TotalPagado'=>$TotalPagado], key('user-profile-three-'.$nom['Folio'].$fechaPago))
                            @endif

                            @livewire('detallesempleados',['anio'=>$anio,'fecha'=>$fechaPago,'folio' => $nom['Folio'],'RFC' =>$this->rfcEmpresa], key('user-profile-four-'.$nom['Folio'].$fechaPago))
                            @endforeach

                           </tbody>

                      {{---------  FIN  DEL FOR   -----}}

                       </table>

                   </div>

               </section>

               <livewire:agregarcheque>
                <livewire:vincular-pagos-automatico>
                    <livewire:uploadrelacionados>
           </div>
       </div>
   </div>




</div>{{-------fin div principal-------}}

