<div>
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

                    <br>

                    {{-- Select para selccionar la empresa (Contadores) --}}
                    @empty(!$empresas)
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Empresa: {{ $empresa }}</label>
                        <select wire:model="rfcEmpresa" id="inputState1" class="select form-control">
                            <option value="">--Selecciona Empresa--</option>

                            {{-- Llenamos el select con las empresa vinculadas --}}
                            <?php $rfc = 0;
                            $rS = 1;
                            foreach ($empresas as $fila) {
                                echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                            } ?>
                        </select>
                    @endempty

                    <br>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    <br>

                    {{-- Seccion del calendario --}}
                    {{-- Fecha de hoy --}}
                    <div id="contfechahoy" align="center">
                        @php
                            //Swich para convertir Int mes en String
                            switch ($mescal) {
                                case 1:
                                    $mescalstr = 'Enero de ';
                                    break;
                                case 2:
                                    $mescalstr = 'Febrero de ';
                                    break;
                                case 3:
                                    $mescalstr = 'Marzo de ';
                                    break;
                                case 4:
                                    $mescalstr = 'Abril de ';
                                    break;
                                case 5:
                                    $mescalstr = 'Mayo de ';
                                    break;
                                case 6:
                                    $mescalstr = 'Junio de ';
                                    break;
                                case 7:
                                    $mescalstr = 'Julio de ';
                                    break;
                                case 8:
                                    $mescalstr = 'Agosto de ';
                                    break;
                                case 9:
                                    $mescalstr = 'Septiembre de ';
                                    break;
                                case 10:
                                    $mescalstr = 'Octubre de ';
                                    break;
                                case 11:
                                    $mescalstr = 'Noviembre de ';
                                    break;
                                case 12:
                                    $mescalstr = 'Diciembre de ';
                                    break;
                            }
                        @endphp

                        <h3>{{ $mescalstr }} {{ $aniocal }}</h3>
                    </div>

                    <br>

                    {{-- Calendario --}}
                    <div class="form-inline mr-auto">
                        {{-- Filtros de busqueda --}}

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
                        &nbsp;&nbsp;&nbsp;&nbsp;

                        {{-- Historico --}}
                        <button class="btn btn-secondary">Historico</button>
                    </div>

                    <br>

                    {{-- Formato calendario --}}
                    <div class="table-responsive">
                        <table id="Tablavolu" class="table table-bordered calemitreci">
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
                                @foreach ($weeks as $week)
                                    {{-- Realizar un echo de codigo HTML --}}
                                    {!! $week !!}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
    {{-- Obtenemos el total de dias --}}
    @php
        $totaldias = $aniocal . '-' . $mescal . '-01'; //Asignamos la fecha inicial del mes y año seleccionado
        $totaldias = date('t', strtotime($totaldias)); //Obtenemos el total de dias de la fecha seleccionada
    @endphp

    {{--Ciclo para crear las fechas--}}
    @for ($dias = 1; $dias <= $totaldias; $dias++)
        @php
            $fecha = date('Y-m-d', strtotime($aniocal . '-' . $mescal . '-' . $dias)); //Creacion de la fecha con el formato
        @endphp

        {{--Llamamos al componente del modal junto con los datos necesarios--}}
        <livewire:volumedata :empresa=$empresa :dia=$fecha :wire:key="'user-profile-one-'.$empresa.$fecha">
    @endfor
</div>
