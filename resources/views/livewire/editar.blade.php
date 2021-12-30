<div>

    <button type="button" class="fabutton" id="{{$datos->_id}}" onclick="filepondEditCheque(this.id)" data-toggle="modal" data-target="#editar-{{$datos->_id}}">
        <a class="fas fa-edit fa-lg" > </a>
    </button>

    @php
  $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $date = $dt->format('Y-m-d');

 @endphp

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="editar-{{$datos->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"  class="icons fas fa-edit fa-lg"> Editar</span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                @if($errors->any())
                <div class="alert alert-danger d-block mt-3"> </div>
                <ul>
                @foreach  ($errors->all() as $error)
                <li> {{$error}} </li>
                @endforeach
                </ul>
               </div>
               @endif

               @if (session()->has(''))

               <script>
                  $(document).ready(function() {
   $('#editar-{{$datos->_id}}').modal("hide")
         });

                   </script>
             @endif

             <script>

function miFunc() {
    location.reload();
  }
                  </script>


                    <form  wire:submit.prevent="actualizar">
                        @csrf
                       <!-- <div class="form-group">
                            <label for="exampleFormControlInput1">Nombre</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="editCheque.numcheque">
                            @error('editCheque.numcheque') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>-->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            
                            <label for="exampleFormControlInput1">Forma de pago</label>

                            <select name="tipo" class="form-control" wire:model="editCheque.tipomov">
                                <option {{ $datos->tipomov == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                <option {{ $datos->tipomov == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                <option {{ $datos->tipomov == 'Domiciliación' ? 'selected' : '' }}>Domiciliación</option>
                                <option {{ $datos->tipomov == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>


                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlInput1">Numero de factura</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="editCheque.numcheque">
                            @error('numcheque') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                    </div><!-- fin group 1--> 
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlInput1">Fecha de pago</label>
                        <input class="form-control" type=date  wire:model="editCheque.fecha" name="fechaCheque"
                         min="2014-01-01" max={{ $date }}>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleFormControlInput1">Total pagado</label>
                        <input class="form-control" id="" type="number" step="0.01"  name="importeCheque"
                        wire:model="editCheque.importecheque">
                </div>
                    </div><!-- fin group2 -->
                <div class="form-group">
                    <label for="exampleFormControlInput1">Beneficiario</label>
                <input class="form-control" type=text   name="beneficiario"
                wire:model="editCheque.Beneficiario">
                </div>

            <div class="form-row"><!-- group 3-->
                <div class="form-group col-md-6">
                    <label for="exampleFormControlInput1">Tipo de operación</label>

                    <select name="tipopera" class="form-control" wire:model="editCheque.tipoopera">
                        <option {{ $datos->tipoopera == 'Impuestos' ? 'selected' : '' }}>Impuestos</option>
                        <option {{ $datos->tipoopera == 'Nómina' ? 'selected' : '' }}>Nómina</option>
                        <option {{$datos->tipoopera == 'Gasto y/o compra' ? 'selected' : '' }}>Gasto y/o
                            compra
                        </option>
                        <option {{ $datos->tipoopera == 'Sin CFDI' ? 'selected' : '' }}>Sin CFDI</option>
                        <option {{ $datos->tipoopera == 'Parcialidad' ? 'selected' : '' }}>Parcialidad
                        </option>
                        <option {{ $datos->tipoopera == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>


                </div>

                <div class="form-group col-md-6">
                    
                </div>

            </div>
            <div class="form-group col-md-12">

                <div id="drop-zone">
                    <p class="mt-5 text-center">
                        <p class="pf">Actualizar Cheque/Transferencia (solo PDF):</p>
                   

                          <input  type="file" accept=".pdf"
                        wire:model="editChequenombrec" class="btn text-dark " />
                    </p>
                    <p id="files-area">
                        <span id="filesList">
                            <span id="files-names"></span>
                        </span>
                    </p>
                </div>

            </div>

            <!--<input    name="editCheque" type="file" id="editCheque{{$datos->_id}}"  />-->

                <div wire:loading wire:target="actualizar" >
                    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                      <div></div>
                      <div></div>
                      
                  </div>
                  Guardando cambios
                  </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
                    <button type="submit"  wire:click="actualizar()" class="btn btn-primary close-modal">Guardar Cambios</button>
                </div>
            </form>

            </div>

        </div>
    </div>





</div>

