<div>
    @php
        //Variables para los dias de la semana
        $lunes = 0;
        $martes = 0;
        $miercoles = 0;
        $jueves = 0;
        $viernes = 0;
        $sabado = 0;
        $domingo = 0;
    @endphp

    {{-- JS --}}
    <script src="{{ asset('js/tareas.js') }}" defer></script>

    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="nuevatarea" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-calendar">Crear/Modificar tarea</span></h6>

                    <button wire:click="Refresh()" type="button" class="closetarea close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Animacion de cargando --}}
                    <div wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

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
                            <select class="form-control frecuente" wire:model.defer="frecuentetarea">
                                <option value="">Seleccione una opcion</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                        </div>

                        {{-- Establecemos el periodo --}}
                        <div class="form-group periodogroup" {{ $hidden }}>
                            <label for="periodo">Periodo</label>
                            <select class="form-control" id="periodo" wire:model.defer="periodotarea"
                                {{ $requiretarea }}>
                                <option value="">Seleccione una opcion</option>
                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Anual">Anual</option>
                            </select>

                            <small>* Al seleccionar un periodo este se genera de forma automática en el periodo
                                seleccionado</small>
                        </div>

                        {{-- Establecemos el tipo de factura --}}
                        <div class="form-group periodogroup" {{ $hidden }}>
                            <label for="actividad">Actividad</label>
                            <select class="form-control" id="actividad" wire:model.defer="imputarea">
                                <option value="">Seleccione una opcion</option>
                                <option value="Cierre_Facturacion">Cierre de facturación</option>
                                <option value="IMSS">IMSS</option>
                                <option value="Impuestos_Remuneraciones">ISN</option>
                                <option value="Impuestos_Estatal">Impuesto Cedular</option>
                                <option value="Impuestos_Hospedaje">ISH</option>
                                <option value="Declaracion_INEGI">Declaración INEGI</option>
                                <option value="Impuestos_Federales">Impuestos Federales</option>
                                <option value="Balanza_Mensual">Balanza Mensual</option>
                                <option value="DIOT">Acuse DIOT</option>
                                <option value="Cierre_Econt">Cierre E-cont</option>
                                <option value="Costo_Ventas">Costo de ventas</option>
                                <option value="Archivo_Digital">Archivo Digital</option>
                                <option value="Conciliacion_Impuesto">Concentrado de impuestos</option>
                                <option value="Notas_Credito">Emision Notas de crédito</option>
                            </select>

                            <small>* Al igual que el periodo, al seleccionar un tipo de impuesto, cuando el colaborador
                                complete la tarea, se marcará el tipo de impuesto de manera automática</small>
                        </div>


                        {{-- Establecemos los dias que se repetiran --}}
                        <div id="diasemselect" class="form-group" {{ $hiddensema }}>
                            <label>Repetir el</label>
                            {{-- Acomodamos los botones en una sola linea --}}
                            <div class="form-inline mr-auto">
                                {{-- Bucles para marcar las fechas --}}
                                @php
                                    if (!empty($diasfrecu)) {
                                        foreach ($diasfrecu as $diaselect) {
                                            switch ($diaselect) {
                                                case 'L':
                                                    $lunes = 1;
                                                    break;
                                    
                                                case 'M':
                                                    $martes = 1;
                                                    break;
                                    
                                                case 'Mi':
                                                    $miercoles = 1;
                                                    break;
                                    
                                                case 'J':
                                                    $jueves = 1;
                                                    break;
                                    
                                                case 'V':
                                                    $viernes = 1;
                                                    break;
                                    
                                                case 'S':
                                                    $sabado = 1;
                                                    break;
                                    
                                                case 'D':
                                                    $domingo = 1;
                                                    break;
                                            }
                                        }
                                    }
                                @endphp

                                {{-- Boton de los dias --}}
                                @if ($lunes == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaLunes">L</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaLunes">L</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="L" name="L" type="checkbox" id="DiaLunes"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($martes == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaMartes">M</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaMartes">M</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="M" name="M" type="checkbox" id="DiaMartes"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($miercoles == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaMierco">Mi</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaMierco">Mi</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="Mi" name="Mi" type="checkbox" id="DiaMierco"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($jueves == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaJueves">J</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaJueves">J</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="J" name="J" type="checkbox" id="DiaJueves"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($viernes == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaViernes">V</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaViernes">V</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="V" name="V" type="checkbox" id="DiaViernes"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($sabado == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaSabado">S</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaSabado">S</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="S" name="S" type="checkbox" id="DiaSabado"
                                    wire:model.defer="diasfrecu" hidden>


                                {{-- Boton de los dias --}}
                                @if ($domingo == 1)
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #0075ff;
                                                    color: #ffffff;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaDomingo">D</label>
                                @else
                                    <label class="checkday"
                                        style="height: 3em;
                                                    width: 3em;
                                                    background: #ebecef;
                                                    border-radius: 100%;
                                                    margin: 5px;"
                                        for="DiaDomingo">D</label>
                                @endif

                                {{-- Checkbox para obtener el valor --}}
                                <input value="D" name="D" type="checkbox" id="DiaDomingo"
                                    wire:model.defer="diasfrecu" hidden>
                            </div>
                        </div>

                        {{-- Establecemos los dias que se repetiran en el mes --}}
                        <div id="diamesselect" class="form-group" {{ $hiddenmes }}>
                            <label>Repetir el</label>
                            <select class="form-control" wire:model.defer="diasfrecumes">
                                <option value="">Seleccione una opcion</option>
                                @for ($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}">El {{ $i }} de cada mes</option>
                                @endfor
                                <option value="finmes">Fin de mes</option>
                            </select>
                        </div>

                        {{-- Establecemos los dias que se repetiran --}}
                        <div class="form-group periodogroup" {{ $hidden }}>
                            <label for="fechafintarea">Termina</label>
                            <input id="fechafintarea" class="form-control" min="2014-01-01" type="date"
                                wire:model.defer="fechafrecufin">
                            <label for="checkfrecnunca">
                                <input type="checkbox" id="checkfrecnunca" wire:model.defer="nuncafecha">
                                Nunca
                            </label>

                            <br>

                            <small>* Fecha de finalización en la que se dejara de crear tareas</small>
                        </div>

                        {{-- Boton para enviar y cancelar --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Finalizar</button>
                        </div>

                        {{-- Mensaje de seleccionar al menos un colaborador --}}
                        <div id="Mnserrorcolab" hidden>
                            <div id="mnscolab" class="alert alert-danger">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
