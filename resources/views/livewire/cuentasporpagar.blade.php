<div>
    @php
    //Llamamos a los modelos requeridos
    use App\Models\MetadataR;
    use App\Models\ListaNegra;
    use App\Models\XmlR;
    use App\Models\Cheques;
  
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

            {{--Botones para mas provvedores--}}
            <div class="row">
              <div class="col-4">
                {{--Boton para mostrar la columna de vincular mas--}}
                <div class="invoice-create-btn mb-1">
                  <a class="btn btn-primary button2" id="BtnMoreProv">Vincular Varios Proveedores</a>
                </div>
              </div>
              <div class="col-4">
                {{--Boton para mostrar la columna de detalles para varios proveedores--}}
                <div class="invoice-create-btn mb-1">
                  <button data-toggle="modal" data-target="#detalles" class="btn btn-secondary button2 DesatallesProv" id="BtnDetMoreProvUp" wire:click="EmitRFCArray()" disabled>Destalles Varios Proveedores</button>
                </div>
              </div>
            </div>

            <br>

            {{--Mostrar tabla de contenido--}}
            {{--Filtros--}}
            <div class="form-inline mr-auto">
              <input wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">    
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
                          <input style="transform: scale(1.5);" class="mis-checkboxes ChkMasProv" type="checkbox" wire:model.defer="moreprov" value="{{$i->emisorRfc}}"/>
                        </div>
                      </td>
                      <td class="text-center align-middle">
                        <span style="color:#3498DB" class="invoice-amount">{{$i->emisorRfc}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{ Str::limit($i->emisorNombre, 20); }}</span>
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
              <br>
              {{--Boton para mostrar la columna de detalles para varios proveedores--}}
              <div class="invoice-create-btn mb-1">
                <button data-toggle="modal" data-target="#detalles" class="btn btn-secondary button2 DesatallesProv" id="BtnMoreProvDown"  wire:click="EmitRFCArray()" disabled>Destalles Varios Proveedores</button>
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
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="CleanRFC()">
                      <span aria-hidden="true close-btn">×</span>
                 </button>
              </div>
              {{--Cuerpo del modal--}}
              <div class="modal-body">
                {{--Listado de cheques a vincular--}}
                <label>Movimiento: </label>
                <select class="select form-control" wire:model="moviselect">
                  <option  value="" >--Selecciona Movimiento--</option>
                  @foreach ($Cheques as $i)
                    <option value="{{ $i->_id }}">
                      {{ $i->fecha }} - {{ $i->numcheque }} - {{ $i->Beneficiario }} -
                      ${{ number_format($i->importecheque, 2) }}
                    </option>
                  @endforeach
                </select>

                <br>

                {{--Seccion de botones--}}
                <div class="row">
                  <div class="col-3">
                    {{--Vincular--}}
                    {{--Condicional para activar o desactivar el boton--}}
                    @if ($btnvinactiv == 1)
                    <div class="invoice-create-btn mb-1">
                      <button class="btn btn-primary" wire:click="VincuCFDIMovi()">Vincular a Movimiento</button>
                    </div>
                    @else
                    <div class="invoice-create-btn mb-1">
                      <button class="btn btn-primary" wire:click="VincuCFDIMovi()" disabled>Vincular a Movimiento</button>
                    </div>
                    @endif
                  </div>
                  <div class="col-3">
                    <div class="invoice-create-btn mb-1">
                      <button class="btn btn-secondary" id="Btnmostrarnewcheq">Vincular a nuevo Movimiento</button>
                    </div>
                  </div>
                </div>

                <br>

                <div wire:loading>
                  <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                    <div></div>
                    <div></div>
                  </div>
                  <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                </div>

                {{--Generacion de la tabla--}}
                <div id="resp-table">
                  <div id="resp-table-body">
                    {{--Encabezado de la tabla--}}
                      <div class="resp-table-row">
                          <div class="tr table-body-cell">UUID</div>
                          <div class="tr table-body-cell">Fecha Emisión</div>
                          <div class="tr table-body-cell">Emisor</div>
                          <div class="tr table-body-cell">Concepto</div>
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
                        $folioF = $FolioCFDI->folioFiscal;

                        //Contador de conceptos
                        $ConceptCount = 0;
                        
                        //Variables que contiene los resultados de la consulta
                        $efecto = $FolioCFDI->efecto;
                        $total = $FolioCFDI->total;
                        $estado = $FolioCFDI->estado;
                        $fechaE = $FolioCFDI->fechaEmision;
                        $nUR = 0;

                        //Rutas de archivos
                        $espa=new MetadataR();
                        $numero = (string) (int) substr($fechaE, 5, 2);
                        $mesNombre = (string) (int) substr($fechaE, 5, 2);
                        $anio = (string) (int) substr($fechaE, 0, 4);
                        $mees=$espa->fecha_es($mesNombre);

                        //Se asignan las rutas donde está almacenado el 
                        //Condicional para saber si el RFC es un arreglo
                        if(is_array($RFC)){
                          //Si es un arreglo
                          foreach ($RFC as $RFCArray) {
                            $rutaXml = "storage/contarappv1_descargas/$RFCArray/$anio/Descargas/$numero.$mees/Recibidos/XML/$folioF.xml";
                            $rutaPdf = "storage/contarappv1_descargas/$RFCArray/$anio/Descargas/$numero.$mees/Recibidos/PDF/$folioF.pdf";
                          }
                        }else {
                          //Si no es un arreglo
                          $rutaXml = "storage/contarappv1_descargas/$RFC/$anio/Descargas/$numero.$mees/Recibidos/XML/$folioF.xml";
                          $rutaPdf = "storage/contarappv1_descargas/$RFC/$anio/Descargas/$numero.$mees/Recibidos/PDF/$folioF.pdf";
                        }

                        

                        //Condicional para saber si el efecto es un egreso
                        if ($efecto == 'Egreso'){
                          //Si es un egreso entonces se saca el valor absoluto del total para descontar
                          $total = -1 * abs($total);
                        }

                        //Consulta de los datos
                        $XmlReci = XmlR::
                        where('UUID', $folioF)
                        ->get();

                        //Condicional para revisa si la consulta nos arrojo algo
                        if (!$XmlReci->isEmpty()){
                          //Por medio de un foreach guardaremos los datos requeridos
                          foreach ($XmlReci as $CompleCFDI) {
                          $Concept = $CompleCFDI['Conceptos.Concepto'];
                          $Folio = $CompleCFDI['Folio'];
                          $MetodPago = $CompleCFDI['MetodoPago'];
                          
                          if ($efecto == 'Pago'){
                            $docRel = $CompleCFDI['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                            $MetodPago = '-';
                            if (!isset($docRel)){
                              $docRel = $CompleCFDI['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado.IdDocumento'];
                            }
                          } elseif ($efecto == 'Egreso' or $efecto == 'Ingreso'){
                            $docRel = $CompleCFDI['CfdiRelacionados.CfdiRelacionado'];
                          }
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
                        <div class="table-body-cell">
                          <div class="form-check">
                            {{--Condicional para ocultar el checkbox cuando este es un pago--}}
                            @if ($efecto != "Pago")
                            <input id="Chk{{$FolioCFDI->folioFiscal}}" class="form-check-input" type="checkbox" wire:model="movivinc" value="{{$FolioCFDI->folioFiscal}}">
                            @endif
                            <label for="Chk{{$FolioCFDI->folioFiscal}}" class="form-check-label">{{$FolioCFDI->folioFiscal}}</label>
                          </div>
                        </div>
                        
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

                        {{--Metodo/Pago--}}
                        <div class="table-body-cell">
                          {{$MetodPago}}
                        </div>
                        
                        {{--UUID Relacionado--}}
                        <div class="table-body-cell">
                          @if (!$XmlReci->isEmpty())
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
                            {{ $UUIDRef }}
                          @endif
                        </div>

                        {{--Folio--}}
                        <div class="table-body-cell">
                          {{$Folio}}
                        </div>
                        
                        {{--Efecto--}}
                        <div class="table-body-cell">
                          {{$efecto}}
                        </div>
                        
                        {{--Total--}}
                        <div class="table-body-cell">
                          ${{number_format($total, 2)}}
                        </div>
                        
                        {{--Estado--}}
                        <div class="table-body-cell">
                          {{$estado}}
                        </div>
                        
                        {{--Descargar--}}
                        <div class="table-body-cell">
                          @if ($estado != 'Cancelado')
                                    <a href="{{ $rutaXml }}" download="{{ $folioF }}.xml">
                                        <i class="fas fa-file-download fa-2x"></i>
                                    </a>
                                    <a href="{{ $rutaPdf }}" target="_blank">
                                        <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                    </a>
                                @endif
                        </div>
                    </div>
                      @endforeach
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>

  {{--Modal de creacion de nuevo cheque--}}
  {{--Creacion del modal--}}
  <div class="modal fade" id="nuevochequecfdi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-plus">&nbsp;Agrega Cheque/Transferencia</span></h6>
          </div>
          <div class="modal-body">
            {{--Parte uno registro del nuevo cheque (Aqui agregamos los cheques con los CFDI)--}}
            
            {{--Parte dos agregar PDF--}}

            {{--Parte tres agregas los documentos relacionados--}}
          </div>
        </div>
    </div>
  </div>
</div>