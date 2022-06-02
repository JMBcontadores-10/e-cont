<div>
    @php
        //Importamos los modelos necesarios
        use App\Models\User;
        
        //Obtenemos la clase al cargar la tabla
        $class = '';
        if (empty($class)) {
            $class = 'table nowrap dataTable no-footer';
        }
        //Obtnemos el nombre de la empresa
        $consulempre = User::where('RFC', $empresa)
            ->get()
            ->first();
    @endphp

    {{-- FACTURACION POR MES --}}
    {{-- Creacion del modal (BASE) --}}
    <div wire:ignore.self class="modal fade" id="factupormes" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="volucaptumodal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fa-comments">Facturación por
                            mes </span></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Filtros de busqueda --}}
                    <div class="form-inline mr-auto">
                        {{-- Busqueda por mes --}}
                        <label for="inputState">Mes</label>
                        <select id="MesSelectFactu" wire:model="factumesselect" id="inputState1"
                            wire:loading.attr="disabled" class="select form-control">
                            <?php foreach ($meses as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Busqueda por año --}}
                        <label for="inputState">Año</label>
                        <select id="AnioSelectFactu" wire:loading.attr="disabled" wire:model="factuanioselect"
                            id="inputState2" class="select form-control">
                            <?php foreach (array_reverse($anios) as $value) {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            } ?>
                        </select>
                        &nbsp;&nbsp;

                        {{-- Animacion de cargando --}}
                        <div wire:loading>
                            <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                                <div></div>
                                <div></div>
                            </div>
                            <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un
                            momento....
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        {{-- Tabla --}}
                        <div class="col">
                            <div class="table-responsive conttablemoni">
                                <table id="factumes" class="{{ $class }}" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center align-middle">
                                                Facturación por mes
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">Dia</th>
                                            <th class="text-center align-middle"># Fact. Emitidas</th>
                                            <th class="text-center align-middle">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- Contenido de facturacion por mes --}}
                                        @foreach ($consulmetames as $datametames)
                                            {!! $datametames !!}
                                        @endforeach

                                        <tr>
                                            {{-- Total --}}
                                            <td><b>Total:</b></td>
                                            <td>{{ $totalcantimes }}</td>
                                            <td>$ {{ number_format($totalmontomes, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="width: 5em"></div>

                        {{-- Graficas --}}
                        <div class="col">
                            <div style="width:34em">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0"><b>Facturacion del mes (Monto)</b>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="cantifactumesmonto" width="1000" height="1000"></canvas>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div style="width:34em">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0"><b>Facturacion del mes
                                                (Cantidad)</b>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="cantifactumescanti" width="1000" height="1000"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
