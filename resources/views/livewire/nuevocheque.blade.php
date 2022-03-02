<div>
  
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="nuevocheque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"> <span style="text-decoration: none;"  class="icons fas fa-upload"> Subir Archivoss</span></h6>
                    <button id="mdl" type="button"  wire:click="refreshh()"  class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <script> 
                     function refresca(){
                 document.getElementById("mdl").click();/// cerrar el modal dando click
                //  location.reload();
                     }
                     
                </script>
               <div class="modal-body" id="cargaArchivos"><!-- modal body -->
       
               

                    <div id="drop-zone">
                       <p class="mt-5 text-center">
                           <p class="pf">Archivos Adicionales (solo PDF):</p>
                           <!-- <label for="attachment">
                              <a class="btn btn-primary text-light " role="button" id="btnupload"  aria-disabled="false">Agregar.. <i class="fa fa-upload"></i></a>

                           </label>-->
                        <!--
                           <input wire:model.defer=""   name="doc_relacionados" type="file" accept=".pdf" id="attachment" style="visibility: hidden; position: absolute;" multiple />
                        -->
                        <input   name="avatar" type="file" id="avatar"  /><!--input filepond -->
                        <input type="hidden" id="user" name="user_id" value="">
                        <input type="hidden" name="nombre" value="avatar">
                    
                        {{-- <input type="text"> --}}
                       </p>
                       <p id="files-area">
                           <span id="filesList">
                               <span id="files-names"> </span>
                           </span>
                       </p>
                   </div>
                  


                   </div>


               <div class="modal-footer">
                <button type="button" wire:click="refreshh()" onclick="refresca()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
            </div>

               </div><!-- modal body -->
            

            </div>

        </div>
    </div>

    




</div>
