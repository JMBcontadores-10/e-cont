<div>{{--- DIV PRINCIPAL--}}
    @php
        $date=date('Y-m-d');
    @endphp

 <!-- Modal -->

 <div wire:ignore.self class="modal fade" id="nuevo-cheque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-plus">&nbsp;Agrega Cheque/Transferencia</span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
<div class="modal-body"><!--modal body -->


    {{--<h2><strong>C</strong></h2>--}}
    <p>llena los campos y da click en siguiente</p>
    <div class="row"> 
        <div class="col-md-12 mx-0">
          
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active" id="account"><strong>Información</strong></li>
                    <li id="personal"><strong>PDF/cheque</strong></li>
                    <li id="payment"><strong>PDF /relacionados</strong></li>
                    <li id="confirm"><strong>Finalizar</strong></li>
                </ul> <!-- fieldsets -->
                <fieldset>
                    

<!----------------------------------- ---->
<div  ALIGN="center">
       

 
      
    <form  wire:submit.prevent="guardar_nuevo_cheque">
        @csrf     
        <div class="form-row">
          <div class="form-group col-md-6">
              {{---tooltip---}}
              <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
              <span id="pago" class="tooltiptext">Como fue que realizó el pago.</span>
              {{----tootip-----}}
            <label for="inputEmail4">Forma de pago</label>
 
            <select wire:model="Nuevo_tipomov" name="tipo" id="tipo" class="form-control">
                <option>Cheque</option>
                <option>Transferencia</option>
                <option>Domiciliación</option>
                <option>Efectivo</option>
            </select>
          </div>
          <div class="form-group col-md-6">
                {{---tooltip---}}
            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
            <span  id="factura" class="tooltiptext">En caso de no tener un número de factura,
            escriba brevemente que es lo que está pagando.
            Si se trata de un cheque, también escriba número de cheque.</span>
              {{---tooltip---}}
            <label for="inputPassword4">#Factura</label>
            <input class="form-control" type=text  name="Nuevo_numCheque" 
            placeholder="Describa lo que está pagando" wire:model="Nuevo_numcheque">
          </div>
        </div>
        <div class="form-group">
            {{---tooltip---}}
            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
         <span id="fecha" class="tooltiptext">Escriba la fecha en que realizó el pago.</span>
         {{---tooltip---}}
          <label for="inputAddress">Fecha de pago</label>
          <input class="form-control" id="fecha" wire:model="Nuevo_fecha"  type=date   min="2014-01-01"
          max={{ $date }}   >

        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                {{---tooltip---}}
                <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
                        <span id="pagado" class="tooltiptext">La cantidad que se pagó con pesos y centavos.</span>
                {{----tootip-----}}
              <label for="inputEmail4">Total pagado</label>
   
              <input class="form-control" wire:model="Nuevo_importecheque" type="number"  step="0.01" placeholder="pesos y centavos Ej. 98.50" name="importeCheque">
            </div>
            <div class="form-group col-md-6">
                  {{---tooltip---}}
          
                {{---tooltip---}}
              <label for="inputPassword4">Total factura(s):</label>
              <input class="form-control" type=text  readonly name="importeT"
                            value="">
            </div>
          </div>
        <div class="form-row">
          <div class="form-group col-md-6">
              {{---tooltip---}}
            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
            <span id="beneficiario" class="tooltiptext"> Razón social a quien realizó el pago.
            </span>
            {{---tooltip---}}
            <label for="inputCity">Beneficiario</label>
            <input class="form-control" wire:model="Nuevo_beneficiario" type=text name="beneficiario"
               placeholder="A quien realizó el pago">
          </div>
          <div class="form-group col-md-4">
              {{---tooltip---}}
            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
            <span id="operacion" class="tooltiptext"> Seleccione que fue lo que pagó.
            </span>
            {{---tooltip---}}
            <label for="inputState">Tipo de operación</label>
            <select wire:model="Nuevo_tipoopera" class="form-control" name="tipoOperacion">
                <option>Impuestos</option>
                <option>Nómina</option>
                <option>Gasto y/o compra</option>
                <option>Sin CFDI</option>
                <option>Parcialidad</option>
                <option>Otro</option>
            </select>
          </div>
       


        </div>
        <div class="form-group">
            <div class="form-group col-md-12">
            {{---tooltip---}}
            <i id="info" class="fa fa-info-circle" aria-hidden="true"></i>
            <span id="pdf" class="tooltiptext"> Sube un comprobante del movimiento.
            </span>
         {{---tooltip---}}
          <label for="inputAddress">Comprobante (PDF)</label>
          <div class="custom-file">
            <input type="file"  wire:model="Nuevo_nombrec" class="custom-file-input" id="customFileLang" lang="es">
            <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
          </div>
            </div>
        </div>

    

        <div id="drop-zone">
            <p class="mt-5 text-center">
                <p class="pf">Archivos Adicionales (solo PDF):</p>
                <label for="attachment">
                    <a class="btn btn-primary text-light " role="button" id="btnupload"  aria-disabled="false">Agregar.. <i class="fa fa-upload"></i></a>

                </label>

                <input  wire:model="pushArchivos"  type="file" accept=".pdf" id="attachment" style="visibility: hidden; position: absolute;" multiple />

            </p>
            <p id="files-area">
                <span id="filesList">
                    <span id="files-names"></span>
                </span>
            </p>
        </div>




        <button type="submit" class="btn btn-primary">Sign in</button>
    
    </form>
            


                
        
  
