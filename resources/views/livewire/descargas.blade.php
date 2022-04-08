<div>
    {{-- Obtener los dias --}}
    @php
        //Recibidos
        
        //Recibimos el mes y el año
        $mesanioreci = $anioreci . '-' . $mesreci;
        //Con el mes y el año dados establecemos una fecha (en este caso es el dia primero)
        $fechareci = strtotime($mesanioreci . '-01');
        //Establecemos el total de dias que tiene la fecha creada
        $totaldayreci = date('t', $fechareci);
        
        //Emitidos inicial
        
        //Recibimos el mes y el año
        $mesanioemitinic = $anioemitinic . '-' . $mesemitinic;
        //Con el mes y el año dados establecemos una fecha (en este caso es el dia primero)
        $fechaemitinic = strtotime($mesanioemitinic . '-01');
        //Establecemos el total de dias que tiene la fecha creada
        $totaldayemitinic = date('t', $fechaemitinic);
        
        //Emitidos final
        
        //Recibimos el mes y el año
        $mesanioemitinic = $mesemitfin . '-' . $mesemitfin;
        //Con el mes y el año dados establecemos una fecha (en este caso es el dia primero)
        $fechaemitinic = strtotime($mesanioemitinic . '-01');
        //Establecemos el total de dias que tiene la fecha creada
        $totaldayemitinic = date('t', $fechaemitinic);
    @endphp

    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    {{-- Aqui va el contenido del modulo --}}
                    {{-- Encabezado del modulo --}}
                    <div class="justify-content-start">
                        <h1 style="font-weight: bold">{{ ucfirst(Auth::user()->nombre) }}</h1>
                        <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
                    </div>

                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @empty(!$empresas)
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:model="rfcEmpresa" id="inputState1" class="select form-control" wire:change="ObtAuth()">
                            <option value="">--Selecciona Empresa--</option>

                            {{-- Llenamos el select con las empresa vinculadas --}}
                            <?php $rfc = 0;
                            $rS = 1;
                            foreach ($empresas as $fila) {
                                echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                            } ?>
                        </select>

                        <br>
                    @endempty

                    {{-- Boton de inicio de sesión --}}
                    <button class="btn btn-success BtnVinculadas" wire:click="AuthEmpre()">Iniciar sesión</button>

                    <br>

                    {{-- Mensaje de alerta del inicio de sesion --}}
                    {{-- Mensaje correcto --}}
                    <div id="MnsSuccess">
                        <br>
                        <div class="alert alert-success">
                            <label class="Lblmsn"> - </label>
                        </div>
                    </div>

                    {{-- Mensaje error --}}
                    <div id="MnsDanger">
                        <br>
                        <div class="alert alert-danger">
                            <label class="Lblmsn"> - </label>
                        </div>
                    </div>

                    <br>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Iniciando sesión espere un momento....
                        <br>
                    </div>


                    {{-- Seccion de descargas --}}
                    {{-- Descargas --}}
                    <h1 style="font-weight: bold">Descarga</h1>

                    {{-- Select/mostrar calendario --}}
                    <div class="row">
                        {{-- Select para seleccionar el tipo de CFDI --}}
                        <div class="col-4">
                            <label for="selecttipocfdi">Tipo:</label>
                            <select wire:model="tipo" name="selecttipocfdi" id="selecttipocfdi"
                                class="select form-control">
                                <option value="">--Selecciona un tipo--</option>
                                <option value="Recibidos">Recibidos</option>
                                <option value="Emitidos">Emitidos</option>
                            </select>
                        </div>

                        {{-- Calendario de registros --}}
                        <div class="col-4">
                            <div id="espaciado" style="height: 23.5px"></div>
                            <div class="invoice-create-btn mb-1">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#calendariomodal"
                                    data-backdrop="static" data-keyboard="false">Calendario de registros</button>
                            </div>
                        </div>
                    </div>

                    <br>

                    {{-- Condicional para mostrar los filtros de cada tipo --}}
                    @if ($tipo == 'Recibidos')
                        {{-- Recibidos --}}
                        <label>Selecciona una fecha:</label>

                        {{-- Filtros de busqueda --}}
                        <div class="form-inline mr-auto">
                            {{-- Busqueda por dia --}}
                            <label for="diareci">Dia</label>
                            <select wire:model="diareci" id="diareci" wire:loading.attr="disabled"
                                class="select form-control">
                                @php
                                    for ($i = 1; $i <= $totaldayreci; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                @endphp
                            </select>
                            &nbsp;&nbsp;

                            {{-- Busqueda por mes --}}
                            <label for="mesreci">Mes</label>
                            <select wire:model="mesreci" id="mesreci" wire:loading.attr="disabled"
                                class=" select form-control">
                                <?php foreach ($meses as $key => $value) {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;


                            {{-- Busqueda por año --}}
                            <label for="anioreci">Año</label>
                            <select wire:loading.attr="disabled" wire:model="anioreci" id="anioreci"
                                class="select form-control">
                                <?php foreach (array_reverse($anios) as $value) {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                } ?>
                            </select>
                            &nbsp;&nbsp;
                        </div>
                    @elseif ($tipo == 'Emitidos')
                        {{-- Emitidos --}}
                        <label>Selecciona un rango de fecha:</label>

                        {{-- Rango de incio --}}

                        <div class="row">
                            <div class="col">
                                <label>Fecha inicial:</label>
                                {{-- Filtros de busqueda --}}
                                <div class="form-inline mr-auto">
                                    {{-- Busqueda por dia --}}
                                    <label for="diaemitinic">Dia</label>
                                    <select wire:model="diaemitinic" id="diaemitinic" wire:loading.attr="disabled"
                                        class="select form-control">
                                        @php
                                            for ($i = 1; $i <= $totaldayemitinic; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        @endphp
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitinic">Mes</label>
                                    <select wire:model="mesemitinic" id="mesemitinic" wire:loading.attr="disabled"
                                        class=" select form-control">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;


                                    {{-- Busqueda por año --}}
                                    <label for="anioemitinic">Año</label>
                                    <select wire:loading.attr="disabled" wire:model="anioemitinic" id="anioemitinic"
                                        class="select form-control">
                                        <?php foreach (array_reverse($anios) as $value) {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="col">
                                {{-- Rango de fin --}}

                                <label>Fecha final:</label>
                                {{-- Filtros de busqueda --}}
                                <div class="form-inline mr-auto">
                                    {{-- Busqueda por dia --}}
                                    <label for="diaemitfin">Dia</label>
                                    <select wire:model="diaemitfin" id="diaemitfin" wire:loading.attr="disabled"
                                        class="select form-control">
                                        @php
                                            for ($i = 1; $i <= $totaldayemitinic; $i++) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        @endphp
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por mes --}}
                                    <label for="mesemitfin">Mes</label>
                                    <select wire:model="mesemitfin" id="mesemitfin" wire:loading.attr="disabled"
                                        class=" select form-control">
                                        <?php foreach ($meses as $key => $value) {
                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;

                                    {{-- Busqueda por año --}}
                                    <label for="anioemitfin">Año</label>
                                    <select wire:loading.attr="disabled" wire:model="anioemitfin" id="anioemitfin"
                                        class="select form-control">
                                        <?php foreach (array_reverse($anios) as $value) {
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Si no se selecciono nada --}}
                    @endif

                </section>
            </div>
        </div>
    </div>

    <script>
        //Mostramos el mensaje de confirmacion de inico de sesion
        window.addEventListener('mnssesion', event => {
            //Alamacenamos el mensaje y el estado en variables
            var mensconfirm = event.detail.mns;
            var statemns = event.detail.state;

            //Funcion para ocultar los mensajes
            function hidemsnconfi() {
                $("#MnsDanger").hide();
                $("#MnsSuccess").hide();
            }

            //Condicional para saber si hay un mensaje
            if (mensconfirm !== null) {
                //Switch para saber si es un mensaje satisfactorio o de error
                switch (statemns) {
                    case 0:
                        //Mostramos el mensaje deseado y ocultamos el no deseado
                        $("#MnsDanger").show();
                        $("#MnsSuccess").hide();

                        //Mostramos label con el mensaje
                        $(".Lblmsn").text(mensconfirm);

                        //Escondemos el mensaje despues de 5 segundos
                        setTimeout(() => {
                            hidemsnconfi();
                        }, 5000);
                        break;

                    case 1:
                        //Mostramos el mensaje deseado y ocultamos el no deseado
                        $("#MnsDanger").hide();
                        $("#MnsSuccess").show();

                        //Mostramos label con el mensaje
                        $(".Lblmsn").text(mensconfirm);

                        //Escondemos el mensaje despues de 5 segundos
                        setTimeout(() => {
                            hidemsnconfi();
                        }, 5000);
                        break;
                }
            }
        });
    </script>

    {{-- Modal del calendario --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="calendariomodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-calendar">Calendario</span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="RefreshCal()">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Seccion del calendario --}}
                    {{-- Fecha de hoy --}}
                    <div id="contfechahoy" align="center">
                        @php
                            //Swich para convertir Int mes en String
                            switch ($mescal) {
                                case 1:
                                    $mescal = 'Enero de ';
                                    break;
                                case 2:
                                    $mescal = 'Febrero de ';
                                    break;
                                case 3:
                                    $mescal = 'Marzo de ';
                                    break;
                                case 4:
                                    $mescal = 'Abril de ';
                                    break;
                                case 5:
                                    $mescal = 'Mayo de ';
                                    break;
                                case 6:
                                    $mescal = 'Junio de ';
                                    break;
                                case 7:
                                    $mescal = 'Julio de ';
                                    break;
                                case 8:
                                    $mescal = 'Agosto de ';
                                    break;
                                case 9:
                                    $mescal = 'Septiembre de ';
                                    break;
                                case 10:
                                    $mescal = 'Octubre de ';
                                    break;
                                case 11:
                                    $mescal = 'Noviembre de ';
                                    break;
                                case 12:
                                    $mescal = 'Diciembre de ';
                                    break;
                            }
                        @endphp

                        <h3>{{ $mescal }} {{ $aniocal }}</h3>
                    </div>

                    <br>

                    {{-- Calendario --}}
                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">
                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select wire:model="mescal" id="inputState1" wire:loading.attr="disabled"
                            class="select form-control">
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select wire:loading.attr="disabled" wire:model="aniocal" id="inputState2"
                            class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;
                    </div>

                    <br>

                    {{-- Formato calendario --}}
                    <div class="table-responsive">
                        <table class="table table-bordered calemitreci">
                            <thead>
                                <tr>
                                    <th>Domingo</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miercoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sabado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    foreach ($weeks as $week) {
                                        echo $week;
                                    }
                                @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
