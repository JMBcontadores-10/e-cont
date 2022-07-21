<div wire:poll>
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
        <h5><b>Contabilidad: <h5 class="PorcentConta"></h5></b></h5>
    </div>

    {{-- Encabezado para la exportacion --}}
    <table class="tareaavance">
        <thead>
            <tr>
                <th hidden data-tableexport-display="always" colspan="2"
                    style="background-color: #b1ffaa; padding: 15px; text-align: center;">
                    <h5>
                        <b>Contabilidad: <h5 class="PorcentConta"></h5></b>
                    </h5>
                </th>
            </tr>
        </thead>
    </table>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        @php
            //Variables para obtener
            //Total de tareas creadas por departamento
            $totaldeptotareas = 0;
            
            //Total de tareas completadas por departamento
            $totaldeptocompletados = 0;
        @endphp

        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['depto'] == 'Contabilidad')
                    @php
                        //Limpiamos el arreglo de proyecto al cambiar de contador
                        $proyectos = [];
                        
                        //Total de tareas creadas
                        $totaltareas = 0;
                        
                        //Total de tareas completadas
                        $totalcompletados = 0;
                    @endphp

                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #b1ffaa; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }} tareaavance" style="width:100%">
                                <thead>
                                    <tr>
                                        <th hidden data-tableexport-display="always" colspan="2"
                                            style="background-color: #b1ffaa; padding: 5px;">
                                            <b>{{ ucfirst($contador['nombre']) }}</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        foreach ($tareas as $tarea) {
                                            if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                //Almacenamos las empresas
                                                $proyectos[] = $tarea['nomproyecto'];
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
                                            //Total de tareas completadas por colaborador
                                            $completado = 0;
                                            
                                            foreach ($tareas as $tarea) {
                                                if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                    if ($tarea['completado'] == '1' && $tarea['nomproyecto'] == $key) {
                                                        $completado++;
                                                    }
                                                }
                                            }
                                            
                                            //Realzamos la suma de los totales
                                            $totalcompletados += $completado;
                                            
                                            //Obtenemos el porcentaje
                                            $porcentaje = ($completado * 100) / $value;
                                            
                                            //Obtenemos el total de tareas
                                            $totaltareas += $value;
                                        @endphp

                                        <tr>
                                            <td style="color: #3e464e">{{ $key }}</td>
                                            <td style="color: #3e464e">Tareas completadas {{ $completado }} de
                                                {{ $value }}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <b>{{ number_format($porcentaje, 2) }}%</b>
                                            </td>
                                        </tr>

                                        @php
                                            //Total de tareas completadas
                                            $completado = 0;
                                        @endphp
                                    @endforeach

                                    @php
                                        //Obtenenmos el total de tareas creadas por departamento
                                        $totaldeptotareas += $totaltareas;
                                        
                                        //Realzamos la suma de los totales por colaborador
                                        $totaldeptocompletados += $totalcompletados;
                                    @endphp

                                    <tr style="background-color: #b1ffaa;">
                                        @php
                                            //Obtenenmos el porcentaje de los totales de cada colaborador
                                            if (!empty($totalcompletados) || !empty($totaltareas)) {
                                                //Obtenemos el porcentaje
                                                $totalporcentaje = ($totalcompletados * 100) / $totaltareas;
                                            } else {
                                                $totalporcentaje = 0;
                                            }
                                        @endphp

                                        <td></td>
                                        <td>
                                            <b>Total:</b>
                                            &nbsp;
                                            {{ $totalcompletados }}
                                            &nbsp;
                                            de
                                            &nbsp;
                                            {{ $totaltareas }}
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <b>{{ number_format($totalporcentaje, 2) }}%</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @php
            //Obtenenmos el porcentaje de los totales de cada departamento
            if (!empty($totaldeptotareas) || !empty($totaldeptocompletados)) {
                //Obtenemos el porcentaje
                $totaldeptoporcentaje = ($totaldeptocompletados * 100) / $totaldeptotareas;
            } else {
                $totaldeptoporcentaje = 0;
            }
        @endphp

        <input id="porcentconta" type="hidden" value="{{ number_format($totaldeptoporcentaje, 2) }}">

        <script>
            //Recibimos el llamado y ejecutamos la funcion
            window.addEventListener('estadonuevo', event => {
                var Porcentaje = $("#porcentconta").val();
                $(".PorcentConta").text('Avance del ' + Porcentaje + '%');
            });

            var Porcentaje = $("#porcentconta").val();
            $(".PorcentConta").text('Avance del ' + Porcentaje + '%');
        </script>
    </div>

    <br>

    {{-- Contabilidad --}}
    <div style="background-color: #fff3aa; padding: 15px; text-align: center;">
        {{-- Encabzado --}}
        <h5><b>Nómina: <h5 class="PorcentNomina"></h5></b></h5>
    </div>

    {{-- Encabezado para la exportacion --}}
    <table class="tareaavance">
        <thead>
            <tr>
                <th hidden data-tableexport-display="always" colspan="2"
                    style="background-color: #fff3aa; padding: 15px; text-align: center;">
                    <h5>
                        <b>Nómina: <h5 class="PorcentNomina"></h5></b>
                    </h5>
                </th>
            </tr>
        </thead>
    </table>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        @php
            //Variables para obtener
            //Total de tareas creadas por departamento
            $totaldeptotareas = 0;
            
            //Total de tareas completadas por departamento
            $totaldeptocompletados = 0;
        @endphp

        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['tipo'] == 'Nomina')
                    @php
                        //Limpiamos el arreglo de proyecto al cambiar de contador
                        $proyectos = [];
                        
                        //Total de tareas creadas
                        $totaltareas = 0;
                        
                        //Total de tareas completadas por colaborador
                        $completado = 0;
                        
                        //Total de tareas completadas
                        $totalcompletados = 0;
                    @endphp

                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #fff3aa; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }} tareaavance" style="width:100%">
                                <thead>
                                    <tr>
                                        <th hidden data-tableexport-display="always" colspan="2"
                                            style="background-color: #fff3aa; padding: 5px;">
                                            <b>{{ ucfirst($contador['nombre']) }}</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        foreach ($tareas as $tarea) {
                                            if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                //Almacenamos las empresas
                                                $proyectos[] = $tarea['nomproyecto'];
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
                                            //Total de tareas completadas por colaborador
                                            $completado = 0;
                                            
                                            foreach ($tareas as $tarea) {
                                                if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                    if ($tarea['completado'] == '1' && $tarea['nomproyecto'] == $key) {
                                                        $completado++;
                                                    }
                                                }
                                            }
                                            
                                            //Realzamos la suma de los totales
                                            $totalcompletados += $completado;
                                            
                                            //Obtenemos el porcentaje
                                            $porcentaje = ($completado * 100) / $value;
                                            
                                            //Obtenemos el total de tareas
                                            $totaltareas += $value;
                                        @endphp

                                        <tr>
                                            <td style="color: #3e464e">{{ $key }}</td>
                                            <td style="color: #3e464e">Tareas completadas {{ $completado }} de
                                                {{ $value }}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <b>{{ number_format($porcentaje, 2) }}%</b>
                                            </td>
                                        </tr>

                                        @php
                                            //Total de tareas completadas
                                            $completado = 0;
                                        @endphp
                                    @endforeach

                                    @php
                                        //Obtenenmos el total de tareas creadas por departamento
                                        $totaldeptotareas += $totaltareas;
                                        
                                        //Realzamos la suma de los totales por colaborador
                                        $totaldeptocompletados += $totalcompletados;
                                    @endphp

                                    <tr style="background-color: #fff3aa;">
                                        @php
                                            //Obtenenmos el porcentaje de los totales de cada colaborador
                                            if (!empty($totalcompletados) || !empty($totaltareas)) {
                                                //Obtenemos el porcentaje
                                                $totalporcentaje = ($totalcompletados * 100) / $totaltareas;
                                            } else {
                                                $totalporcentaje = 0;
                                            }
                                        @endphp

                                        <td></td>
                                        <td>
                                            <b>Total:</b>
                                            &nbsp;
                                            {{ $totalcompletados }}
                                            &nbsp;
                                            de
                                            &nbsp;
                                            {{ $totaltareas }}
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <b>{{ number_format($totalporcentaje, 2) }}%</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @php
            //Obtenenmos el porcentaje de los totales de cada departamento
            if (!empty($totaldeptotareas) || !empty($totaldeptocompletados)) {
                //Obtenemos el porcentaje
                $totaldeptoporcentaje = ($totaldeptocompletados * 100) / $totaldeptotareas;
            } else {
                $totaldeptoporcentaje = 0;
            }
        @endphp

        <input id="porcentnomina" type="hidden" value="{{ number_format($totaldeptoporcentaje, 2) }}">

        <script>
            //Recibimos el llamado y ejecutamos la funcion
            window.addEventListener('estadonuevo', event => {
                var Porcentaje = $("#porcentnomina").val();
                $(".PorcentNomina").text('Avance del ' + Porcentaje + '%');
            });

            var Porcentaje = $("#porcentnomina").val();
            $(".PorcentNomina").text('Avance del ' + Porcentaje + '%');
        </script>
    </div>

    <br>

    {{-- Contabilidad --}}
    <div style="background-color: #aac6ff; padding: 15px; text-align: center;">
        {{-- Encabzado --}}
        <h5><b>Facturación: <h5 class="PorcentFactu"></h5></b></h5>
    </div>

    {{-- Encabezado para la exportacion --}}
    <table class="tareaavance">
        <thead>
            <tr>
                <th hidden data-tableexport-display="always" colspan="2"
                    style="background-color: #aac6ff; padding: 15px; text-align: center;">
                    <h5>
                        <b>Facturación: <h5 class="PorcentFactu"></h5></b>
                    </h5>
                </th>
            </tr>
        </thead>
    </table>

    <br>

    {{-- Ciclo para obtener los colaboradores y filtrarlos --}}
    <div id="pt-3">
        @php
            //Variables para obtener
            //Total de tareas creadas por departamento
            $totaldeptotareas = 0;
            
            //Total de tareas completadas por departamento
            $totaldeptocompletados = 0;
        @endphp

        <div class="row g-2">
            @foreach ($colaboradores as $contador)
                @if ($contador['depto'] == 'Facturacion')
                    @php
                        //Limpiamos el arreglo de proyecto al cambiar de contador
                        $proyectos = [];
                        
                        //Total de tareas creadas por colaborador
                        $totaltareas = 0;
                        
                        //Total de tareas completadas por colaborador
                        $completado = 0;
                        
                        //Total de tareas completadas por colaborador
                        $totalcompletados = 0;
                    @endphp

                    <div class="col-12 col-md-6 order-2 order-md-1" style="padding: 5px;">
                        <div style="background-color: #aac6ff; padding: 5px;" align=center>
                            <b>{{ ucfirst($contador['nombre']) }}</b>
                        </div>

                        {{-- Tabla de tareas --}}
                        <div class="table-responsive">
                            <table class="{{ $class }} tareaavance" style="width:100%">
                                <thead>
                                    <tr>
                                        <th hidden data-tableexport-display="always" colspan="2"
                                            style="background-color: #aac6ff; padding: 5px;">
                                            <b>{{ ucfirst($contador['nombre']) }}</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Avance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        foreach ($tareas as $tarea) {
                                            if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                //Almacenamos las empresas
                                                $proyectos[] = $tarea['nomproyecto'];
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
                                            //Total de tareas completadas por colaborador
                                            $completado = 0;
                                            
                                            foreach ($tareas as $tarea) {
                                                if ($tarea['rfccolaborador'] == $contador['RFC']) {
                                                    if ($tarea['completado'] == '1' && $tarea['nomproyecto'] == $key) {
                                                        $completado++;
                                                    }
                                                }
                                            }
                                            
                                            //Realzamos la suma de los totales
                                            $totalcompletados += $completado;
                                            
                                            //Obtenemos el porcentaje
                                            $porcentaje = ($completado * 100) / $value;
                                            
                                            //Obtenemos el total de tareas
                                            $totaltareas += $value;
                                        @endphp

                                        <tr>
                                            <td style="color: #3e464e">{{ $key }}</td>
                                            <td style="color: #3e464e">Tareas completadas {{ $completado }} de
                                                {{ $value }}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <b>{{ number_format($porcentaje, 2) }}%</b>
                                            </td>
                                        </tr>

                                        @php
                                            //Total de tareas completadas
                                            $completado = 0;
                                        @endphp
                                    @endforeach

                                    @php
                                        //Obtenenmos el total de tareas creadas por departamento
                                        $totaldeptotareas += $totaltareas;
                                        
                                        //Realzamos la suma de los totales por colaborador
                                        $totaldeptocompletados += $totalcompletados;
                                    @endphp

                                    <tr style="background-color: #aac6ff;">
                                        @php
                                            //Obtenenmos el porcentaje de los totales de cada colaborador
                                            if (!empty($totalcompletados) || !empty($totaltareas)) {
                                                //Obtenemos el porcentaje
                                                $totalporcentaje = ($totalcompletados * 100) / $totaltareas;
                                            } else {
                                                $totalporcentaje = 0;
                                            }
                                        @endphp

                                        <td></td>
                                        <td>
                                            <b>Total:</b>
                                            &nbsp;
                                            {{ $totalcompletados }}
                                            &nbsp;
                                            de
                                            &nbsp;
                                            {{ $totaltareas }}
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <b>{{ number_format($totalporcentaje, 2) }}%</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    @php
        //Obtenenmos el porcentaje de los totales de cada departamento
        if (!empty($totaldeptotareas) || !empty($totaldeptocompletados)) {
            //Obtenemos el porcentaje
            $totaldeptoporcentaje = ($totaldeptocompletados * 100) / $totaldeptotareas;
        } else {
            $totaldeptoporcentaje = 0;
        }
    @endphp

    <input id="porcentfactu" type="hidden" value="{{ number_format($totaldeptoporcentaje, 2) }}">

    <script>
        //Recibimos el llamado y ejecutamos la funcion
        window.addEventListener('estadonuevo', event => {
            var Porcentaje = $("#porcentfactu").val();
            $(".PorcentFactu").text('Avance del ' + Porcentaje + '%');
        });

        var Porcentaje = $("#porcentfactu").val();
        $(".PorcentFactu").text('Avance del ' + Porcentaje + '%');
    </script>
</div>
