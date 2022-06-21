<div>
    @php
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
    @endphp

    {{-- Seccion para mostrar los colaboradores --}}
    {{-- Contabilidad --}}
    <div style="background-color: #b1ffaa; padding: 15px; text-align: center;">
        {{-- Encabzado --}}
        <h5><b>Contabilidad: </b></h5>
    </div>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['depto'] == 'Contabilidad')
                    @php
                        //Limpiamos el arreglo de proyecto al cambiar de contador
                        $proyectos = [];
                        
                        //Total de tareas completadas
                        $completado = 0;
                    @endphp

                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #b1ffaa; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        foreach ($tareas as $tarea) {
                                            foreach ($tarea['colaboradores'] as $colaborador) {
                                                if ($colaborador['RFC'] == $contador['RFC']) {
                                                    //Almacenamos las empresas
                                                    $proyectos[] = $tarea['nomproyecto'];
                                        
                                                    //Corroboramos los completados
                                                    if ($tarea['completado'] == 1) {
                                                        $completado++;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @php
                                        //Contamos los repetidos
                                        $infotareas = array_count_values($proyectos);
                                    @endphp

                                    {{-- Mostramos el total de las empresas --}}
                                    @foreach ($infotareas as $key => $value)
                                        @php
                                            //Obtenemos el porcentaje
                                            $porcentaje = ($completado * 100) / $value;
                                        @endphp

                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>Tareas completadas {{ $completado }} de {{ $value }}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <b>{{ number_format($porcentaje, 2) }}%</b>
                                            </td>
                                        </tr>

                                        @php
                                            //Total de tareas completadas
                                            $completado = 0;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <br>

    {{-- Contabilidad --}}
    <div style="background-color: #fff3aa; padding: 15px; text-align: center;">
        {{-- Encabzado --}}
        <h5><b>Nomina: </b></h5>
    </div>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['depto'] == 'Nomina')
                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #fff3aa; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <br>
            @endforeach
        </div>
    </div>

    <br>

    {{-- Contabilidad --}}
    <div style="background-color: #aac6ff; padding: 15px; text-align: center;">
        {{-- Encabzado --}}
        <h5><b>Factuación: </b></h5>
    </div>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['depto'] == 'Facturacion')
                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #aac6ff; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }}" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <br>
            @endforeach
        </div>
    </div>
</div>
