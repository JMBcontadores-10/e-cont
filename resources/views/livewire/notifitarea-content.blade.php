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
                    @if (!empty(auth()->user()->admin))
                        @foreach ($tareas as $tarea)
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
                                    <div wire:click="TareaAdmin()" class="ContentNoti">
                                        <b>{{ ucfirst($tarea['nomcolaborador']) }}</b> finalizo la tarea <br>
                                        <b>Nombre: </b>{{ $tarea['nombre'] }} <br>
                                        <b>Finalizo: </b>{{ $tarea['finalizo'] }} <br>
                                        <small>{{ $tarea['updated_at']->locale('es')->diffForHumans() }}
                                        </small>

                                        <br>
                                        <br>

                                        <b>Haz click para ir a tareas</b>
                                    </div>
                                @break
                            @endswitch

                            <script>
                                //Accion para enviarnos a la seccion de tareas
                                $(".ContentNoti").click(function() {
                                    sessionStorage.setItem('Seccion', 'Tareas');
                                });
                            </script>
                        @endforeach
                    @else
                        @foreach ($tareas as $tarea)
                            {{-- Filtramos por el tipo de colaborador --}}
                            @if ($tarea['rfccolaborador'] == auth()->user()->RFC)
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
