<div>
    {{-- JS --}}
    <script src="{{ asset('js/tareas.js') }}" defer></script>

    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="nuevatarea" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-calendar">Nueva tarea</span></h6>

                    <button type="button" class="closetarea close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    <form id="NuevaTarea" wire:submit.prevent="NuevaTarea">
                        {{-- Nombre de la tarea --}}
                        <div class="form-group">
                            <label for="nombre">*Nombre de la tarea</label>
                            <input type="text" class="form-control" id="nombre" wire:model.defer="nombretarea"
                                placeholder="Nombre de la tarea" required>
                        </div>

                        {{-- Descripcion de la tarea --}}
                        <div class="form-group">
                            <label for="descripcion">Descripcion de la tarea</label>
                            <textarea class="form-control" id="descripcion" wire:model.defer="descripciontarea"
                                placeholder="Descripcion de la tarea"></textarea>
                        </div>

                        {{-- Proyecto/Empresa --}}
                        <div class="form-group">
                            <label for="proyecto">Proyecto</label>
                            <select class="form-control" id="proyecto" wire:model.defer="proyectotarea">
                                <option value="">Seleccione un proyecto</option>
                                @foreach ($empresas as $empresa)
                                    <option
                                        value="{{ json_encode(['RFC' => $empresa['RFC'], 'Nombre' => $empresa['Nombre']]) }}">
                                        {{ $empresa['Nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            {{-- Fecha de entrega --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fechaentrega">Fecha de entrega</label>
                                    <input type="date" class="form-control" id="fechaentrega"
                                        wire:model.defer="fechaentregatarea" placeholder="Fecha de entrega">
                                </div>
                            </div>

                            {{-- Prioridad --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prioridad">*Prioridad</label>
                                    <select class="form-control" id="prioridad" wire:model.defer="prioridadtarea"
                                        required>
                                        <option value="">Seleccione una prioridad</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Mensaje de seleccionar al menos un colaborador --}}
                        <div id="Mnserrorcolab" hidden>
                            <div id="mnscolab" class="alert alert-danger">
                            </div>
                        </div>

                        {{-- Colaboradores --}}
                        <div class="form-group">
                            <label for="colaboradores">*Colaborador(es)</label>

                            <div class="row">
                                <div class="col-8">
                                    <select class="form-control" id="colaboradores" wire:model.defer="colaboradortarea">
                                        <option value="">Seleccione un colaborador</option>
                                        @foreach ($contadores as $contador)
                                            <option
                                                value="{{ json_encode(['RFC' => $contador['RFC'], 'Nombre' => $contador['nombre']]) }}">
                                                {{ ucfirst($contador['nombre']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" wire:click="AddColabo()" class="btn btn-primary"> <i
                                            class="fas fa-plus" style="top: 0 !important"></i> Asignar</button>
                                </div>
                            </div>
                        </div>

                        {{-- Colaboradores asignados --}}
                        <div class="form-group">
                            <label for="colaboradores">Colaborador(es) asignados</label>

                            <br>

                            {{-- Area para mostrar los colaboradores --}}
                            <div id="ColabZone">
                                <ul>
                                    @foreach ($colaboradorestarea as $colaboradores)
                                        <li>
                                            {{ ucfirst(json_decode($colaboradores)->Nombre) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-8">
                                    <select class="form-control" id="colaboradores" wire:model.defer="colaboradortarea">
                                        <option value="">Seleccione un colaborador</option>
                                        @foreach ($colaboradorestarea as $colaboradores)
                                            <option value="{{ $colaboradores }}">
                                                {{ ucfirst(json_decode($colaboradores)->Nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="button" wire:click="RemoveColabo()" class="btn btn-danger"> <i
                                            class="fas fa-minus" style="top: 0 !important"></i> Quitar</button>
                                </div>
                            </div>
                        </div>

                        {{-- Establecer una tarea como frecuente --}}
                        <div class="form-group">
                            <label for="frecuente">Establecer como frecuente</label>
                            <select class="form-control" id="frecuente" wire:model.defer="frecuentetarea">
                                <option value="">Seleccione una opcion</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                        </div>

                        {{-- Establecemos el periodo --}}
                        <div class="form-group periodogroup" hidden>
                            <label for="periodo">Periodo</label>
                            <select class="form-control" id="periodo" wire:model.defer="periodotarea">
                                <option value="">Seleccione una opcion</option>
                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>

                        {{-- Mensaje --}}
                        <div class="form-group periodogroup" hidden>
                            <label>Al seleccionar un periodo este se genera de forma automatica en el periodo</label>
                        </div>

                        {{-- Boton para enviar y cancelar --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
