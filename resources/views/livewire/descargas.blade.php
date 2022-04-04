<div>
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

            </section>
          </div>
        </div>
    </div>
</div>
