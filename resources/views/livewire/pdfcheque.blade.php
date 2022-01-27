<div>

    



 
     <!-- Modal -->

     <div wire:ignore.self class="modal fade" id="pdfcheque{{$datos->id}}"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h6 class="modal-title" id="exampleModalLabel">  <span style="text-decoration: none;"  class="icons fas fa-balance-scale"> Ver PDF</span></h6>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true close-btn">×</span>
                     </button>
                 </div>

                 @if (session()->has('flash'))
                 <script>



//$("[data-dismiss=modal]").trigger({ type: "click" });// cerrar modal por data-dismiss.:)



                 </script>


                 @endif


 <div class="modal-body"><!--modal body -->
{{$datos->numcheque}}




<div class="dropzone">
    <p   class="mt-5 text-center">
        <p class="pf">Archivo Exsitente:</p>

    </p>
    <p id="files-area">
        <span id="filesList">
            <div class="wrapper" >


                @php $n=1; @endphp

     @if($datos->nombrec =="0")

    
        <h4>  No hay archivo </h4>
        
        

        <input   name="editCheque" type="file" id="editCheque{{$datos->id}}"  />
     @else
    
     <div class="b" id="c" >

        <input id="rutaAdicional" name="ruta-adicionales" type="hidden"
        value="">

        <input id="iden" type="hidden" value="{{ $datos->nombrec}}" >

            <button style="margin-bottom: -20px;" class="fabutton" id="" onclick="verPdf(this.id)" type="submit">
                <a title={{ $datos->nombrec}}  class="alert fas fa-file-pdf"></a> </button>


            <span> {{Str::limit(Str::afterLast($datos->nombrec, '&'), 10); }} <span>


    <hr>

<button   wire:click="eliminar('{{$datos->id}}')" wire:loading.attr="disabled"  style="margin-top:-20px;" class="fabutton" >
<i class="icons fas fa-trash-alt"></i> </button>


<!-- <hr>
        <button style="margin-top:-20px;" class="fabutton" wire:click="" type="submit">
            <i class="icons fas fa-eye"></i> </button>-->



     </div>
@endif





    </span>
    </p>
</div>


                       

<div wire:loading wire:target="eliminar" >
    <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
      <div></div>
      <div></div>
      
  </div>
  Eliminando archivo
  </div>
<br>
<br>





<div class="modal-footer">
    <button type="button" name="cierra" wire:click.prevent="refresh()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

</div>



</div>


</div>
</div>




</div>







</div>
