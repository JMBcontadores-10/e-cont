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
      <a href="app-invoice-add.html" class="btn btn-primary glow invoice-create" role="button" aria-pressed="true">Create
        Invoice</a>
    </div>
    <input wire:model.debounce.300ms="search" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"placeholder="Search users...">

    <form  wire:submit.prevent="buscar">
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
    
         </form>
    
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
              <span class="align-middle">Invoice#</span>
            </th>
            
            <th>Amount</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Ajuste</th>
            <th>Tags</th>
            <th>Status</th>
            <th>Action</th>
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
        @endphp
        <tbody>
          <tr>
       
            <td>
              <a href="app-invoice.html">{{$numCheque}}</a>
            </td>
            <td><span class="invoice-amount">$459.30</span></td>
            <td><small class="text-muted">12-08-19</small></td>
            <td><span class="invoice-customer">Pixinvent PVT. LTD</span></td>
            <td ><span class="invoice-customer" > 

                 <a  href="#" style="text-decoration: none; " class="icons fas fa-upload"
                               data-toggle="modal" id="{{$id}}" onclick="filepond(this.id)"  data-target="#uploadRelacionados">
                              </a>
                              <button data-toggle="modal" data-target="#exampleModal" wire:click="editar('{{$id}}')"class="btn btn-primary btn-sm">Edit</button>
                             
            </span></td>
            <td>
              <span class="bullet bullet-success bullet-sm"></span>
              <small class="text-muted">Technology</small>
            </td>
            <td><span class="badge badge-light-danger badge-pill">UNPAID</span></td>
            <td>
                <span class="badge badge-light-danger badge-pill">UNPAID</span>
            </td>
          </tr>
         @endforeach
        </tbody>
      </table>
    </div>
  </section>
          </div>
        </div>
      </div>
      <!-- END: Content-->

      <livewire:ajuste  :ajusteCheque=$i : key="$i->id">


      <livewire:uploadrelacionados >

   
        @include('livewire.demo')
</div><!-- fin div contenedor principal-->