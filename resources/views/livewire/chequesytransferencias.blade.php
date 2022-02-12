<div><!-- div contenedor principal-->
@php
use App\Models\Cheques;
use App\Http\Controllers\ChequesYTransferenciasController;
@endphp
        @php
        $rfc = Auth::user()->RFC;
       $class='';
        if(empty($class)){
           $class="table nowrap dataTable no-footer";

        }
     @endphp
          <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body"><!-- invoice list -->
  <section class="invoice-list-wrapper">
    <!-- create invoice button-->
    <div class="invoice-create-btn mb-1">

        <a  data-toggle="modal" data-target="#nuevo-cheque" class="btn btn-primary glow invoice-create"
   wire:click="editar()" >Nuevo Cheque/Transferencia </a>
    </div>
    <!--<form action="{{ url('vincular-cheque') }}" method="POST">
        @csrf
        <button class="button2">Registrar Cheque/Transferencia</button>
    </form>-->

   <!-- <form  wire:submit.prevent="buscar">
        @csrf

    <input wire:model.defer="search" type="text"  name="ajuste" class="form-control">

    <div wire:loading wire:target="buscar" >
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
          <div></div>
          <div></div>

      </div>
      Guardando ajuste
      </div>

    <button type="submit"   class="btn btn-primary close-modal">Ajustar</button>

         </form>-->

@empty(!$empresas)

<h4>{{$empresas[3][0]}}</h4>

{{$empresa}}

<br><br>

{{--@php
foreach($empresas as $fila)
{
    foreach($fila as $nombre)
    {
	echo " $nombre ";
    }

	echo "<br>";
}
@endphp--}}


            <label for="inputState">Empresa</label>
            <select wire:model="rfcEmpresa" id="inputState1" class=" select form-control"  >
                <option  value="00" >--Seleccione una  Empresa--</option>
                <?php $rfc=0; $rS=1;foreach($empresas as $fila)
          {

                    echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';

          }
                ?>
            </select>
           

            &nbsp;&nbsp;<br>
@endempty

            <div class="form-inline mr-auto">
            <input  wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Search" aria-label="Buscar...">
            &nbsp;&nbsp;
            <label for="inputState">Mes</label>
            <select wire:model="mes" id="inputState1" class=" select form-control"  >
                <option  value="00" >Todos</option>
                <?php foreach ($meses as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                } ?>
            </select>
            &nbsp;&nbsp;
            <label for="inputState">Año</label>
            <select wire:model="anio" id="inputState2" class="select form-control">

                <?php foreach (array_reverse($anios) as $value) {
                    echo '<option value="' . $value . '">' . $value . '</option>';
                } ?>
            </select>
    &nbsp;&nbsp;
    <div class="form-check">
        <input class="form-check-input" type="checkbox" wire:model="todos"  name="stOne" id="stOne"  >
        <label class="form-check-label" for="flexCheckChecked">
          Todos los registros
        </label>
      </div>

