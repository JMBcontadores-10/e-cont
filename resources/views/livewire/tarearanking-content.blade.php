<div>
    @php
        //Obtenemos la clase para agregar a la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        //Variable de contador para obtener las posciones
        $posicion = 1;
    @endphp

    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="tarearanking" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-chart-line">Ranking</span></h6>

                    <button type="button" class="closetarea close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div wire:poll class="modal-body">
                    @if (empty(auth()->user()->admin))
                        <div class="ContentRank">
                            {{-- Mostramos la posicion --}}
                            <b>¡Estás en el lugar {{ $posrank }} del ranking
                                semanal!</b>

                            <br>

                            {{-- Mostramos el porcentaje --}}
                            Con una productividad
                            del {{ number_format($porcent, 2) }}%
                        </div>

                        <div align=center>
                            {{-- Top 3
                        Medallas
                        Dorado: #ffd55d
                        Plata: #cdcdcd
                        Bronce: #ac6132 --}}

                            {{-- Condicional para saber si finalizo las tareas --}}
                            @if ($porcent == '100')
                                <i class="fas fa-medal fa-3x" style="color: #ffd55d"></i>

                                <br>

                                <small>Finalizaste todas tus tareas</small>
                            @else
                                @switch($posrank)
                                    {{-- Primer puesto --}}
                                    @case(1)
                                        <i class="fas fa-medal fa-3x" style="color: #ffd55d"></i>
                                    @break

                                    {{-- Segundo puesto --}}
                                    @case(2)
                                        <i class="fas fa-medal fa-3x" style="color: #cdcdcd"></i>
                                    @break

                                    {{-- Tercer puesto --}}
                                    @case(3)
                                        <i class="fas fa-medal fa-3x" style="color: #ac6132"></i>
                                    @break

                                    @default
                                        {{-- Obtenemos los ultimos puestos --}}
                                        @php
                                            $tamaño = sizeof($ranking); //Obtenemos el tamaño de los areglos
                                            $ultimos = $tamaño - 3; //Obtenemos los ultimos 3 puestos
                                        @endphp

                                        @if ($posrank >= $ultimos || $porcent < 25)
                                            {{-- Insatisfecho --}}
                                            <i class="fas fa-frown-open fa-3x"></i>
                                        @else
                                            {{-- Suficiente --}}
                                            <i class="fas fa-laugh fa-3x"></i>
                                        @endif
                                @endswitch
                            @endif
                        </div>
                    @else
                        <div align="center">
                            <label>Resultados de las tareas realizadas en la semana</label>
                        </div>

                        <div class="ContentRank">
                            {{-- Tabla de tareas --}}
                            <div class="table-responsive">
                                <table class="{{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">Posicion</th>
                                            <th class="text-center align-middle">Colaborador</th>
                                            <th class="text-center align-middle">Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranking as $rank)
                                            <tr>
                                                <td>{{ $posicion++ }}</td>
                                                <td>{{ ucfirst($rank['Nombre']) }}</td>
                                                <td>{{ number_format($rank['Porcentaje'], 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
