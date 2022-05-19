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


 {{-- Busqueda por año --}}
 <label for="inputState">Año</label>
 <select wire:loading.attr="disabled" wire:model="anio" id="inputState2"
     class="select form-control">
     <?php foreach (array_reverse($anios) as $value) {
         echo '<option value="' . $value . '">' . $value . '</option>';
     } ?>
 </select>
 &nbsp;&nbsp;


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
                // $TotalPagado=DB::Table('xmlemitidos')
                //  -> where('Emisor.Rfc',$this->rfcEmpresa)
                //  ->where('TipoDeComprobante','N')
                //  ->where('Serie', $this->anio)
                // ->where('Folio',$nom['Folio'])
                // // ->select('Fecha','Complemento','Total')
                // ->get();
                ##############################################
                // $ISR=XmlE::
                // where('Emisor.Rfc',$this->rfcEmpresa)
                // ->where('TipoDeComprobante','N')
                //  ->where('Serie', $this->anio)
                // ->where('Folio',$nom['Folio'])
                // // ->select('Fecha','Complemento','Total')
                // ->get()->sum('Complemento.0.Nomina.Deducciones.Deduccion.1.Importe');



                if (isset($nom['Complemento.0.Nomina.FechaFinalPago'])){
                $dateValue = strtotime($nom['Complemento.0.Nomina.FechaFinalPago']);//obtener la fecha
                $mes = date('m',$dateValue);// obtener el mes
                $anio= date('Y',$dateValue);// obtener el año
                }else{

                $dateValue = strtotime($nom['Complemento.Nomina.FechaFinalPago']);//obtener la fecha
                $mes = date('m',$dateValue);// obtener el mes
                $anio= date('Y',$dateValue);// obtener el año

                }

                                     @endphp

                            <tr>
                         <td class="text-center align-middle">{{$nom['Folio']}} </td>
                                      @if (isset($nom['Complemento.0.Nomina.FechaInicialPago']))
                        <td class="text-center align-middle">{{$nom['Complemento.0.Nomina.FechaInicialPago']}} al {{$nom['Complemento.0.Nomina.FechaFinalPago']}} </td>
                        <td class="text-center align-middle">{{$nom['Complemento.0.Nomina.FechaPago']}} </td>
                                     @else
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
                             <i data-toggle="modal"
                            data-controls-modal="#raya"
                            name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                            data-keyboard="false" onclick="filepondRaya('{{$this->rfcEmpresa}}','{{$anio}}','{{$nom['Folio']}}')"
                            data-target="#raya{{ $nom['Folio'] }}" class="{{$clas}} fas fa-clipboard-list"></i>
                        </td>
                         <td class="text-center align-middle">
                            <i data-toggle="modal"
                            data-controls-modal="#recibosnom"
                            name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                            data-keyboard="false" onclick="filepondRecibosNomina('{{$this->rfcEmpresa}}','{{$anio}}','{{$nom['Folio']}}')"
                            data-target="#recibosnom{{ $nom['Folio'] }}" class="{{$clasR}} fas fa-file-invoice"></i>

                        </td>
                         <td class="text-center align-middle">
                             <i  data-toggle="modal" data-controls-modal="#detallesEmpleados{{ $nom['Folio'] }}" data-backdrop="static"
                             data-keyboard="false" data-target="#detallesEmpleados{{ $nom['Folio'] }}"   class=" icons fas fa-eye"></i></td>
                         <td class="text-center align-middle"> </td>
                         <td class="text-center align-middle"></td>
                         <td class="text-center align-middle">
                            <i  data-toggle="modal"
                            data-controls-modal="#asingnarCheque"
                            name="14" id="{{$nom['Folio']}}" data-backdrop="static"
                            data-keyboard="false"
                            data-target="#asignarCheque{{ $nom['Folio'] }}" class=" icons fas fa-money-check"></i>
                        </td>

                            </tr>
                            @livewire('lista-raya', ['raya' => $nom, 'RFC' =>$this->rfcEmpresa], key('user-profile-one-'.$nom['Folio']))
                            @livewire('recibosnomina',['recibosNomina' => $nom, 'RFC' =>$this->rfcEmpresa], key('user-profile-twoo-'.$nom['Folio']))
                            @livewire('asignar-cheque',['fecha'=>$nom['Complemento.0.Nomina.FechaFinalPago'],'asignarCheque' => $nom['Folio'],'RFC' =>$this->rfcEmpresa], key('user-profile-three-'.$nom['Folio']))
                            @livewire('detallesempleados',['fecha'=>$nom['Complemento.0.Nomina.FechaFinalPago'],'folio' => $nom['Folio'],'RFC' =>$this->rfcEmpresa], key('user-profile-four-'.$nom['Folio']))
                            @endforeach

                           </tbody>

                      {{---------  FIN  DEL FOR   -----}}
                       </table>

                   </div>

               </section>




           </div>
       </div>
   </div>

</div>


<livewire:agregarcheque>



    <!-- Modal -->


</div>{{-------fin div principal-------}}
