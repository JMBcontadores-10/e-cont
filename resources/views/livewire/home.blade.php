<div>
    @php
        use App\Models\Cheques;
        use Illuminate\Support\Facades\DB;
        
        $rfc = Auth::user()->RFC;
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        
        //Mostrar mensaje de saludo dependiendo la hora
        //Establecemos la zona horaria
        date_default_timezone_set('America/Mexico_City');
        //Obtenemos la hora
        $horahoy = date('H', time());
        
        //Condicional para mostrar los mensajes
        if ($horahoy >= 0 && $horahoy < 12) {
            $saludo = 'Buenos Días';
        } elseif ($horahoy >= 12 && $horahoy < 18) {
            $saludo = 'Buenas Tardes';
        } elseif ($horahoy >= 18 && $horahoy < 24) {
            $saludo = 'Buenas Noches';
        }
    @endphp

    {{-- Contenedor para mantener responsivo el contenido del modulo --}}
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-list-wrapper">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="inicio" align="center">
                                @php
                                    $rfc = Auth::user()->RFC;
                                    $tipo = Session::get('tipoU');
                                @endphp

                                <h2 id="txtsaludo">{{ $saludo }}</h2>
                                @if (!empty(auth()->user()->tipo))
                                    <h5>Contador@</h5>
                                @endif

                                @if (Auth::check())
                                    <h6>{{ auth()->user()->RFC }}</h6>
                                @endif
                                {{-- --------contenido seccion------- --}}

                                {{-- --------contenido seccion------- --}}
                            </div>
                        </div>

                        {{-- SECCION PARA MOSTRAR LOS PENDIENTES DE CHEQUES Y TRANSFERENCIAS --}}
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xl-5 col-xl-5 mb-5">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0"><b>Cheques y transferencias</b></h5>
                                        <div class="row">
                                            <div class="col-7">
                                                @if (Auth::user()->tipo || Auth::user()->TipoSE)
                                                    <label for="inputState">Empresa: {{ $empresa }}</label>
                                                    <select wire:model="rfcEmpresa" id="inputState1"
                                                        class="select form-control">
                                                        <option value="">--Selecciona Empresa--</option>
                                                        {{-- Llenamos el select con las empresa vinculadas --}}
                                                        <?php $rfc = 0;
                                                        $rS = 1;
                                                        foreach ($empresas as $fila) {
                                                            echo '<option value="' . $fila[$rfc] . '">' . $fila[$rS] . '</option>';
                                                        } ?>
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <label>Año</label>
                                                <select wire:model="anio" id="inputState2" class="select form-control">
                                                    <option value="">--Año--</option>
                                                    <?php foreach (array_reverse($anios) as $value) {
                                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            //Vamos a contabilizar cada aspecto faltante
                                            //Faltantes
                                            $TotalPendientes = 0;
                                            
                                            //No revisados
                                            $TotalNoRevisado = 0;
                                            
                                            //No contabilizados
                                            $TotalNoConta = 0;
                                            
                                            foreach ($pendientes as $ContaCheq) {
                                                if ($ContaCheq->pendi == 1) {
                                                    $TotalPendientes = ++$TotalPendientes;
                                                }
                                            
                                                if ($ContaCheq->verificado == 0) {
                                                    $TotalNoRevisado = ++$TotalNoRevisado;
                                                    $TotalNoConta = ++$TotalNoConta;
                                                } else {
                                                    if ($ContaCheq->conta == 0) {
                                                        $TotalNoConta = ++$TotalNoConta;
                                                    }
                                                }
                                            }
                                        @endphp

                                        {{-- Movimientos pendientes --}}
                                        <div class="row" style="text-align: center">
                                            <div class="col-1">
                                                <i class="fas fa-exclamation fa-2x"
                                                    style="padding: 0px 0px 0px 8px; color: red"></i>
                                            </div>
                                            <div class="col">
                                                <h6><b>Total de <br> pendientes</b></h6>
                                            </div>
                                            <div class="col-2">
                                                <h6>{{ $TotalPendientes }}</h6>
                                            </div>
                                        </div>

                                        @if (Auth::user()->tipo)
                                            {{-- Movimientos sin revisar --}}
                                            <br>
                                            <div class="row" style="text-align: center">
                                                <div class="col-1">
                                                    <i class="fas fa fa-check fa-2x" style="color: green"></i>
                                                </div>
                                                <div class="col">
                                                    <h6><b>Total de <br> no revisados</b></h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>{{ $TotalNoRevisado }}</h6>
                                                </div>
                                            </div>
                                            <br>
                                            {{-- Movimientos sin contabilizar --}}
                                            <div class="row" style="text-align: center">
                                                <div class="col-1">
                                                    <i class="fas fa-calculator fa-2x" style="color: blue"></i>
                                                </div>
                                                <div class="col">
                                                    <h6><b>Total de <br> no contabilizados</b></h6>
                                                </div>
                                                <div class="col-2">
                                                    <h6>{{ $TotalNoConta }}</h6>
                                                </div>
                                            </div>
                                        @endif
                                        <br>
                                        <a class="btn btn-primary shadow mr-1 mb-1 BtnVinculadas"
                                            href="{{ url('chequesytransferencias') }}">Ir a Cheques y
                                            transferencias</a>
                                    </div>
                                </div>
                            </div>





                            {{-- Lista de tareas pendientes (SOLO CONTADORES Y ADMINISTRADORES) --}}
                            @if (Auth::user()->tipo)
                                <div class="col-md-8 col-lg-8 col-xl-7 col-xl-7 mb-7">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            {{-- Titulo de la tarjeta --}}
                                            <h5 class="card-title mb-0"><b>Tareas pendientes</b></h5>

                                            {{-- Mostramos un select con el listado de los colaboradores --}}
                                            @if (Auth::user()->admin)
                                                <div>
                                                    {{-- Mostramos el RFC de la empresa que se selecciona --}}
                                                    <label for="inputState">Colaborador: {{ $rfccolab }}</label>
                                                    <select wire:model="rfccolab" class="select form-control">
                                                        <option value="">--Selecciona Sucursal--</option>

                                                        {{-- Mostramos las sucursales --}}
                                                        @foreach ($colaboradores as $colabo)
                                                            <option value="{{ $colabo['RFC'] }}">
                                                                {{ ucfirst($colabo['nombre']) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            {{-- Contenido --}}
                                            {{-- Tabla de tareas --}}
                                            <div class="table-responsive">
                                                <table class="{{ $class }}" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center align-middle">Tareas</th>
                                                            <th class="text-center align-middle">Fecha de inicio</th>
                                                            <th class="text-center align-middle">Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($proyectos as $proyecto)
                                                            <tr style="background-color: #f8f8f8;">
                                                                <td colspan="10">
                                                                    <label>{{ $proyecto['Nombre'] }}</label>
                                                                </td>
                                                            </tr>
                                                            {{-- Mostramos la lista de tareas --}}
                                                            @foreach ($tareas as $tarea)
                                                                @if ($tarea['rfccolaborador'] == $rfccolab && $tarea['rfcproyecto'] == $proyecto['RFC'])
                                                                    <tr>
                                                                        {{-- Nombre --}}
                                                                        <td>{{ $tarea['nombre'] }}</td>

                                                                        {{-- Fecha de inicio --}}
                                                                        <td>{{ $tarea['asigntarea'] }}</td>

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
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <br>

                                            <a id="TarePendi" class="btn btn-primary shadow mr-1 mb-1 BtnVinculadas"
                                                href="{{ url('tareas') }}">Revisar tareas pendientes</a>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    //Accion para enviarnos a la seccion de tareas
                                    $("#TarePendi").click(function() {
                                        sessionStorage.setItem('Seccion', 'Tareas');
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
