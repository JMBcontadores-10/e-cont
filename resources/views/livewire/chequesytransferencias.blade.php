<div><!-- div contenedor principal-->
 
    @php
use App\Models\Cheques;
use App\Http\Controllers\ChequesYTransferenciasController;

@endphp


    <div class="container" >
        <div class="float-md-left">
            <a class="b3" href="{{ url('/modules') }}">
                << Regresar</a>
        </div>
        <div class="float-md-right">
            <p class="label2">Cheques y Transferencias</p>
        </div>
        <br>
         <!-- Your application content -->


        <hr style="border-color:black; width:100%;">
        <div class="justify-content-start">
            <label class="label1" style="font-weight: bold"> Sesión de: </label>
            <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
            <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            <hr style="border-color:black; width:100%;">
        </div>
        @php
        $rfc = Auth::user()->RFC;

     @endphp

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
                        <select class="form-control"onchange="funcion(this);" id="anio" id="select" name="anio">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option name="valor1" value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary ml-2">Consultar</button>
                    </div>
                </form>
            </div>
            <div>
                <script> 
function funcion(e) {
  var option = e.options[e.selectedIndex];
  var inputAux = document.getElementById('inputAux');
  alert("Valor Option: " + option.value + ", Texto Option: " + option.text);
  alert("Valor Input: " + inputAux.value);

}
                </script>
        

                <form action="{{ url('vincular-cheque') }}" method="POST">
                    @csrf
                    <button class="button2">Registrar Cheque/Transferencia</button>
                </form>
            </div>
        </div>

        <form >

            <div  class="mw-100" style="text-align: center; " >
            @php
            $fechamx = new Cheques();
            $mes_actual= date("m");
            @endphp
            @if ((!empty($_GET['filtro_cheques'])))

             <h5 style="font-weight: bold"> @php echo "Busqueda:&nbsp;".$_GET['filtro_cheques'];@endphp </h5>

             @elseif (!empty($_GET['mes']))
             <h5 style="font-weight: bold"> @php echo  $fechamx->fecha_es($_GET['mes']);@endphp </h5>
            @else

            <h5 style="font-weight: bold"> @php echo  $fechamx->fecha_es($mes_actual);@endphp </h5>

          @endif


            </div>

           
            </form>
            <br>
        </div>
        <div class="mx-4" style="overflow: auto" id="table_refresh" >
            
<!-- ===========================================================================-->
<div class="w-full flex pb-10">
    <div class="w-3/6 mx-1">
        <input wire:model.debounce.500ms="search" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"placeholder="Search users...">
    </div>
    <div class="w-1/6 relative mx-1">
        <select wire:model="orderBy" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
            <option value="id">ID</option>
            <option value="name">Name</option>
            <option value="email">Email</option>
            <option value="created_at">Sign Up Date</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>
    <div class="w-1/6 relative mx-1">
        <select wire:model="orderAsc" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
            <option value="1">Ascending</option>
            <option value="0">Descending</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>
    <div class="w-1/6 relative mx-1">
        <select wire:model="perPage" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>
