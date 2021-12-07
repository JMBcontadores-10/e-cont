 @php
  $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $date = $dt->format('Y-m-d');

 @endphp

 <!-- MODDAL AJUSTE-->
<!-- si se necesita cambiar tamaño de modal agregar modal-lg a la linea
  <div class="modal-dialog"> por <div class="modal-dialog modal-lg">-->

  <!-- Modal-->
  <div class="modal fade" id="ajuste{{$n}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
           @if ($ajuste==0)
               <p>No existe ajuste </p>
           @else
            <p>Se realizo por: ${{ $ajuste }} mxn. </p>
            @endif

            @if ($verificado == 0)
         <form action="{{ url('cheques-transferencias') }}">
         <input type="hidden" name="id" value="{{ $id }}">
         <input type="number" step="any" name="ajuste" class="form-control">
         <input class="btn btn-primary btn-sm" type="submit" value="Ajustar"><br>
                </form>
            @endif
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
<!-- FIN MODDAL AJUSTE -->

 <!-- MODDAL COMENTARIOS-->
  <!-- Modal-->
  <div class="modal fade" id="exampleModal{{$n}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            @if (!empty($comentario))
           <p></p>
           <form action="{{ url('cheques-transferencias') }}" method="POST">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $id }}">
            <textarea name="comentario" cols="20" rows="2" class="form-control">{{$comentario}} </textarea><br>
            <input class="btn btn-primary btn-sm" type="submit" value="Aceptar">
            </form>
           @else
           <p> No hay comentarios asignados.<br>¿Deseas agregar un comentario?</p>
           <form action="{{ url('cheques-transferencias') }}" method="POST">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $id }}">
            <textarea name="comentario" cols="20" rows="2" class="form-control"></textarea><br>
            <input class="btn btn-primary btn-sm" type="submit" value="Aceptar">
            </form>


           @endif

        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
<!-- FIN MODAL COMENTARIOS -->

<!-- MODAL ARCHIVOS RELACIONADOS -->

<div class="modal fade" id="relacionados{{$n}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">


            @if (!$docAdi['0'] == '')

         <select id="{{ "docs-adicionales$n" }}" name="docs-adicionales"
         class="form-control mb-2">
         @foreach ($docAdi as $d)
         @php
         $newstring = ltrim(stristr($d, '-'), '-');
         @endphp
         <option value="{{ $d }}">{{ $newstring }}</option>
         @endforeach
        </select>
        <input id="ruta-adicionales" name="ruta-adicionales" type="hidden"
        value="{{ $rutaDescarga . 'Documentos_Relacionados/' }}">
          <input id="{{ $n }}" onclick="verAdicional(this.id)" type="submit"
              value="Ver">

              @endif

        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>

<!--FIN  MODAL ARCHIVOS RELACIONADOS -->

<!-- MODAL EDITAR /CHEQUES Y TRANSGERENCIAS-->

