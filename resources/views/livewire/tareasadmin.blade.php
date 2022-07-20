<div>
    {{-- Libreria de exportacion --}}
    <script src="{{ asset('js/tableExport/libs/FileSaver/FileSaver.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/tableExport.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/jsPDF/jspdf.umd.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/pdfmake/vfs_fonts.js') }}" defer></script>
    <script src="{{ asset('js/tableExport/libs/js-xlsx/xlsx.core.min.js') }}" defer></script>


    @php
        //Importamos los modelos
        use App\Models\User;
        use App\Models\ExpedFiscal;
        use App\Models\Cheques;
        
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

    {{-- JS --}}
    <script src="{{ asset('js/tareas.js') }}" defer></script>

    {{-- Filtros de busqueda --}}
    <div class="form-inline mr-auto">
        {{-- Boton para agregar una nueva tarea --}}
        <button class="btn btn-primary" data-toggle="modal" data-target="#nuevatarea" data-backdrop="static"
            data-keyboard="false">
            <i class="fas fa-plus" style="top: 0 !important"></i> Nueva tarea
        </button>

        {{-- Espaciado --}}
        <div style="width: 5em"></div>

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

        {{-- Busqueda por avance --}}
        <label for="inputState">Avance</label>
        &nbsp;&nbsp;
        <select wire:loading.attr="disabled" wire:change="ReloadFilt()" wire:model="avancetareaadmin" id="inputState2"
            class="select form-control AvanceSelectTarea">
            <option>Departamento</option>
            <option>Colaboradores</option>
            <option>Tareas</option>
        </select>
        &nbsp;&nbsp;
        &nbsp;&nbsp;

        {{-- Departamentos --}}
        @if (empty($active))
            {{-- Busqueda por avance --}}
            <label {{ $active }} for="inputState">Departamento</label>
            &nbsp;&nbsp;
            <select {{ $active }} wire:loading.attr="disabled" id="selectdepto" wire:model="departament"
                class="select form-control AvanceSelectTarea">
                <option>Contabilidad</option>
                <option>Nóminas</option>
                <option>Facturación</option>
            </select>
        @endif

        {{-- Lista de colaboradores --}}
        @if (empty($activetarea))
            {{-- Busqueda por avance --}}
            <label {{ $activetarea }} for="inputState">Colaborador</label>
            &nbsp;&nbsp;
            <select {{ $activetarea }} wire:loading.attr="disabled" id="selectdepto" wire:model="colaboselect"
                class="select form-control AvanceSelectTarea">
                <option value="">Seleccione un colaborador</option>
                @foreach ($consulconta as $infocolabo)
                    <option value="{{ $infocolabo['RFC'] }}">{{ ucfirst($infocolabo['nombre']) }}</option>
                @endforeach
            </select>
        @endif

        {{-- Botones de exportacion --}}
        @if (empty($activecolao))
            {{-- Exportar a Excel --}}
            <button type="button" class="btn btn-success BtnVinculadas"
                onclick="exporttareasavanceexcel('{{ $fechahoy }}')">Excel</button>
            &nbsp;&nbsp;
        @endif

    </div>

    <br><br>

    {{-- Animacion de cargando --}}
    <div wire:loading>
        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
            <div></div>
            <div></div>
        </div>
        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
    </div>

    {{-- switch para seleccionar el tipo de avance --}}
    @switch($avancetareaadmin)
        @case('Departamento')
            {{-- Importamos los componentes --}}
            <livewire:tareadepto :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin :departament=$departament
                :wire:key="'user-profile-one-'.$mestareaadmin.$aniotareaadmin.$departament" />
        @break

        @case('Colaboradores')
            {{-- Importamos los componentes --}}
            <livewire:tareaadmincolab :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin
                :wire:key="'user-profile-two-'.$mestareaadmin.$aniotareaadmin" />
        @break

        @case('Tareas')
            <livewire:tareacolab :colaboselect=$colaboselect :mestareaadmin=$mestareaadmin :aniotareaadmin=$aniotareaadmin
                :wire:key="'user-profile-three-'.$mestareaadmin.$aniotareaadmin.$colaboselect" />
        @break
    @endswitch

    {{-- Importamos los componentes --}}
    <livewire:tareanueva />

    @if (!empty(auth()->user()->admin))
        <script>
            //Emitir los datos de la empresa al componente
            $(document).ready(function() {
                //Guardamos en variables locales el contenido de sessionstorage
                var Seccion = sessionStorage.getItem('Seccion');

                //Condicion para saber si las variables no estan vacias
                if (Seccion !== null) {
                    //Emitimos los datos al controlador
                    window.livewire.emit('tareaselect', {
                        seccion: Seccion,
                    });
                    sessionStorage.clear();
                }
            });
        </script>
    @endif
</div>
