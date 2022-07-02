<div wire:poll>
    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="calcolabotarea" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-calendar">Calendario de actividades</span></h6>

                    <button wire:click="Refresh()" type="button" class="closeacuse close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Colaborador --}}
                    @if (!empty(auth()->user()->admin))
                        {{-- Mostramos el RFC de la empresa que se selecciona --}}
                        <label for="inputState">Colaborador: {{ $contaselect }}</label>
                        <select wire:model="contaselect" class="select form-control">
                            <option value="">--Selecciona Sucursal--</option>

                            {{-- Mostramos las sucursales --}}
                            @foreach ($consulconta as $colabo)
                                <option value="{{ $colabo['RFC'] }}">{{ ucfirst($colabo['nombre']) }}</option>
                            @endforeach
                        </select>

                        <br>
                    @endif

                    {{-- Filtros de mes y año --}}
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
                    </div>

                    <br>

                    {{-- Animacion de cargando --}}
                    <div wire:loading wire:target="contaselect, mescal, aniocal">
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

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
