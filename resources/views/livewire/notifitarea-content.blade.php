<div>
    <div wire:ignore.self class="modal fade come-from-modal right" id="notifitarea" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="closeacuse close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>


                    <b>¡Tienes {{ $totaltareas }} tareas nuevas!</b>
                </div>
                <div wire:poll class="modal-body">
                    {{-- Ciclo para obtener las tareas --}}
                    @foreach ($tareas as $tarea)
                        {{-- Condicional para identificar si la notificacion es emitido o recibido --}}

                        {{-- Notificacion para los que emiten tareas --}}
                        @if (!empty(auth()->user()->admin))
                            @switch($tarea['estado'])
                                @case('1')
                                    <div wire:click="TareaAdmin()" class="ContentNoti">
                                        <b>{{ ucfirst($tarea['nomcolaborador']) }}</b> inicio la tarea <br>
                                        <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                        <b>Inicio: </b>{{ $tarea['iniciotareas'] }} <br>
                                        <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                        </small>

                                        <br>
                                        <br>

                                        <b>Haz click para ir a tareas</b>
                                    </div>
                                @break

                                @case('2')
                                    <div class="ContentNoti">
                                        <div wire:click="TareaAdmin()">
                                            <b>{{ ucfirst($tarea['nomcolaborador']) }}</b> finalizó la tarea <br>
                                            <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                            <b>finalizó: </b>{{ $tarea['finalizo'] }} <br>
                                            <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                            </small>

                                            <br>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div wire:click="TareaAdmin()" class="col-10">
                                                <b>Haz click para ir a tareas</b>
                                            </div>
                                            <div align="right" wire:click="FinTareas('{{ $tarea['_id'] }}')" class="col">
                                                <a title="Finalizar" class="icons fas fa-trash-alt"></a>
                                            </div>
                                        </div>
                                    </div>
                                @break
                            @endswitch

                            <script>
                                //Accion para enviarnos a la seccion de tareas
                                $(".ContentNoti").click(function() {
                                    sessionStorage.setItem('Seccion', 'Tareas');
                                });
                            </script>
                        @endif

                        {{-- Notificacion para los que emiten tareas y reciben tareas --}}
                        @if (auth()->user()->tipo == 'VOLU' && $tarea['rfcadmin'] == auth()->user()->RFC)
                            @switch($tarea['estado'])
                                @case('1')
                                    <div wire:click="TareaAdmin()" class="ContentNoti">
                                        <b>{{ ucfirst($tarea['nomcolaborador']) }}</b> inicio la tarea <br>
                                        <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                        <b>Inicio: </b>{{ $tarea['iniciotareas'] }} <br>
                                        <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                        </small>

                                        <br>
                                        <br>

                                        <b>Haz click para ir a tareas</b>
                                    </div>

                                    <hr>
                                @break

                                @case('2')
                                    <div class="ContentNoti">
                                        <div wire:click="TareaAdmin()">
                                            <b>{{ ucfirst($tarea['nomcolaborador']) }}</b> finalizó la tarea <br>
                                            <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                            <b>finalizó: </b>{{ $tarea['finalizo'] }} <br>
                                            <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                            </small>

                                            <br>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div wire:click="TareaAdmin()" class="col-10">
                                                <b>Haz click para ir a tareas</b>
                                            </div>
                                            <div align="right" wire:click="FinTareas('{{ $tarea['_id'] }}')" class="col">
                                                <a title="Finalizar" class="icons fas fa-trash-alt"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                @break
                            @endswitch
                        @endif

                        {{-- Notificacion para los que reciben tareas --}}
                        @if ($tarea['rfccolaborador'] == auth()->user()->RFC && $tarea['estado'] == '0')
                            <div wire:click="IniciarTarea('{{ $tarea['_id'] }}')" class="ContentNoti">
                                <b>{{ ucfirst($tarea['nombreadmin']) }}</b> te asignó la
                                siguente tarea: <br>
                                <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                <b>Fecha asignación: </b>{{ $tarea['asigntarea'] }} <br>

                                @if (!empty($tarea['fechaentrega']))
                                    <b>Fecha esperada: </b>{{ $tarea['fechaentrega'] }} <br>
                                @endif

                                @if (!empty($tarea['nomproyecto']) && $tarea['nomproyecto'] != 'Sin proyecto')
                                    <b>Proyecto: </b>{{ $tarea['nomproyecto'] }} <br>
                                @endif
                                <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                </small>

                                <br>
                                <br>

                                <b>Haz click para iniciar tarea</b>
                            </div>

                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