<!-- <input  wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Search" aria-label="Search">
           -->



        </div>

     

    <!-- Options and filter dropdown button-->
    <div class="action-dropdown-btn d-none">
      <div class="dropdown invoice-filter-action">
        <button class="btn border dropdown-toggle mr-1" type="button" id="invoice-filter-btn" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          Filter Invoice
        </button>

      </div>

    </div>
    <div class="table-responsive">
      <table id="example" class="{{$class}}" style="width:100%">
        <thead>
          <tr>

            <th>
              <span class="align-middle">fecha </span>
            </th>

            <th>Factura#</th>
            <th>beneficiario</th>
            <th>T.operación</th>
            <th>F.pago</th>
            <th>Pagado</th>
            <th>$Cfdi</th>
            <th>comprobar</th>
           

            <th >...</th>




          </tr>
        </thead>
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
            $impresion = $i['impresion'];
        @endphp
        <tbody>
          <tr onclick="showHideRow('hidden_row{{$id}}');">
            <td><small class="text-muted">{{$fecha}}</small></td>
            
            <td>
              <a style="color:#3498DB" >{{ Str::limit($numCheque, 20); }}</a>
            </td>
            <td> {{ Str::limit($beneficiario, 20);}}</td>
            <td><small class="text-muted">{{$tipoO}}</small></td>

            <td><span class="invoice-amount">{{$tipo}}</span></td>
            <td><span class="invoice-amount">${{ number_format($importeC, 2) }}</span></td>

            <td><span class="invoice-amount">${{ number_format($sumaxml, 2) }}</span></td>
            <td><span class="invoice-amount">${{ $diferencia }}</span></td>
            <td>{{-- ajuste y notas---}}<span class="invoice-amount">

          </tr>

          <tr id="hidden_row{{$id}}" class="hidden_row">
            <td colspan=12>

            <a style="color:#3498DB">{{$numCheque}}</a>
               
               &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <!--               
               <br>
          -->

               {{$beneficiario}}
               
               <br>

               <div class="box">
                <div>

                    <div class="tr"> Ajuste</div>


                    @if (Auth::user()->tipo)
                    @if ($ajuste!=0)
                    @php $class="content_true" @endphp
                    @else
                   @php $class="icons" @endphp
                 @endif

                   <a class="{{$class}} fas fa-balance-scale"
                    data-toggle="modal" data-target="#ajuste{{$id}}"></a>

                    @endif
                </div>

                
                <div>
                    <div class="tr"> Comentario</div>

               @if (!empty($comentario))

               @php $class_c="content_true" @endphp

               @else

               @php $class_c="icons" @endphp

            @endif

      <a  class="{{$class_c}} fas fa-sticky-note" data-toggle="modal" data-target="#comentarios-{{$id}}"> </a>
                </div>
                <div>
                    <div class="tr">Cheque o Transferencia</div>
                     @if ($nombreCheque!="0")
                     <a id="rutArc" href="{{ $rutaArchivo }}" target="_blank"> </a>
   
        @php $class_p="content_true_pdf" @endphp
        @else
        @php $class_p="icons" @endphp
        @endif
        
        
       <a id="{{$id}}" class="{{$class_p}} fas fa-file-pdf"
   data-toggle="modal" data-target="#pdfcheque{{$id}}"  onclick="filepondEditCheque(this.id)" > </a>

                </div>
                <div>
                    <div class="tr"> Documentos Adicionales</div>
                    <a class="icons fas fa-upload"
                    data-toggle="modal" data-controls-modal="#uploadRelacionados"  name="{{$id}}"  data-backdrop="static" data-keyboard="false"   onclick="filepond(this.name)"  data-target="#uploadRelacionados">
                   </a>{{-- id="{{$id}}"--}}

                    &nbsp; | &nbsp;
                    @if (!$docAdi['0'] == '')

                    @php $class="content_true" @endphp

                    @else

                    @php $class="icons" @endphp


                 @endif

                    <a  class="{{$class}} fas fa-folder-open"
                    data-toggle="modal"     data-target="#relacionados-{{$id}}" >{{--id="{{$id}}"--}}
                   </a>

                </div>
                <div>
                    <div class="tr">Vinculadas</div>
                    @if ($faltaxml != 0)

            <i class="icons fas fas fa-eye " ></i>

        @else

       

                                          <form action="{{ url('detallesCT') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="verificado" value="{{ $verificado }}">
                                            <button   style= "background:none; border:none">
                                                <i  class="icons fas fas fa-eye" style="color: rgb(8, 8, 8)"></i>
                                          </button>
                                        </form>


                @endif


                      </div>

                     


                <div>
              <div class="tr">Editar</div>
              
              <a  class="icons fas fa-edit"
              data-toggle="modal"     data-target="#editar-{{$id}}" >{{--id="{{$id}}"--}}
             </a>

                </div>
              
                <div>

                    <div class="tr">Eliminar Cheque</div>
                    @if (Auth::user()->tipo)
                    @if ($verificado == 0)

                    <form action="{{ url('delete-cheque') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <input type="hidden" name="rutaArchivo" value="{{ $rutaArchivo }}">
                                            <button  style= "background:none; border:none"
                                                onclick="return confirm('¿Seguro que deseas eliminar el cheque/transferencia?')"
                                                type="submit" class="fabutton">
                                                <i class="fas fa-trash" style="color: rgb(8, 8, 8)"></i>
                                            </button>
                                        </form>

            @else
            <i class="fas fa-trash"></i>

                @endif
                @endif


                      </div>

                      <div>
                                                
                        <div class="tr">Contabilizado</div>
                              

                                <a>
                    @if (Auth::user()->tipo)
                    @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                        @php
                                            Cheques::find($id)->update(['pendi' => 1]);
                                        @endphp
                            
                                    @elseif ($verificado == 0 )
                                        <form action="{{ url('cheques-transferencias') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            <a >
                                            &nbsp;&nbsp;
                                            <input type="checkbox" name="revisado" required class="mb-2">
                                            </a>
                                            Revisado
                                            <input type="submit" name="Aceptar" value="Aceptar">
                                        </form>
                                    @else
                                        <i class="far fa-check-circle" style="color: green"></i>

                                        @if (isset($revisado_fecha))
                                        <br>
                                        
                                            {{ $revisado_fecha }}
                                        @endif
                                    @endif
                            </a>

                                <a>
                                    @if ($verificado == 1 and $contabilizado == 0)
                                        <form action="{{ url('cheques-transferencias') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                           
                                            <input type="checkbox" name="conta" required class="mt-4">
                                            Contabilizado
                                            
                                                Póliza:
                                                <input type="text" name="poliza" size="2" required class="mt-1 mb-2">
                                    
                                            <input type="submit" name="Aceptar" value="Aceptar">
                                        </form>
                                    @elseif ($verificado == 1 and $contabilizado == 1)
                                    <i   style="color: blue; " class="fas fa-calculator"></i>
                                  
                                        
                                        @if (isset($contabilizado_fecha))
                                        <br>
                                        &nbsp;&nbsp;
                                          {{ $contabilizado_fecha }}
                                        @endif
                                        @if (isset($poliza))
                                        &nbsp;
                                            Póliza: {{ $poliza }}
                                            &nbsp;&nbsp;
                                        @endif
                                    @else
                                    <a   style="text-decoration: none; " class="alert fas fa-file-invoice-dollar"></a>

                                    @endif


 @if ($tipo != 'Efectivo' and ($tipoO == 'Impuestos' || $tipoO == 'Sin CFDI' ? $nombreCheque == '0' : ($faltaxml == 0 or $diferenciaP != 1 or $nombreCheque == '0')))
                                        @php
                                            Cheques::find($id)->update(['pendi' => 1]);
                                        @endphp
                                        @endif
                                        @endif

                                
                                </div>
                                </a>
                            <div>


          
                            <div class="tr">Impresion</div>
  
                                    @if($impresion == 'on')
                                   
                                    <i class="icons fas fa-print" style="color: green"></i>
                                        

                                    @endif

                                    @if($impresion == '')
                                    <form action="{{ url('cheques-transferencias') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $id }}">
                                            
                                            <input type="checkbox" name="impresion" required class="mb-2">
                                            
                                            Impresion
                                            <div>
                                              
                                            <input type="submit" name="Aceptar" value="Aceptar">
                                 </div>
                                            @endif
                                        </form>


                    

                          </div>


                                      <div>
                        
                                        <div class="tr">Cheque Id</div>
                                        &nbsp;&nbsp;&nbsp; {{$id}}

                                               </div>
                                               </div>

                                               
   </span>

   
</div>

</div>
            </td>



        </tr>

         @endforeach
        </tbody>
      </table>
      {{ $colCheques->links() }}

      

    @livewireScripts
  
    </div>


  </section>
  
          </div>
          
        </div>
        
      </div>
      
          
      
      <livewire:uploadrelacionados >
      

      
        @include('livewire.demo') 
        
        
      {{-- @include('livewire.ajuste') --}}
</div><!-- fin div contenedor principal-->

@php


$col = Cheques:: where(['rfc' => $empresa])

        ->get()

        @endphp



@foreach ($col as $i)

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
<livewire:ajuste  :ajusteCheque=$i : key="$i->id" >
<livewire:comentarios  :comentarioCheque=$i : key="$i->id">
<livewire:ajuste  :ajusteCheque=$i : key="$i->id" >
<livewire:relacionados  :filesrelacionados=$i : key="$i->id" >

<livewire:editar  :editCheque=$i : key="$i->id">

<livewire:agregarcheque>
<livewire:pdfcheque :pdfcheque=$i : key="$i->id" >  

@endforeach









            