<div class="modal fade" id="editar{{$n}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        <!--BODY MODAL-->

        <div class="container">

            @php


            @endphp

            <div style="color:black;" ALIGN=center>

                    <div  ALIGN="center">
                        <h1 style="color:#035CAB">Editar Cheque/Transferencia</h1><br>

                       <form enctype="multipart/form-data" action="{{ url('archivo-pagar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="nombrec" value="{{ $nombreCheque }}">
                            <div style="overflow: auto">
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Forma de pago:</p>
                                    </div>
                                    <div class="col-4">
                                        <select name="tipo" class="form-control">
                                            <option {{ $tipo == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option {{ $tipo == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                            <option {{ $tipo == 'Domiciliación' ? 'selected' : '' }}>Domiciliación</option>
                                            <option {{ $tipo == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Numero de factura:</p>
                                    </div>
                                    <div class="col-4">
                                        <input class="form-control" type=text required name="numCheque"
                                            value="{{ $numCheque }}">
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Fecha de pago:</p>
                                    </div>
                                    <div class="col-4">
                                        <input class="form-control" type=date required name="fechaCheque"
                                            value="{{ $fecha }}" min="2014-01-01" max={{ $date }}>
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Total pagado:</p>
                                    </div>
                                    <div class="col-4">
                                        <!-- id valor que estba por defecto = number   -->
                                        <input class="form-control" id="" type="number" step="0.01" required name="importeCheque"
                                            value="{{ $importeC }}">
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Total factura(s):</p>
                                    </div>
                                    <div class="col-4">
                                        <input class="form-control" type=text required readonly name="importeT"
                                            value="{{ $sumaxml }}">
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Beneficiario:</p>
                                    </div>
                                    <div class="col-4">
                                        <input class="form-control" type=text required name="beneficiario"
                                            value="{{ $beneficiario }}">
                                    </div>
                                </div>
                                <div class="mainbox2 row">
                                    <div class="col-6 d-flex justify-content-end mt-2">
                                        <p class="pf">Tipo de operación:</p>
                                    </div>
                                    <div class="col-4">
                                        <select class="form-control" name="tipoOperacion">
                                            <option {{ $tipoO == 'Impuestos' ? 'selected' : '' }}>Impuestos</option>
                                            <option {{ $tipoO == 'Nómina' ? 'selected' : '' }}>Nómina</option>
                                            <option {{ $tipoO == 'Gasto y/o compra' ? 'selected' : '' }}>Gasto y/o
                                                compra
                                            </option>
                                            <option {{ $tipoO == 'Sin CFDI' ? 'selected' : '' }}>Sin CFDI</option>
                                            <option {{ $tipoO == 'Parcialidad' ? 'selected' : '' }}>Parcialidad
                                            </option>
                                            <option {{ $tipoO == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- id="{{ !$subirArchivo ? 'subir-archivo' : '' }}" id del div -->
                                <div  class="mainbox2 row mt-3">

                                    <div class="col-6 d-flex justify-content-end">
                                        <p class="pf">Ver Archivo Actual:</p>
                                    </div>
                                    <div class="col-4">

                                        <a id="rutArc" href="{{$rutaArchivo}}" target="_blank">
                                            <i class="fas fa-file-pdf fa-2x" style="color: rgb(202, 19, 19)"></i>
                                        </a>

                                    </div>

                                    <div class="col-6 d-flex justify-content-end">
                                        <p class="pf">Cambiar Archivo Actual (solo PDF):</p>
                                    </div>
                                    <div class="col-4">
                                        <input name="subir_archivo" type="file" accept=".pdf" />
                                    </div>
                                </div>


                                   <!-- id="{{ !$subirArchivo ? 'doc-relacionados' : '' }}"  id del div-->
                                <div  class="mainbox2 row mt-3">

                                    @foreach ($colCheques as $i)
                                    @php
                                     $rfc = $i['rfc'];
                                     $docrel=$i['doc_relacionados'];

                                    @endphp
                                     @endforeach
                                    <div class="col-6 d-flex justify-content-end">
                                        <p class="pf">Archivos Relacionados existentes:</p>
                                    </div>
                                 @if (!$docrel[0] == '' )
                                    <div class="col-4">



                                        <select id="{{ "docs-adicionales" }}" name="docs-adicionales"
                                            class="form-control mb-2">
                                            @foreach ($docrel as $d)
                                                @php
                                                    $newstring = ltrim(stristr($d, '-'), '-');
                                                @endphp
                                                <option value="{{ $d }}"><a>{{ $newstring }}</a></option>
                                            @endforeach
                                        </select>


                                    </div>

                                       @endif

                                       @if (empty($docrel[0]))
                                       <div class="col-4">
                                       <i class="far fa-times-circle fa-2x" style="color: rgb(255, 44, 44)"></i>
                                       </div>
                                       @endif





                                        <p class="mt-5 text-center">
                                    <div class="col-6 d-flex justify-content-end">
                                        <p class="pf">Actualizar Documentos Relacionados (solo PDF):</p>
                                    </div>
                                    <div class="col-4">
                                        <input name="doc_relacionados[]" type="file" accept=".pdf" multiple />
                                    </div>
                                </div>



                            </div>
                            <input type="submit" id="create-account-button" />
                            <button id="reg-cheque"  onclick="submitBlock()" class="btn btn-linkj mt-3">Actualizar Cheque/Transferencia</button>
                        </form>
                        <div id="respuesta" > </div>
                    </div>


            </div>


        </div>


        <!--BODY MODAL-->
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>



<!-- FIN MODAL EDITAR-->

