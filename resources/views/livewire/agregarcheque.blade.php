<div>{{-- - DIV PRINCIPAL --}}
    @php
        $date = date('Y-m-d');
    @endphp

    <!-- Modal -->

    <script>
        window.addEventListener('cier', event => {

            document.getElementById("mdla").click();
            if ($('.modal-backdrop').is(':visible')) {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }

        });




        window.addEventListener('step2', event => {

            p = document.getElementById("step1");


            $("#step1").fadeOut("slow");




            document.getElementById("step2").style.display = "visible";
            $("#step2").fadeIn("slow");



        });

        window.addEventListener('step3', event => {





            document.getElementById("step1").style.display = "hidden";
            $("#step1").fadeOut("slow");

            document.getElementById("step2").style.display = "hidden";
            $("#step2").fadeOut("slow");

            document.getElementById("step3").style.display = "visible";
            $("#step3").fadeIn("slow");



        });
    </script>



    <div wire:ignore.self class="modal fade" id="nuevo-cheque" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;"
                            class="icons fas fa-plus">&nbsp;Agrega Cheque/Transferencia</span></h6>
                    @if ($idNuevoCheque !== null)
                        <button id="mdla" type="button" wire:click="refresh2()" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    @else
                        <button id="mdla" type="button" wire:click="refresh2()" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    @endif

                </div>
                <div class="modal-body">
                    <!--modal body -->
                    @if (isset($chequesAsignados))
                        {{ var_dump($chequesAsignados) }}
                    @endif

                    <!-- arreglo_cuentas -->
 <!--si $uuids_cuentas esta vacio-->


                    @if (session()->get('cuentas'))
                        {{-- <div class="alert alert-success">
</div> --}}
                        @php

                            $name = Session::get('cuentas');
                            //    echo $name;
                        @endphp

          @endif
                    {{-- <div class="steps">
  <div class="active_step step_desing"> <li  style="color:white;"class="icons fas fa-plus"></li><br>Información Basica</div>

  <div class=" step_desing"><li  style="color:white;"class="icons fas fa-plus"></li><br>Información Basica</div>
  <div class=" step_desing"><li  style="color:white;"class="icons fas fa-plus"></li><br>Información Basica</div>
  </div> --}}

                    <!--  setep 1 inicio -->
                    <div id="step1">

                        {{-- <h2><strong>C</strong></h2> --}}
                        {{-- @if ($folio != 'vacio')
{{$folio}}
{{$rfc}}
{{$fecha}}

@else
<p>  esta vacio</p>
@endif --}}
                        <p>Llena los campos correspondientes</p>
                        <div class="row">
                            <div class="col-md-12 mx-0">



                                <!----------------------------------- ---->
                                <div ALIGN="center">




                                    <form wire:submit.prevent="guardar_nuevo_cheque">


                                        @csrf
                                        <div class="form-row">

                                            @if (auth()->user()->tipo || auth()->user()->TipoSE)


                                                @if (isset($folio))
                                                    <label for="inputState">Empresa</label>

                                                    <select wire:model="rfcEmpresa" id="empresas"
                                                        class=" select form-control" required disabled>
                                                        <option value="">--Selecciona Empresa--</option>
                                                        <?php $rfc = 0;
                                                        $rS = 1;
                                                        foreach ($empresas as $fila) {
                                                            echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                @else
                                                    <label for="inputState">Empresa</label>
                                                    <select wire:model="rfcEmpresa" id="empresas"
                                                        class=" select form-control" required>
                                                        <option value="">--Selecciona Empresa--</option>
                                                        <?php $rfc = 0;
                                                        $rS = 1;
                                                        foreach ($empresas as $fila) {
                                                            echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                @endif



                                            @endif


                                            {{-- <script>

    $('#empresas').on('change', function() {

//alert('hola');
document.getElementById("tipo").disabled = false;

});


           </script> --}}


                                            <div class="form-group col-md-6">
                                                {{-- -tooltip- --}}
                                                <i id="info" class=" fa fa-info-circle" aria-hidden="true"></i>
                                                <span id="pago" class="tooltiptext">Como fue que realizó el
                                                    pago.</span>
                                                {{-- --tootip--- --}}
                                                <label for="inputEmail4">Forma de pago</label>

                                                <select wire:model="Nuevo_tipomov" name="tipo" id="tipo"
                                                    class="agregarInputs form-control" required>
                                                    <option value="">--Selecciona Forma--</option>
                                                    <option>Cheque</option>
                                                    <option>Transferencia</option>
                                                    <option>Domiciliación</option>
                                                    <option>Efectivo</option>
                                                    <option>Débito</option>
                                                </select>
                                            </div>


                                            <div class="form-group col-md-6">
                                                {{-- -tooltip- --}}
                                                <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span id="factura" class="tooltiptext">En caso de no tener un número
                                                    de factura,
                                                    escriba brevemente que es lo que está pagando.
                                                    Si se trata de un cheque, también escriba número de cheque.</span>
                                                {{-- -tooltip- --}}
                                                <label for="inputPassword4">#Factura</label>
                                                <input class="form-control" type="text" name="Nuevo_numCheque"
                                                    placeholder="Describa lo que está pagando"
                                                    wire:model="Nuevo_numcheque" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{-- -tooltip- --}}
                                            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                                            <span id="fecha" class="tooltiptext">Escriba la fecha en que realizó el
                                                pago.</span>
                                            {{-- -tooltip- --}}
                                            <label for="inputAddress">Fecha de pago</label>
                                            <input class="form-control" id="fecha" wire:model="Nuevo_fecha"
                                                type="date" min="2014-01-01" max={{ $date }} required>

                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                {{-- -tooltip- --}}
                                                <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span id="pagado" class="tooltiptext">La cantidad que se pagó con
                                                    pesos y centavos.</span>
                                                {{-- --tootip--- --}}
                                                <label for="inputEmail4">Total pagado</label>

                                                <input class="form-control" wire:model="Nuevo_importecheque"
                                                    type="number" step="0.01" placeholder="pesos y centavos Ej. 98.50"
                                                    name="importeCheque">
                                            </div>
                                            <div class="form-group col-md-6">
                                                {{-- -tooltip- --}}

                                                {{-- -tooltip- --}}
                                                <label for="inputPassword4">Total factura(s):</label>
                                                <input class="form-control" type="text" readonly name="importeT"
                                                    value="${{ number_format(floatval($totalfactu), 2) }}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                {{-- -tooltip- --}}
                                                <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span id="beneficiario" class="tooltiptext"> Razón social a quien
                                                    realizó el pago.
                                                </span>
                                                {{-- -tooltip- --}}
                                                <label for="inputCity">Beneficiario</label>
                                                <input class="form-control" wire:model="Nuevo_beneficiario"
                                                    type="text" name="beneficiario"
                                                    placeholder="A quien realizó el pago" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                {{-- -tooltip- --}}
                                                <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                                                <span id="operacion" class="tooltiptext"> Seleccione que fue lo que
                                                    pagó.
                                                </span>
                                                {{-- -tooltip- --}}
                                                <label for="inputState">Tipo de operación</label>
                                                <select wire:model="Nuevo_tipoopera" class="form-control"
                                                    name="tipoOperacion" required>
                                                    <option value="">--Selecciona tipo--</option>
                                                    <option>Impuestos</option>
                                                    <option>Nómina</option>
                                                    <option>Gasto y/o compra</option>
                                                    <option>Sin CFDI</option>
                                                    <option>Parcialidad</option>
                                                    <option>Otro</option>
                                                </select>
                                            </div>



                                        </div>

                                        <div wire:loading wire:target="guardar_nuevo_cheque">
                                            <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                                                <div></div>
                                                <div></div>

                                            </div>
                                            Creando cheque ...
                                        </div>

                                        <button type="submit" wire:loading.attr="disabled"
                                            class="btn btn-primary">Siguiente</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  setep 1 fin -->
                    <div id="step2" style="display:none;">
                        @if ($idNuevoCheque !== null)

                            {{-- {{$idNuevoCheque->_id}} --}}



                            @if ($step3)
                                {{-- {{"estep:".$step3}} --}}
                                <script>
                                    filepondNuevoCheque('{{ $idNuevoCheque->_id }}');
                                </script>
                            @endif

                        @endif

                        <input name="nuevoCheque" type="file" id="nuevoCheque" />
                        <!--input filepond -->
                        <div style="background-color: #61A2C8; color:white;" class="alert  alert-dismissible mb-2"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex align-items-center" style="font-size: small;">
                                <i class="bx bx-check"></i>
                                <span>
                                    ¡Se ha creado el cheque exitosamente!. Ahora agrega un comprobante de pago
                                    @if ($idNuevoCheque !== null)
                                        @if ($idNuevoCheque->tipomov == 'Cheque' || $idNuevoCheque->tipomov == 'Transferencia' || $idNuevoCheque->tipomov == 'Domiciliación')
                                            , puedes subirlo mas tarde
                                            aunque tu movimiento quedará con estatus de pendiente.
                                        @else
                                            puedes subirlo mas tarde, tu movimiento no requiere un comporbante de pago
                                            estrictamente aunque, es recomendable adjuntar alguno.
                                        @endif
                                    @endif
                                    <i class='bx bxs-file-pdf'></i>
                                </span>
                            </div>
                        </div>


                        <button wire:click="step3()" type="submit" class="btn btn-primary">Siguiente</button>

                    </div>
                    <div id="step3" style="display:none;">

                        @if ($idNuevoCheque !== null)

                            {{-- {{$idNuevoCheque->_id}} --}}
                            @if (!$step3)
                                <script>
                                    filepondAdicionalesNuevoCheque('{{ $idNuevoCheque->_id }}');
                                </script>
                            @endif
                        @endif

                        <input name="adicionalesNuevoCheque" type="file" id="adicionalesNuevoCheque" />
                        <!--input filepond -->
                        <div style="background-color: #61A2C8; color:white;" class="alert  alert-dismissible mb-2"
                            role="alert">
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

                        <button type="button" wire:click="refresh()" class="btn btn-secondary close-btn"
                            data-dismiss="modal">Finalizar</button>

                        {{-- <button wire:click="step3()" type="submit" class="btn btn-primary"></button> --}}


                    </div>








                    <!-------------------------------------------- -->






                </div> <!-- fin modal body -->


            </div>
        </div>
    </div>






</div>{{-- --FIN DIV PRINCIPAL-- --}}
