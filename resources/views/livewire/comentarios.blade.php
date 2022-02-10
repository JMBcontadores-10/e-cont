<div>
    


<span class="tooltip-content">Comentarios</span>
     <!-- Modal -->

     <div wire:ignore.self class="modal fade" id="comentarios-{{$datos->_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-comments"> Comentarios</span></h6>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true close-btn">X</span>
                     </button>
                 </div>
 <div class="modal-body"><!--modal body -->

    <form  wire:submit.prevent="guardar">
        @csrf     

    @if (!empty($datos->comentario))
    <p></p>
    
     <input type="hidden" id="id" name="id" value="{{ $datos->id }}">
     <textarea wire:model.defer="comentarioCheque.comentario" cols="20" rows="2" class="form-control">{{$datos->comentario}} </textarea><br>
    


    @else
    <p> No hay comentarios asignados.<br>¿Deseas agregar un comentario?</p>
   
     
     <textarea wire:model.defer="comentarioCheque.comentario" cols="20" rows="2" class="form-control"></textarea><br>


    @endif
    
    <div wire:loading wire:target="guardar" >
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
          <div></div>
          <div></div>
          
      </div>
      Guardando cambios
      </div>

    <button type="submit"  wire:click="guardar()" class="btn btn-primary close-modal">Guardar Cambios</button>

             </div>  <!-- fin modal body -->
            </form>
         
         </div>
     </div>

 </div>
</div>
