<div>
    @php
      //Llamamos a los modelos requeridos
      use App\Models\MetadataR;
      use App\Models\ListaNegra;
      use App\Models\XmlR;
      use App\Models\Cheques;
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


    {{--Llamamos a las modales--}}
    {{--Modal de detalles de cuentas por pagar--}}
    {{--Creacion del modal--}}
    <div wire:ignore.self class="modal fade" id="detalles{{$facturas->emisorRfc}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{--Encabezado--}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;" class="icons fas fa-folder-open">Cuentas por pagar</span></h6>
                    <h6  class="modal-title" id="exampleModalLabel"><span> Total seleccionado: $</span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                          $RFC = $this->facturas->emisorRfc;
  
                          //Se asignan las rutas donde está almacenando
                          $rutaXml = "storage/contarappv1_descargas/$RFC/$anio/Descargas/$numero.$mees/Recibidos/XML/$folioF.xml";
                          $rutaPdf = "storage/contarappv1_descargas/$RFC/$anio/Descargas/$numero.$mees/Recibidos/PDF/$folioF.pdf";
  
  
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
                              <input wire:loading.attr="disabled" id="Chk{{$FolioCFDI->folioFiscal}}" class="form-check-input" type="checkbox" wire:click="SumFactu()" wire:model="movivinc" value="{{$FolioCFDI->folioFiscal}}">
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
</div>
