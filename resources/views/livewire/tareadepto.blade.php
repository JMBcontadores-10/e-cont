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


    {{-- Switch para saber que tipo de departamento se selecciono --}}
    @switch($departament)
        @case('Contabilidad')
            <livewire:tareaproyect :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin
                :wire:key="'user-profile-one-'.$mestareaadmin.$aniotareaadmin" />
        @break

        @case('Nóminas')
        @break

        @case('Facturación')
            <label>En el departamento de facturación se mostrará las actividades periódicas mensuales y bimestrales</label>
            {{-- Tabla para mostrar los datos de cada departamento --}}
            <div wire:poll class="table-responsive">
                <table class="{{ $class }} tablefacturacion" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Emisión de facturas</th>
                            <th class="text-center align-middle">Enero</th>
                            <th class="text-center align-middle">Febrero</th>
                            <th class="text-center align-middle">Marzo</th>
                            <th class="text-center align-middle">Abril</th>
                            <th class="text-center align-middle">Mayo</th>
                            <th class="text-center align-middle">Junio</th>
                            <th class="text-center align-middle">Julio</th>
                            <th class="text-center align-middle">Agosto</th>
                            <th class="text-center align-middle">Septiembre</th>
                            <th class="text-center align-middle">Octubre</th>
                            <th class="text-center align-middle">Noviembre</th>
                            <th class="text-center align-middle">Diciembre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listafactu as $colabfactu)
                            @php
                                //Obtenemos las tareas con periodo
                                $listatarea = Tareas::where('periodo', 'Mensual')
                                    ->orwhere('periodo', 'Bimestral')
                                    ->where('rfccolaborador', $colabfactu->RFC)
                                    ->get();
                                
                                //Obtenemos los nombres de las faturas (Tareas)
                                $nombresfactu = []; //Los almacenamos en un arreglo
                                
                                //Ciclo para almacenar los nombre
                                foreach ($listatarea as $tareas) {
                                    $nombresfactu[] = ['id' => $tareas['id'], 'Nombre' => $tareas['nombre']];
                                }
                                
                                //Eliminamos los datos repetidos
                                $nombresfactu = array_map('unserialize', array_unique(array_map('serialize', $nombresfactu)));
                            @endphp

                            {{-- Bucle para mostrar los registros --}}
                            @foreach ($nombresfactu as $infofactu)
                                <tr>
                                    <td style="color: #3e464e">{{ $infofactu['Nombre'] }}</td>

                                    @for ($i = 1; $i <= 12; $i++)
                                        @php
                                            //Variable bandera para saber si existe una tarea periodica terminada
                                            $factutermi = 0;
                                        @endphp

                                        {{-- Mostramos las celdas --}}
                                        @foreach ($listatarea as $tareas)
                                            @php
                                                $mesanio = date('Y-m', strtotime($tareas['finalizo']));
                                            @endphp

                                            @if ($mesanio == date('Y-m', strtotime($aniotareaadmin . '-' . $i)) && $tareas['id'] == $infofactu['id'])
                                                @php
                                                    //Bandera para omitir el dato en blanco
                                                    $factutermi = 1;
                                                @endphp

                                                <td class="text-center align-middle" style="background-color: #e0ffca">
                                                    <img src="{{ asset('img/ima.png') }}" alt="">

                                                    <br>

                                                    {{ $tareas['finalizo'] }}
                                                </td>
                                            @endif
                                        @endforeach

                                        {{-- Condicional para saber si el registro tiene algo --}}
                                        @if ($factutermi == 0)
                                            <td></td>
                                        @endif
                                    @endfor
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @break

        @default
    @endswitch
</div>
