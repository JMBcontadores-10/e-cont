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
        
        //Obtenemos la fecha del dia de hoy
        $dtz = new DateTimeZone('America/Mexico_City');
        $dt = new DateTime('now', $dtz);
        $fechahoy = $dt->format('Y-m-d H:i:s');
    @endphp

    {{-- Filtros para los colaboradores --}}
    @if (empty(auth()->user()->admin))
        {{-- Filtros de busqueda --}}
        <div class="form-inline mr-auto">
            {{-- Filtros de para los colaboradores --}}
            @if (auth()->user()->tipo == 'VOLU')
                {{-- Boton para agregar una nueva tarea --}}
                <button class="btn btn-primary" data-toggle="modal" data-target="#nuevatarea" data-backdrop="static"
                    data-keyboard="false">
                    <i class="fas fa-plus" style="top: 0 !important"></i> Nueva tarea
                </button>

                {{-- Espaciado --}}
                <div style="width: 5em"></div>
            @endif

            {{-- Filtros de para los colaboradores --}}
            {{-- Busqueda por mes --}}
            <label class="mestarea" for="inputState">Mes</label>
            &nbsp;&nbsp;
            <select id="MesSelecTarea" wire:model="mestareaadmin" id="inputState1" wire:loading.attr="disabled"
                class="mestarea select form-control">
                <?php foreach ($meses as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                } ?>
            </select>
            &nbsp;&nbsp;
            &nbsp;&nbsp;

            {{-- Filtros de para los colaboradores --}}
            {{-- Busqueda por año --}}
            <label for="inputState">Año</label>
            &nbsp;&nbsp;
            <select id="AnioSelectTarea" wire:loading.attr="disabled" wire:model="aniotareaadmin" id="inputState2"
                class="select form-control">
                <?php foreach (array_reverse($anios) as $value) {
                    echo '<option value="' . $value . '">' . $value . '</option>';
                } ?>
            </select>
            &nbsp;&nbsp;
            &nbsp;&nbsp;

            {{-- Filtros de para los colaboradores --}}
            @if (auth()->user()->tipo == 'VOLU')
                {{-- Busqueda por avance --}}
                <label for="inputState">Colaborador</label>
                &nbsp;&nbsp;
                <select wire:loading.attr="disabled" id="selectdepto" wire:model="colaboselect"
                    class="select form-control AvanceSelectTarea">
                    <option value="">Seleccione un colaborador</option>
                    @foreach ($consulconta as $infocolabo)
                        <option value="{{ $infocolabo['RFC'] }}">{{ ucfirst($infocolabo['nombre']) }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <br>
    @endif

    {{-- Animacion de cargando --}}
    <div wire:loading wire:target="rfccolab, Completado, Cancelar, colaboselect, mestareaadmin, aniotareaadmin">
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
            <div></div>
            <div></div>
        </div>
        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
    </div>

    {{-- Titulo --}}
    @if (!empty(auth()->user()->admin))
        {{-- Filtros de busqueda --}}
        <div class="form-inline mr-auto">
            <h2><b>Tareas creadas</b></h2>
            &nbsp;&nbsp;
            &nbsp;&nbsp;
            {{-- Exportar a Excel --}}
            <button type="button" class="btn btn-success BtnVinculadas"
                onclick="exporttareasexcel('{{ $fechahoy }}')">Excel</button>
        </div>
    @else
        <h2><b>Mis tareas</b></h2>
    @endif

    {{-- Tabla de tareas --}}
    <div wire:poll class="table-responsive">
        <table class="{{ $class }} tabletareas" style="width:100%">
            <thead>
                <tr>
                    <th data-tableexport-display="none" class="text-center align-middle">Finalizar</th>
                    {{-- Opciones para administradores --}}
                    @if (!empty(auth()->user()->admin))
                        <th data-tableexport-display="none" class="text-center align-middle">Editar</th>
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
                    @if (!empty(auth()->user()->admin) || auth()->user()->tipo == 'VOLU')
                        <th class="text-center align-middle">Colaborador</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($proyectos as $proyecto)
                    <tr style="background-color: #f8f8f8;">
                        <td colspan="12"><label>{{ $proyecto['Nombre'] }}</label></td>
                    </tr>
                    {{-- Mostramos la lista de tareas --}}
                    @foreach ($tareas as $tarea)
                        @if (!empty(auth()->user()->admin) || auth()->user()->tipo == 'VOLU')
                            @if ($tarea['rfcproyecto'] == $proyecto['RFC'])
                                @php
                                    //Colores de estado
                                    switch ($tarea['estado']) {
                                        case '0':
                                            $complete = null;
                                            break;
                                    
                                        case '1':
                                            $complete = 'background-color: #fff4b2;';
                                            break;
                                    
                                        case '2':
                                            $complete = 'background-color: #d0ffae; pointer-events: none; text-decoration:line-through;';
                                            break;
                                    
                                        case 'fin':
                                            $complete = 'background-color: #d0ffae; pointer-events: none; text-decoration:line-through;';
                                            break;
                                    }
                                @endphp

                                <tr style="{{ $complete }} color: #3e464e">
                                    {{-- Boton de completado --}}
                                    @if (!empty($tarea['completado']))
                                        <td data-tableexport-display="none">
                                            <a class="content_true icons fas fa-check-circle fa-2x"></a>
                                        </td>
                                    @else
                                        <td data-tableexport-display="none">
                                            {{-- Boton de completado --}}
                                            <a wire:click="Completado('{{ $tarea['_id'] }}')"
                                                class="icons fas fa-check-circle fa-2x"></a>

                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            {{-- Boton de eliminar registro --}}
                                            <a title="Cancelar" wire:click="Cancelar('{{ $tarea['_id'] }}')"
                                                class="icons fas fa-trash-alt fa-2x"></a>
                                        </td>
                                    @endif

                                    {{-- Editar tarea --}}
                                    @if (!empty(auth()->user()->admin))
                                        {{-- Boton para cancelar (eliminar)/ finalizar una tarea --}}
                                        <td data-tableexport-display="none">
                                            <a title="Editar"
                                                wire:click="SendInfoEdit('{{ $tarea['_id'] }}')"data-backdrop="static"
                                                data-keyboard="false" data-toggle="modal" data-target="#nuevatarea"
                                                class="icons fas fa-edit fa-2x"></a>
                                        </td>
                                    @endif

                                    {{-- Nombre --}}
                                    <td>{{ $tarea['nombre'] }}</td>

                                    {{-- Fecha de inicio --}}
                                    <td>{{ $tarea['asigntarea'] }}</td>

                                    {{-- Fecha de vencimiento --}}
                                    <td>{{ $tarea['fechaentrega'] ?? '-' }}</td>

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
                                    <td>{{ $tarea['descripcion'] ?? '-' }}</td>

                                    {{-- Estado --}}
                                    @switch($tarea['estado'])
                                        @case('0')
                                            <td>
                                                <label>No iniciada</label>
                                            </td>
                                        @break

                                        @case('1')
                                            <td>
                                                <label>En proceso</label>
                                            </td>
                                        @break

                                        @case('2')
                                            <td>
                                                <label>Concluida</label>
                                            </td>
                                        @break

                                        @case('fin')
                                            <td>
                                                <label>Concluida</label>
                                            </td>
                                        @break
                                    @endswitch

                                    {{-- Cancelar tarea --}}
                                    @if (!empty(auth()->user()->admin) || auth()->user()->tipo == 'VOLU')
                                        {{-- Prioridad --}}
                                        <td>{{ ucfirst($tarea['nomcolaborador']) }}</td>
                                    @endif
                                </tr>
                            @endif
                        @else
                            @if ($tarea['rfccolaborador'] == $rfccolab && $tarea['rfcproyecto'] == $proyecto['RFC'])
                                @php
                                    //Colores de estado
                                    switch ($tarea['estado']) {
                                        case '0':
                                            $complete = null;
                                            break;
                                    
                                        case '1':
                                            $complete = 'background-color: #fff4b2;';
                                            break;
                                    
                                        case '2':
                                            $complete = 'background-color: #d0ffae; pointer-events: none; text-decoration:line-through;';
                                            break;
                                    
                                        case 'fin':
                                            $complete = 'background-color: #d0ffae; pointer-events: none; text-decoration:line-through;';
                                            break;
                                    }
                                @endphp

                                <tr style="{{ $complete }} color: #3e464e">
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
                                            <td>
                                                <label>No iniciada</label>
                                            </td>
                                        @break

                                        @case('1')
                                            <td>
                                                <label>En proceso</label>
                                            </td>
                                        @break

                                        @case('2')
                                            <td>
                                                <label>Concluida</label>
                                            </td>
                                        @break

                                        @case('fin')
                                            <td>
                                                <label>Concluida</label>
                                            </td>
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