</div>
<!--=========================================================================== -->

           
        <div >

            <table  id="tabla" >
                <thead>
                    <tr class="row100 head" >
                       <!-- <th   class="cell100 column1" scope="col">No.</th>-->
                        <th   class="cell100 column1" scope="col">Fecha de pago</th>
                        <th  class="cell100 column1" scope="col">Núm de factura</th>
                        <th   >Beneficiario</th>
                        <th  class="cell100 column1" scope="col">Tipo de operación</th>
                        <th  class="cell100 column1" scope="col">Forma de pago</th>
                        <th  class="cell100 column1" scope="col">Total pagado</th>
                        <th  class="cell100 column1" scope="col">Total CFDI</th>
                        <th  class="cell100 column1" scope="col">Por comprobar</th>
                        <!--if (Session::get('tipoU') == '2')-->
                            <th class="cell100 column1" scope="col">Ajuste</th>
                        <!--endif-->
                        <th  class="cell100 column1" scope="col">PDF cheque o transferencia</th>
                        <th  class="cell100 column1" scope="col">Documentos adicionales</th>
                        <th  class="cell100 column1" scope="col">Acciones</th>
                        @if (Session::get('tipoU') == '2')
                            <th scope="col" colspan="2">Contabilizado</th>
                        @endif
                      <!--  <th  class="cell100 column1" scope="col">Comentarios</th>
                        <th  class="cell100 column1" scope="col">Cheque id</th>
                      -->
                    </tr>
                </thead>
               
                <tbody class="buscar">
                    {{$n=0;}}
                    @foreach ($colCheques as $i)
                  
                        @php
                           
                         $editar = true;
                        $id = $i->_id;
                        $tipo = $i->tipomov;
                        $fecha = $i->fecha;
                        $dateValue = strtotime($fecha);
                         $anio = date('Y',$dateValue);
                        $rutaDescarga = 'storage/contarappv1_descargas/'.$rfc.'/'.$anio.'/Cheques_Transferencias/';
                        $numCheque = $i->numcheque;
                        $beneficiario = $i->Beneficiario;
                        $importeC = $i->importecheque;
                        $sumaxml = $i->importexml;
                        $ajuste = $i->ajuste;
                        $verificado = $i->verificado;
                        $faltaxml = $i->faltaxml;
                        $contabilizado = $i->conta;
                        $pendiente = $i->pendi;
    
                            $tipoO = $i->tipoopera;
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
    
                            $nombreCheque = $i->nombrec;
                            if ($nombreCheque == '0') {
                                $subirArchivo = true;
                                $nombreChequeP = 0;
                            } else {
                                $subirArchivo = false;
                                $nombreChequeP = 1;
                            }
    
                            $rutaArchivo = $rutaDescarga . $nombreCheque;
                            if (!empty($i->doc_relacionados)) {
                                $docAdi = $i->doc_relacionados;
                            }
    
                            $revisado_fecha = $i->revisado_fecha;
                            $contabilizado_fecha = $i->contabilizado_fecha;
                            $poliza = $i->poliza;
                            $comentario = $i->comentario;
                        @endphp
                        <tr class="CellWithComment">
                            <!--<td data-label="No">{{ ++$n }}</td>-->
                            <td data-label="Fecha de pago">
                                {{ $fecha }}
                               <!-- @if (isset($comentario) && $verificado == 0)
                                    <span class="CellComment">{{ $comentario }}</span>
                                @endif-->
                            </td>
                            <td data-label="#factura">{{ $numCheque }}</td>
                            <td data-label="Beneficiario">{{ $i->Beneficiario}}</td>
    
                            <td data-label="Operación">{{ $tipoO }}</td>
                            <td data-label="forma de pago">{{ $tipo }}</td>
                            <td data-label="Total pagado">${{ number_format($importeC, 2) }}</td>
                            <td data-label="Total CFDI">${{ number_format($sumaxml, 2) }}</td>
                            <td data-label="Por Comprobar">${{ $diferencia }}</td>
                            <td class="text-center align-middle CellWithComment">
                               
                                @if (Session::get('tipoU') == '2')
        
        
        <!-- seccion ajustes -->
        
        <li  style="list-style:none; "  >
            <livewire:ajuste  :ajusteCheque=$i : key="$i->id" >
              </li>
              <hr>
              @endif
                   <!--fin  seccion ajustes -->

                    <!---icon seccion comentarios -->

                    <li  style="list-style:none; "  >

                       
     <livewire:comentarios  :comentarioCheque=$i : key="$i->id" >
                         
                          </li>
                        <!--- fin icon seccion comentarios -->

                            </td>
           

                            <td data-label="Pdf">
                                <!-- MODAL pdfcheeque-->
               <livewire:pdfcheque :pdfcheque=$i : key="$i->id" >
                                   
                   <hr>

                   

                                   <!-- <a id="rutArc" href="{{ $rutaArchivo }}" target="_blank">
                                        <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                    </a>
                                    <br><br>
                                        <form action="{{ url('borrarArchivo') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button  class="fabutton"  onclick="return confirm('¿Seguro que deseas eliminar el Pdf ?')" type="submit" >
                                                <i class="fas fa-trash-alt fa-lg" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>-->
    
                            </td>

                            <td data-label="D. adicionales" >
                                @if (empty($i['doc_relacionados']))
                                <a  href="#" style="text-decoration: none; " class="icons fas fa-falder-open"
                                data-toggle="modal"  id="{{$id}}" onclick="filepond(this.id)" data-target="#relacionados-{{$id}}">
                               </a>
                           <hr>
                               <a  href="#" style="text-decoration: none; " class="icons fas fa-upload"
                               data-toggle="modal" id="{{$id}}" onclick="filepond(this.id)"  data-target="#uploadRelacionados">
                              </a>
    
                               <!--    <a  href="#" style="text-decoration: none; " class="icons fas fa-falder-open"
                                data-toggle="modal"  id="{{$id}}" onclick="filepond(this.id)" data-target="#relacionados-{{$id}}">
                               </a> -->
    
    
                                @else


     <!-- MODAL RELACIONADOS-->
     <livewire:relacionados  :filesrelacionados=$i : key="$i->id" >
                                
    
                                @endif
                            </td>

                            <td data-label="Acciones">
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
                                        <!--
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
                                            <input type="hidden" name="ruta" value="{{ $rutaArchivo }}">

                                            <button type="submit" class="fabutton">
                                                <i   class="fas fa-edit fa-lg" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>-->
                                       <!-- <a class="fas fa-edit fa-lg"   data-toggle="modal" data-target="#editar{{$n}}"> </a>-->



       <livewire:editar  :editCheque=$i : key="$i->id">
        <!--FIN  MODAL RELACIONADOS-->

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
                    <td >
                        <div class="mx-1">
                            @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                @php
                                    Cheques::find($id)->update(['pendi' => 1]);
                                @endphp


                                 <a   style="text-decoration: none; " class="alert parpadea fas fa-exclamation"
                                  onclick="alertaP({{ $diferenciaP }},{{ $faltaxml }}, {{ $nombreChequeP }})">
                                </a>

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
                            <a   style="text-decoration: none; " class="icon_basic fas fa-calculator">
                            </a>
                                @if (isset($contabilizado_fecha))
                                    <div class="mt-1">{{ $contabilizado_fecha }}</div>
                                @endif
                                @if (isset($poliza))
                                    <div class="mt-1">Póliza: {{ $poliza }}</div>
                                @endif
                            @else
                            <a   style="text-decoration: none; " class="alert fas fa-file-invoice-dollar">
                           </a>

                            @endif
                        </div>
                    </td>
                @endif

                 <!--
                        <td data-label="Comentarios">
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
                        <td data-label="cheque id">{{ $id }}</td>
                    se eliminan los campos -->
                </tr>
                @endforeach
            </tbody>
        </table>

        <livewire:uploadrelacionados  >

 

    </div><!-- Fin del div refresh tabla-->
    </div>
    <div class="ml-4 mt-3">
        {{ $colCheques->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
    </div>









</div><!-- fin div contenedor principal-->