</div>

<!-------------------------------------------- -->
                  <input type="button" name="next" class="next action-button" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <div class="form-card">
                        <h4>Adjunta un comprobante (PDF)</h4> 
                        <div id="drop-zone">
                       
                        <input name="agregarCheque"   id="agregarCheque"  />
                        </div>
                        <h4>Adjunta un comprobante (PDF)</h4> 
                        <div id="drop-zone">
                            <input name="agregarCheque_relacionados"   id="agregarCheque_relacionados"  />

                            </div>
                    </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" class="next action-button" value="Next Step" />
                </fieldset>
                <fieldset>
                    <div class="form-card">
                        <h4 class="fs-title">Documentos Adicionales</h4>
                        <div class="radio-group">
                            <div class='radio' data-value="credit"><img src="https://i.imgur.com/XzOzVHZ.jpg" width="200px" height="100px"></div>
                            <div class='radio' data-value="paypal"><img src="https://i.imgur.com/jXjwZlj.jpg" width="200px" height="100px"></div> <br>
                        </div> <label class="pay">Card Holder Name*</label> <input type="text" name="holdername" placeholder="" />
                        <div class="row">
                            <div class="col-9"> <label class="pay">Card Number*</label> <input type="text" name="cardno" placeholder="" /> </div>
                            <div class="col-3"> <label class="pay">CVC*</label> <input type="password" name="cvcpwd" placeholder="***" /> </div>
                        </div>
                        <div class="row">
                            <div class="col-3"> <label class="pay">Expiry Date*</label> </div>
                            <div class="col-9"> <select class="list-dt" id="month" name="expmonth">
                                    <option selected>Month</option>
                                    <option>January</option>
                                    <option>February</option>
                                    <option>March</option>
                                    <option>April</option>
                                    <option>May</option>
                                    <option>June</option>
                                    <option>July</option>
                                    <option>August</option>
                                    <option>September</option>
                                    <option>October</option>
                                    <option>November</option>
                                    <option>December</option>
                                </select> <select class="list-dt" id="year" name="expyear">
                                    <option selected>Year</option>
                                </select> </div>
                        </div>
                    </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" class="next action-button" value="Confirm" />
                </fieldset>
                <fieldset>
                    <div class="form-card">
                        <h2 class="fs-title text-center">Success !</h2> <br><br>
                        <div class="row justify-content-center">
                            <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                        </div> <br><br>
                        <div class="row justify-content-center">
                            <div class="col-7 text-center">
                                <h5>You Have Successfully Signed Up</h5>
                            </div>
                        </div>
                    </div>
                </fieldset>
            
        </div>
    </div>













        </div>  <!-- fin modal body -->
 
    
    </div>
</div>




</div>





</div>{{----FIN DIV PRINCIPAL----}}
