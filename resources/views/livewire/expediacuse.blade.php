<div>
    {{-- JS --}}
    <script src="{{ asset('js/expedfisc.js') }}" defer></script>

    @php
        use App\Models\ExpedFiscal;
        use App\Models\Cheques;
        
        if (!empty($dataacuse)) {
            //Arreglo que almacenara las rutas
            $rutasacuse = [];
        
            //Descomponemos la cadena enviada (a un arreglo)
            $iddescompuestos = explode('&', $dataacuse);
        
            //Tipo
            $Tipo = $iddescompuestos[0];
        
            //Empresa
            $Empresa = $iddescompuestos[1];
        
            //Mes
            $Mes = $iddescompuestos[2];
        
            //Año
            $Anio = $iddescompuestos[3];
        
            //Realizamos una consulta a la coleccion de expediente
            $infoacuse = ExpedFiscal::where(['rfc' => $Empresa])->first();
        }
    @endphp

    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="acuseexp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-file-pdf">Acuse(s)</span></h6>

                    <button type="button" class="closeacuse close" data-dismiss="modal" aria-label="Close"
                        wire:click="Refresh()">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Mensaje de aviso --}}
                    <div id="mnssuccess" class="alert alert-warning">
                        <div align="center"><b>Importante</b></div>
                        Todo acuse cargado en este módulo Econt lo enviara de forma automática por medio de correo
                        electrónico a la empresa seleccionada
                    </div>

                    {{-- Texto de archivos existentes --}}
                    <div class="ArchExistContenedor">
                        <p class="pf LblArchExist"><b>Archivos Existentes</b></p>
                    </div>

                    {{-- Animacion de cargando --}}
                    <div id="loadfileexpe" wire:loading>
                        <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                            <div></div>
                            <div></div>
                        </div>
                        <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                    </div>

                    {{-- Zona para subir archivos --}}
                    <div class="dropzone">
                        <p id="files-area">
                            <span id="filesList">
                                <div class="wrapper">
                                    @if (!empty($dataacuse) && !empty($infoacuse['ExpedFisc.' . $Anio . '.' . $Tipo . '.' . $Mes . '.Acuse']))
                                        @php
                                            $n = 1;
                                            
                                            //Arreglo donde almacenamos las rutas
                                            $resendruta = [];
                                        @endphp

                                        @foreach ($infoacuse['ExpedFisc.' . $Anio . '.' . $Tipo . '.' . $Mes . '.Acuse'] as $dataacuse)
                                            @php
                                                //Ruta de descarga
                                                $ruta = 'storage/contarappv1_descargas/' . $Empresa . '/' . $Anio . '/Expediente_Fiscal/' . $Tipo . '/' . $Mes . '/' . $dataacuse;
                                            @endphp

                                            <div class="b" id="c{{ $n }}">
                                                <!--Contenedor para eliminar y visualizar PDF-->
                                                <div class="EncabezadoPDFContainer">
                                                    <a class="DocumentPDF fas fa-file-pdf" target="_blank"
                                                        href="{{ asset($ruta) }}"></a>
                                                </div>

                                                <div class="CuerpoNamePDFContainer">
                                                    <span
                                                        class="SpanNamePDF">{{ Str::limit(Str::afterLast($dataacuse, '&'), 10) }}<span>
                                                </div>

                                                {{-- Condicional para la accion eliminar, cuando el movimiento esta revisado --}}
                                                <div class="BotonesPDFContainer">
                                                    <!--Eliminar PDF-->
                                                    <div class="BtnDelPDF" wire:click="Eliminar('{{ $dataacuse }}')"
                                                        wire:loading.attr="disabled">
                                                        <i class="icons fas fa-trash-alt"></i>
                                                    </div>
                                                </div>

                                            </div>

                                            @php
                                                $n++;
                                                
                                                //Almacenamos las rutas en el arreglo
                                                $resendruta[] = $ruta;
                                            @endphp
                                        @endforeach
                                    @else
                                        <div class="TxtNoArchivos">
                                            <h4>No hay archivo</h4>
                                        </div>
                                    @endif
                                </div>
                            </span>
                        </p>
                    </div>

                    <br>

                    {{-- Zona para subir archivos --}}
                    <div class="dropzone">
                        <p id="files-area">
                            <span id="filesList">
                                <form>
                                    @csrf
                                    {{-- -------Input FilePond---------- --}}
                                    <input name="acuse" type="file" id="acuse" />
                                </form>
                            </span>
                        </p>
                    </div>

                    {{-- Condicional para mostrar el boton de reenviar solamente a los impuestos disponibles --}}
                    @if (!empty($dataacuse) && !empty($resendruta))
                        @php
                            //Convertimos en JSON la lista de las rutas
                            $jsonrutas = json_encode($resendruta);
                        @endphp

                        @if ($Tipo == 'Impuestos_Federales' ||
                            $Tipo == 'Impuestos_Remuneraciones' ||
                            $Tipo == 'Impuestos_Hospedaje' ||
                            $Tipo == 'IMSS' ||
                            $Tipo == 'Impuestos_Estatal')
                            <div align="center" style="padding: 15px;">
                                <button
                                    wire:click="ResendAcuse('{{ $Tipo }}', '{{ $Empresa }}', {{ $jsonrutas }})"
                                    type="button" class="btn btn-success BtnVinculadas">Enviar correo</button>
                            </div>
                        @endif
                    @endif

                    <button id="btnsuccessemail" data-toggle="modal" data-target="#successemail" data-backdrop="static"
                        data-keyboard="false" hidden>Exito</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal mensaje de envio de correo exitoso --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="successemail" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-check-circle">Exito</span></h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    <div id="MensSuccess" align="center">
                        <h3 id="EncaSuccess"></h3>
                        <h5 id="BodySuccess"></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
