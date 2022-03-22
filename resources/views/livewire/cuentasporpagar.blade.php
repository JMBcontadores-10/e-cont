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

    //Obtenemos el valor de la fecha del dia de hoy
    $date=date('Y-m-d');
    @endphp

  {{--Scrips para mostrar las modales--}}
  <script>
    window.addEventListener('agregarpdf', event => {
    $("#step1").fadeOut("slow");
    $("#step1").hide();

    $("#step2").fadeIn("slow");
  });

  window.addEventListener('agregarrela', event => {
    $("#step1").fadeOut("slow");
    $("#step1").hide();

    $("#step2").fadeOut("slow");
    $("#step2").hide();

    $("#step3").fadeIn("slow");
  });
  </script>


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
                  <button data-toggle="modal" data-target="#detalles" class="btn btn-secondary button2 DesatallesProv" id="BtnDetMoreProvUp" wire:click="EmitRFCArray()" disabled>Detalles Varios Proveedores</button>
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
                        <a class="icons fas fa-eye" wire:click="EmitRFC('{{$i->emisorRfc}}')" onclick="OpenModalDetail()"></a>
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

                 {{--Select que contiene la lista de los cheques--}}
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
                  {{--Vincular--}}
                  <div class="col-3">
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

                  {{--Vincular a un nuevo movimiento--}}
                  <div class="col-3">
                    {{--Condicional para activar o desactivar el boton--}}
                    @if ($btnvinanewctiv == 1)
                    <div class="invoice-create-btn mb-1">
                      <button class="btn btn-secondary" id="Btnmostrarnewcheq" onclick="OpenModalNewCheque()">Vincular a nuevo Movimiento</button>
                    </div>
                    @else
                    <div class="invoice-create-btn mb-1">
                      <button class="btn btn-secondary" id="Btnmostrarnewcheq" disabled>Vincular a nuevo Movimiento</button>
                    </div>
                    @endif
                  </div>
                </div>

                <br>

                {{--Filtro de busqueda--}}
                <div class="form-inline mr-auto">
                  <input wire:model.debounce.300ms="searchcfdi" class="form-control" type="text" placeholder="Filtro" aria-label="Search">    
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

    {{--Llamamos a las modales--}}
    {{--Modal de detalles de cuentas por pagar--}}
    {{--Creacion del modal--}}
    <div wire:ignore.self class="modal fade" id="newchequevinc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
              {{--Encabezado--}}
              <div class="modal-header">
                  <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;" class="icons fas fa-plus">Agrega Cheque/Transferencia</span></h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true close-btn">×</span>
                 </button>
              </div>
              {{--Cuerpo del modal--}}
              <div class="modal-body">
                {{--Paso 1 llenado de formulario--}}
                <div id="step1">
                  <p>Llena los campos correspondientes</p>
                  <div class="row">
                    {{--Formulario--}}
                    <div class="col-md-12 mx-0" align="center">
                      <form wire:submit.prevent="AgregarChequeCFDI">
                        @csrf

                        <div class="row">
                          <div class="col">
                            {{---tooltip---}}
                            <i id="info" class=" fa fa-info-circle" aria-hidden="true"></i>
                            <span id="pago" class="tooltiptext">Como fue que realizó el pago.</span>

                            <label for="inputEmail4">Forma de pago</label>

                            <select wire:model="Nuevo_tipomov" name="tipo" id="tipo" class="agregarInputs form-control" required >
                              <option  value="" >--Selecciona Forma--</option>
                              <option>Cheque</option>
                              <option>Transferencia</option>
                              <option>Domiciliación</option>
                              <option>Efectivo</option>
                            </select>
                          </div>

                          <div class="col">
                            {{---tooltip---}}
                            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                            <span  id="factura" class="tooltiptext">En caso de no tener un número de factura,
                            escriba brevemente que es lo que está pagando.
                            Si se trata de un cheque, también escriba número de cheque.</span>
                              {{---tooltip---}}
                            <label for="inputPassword4">#Factura</label>
                            <input class="form-control" type="text"  name="Nuevo_numCheque"
                            placeholder="Describa lo que está pagando" wire:model="Nuevo_numcheque" required>
                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="col">
                            {{---tooltip---}}
                            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                            <span id="fecha" class="tooltiptext">Escriba la fecha en que realizó el pago.</span>

                            <label for="inputAddress">Fecha de pago</label>
                            <input class="form-control" id="fecha" wire:model="Nuevo_fecha" type="date" min="2014-01-01"
                            max={{ $date }} required>
                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="col">
                              {{---tooltip---}}
                              <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                              <span id="pagado" class="tooltiptext">La cantidad que se pagó con pesos y centavos.</span>
                              
                              <label for="inputEmail4">Total pagado</label>
                              <input class="form-control" wire:model="Nuevo_importecheque" type="number"  step="0.01" placeholder="pesos y centavos Ej. 98.50" name="importeCheque">
                          </div>
                          <div class="col">
                            <label for="inputPassword4">Total factura(s):</label>
                            <input class="form-control" type="text" readonly name="importeT" value="">
                          </div>
                        </div>

                        <br>

                        <div class="row">
                          <div class="col">
                            {{---tooltip---}}
                            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                            <span id="beneficiario" class="tooltiptext"> Razón social a quien realizó el pago.</span>
                            
                            <label for="inputCity">Beneficiario</label>
                            <input class="form-control" wire:model="Nuevo_beneficiario" type="text" name="beneficiario" placeholder="A quien realizó el pago" required>
                          </div>
                          <div class="col">
                            {{---tooltip---}}
                            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                            <span id="operacion" class="tooltiptext"> Seleccione que fue lo que pagó.
                            </span>
                            
                            <label for="inputState">Tipo de operación</label>
                            <select wire:model="Nuevo_tipoopera" class="form-control" name="tipoOperacion" required>
                                <option  value="">--Selecciona tipo--</option>
                                <option>Impuestos</option>
                                <option>Nómina</option>
                                <option>Gasto y/o compra</option>
                                <option>Sin CFDI</option>
                                <option>Parcialidad</option>
                                <option>Otro</option>
                            </select>
                          </div>
                        </div>

                        {{--Carga--}}
                        <div wire:loading wire:target="guardar_nuevo_cheque" >
                          <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                          </div>
                          Creando cheque ...
                        </div>
                    
                        <br>

                        {{--Boton para enviar el form--}}
                       <button type="submit"  wire:loading.attr="disabled" class="btn btn-primary">Siguiente</button>
                      </form>
                    </div>
                  </div>
                </div>

                {{--Paso 2 subir el PDF--}}
                <div id="step2" style="display:none;">
                  @if($idNuevoCheque!==null)
                    @if($step3)
                    <script>
                      AddPDFChequeCFDI('{{$idNuevoCheque->_id}}');
                    </script>
                    @endif
                  @endif

                  {{--Input filepond--}}
                  <input name="addpdf" type="file" id="addpdf"/>

                  <div style="background-color: #61A2C8; color:white;"  class="alert  alert-dismissible mb-2" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="d-flex align-items-center" style="font-size: small;">
                      <i class="bx bx-check"></i>
                      <span>
                      ¡Se ha creado el cheque exitosamente!. Ahora agrega un comprobante de pago
                      @if($idNuevoCheque!==null)
                        @if($idNuevoCheque->tipomov == 'Cheque' || $idNuevoCheque->tipomov == 'Transferencia' || $idNuevoCheque->tipomov == 'Domiciliación')
                        , puedes subirlo mas tarde
                        aunque tu movimiento quedará con estatus de pendiente.
                        @else
                        puedes subirlo mas tarde, tu movimiento no requiere un comporbante de pago estrictamente aunque, es recomendable adjuntar alguno.
                        @endif
                      @endif
                      <i class='bx bxs-file-pdf'></i>
                      </span>
                    </div>
                  </div>

                  <br>

                  <button wire:click="Subirrela()" type="submit" class="btn btn-primary">Siguiente</button>
                </div>

                {{--Paso 3 agregar archivos relacionados--}}
                <div id="step3" style="display:none;">
                  @if($idNuevoCheque!==null)
                    @if(!$step3)
                    <script>
                      AddRelChequeCFDI('{{$idNuevoCheque->_id}}');
                    </script>
                    @endif
                  @endif

                  {{--Input filepond--}}
                  <input name="addadicion" type="file" id="addadicion"/>

                  <div style="background-color: #61A2C8; color:white;"  class="alert  alert-dismissible mb-2" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="d-flex align-items-center" style="font-size: small;">
                      <i class="bx bx-check"></i>
                      <span>
                       Sube documentos adicionales para complementar los detalles de tu movimiento.
                       <i class='fas fa-folder-open'></i>
                      </span>
                    </div>
                  </div>

                  <br>

                  <button type="button" wire:click="GotoChyT()" class="btn btn-secondary close-btn" data-dismiss="modal">Finalizar</button>
                </div>
              </div>
          </div>
      </div>
    </div>
</div>