<div>
    @php
        //Importamos los modelos
        use App\Models\User;
        use App\Models\ExpedFiscal;
        use App\Models\Cheques;
        use App\Models\Tareas;
        
        //Convertimos los meses de numero a palabra
        $espa = new Cheques();
        
        //Obtenemos la clase para agregar a la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- Animacion de cargando --}}
    <div wire:loading wire:target="rfccolab, Completado, Cancelar">
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
            <div></div>
            <div></div>
        </div>
        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
    </div>

    {{-- Titulo --}}
    @if (!empty(auth()->user()->admin))
        <h2><b>Tareas creadas</b></h2>
    @else
        <h2><b>Mis tareas</b></h2>
    @endif

    {{-- Tabla de tareas --}}
    <div wire:poll class="table-responsive">
        <table class="{{ $class }}" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center align-middle">Finalizar</th>
                    {{-- Opciones para administradores --}}
                    @if (!empty(auth()->user()->admin))
                        <th class="text-center align-middle">Cancelar</th>
                    @endif
                    <th class="text-center align-middle">Tareas</th>
                    <th class="text-center align-middle">Fecha de inicio</th>
                    <th class="text-center align-middle">Fecha de vencimiento</th>
                    <th class="text-center align-middle">Fecha termino</th>
                    <th class="text-center align-middle">Prioridad </th>
                    <th class="text-center align-middle">Frecuencia </th>
                    <th class="text-center align-middle">Descripción </th>
                    <th class="text-center align-middle">Estado</th>
                    {{-- Opciones para administradores --}}
                    @if (!empty(auth()->user()->admin))
                        <th class="text-center align-middle">Colaborador</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($proyectos as $proyecto)
                    <tr style="background-color: #f8f8f8;">
                        <td colspan="11"><label>{{ $proyecto['Nombre'] }}</label></td>
                    </tr>
                    {{-- Mostramos la lista de tareas --}}
                    @foreach ($tareas as $tarea)
                        @if (!empty(auth()->user()->admin))
                            @if ($tarea['rfcproyecto'] == $proyecto['RFC'])
                                @php
                                    if (!empty($tarea['completado']) && empty(auth()->user()->admin)) {
                                        $complete = 'background-color: #ddd; pointer-events: none;';
                                    } else {
                                        $complete = null;
                                    }
                                @endphp

                                <tr style="{{ $complete }}">
                                    {{-- Boton de completado --}}
                                    @if (!empty($tarea['completado']))
                                        <td>
                                            <a class="content_true icons fas fa-check-circle fa-2x"></a>
                                        </td>
                                    @else
                                        <td>
                                            <a wire:click="Completado('{{ $tarea['_id'] }}')"
                                                class="icons fas fa-check-circle fa-2x"></a>
                                        </td>
                                    @endif

                                    {{-- Cancelar tarea --}}
                                    @if (!empty(auth()->user()->admin))
                                        {{-- Boton para cancelar (eliminar)/ finalizar una tarea --}}
                                        <td>
                                            <a title="Cancelar" wire:click="Cancelar('{{ $tarea['_id'] }}')"
                                                class="icons fas fa-times-circle fa-2x content_true_pdf"></a>
                                        </td>
                                    @endif

                                    {{-- Nombre --}}
                                    <td>{{ $tarea['nombre'] }}</td>

                                    {{-- Fecha de inicio --}}
                                    <td>{{ $tarea['asigntarea'] }}</td>

                                    {{-- Fecha de vencimiento --}}
                                    <td>{{ $tarea['fechaentrega'] ?? 'Sin fecha' }}</td>

                                    {{-- Fecha termino --}}
                                    @if (!empty($tarea['completado']))
                                        <td>{{ $tarea['finalizo'] }}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    {{-- Prioridad --}}
                                    <td>{{ $tarea['prioridad'] }}</td>

                                    {{-- Frecuencia --}}
                                    <td>{{ $tarea['periodo'] ?? 'Unica' }}</td>

                                    {{-- Descripcion --}}
                                    <td>{{ $tarea['descripcion'] ?? 'Sin descripción' }}</td>

                                    {{-- Estado --}}
                                    @switch($tarea['estado'])
                                        @case('0')
                                            <td>No iniciada</td>
                                        @break

                                        @case('1')
                                            <td>En proceso</td>
                                        @break

                                        @case('2')
                                            <td>Concluida</td>
                                        @break
                                    @endswitch

                                    {{-- Cancelar tarea --}}
                                    @if (!empty(auth()->user()->admin))
                                        {{-- Prioridad --}}
                                        <td>{{ ucfirst($tarea['nomcolaborador']) }}</td>
                                    @endif
                                </tr>
                            @endif
                        @else
                            @if ($tarea['rfccolaborador'] == $rfccolab && $tarea['rfcproyecto'] == $proyecto['RFC'])
                                @php
                                    if (!empty($tarea['completado']) && empty(auth()->user()->admin)) {
                                        $complete = 'background-color: #ddd; pointer-events: none;';
                                    } else {
                                        $complete = null;
                                    }
                                @endphp

                                <tr style="{{ $complete }}">
                                    {{-- Boton de completado --}}
                                    @if (!empty($tarea['completado']))
                                        <td>
                                            <a class="content_true icons fas fa-check-circle fa-2x"></a>
                                        </td>
                                    @else
                                        <td>
                                            <a wire:click="Completado('{{ $tarea['_id'] }}')"
                                                class="icons fas fa-check-circle fa-2x"></a>
                                        </td>
                                    @endif

                                    {{-- Nombre --}}
                                    <td>{{ $tarea['nombre'] }}</td>

                                    {{-- Fecha de inicio --}}
                                    <td>{{ $tarea['asigntarea'] }}</td>

                                    {{-- Fecha de vencimiento --}}
                                    <td>{{ $tarea['fechaentrega'] ?? 'Sin fecha' }}</td>

                                    {{-- Fecha termino --}}
                                    @if (!empty($tarea['completado']))
                                        <td>{{ $tarea['finalizo'] }}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    {{-- Prioridad --}}
                                    <td>{{ $tarea['prioridad'] }}</td>

                                    {{-- Frecuencia --}}
                                    <td>{{ $tarea['periodo'] ?? 'Unica' }}</td>

                                    {{-- Descripcion --}}
                                    <td>{{ $tarea['descripcion'] ?? 'Sin descripción' }}</td>

                                    {{-- Estado --}}
                                    @switch($tarea['estado'])
                                        @case('0')
                                            <td>No iniciada</td>
                                        @break

                                        @case('1')
                                            <td>En proceso</td>
                                        @break

                                        @case('2')
                                            <td>Concluida</td>
                                        @break
                                    @endswitch
                                </tr>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
