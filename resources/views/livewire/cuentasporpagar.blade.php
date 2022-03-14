<div>
    @php
    //Llamamos a los modelos requeridos
    use App\Models\MetadataR;
    use App\Models\ListaNegra;
    use App\Models\XmlR;
  
    //Obtenemos la clase para agregar a la tabla
    $rfc = Auth::user()->RFC;
    $class='';
    if(empty($class)){
      $class="table nowrap dataTable no-footer";
      }
    @endphp
  
    {{--Contenedor para mantener responsivo el contenido del modulo--}}
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-body">
          <section class="invoice-list-wrapper">
            {{--Aqui va el contenido del modulo--}}
            {{--Encabezado del modulo--}}
            <div class="justify-content-start">
              <h1 style="font-weight: bold">{{ ucfirst(Auth::user()->nombre) }}</h1>
              <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            </div>
  
            <br>
  
            {{--Select para selccionar la empresa (Contadores)--}}
            @empty(!$empresas)
             {{--Mostramos el RFC de la empresa que se selecciona--}}
              <label for="inputState">Empresa: {{$empresa}}</label>
              <select wire:model="rfcEmpresa" id="inputState1" class="select form-control"  >
                <option  value="" >--Selecciona Empresa--</option>
  
                {{--Llenamos el select con las empresa vinculadas--}}
                <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                  echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                }?>
              </select>
            @endempty
  
            <br>
  
            {{--Boton para mostrar la columna de vincular mas--}}
            <div class="invoice-create-btn mb-1">
              <a class="btn btn-primary button2" id="vinpbtn">Vincular Varios Proveedores</a>
            </div>
  
            <br>
  
  
            {{--Mostrar tabla de contenido--}}
            {{--Filtros--}}
            <div class="form-inline mr-auto">
              <input wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">    
                
                &nbsp;&nbsp;
                  
                <label for="inputState">Mes</label>
                <select wire:model="mes" id="inputState1" class=" select form-control"  >
                  <option  value="00" >Todos</option>
                  <?php foreach ($meses as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                  }?>
                </select>
                  
                &nbsp;&nbsp;
                  
                <label for="inputState">Año</label>
                <select wire:model="anio" id="inputState2" class="select form-control">
                  <?php foreach (array_reverse($anios) as $value) {
                    echo '<option value="' . $value . '">' . $value . '</option>';
                  }?>
                </select>
  
                &nbsp;&nbsp;
  
                <fieldset>
                  <div class="custom-control custom-checkbox">
                    <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger" checked name="customCheck" id="customColorCheck4">
                    <label class="custom-control-label" for="customColorCheck4">Todos</label>
                  </div>
                </fieldset>
  
                &nbsp;&nbsp;
  
                <input  wire:model.debounce.300ms="importe" class="form-control"  placeholder="Importe $"  type="number"  step="0.01" aria-label="importe" style="width:110px;" >
                
                &nbsp;&nbsp;
  
                <select wire:model="condicion" id="inputState1" class=" select form-control"  >
                  <option  value=">=" >--Condición--</option>
                  <option value="=" >igual</option>
                  <option value=">" >mayor que</option>
                  <option value="<" >menor que</option>
                </select>
                
                &nbsp;&nbsp;
                
                <select wire:model="estatus" id="inputState1" class=" select form-control"  >
                  <option  value="" >--Estatus--</option>
                  <option value="pendi" >Pendientes</option>
                  @if(auth()->user()->tipo)
                    <option value="sin_revisar" >Sin Revisar</option>
                    <option value="sin_conta" >Sin Contabilizar</option>
                  @endif
                </select>
              </div>

              <div wire:loading>
                <br>
                <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                  <div></div>
                  <div></div>
                </div>
                <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                <br>
              </div>

              {{--Tabla--}}
              <div class="table-responsive">
                <table id="example" class="{{$class}}" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-center align-middle VincVarProv">Vincular Proveedores</th>
                      <th class="text-center align-middle">RFC Emisor</th>
                      <th class="text-center align-middle">Razón Social</th>
                      <th class="text-center align-middle">Lista Negra</th>
                      <th class="text-center align-middle">N° de CFDI's</th>
                      <th class="text-center align-middle">Total</th>
                      <th class="text-center align-middle">Detalles</th>
                      <th >...</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{--Contenido de nuestra tabla--}}
                    {{--Llenamos nuestra tabla con un foreach--}}
                    @foreach ($col as $i)
                    @php
                    //Variable para obtener el total
                    $SumTotal = 0;
  
                    //Obtener el numero de CFDI
                    $DatosMetaR = MetadataR::
                    select('total', 'efecto')
                    ->where('receptorRfc', $this -> rfcEmpresa)
                    ->where('emisorRfc', $i->emisorRfc)
                    ->where('estado', '<>', 'Cancelado')
                    ->whereNull('cheques_id')
                    ->get();
  
                    //Obtenemos el numero de CFDI
                    $NoCFDI = $DatosMetaR -> count();
  
                    //Calcular el total (Convierte el campo total en en float y negativo si es egreso)
                    foreach ($DatosMetaR as $Total) {
                      $TotalFloat = (float) $Total['total'];
                      if ($Total['efecto'] == 'Egreso'){
                        $TotalFloat = -1 * abs($TotalFloat);
                      }
  
                      $SumTotal = $SumTotal + $TotalFloat;
                    }
                    @endphp
  
                    {{--Condicional para saber si hay mas de un CFDI--}}
                    @if (!$NoCFDI == 0)
                    <tr>
                      {{--Contenido de la columna para vincular varios--}}
                      <td class="text-center align-middle VincVarProv">
                        <div id="checkbox-group" class="checkbox-group">
                          <input style="transform: scale(1.5);" class="mis-checkboxes ChkMasProv" type="checkbox" id="allcheck" name="allcheck[]"value="{{$i->emisorRfc}}" />
                        </div>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{$i->emisorRfc}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{$i->emisorNombre}}</span>
                      </td>
  
                      {{-- Valida si existe una coincidencia de RFC en la lista negra --}}
                      @if (!DB::collection('lista_negra')->select('RFC')->where(['RFC' => $i['emisorRfc']])->exists())
                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima.png') }}" alt=""></td>
                      @else
                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima2.png') }}" alt=""></td>
                      @endif
  
                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{$NoCFDI}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">${{number_format($SumTotal, 2)}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <a data-toggle="modal" data-target="#detalles" class="icons fas fa-eye" wire:click="EmitRFC('{{$i->emisorRfc}}')"></a>
                      </td>
                      <td class="text-center align-middle"></td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
          </section>
        </div>
    </div>
  </div>

    {{--Llamamos a las modales--}}
    {{--Modal de detalles de cuentas por pagar--}}
    {{--Creacion del modal--}}
    <div wire:ignore.self class="modal fade" id="detalles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
          <div class="modal-content">
              {{--Encabezado--}}
              <div class="modal-header">
                  <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;" class="icons fas fa-folder-open">Cuentas por pagar</span></h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true close-btn">×</span>
                 </button>
              </div>
              {{--Cuerpo del modal--}}
              <div class="modal-body">
                {{--Generacion de la tabla--}}
                <div id="resp-table">
                  <div id="resp-table-body">
                    {{--Encabezado de la tabla--}}
                      <div class="resp-table-row">
                          <div class="tr table-body-cell">UUID</div>
                          <div class="tr table-body-cell">Fecha Emisión</div>
                          <div class="tr table-body-cell">Emisor</div>
                          <div class="tr table-body-cell">Concepto</div>
                          <div class="tr table-body-cell">Folio</div>
                          <div class="tr table-body-cell">Metodo - Pago</div>
                          <div class="tr table-body-cell">UUID Relacionado</div>
                          <div class="tr table-body-cell">Folio</div>
                          <div class="tr table-body-cell">Efecto</div>
                          <div class="tr table-body-cell">Total</div>
                          <div class="tr table-body-cell">Estado</div>
                          <div class="tr table-body-cell">Descargar</div>
                      </div>

                      {{--Cuerpo de la tabla--}}
                      @foreach ($CFDI as $FolioCFDI)
                      {{--Obtenemos los datos del XMLRecibidos--}}
                      @php
                        //Guardamos el folio fiscal en una variable
                        $FolFsical = $FolioCFDI->folioFiscal;

                        //Contador de conceptos
                        $ConceptCount = 0;
                        
                        //Consulta de los datos
                        $XmlReci = XmlR::
                        where('UUID', $FolFsical)
                        ->get();

                        //Condicional para revisa si la consulta nos arrojo algo
                        if (!$XmlReci->isEmpty()){
                          //Por medio de un foreach guardaremos los datos requeridos
                          foreach ($XmlReci as $CompleCFDI) {
                          $Concept = $CompleCFDI['Conceptos.Concepto'];
                          $Folio = $CompleCFDI['Folio'];
                          $MetodPago = $CompleCFDI['MetodoPago'];
                        }
                      }
                      //En caso de campos vacios estas se cambiaran por X
                      else {
                          $Concept = "X";
                          $Folio = "X";
                          $MetodPago = "X";
                          $UUIDRef = 'X';
                      }
                      @endphp   


                      <div class="resp-table-row">
                        {{--UUID--}}
                        <div class="table-body-cell">{{$FolioCFDI->folioFiscal}}</div>
                        
                        {{--Fecha emision--}}
                        <div class="table-body-cell">{{$FolioCFDI->fechaEmision}}</div>
                        
                        {{--Emisor--}}
                        <div class="table-body-cell">{{$FolioCFDI->emisorNombre}}</div>
                        
                        {{--Concepto--}}
                        <div class="table-body-cell">
                          @if (!$XmlReci->isEmpty())
                            @foreach ($Concept as $c)
                               {{++$ConceptCount}}.- {{$c['Descripcion']}}
                              <br>
                            @endforeach
                          @else
                            {{ $Concept }}
                          @endif
                        </div>

                        {{--Folio--}}
                        <div class="table-body-cell">
                          {{$Folio}}
                        </div>
                        
                        {{--Metodo/Pago--}}
                        <div class="table-body-cell">
                          {{$MetodPago}}
                        </div>
                        
                        {{--UUID Relacionado--}}
                        <div class="table-body-cell">
                          
                        </div>
                        
                        {{--Folio--}}
                        <div class="table-body-cell">

                        </div>
                        
                        {{--Efecto--}}
                        <div class="table-body-cell">

                        </div>
                        
                        {{--Total--}}
                        <div class="table-body-cell">

                        </div>
                        
                        {{--Estado--}}
                        <div class="table-body-cell">

                        </div>
                        
                        {{--Descargar--}}
                        <div class="table-body-cell">

                        </div>
                    </div>
                      @endforeach
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>

  </div>