<div>
    @php
        use App\Models\Volumetrico;
        
        if (!empty($sucursales)) {
            //Vamos a realizar una consulta a los volumetricos
            $datavolu = Volumetrico::where(['rfc' => $sucursales])
                ->get()
                ->first();
        } else {
            //Vamos a realizar una consulta a los volumetricos
            $datavolu = Volumetrico::where(['rfc' => $empresa])
                ->get()
                ->first();
        }
    @endphp

    {{-- Modal de PDF volumetricos --}}
    {{-- Creacion del modal --}}
    <div wire:ignore.self class="modal fade" id="volupdfmodal{{ $dia }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Encabezado --}}
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"><span style="text-decoration: none;"
                            class="icons fas fas fa-file-pdf">PDF Volumetrico {{ $dia }}</span></h6>

                    <button id="closepdfvolu{{ $dia }}" type="button" wire:click="Refresh()"
                        class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                {{-- Cuerpo del modal --}}
                <div class="modal-body">
                    {{-- Texto de archivos existentes --}}
                    <div class="ArchExistContenedor">
                        <p class="pf LblArchExist"><b>Archivo Existente</b></p>
                    </div>

                    {{-- Animacion de cargando --}}
                    <div wire:loading>
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
                                    @if (empty($datavolu['volumetrico.' . $dia . '.PDFVolu']))
                                        <form>
                                            @csrf
                                            <div class="TxtNoArchivos">
                                                <h4>No hay archivo</h4>
                                            </div>

                                            @if (!empty($sucursales))
                                                {{-- -------Input FilePond---------- --}}
                                                <input name="volupdf" type="file"
                                                    id="volupdf{{ $dia }}&{{ $empresa }}&{{ $sucursales }}&{{ $nomsucur }}"
                                                    class="inputfilevolu" />
                                            @else
                                                {{-- -------Input FilePond---------- --}}
                                                <input name="volupdf" type="file"
                                                    id="volupdf{{ $dia }}&{{ $empresa }}"
                                                    class="inputfilevolu" />
                                            @endif
                                        </form>
                                    @else
                                        <div class="b" id="c">
                                            <input id="rutaAdicional" name="ruta-adicionales" type="hidden" value="">
                                            <input id="iden" type="hidden"
                                                value="{{ $datavolu['volumetrico.' . $dia . '.PDFVolu'] }}">

                                            @php
                                                //Obtenemos el nombre original de los PDF
                                                $NomPDF = $datavolu['volumetrico.' . $dia . '.PDFVolu'];
                                                $NomPDF = preg_split('/(&|PM-|AM-)/', $NomPDF);
                                                $NomPDF = end($NomPDF);
                                                
                                                $dateValue = strtotime($dia); //Obtener la fecha
                                                $mesvolu = date('m', $dateValue); //Obtener el mes
                                                $aniovolu = date('Y', $dateValue); //Obtener el año
                                                
                                                //Switch de meses
                                                //Obtenemos las rutas de los CFDI recibidos
                                                //Mes
                                                switch ($mesvolu) {
                                                    case '1':
                                                        $mesvolu = 'Enero';
                                                        break;
                                                
                                                    case '2':
                                                        $mesvolu = 'Febrero';
                                                        break;
                                                
                                                    case '3':
                                                        $mesvolu = 'Marzo';
                                                        break;
                                                
                                                    case '4':
                                                        $mesvolu = 'Abril';
                                                        break;
                                                
                                                    case '5':
                                                        $mesvolu = 'Mayo';
                                                        break;
                                                
                                                    case '6':
                                                        $mesvolu = 'Junio';
                                                        break;
                                                
                                                    case '7':
                                                        $mesvolu = 'Julio';
                                                        break;
                                                
                                                    case '8':
                                                        $mesvolu = 'Agosto';
                                                        break;
                                                
                                                    case '9':
                                                        $mesvolu = 'Septiembre';
                                                        break;
                                                
                                                    case '10':
                                                        $mesvolu = 'Octubre';
                                                        break;
                                                
                                                    case '11':
                                                        $mesvolu = 'Noviembre';
                                                        break;
                                                
                                                    case '12':
                                                        $mesvolu = 'Diciembre';
                                                        break;
                                                }
                                                
                                                if (!empty($sucursales)) {
                                                    //Ruta predeterminada
                                                    $ruta = 'storage/contarappv1_descargas/' . $empresa . '/' . $aniovolu . '/Volumetricos/' . $mesvolu . '/' . $nomsucur . '/' . $datavolu['volumetrico.' . $dia . '.PDFVolu'] . '';
                                                } else {
                                                    //Ruta predeterminada
                                                    $ruta = 'storage/contarappv1_descargas/' . $empresa . '/' . $aniovolu . '/Volumetricos/' . $mesvolu . '/' . $datavolu['volumetrico.' . $dia . '.PDFVolu'] . '';
                                                }
                                            @endphp

                                            <!--Contenedor para eliminar y visualizar PDF-->
                                            <div class="EncabezadoPDFContainer">
                                                <a class="DocumentPDF fas fa-file-pdf" target="_blank"
                                                    href="{{ asset($ruta) }}"></a>
                                            </div>
                                            <div class="CuerpoNamePDFContainer">
                                                <span class="SpanNamePDF"> {{ Str::limit($NomPDF, 10) }} <span>
                                            </div>


                                            @if (empty($datavolu['volumetrico.' . $dia . '.Revisado']))
                                                <div class="BotonesPDFContainer">
                                                    <!--Eliminar PDF-->
                                                    <div class="BtnDelPDF" wire:loading.attr="disabled"
                                                        wire:click="ElimPDFVolu()">
                                                        <i class="icons fas fa-trash-alt"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Js --}}
    <script>
        $(document).ready(function() {
            window.addEventListener('CerrarVoluPDF', event => {
                //Hacemos click al boton de cerrar
                $("#closepdfvolu" + event.detail.dia).click();
            });
        });
    </script>
</div>
