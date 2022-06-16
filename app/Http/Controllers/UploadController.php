<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Cheque;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Models\ExpedFiscal;
use App\Models\Volumetrico;
use Exception;

// controlador para el plugin filepond
class UploadController extends Controller
{
    //Metodo para el envio de los correos (Este se ejecuta en el servidor de correos)
    public function SendEmail()
    {
        try {
            //Creamos el asunto del mensaje
            //Switch para identificar el tipo de impuesto
            switch ($_GET['Tipo']) {
                case 'Impuestos_Federales':
                    $asunto = 'Impuestos Federales';
                    break;

                case 'Impuestos_Remuneraciones':
                    $asunto = 'Impuestos sobre Remuneraciones';
                    break;

                case 'Impuestos_Hospedaje':
                    $asunto = 'Impuestos de Hospedaje';
                    break;

                case 'IMSS':
                    $asunto = 'IMSS';
                    break;

                case 'DIOT':
                    $asunto = 'DIOT';
                    break;

                case 'Balanza Mensual':
                    $asunto = 'Balanza Mensual';
                    break;
            }

            $filename = $_GET['RFC']  . '.pdf';
            $path = '../storage/FTP/' . $_GET['Tipo'] . '/';
            $file = $path . "/" . $filename;

            //Condicional si el archivo existe
            if (file_exists($file)) {
                $mailto = $_GET['Mail'];
                $subject = 'Línea de captura ' . $asunto;

                $content = file_get_contents($file);
                $content = chunk_split(base64_encode($content));

                //Separador de contenido con el mensaje
                $separator = md5(time());

                //Saltos de pagina
                $eol = "\r\n";

                //Encabezado
                $headers = "From: Econt <Econt@e-cont.com>" . $eol;
                $headers .= "MIME-Version: 1.0" . $eol;
                $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
                $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
                $headers .= "This is a MIME encoded message." . $eol;

                //Mensaje
                $body = "--" . $separator . $eol;
                $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
                $body .= "Content-Transfer-Encoding: 8bit" . $eol;
                $body .= "Buen dia" . $eol;
                $body .= "Se envía línea de captura para el pago de " . $asunto . " del mes." . $eol . $eol;
                $body .= "Atentamente:" . $eol;
                $body .= "JMB CONTADORES" . $eol;
                $body .= "TEL. (55) 5536-0293, (55) 8662-3397" . $eol . $eol . $eol;
                $body .= "*Favor de no responder a este correo, ya que se genera automáticamente. Si deseas comunicarte con nosotros hazlo a través de los teléfonos de oficina o al correo contabilidad@jmbcontadores.mx*" . $eol . $eol;
                $body .= "La Información contenida en este correo electrónico y anexos es confidencial. Está dirigido únicamente para el uso del individuo o entidad a la que fue dirigida y puede contener información propietaria que no es del dominio público. Si has recibido este correo por error o no eres el destinatario al que fue enviado, por favor notificar al remitente de inmediato y borra este mensaje de tú computadora. Cualquier uso, distribución o reproducción de este correo que no sea por el destinatario queda prohibido." . $eol;

                //Archivo adjunto
                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol;
                $body .= $content . $eol;
                $body .= "--" . $separator . "--";

                //Envio de correo
                if (mail($mailto, $subject, $body, $headers)) {
                    echo "Correo:" . $mailto . " , enviado satisfactoriamente";
                } else {
                    echo "Correo:" . $mailto . ", no se envio, hubo un error";
                    print_r(error_get_last());
                }
            } else {
                echo "No existe el archivo: " . $filename . "<br>";
            }
        } catch (Exception $e) {
            echo "Hubo un error: " . $e;
        }
    }


    public function store(Request $r, $id)
    { // recibe por request llos parametros del filepond
        //establecer hora y año para documento
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $hora = date('h:i:s A');
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        // id del cheuqe

        $ID = $id;


        if ($r->hasFile('avatar')) { /// se consulta hasFile 'avatar'
            $file = $r->file('avatar');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo
            $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
            $rfc = Auth::user()->RFC; // obtencion del rfc del usuario logeado
            $dateValue = strtotime($cheque->fecha); // metodo strtotime() para fecha
            $mesfPago = date('m', $dateValue); // obtencion mes del cheque
            $aniocheque = date('Y', $dateValue); // obtencion mes del cheque
            $espa = new Cheques(); // creacion del obejeto cheques para llamar a funcion fecha_es()
            //$espa->fecha_es($mes);
            $Mes = $espa->fecha_es($mesfPago); // funcion fecha_es para convetir mes int en mes español
            //===============================================================================================//
            /// mes en el que el usuario sube los archivos relacionados
            $mesActual = date('m');
            $dia = date('d');
            $actual = $espa->fecha_es($mesActual);
            //===============================================================================================//
            $Id = $dt->format('Y\Hh\Mi\SsA'); // obtener año y hora con segundos para evitar repetidos
            $Id2 = $dt->format('d');
            $fn = preg_replace('/[^A-z0-9.-]+/', '', $filename);

            $renameFile = $Id2 . $Mes . $Id . "&" . $fn; // renombra los archivos
            //ruta donde se almacenan los archivos
            $ruta = 'contarappv1_descargas/' . $cheque->rfc . "/" . $aniocheque . "/Cheques_Transferencias/Documentos_Relacionados/" . $Mes . "/";




            /// se guarda el documento en la ruta especifica con store / storeAs
            // se guradan los documentos relacionados en la carpeta correspondiente al mes
            $file->storeAs($ruta, $renameFile, 'public2');

            /// se guardan los enlases en la bd
            $cheque->pull('doc_relacionados', "");
            $cheque->push('doc_relacionados', $renameFile);
            // $cheque->push('doc_relacionados', $renameFile);


            return "entro en zona de carga" . $ID;
        } // fin if

        return "no entro";
    } // fin funcion store



    public function store2(Request $r, $id)
    { // recibe por request llos parametros del filepond
        //establecer hora y año para documento
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $hora = date('h:i:s A');
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        // id del cheuqe

        $ID = $id;


        if ($r->hasFile('adicionalesNuevoCheque')) { /// se consulta hasFile 'avatar'
            $file = $r->file('adicionalesNuevoCheque');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo
            $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
            $rfc = Auth::user()->RFC; // obtencion del rfc del usuario logeado
            $dateValue = strtotime($cheque->fecha); // metodo strtotime() para fecha
            $mesfPago = date('m', $dateValue); // obtencion mes del cheque
            $aniocheque = date('Y', $dateValue); // obtencion mes del cheque
            $espa = new Cheques(); // creacion del obejeto cheques para llamar a funcion fecha_es()
            //$espa->fecha_es($mes);
            $Mes = $espa->fecha_es($mesfPago); // funcion fecha_es para convetir mes int en mes español
            //===============================================================================================//
            /// mes en el que el usuario sube los archivos relacionados
            $mesActual = date('m');
            $dia = date('d');
            $actual = $espa->fecha_es($mesActual);
            //===============================================================================================//
            $Id = $dt->format('Y\Hh\Mi\SsA'); // obtener año y hora con segundos para evitar repetidos
            $Id2 = $dt->format('d');
            $fn = preg_replace('/[^A-z0-9.-]+/', '', $filename);

            $renameFile = $Id2 . $Mes . $Id . "&" . $fn; // renombra los archivos
            //ruta donde se almacenan los archivos
            $ruta = 'contarappv1_descargas/' . $cheque->rfc . "/" . $aniocheque . "/Cheques_Transferencias/Documentos_Relacionados/" . $Mes . "/";




            /// se guarda el documento en la ruta especifica con store / storeAs
            // se guradan los documentos relacionados en la carpeta correspondiente al mes
            $file->storeAs($ruta, $renameFile, 'public2');

            /// se guardan los enlases en la bd
            $cheque->pull('doc_relacionados', "");
            $cheque->push('doc_relacionados', $renameFile);
            // $cheque->push('doc_relacionados', $renameFile);


            return "entro en zona de carga" . $ID;
        } // fin if

        return "no entro";
    } // fin funcion store










    /*====================================================================== */

    public function  storeEditPdf(Request $r, $id)
    {

        $ID = $id;


        if ($r->hasFile('editCheque')) { /// se consulta hasFile 'editcheque'
            $file = $r->file('editCheque');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo
            $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);

            $rfc = Auth::user()->RFC;
            $Id = $dt->format('Y\Hh\Mi\SsA'); // obtener año y hora con segundos para evitar repetidos
            $Id2 = $dt->format('d');
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
            $anio = $dt->format('Y');
            $dateValue = strtotime($cheque->fecha);
            $anio = date('Y', $dateValue);
            $mesfPago = date('m', $dateValue);
            $mesActual = date('m');
            $espa = new Cheques();
            //$espa->fecha_es($mes);
            $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;


            $ruta = "contarappv1_descargas/" . $cheque->rfc . "/" . $anio . "/Cheques_Transferencias/" . $espa->fecha_es($mesfPago) . "/";




            // se guradan los documentos relacionados en la carpeta correspondiente al mes
            $file->storeAs($ruta, $renameFile, 'public2');

            $cheque->update([  // actualiza el campo nombrec a 0
                'nombrec' => $renameFile,
            ]);



            return "entro en zona de carga" . $ID . "<br>" . $ruta;
        }
    } // fin funcion edit pdf





    public function  storeEditPdf2(Request $r, $id)
    {

        $ID = $id;


        if ($r->hasFile('nuevoCheque')) { /// se consulta hasFile 'editcheque'
            $file = $r->file('nuevoCheque');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo
            $cheque = Cheques::where('_id', $ID)->first(); // enlase al documento cheques por _id cheque
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);

            $rfc = Auth::user()->RFC;
            $Id = $dt->format('Y\Hh\Mi\SsA'); // obtener año y hora con segundos para evitar repetidos
            $Id2 = $dt->format('d');
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
            $anio = $dt->format('Y');
            $dateValue = strtotime($cheque->fecha);
            $anio = date('Y', $dateValue);
            $mesfPago = date('m', $dateValue);
            $mesActual = date('m');
            $espa = new Cheques();
            //$espa->fecha_es($mes);
            $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;


            $ruta = "contarappv1_descargas/" . $cheque->rfc . "/" . $anio . "/Cheques_Transferencias/" . $espa->fecha_es($mesfPago) . "/";




            // se guradan los documentos relacionados en la carpeta correspondiente al mes
            $file->storeAs($ruta, $renameFile, 'public2');

            $cheque->update([  // actualiza el campo nombrec a 0
                'nombrec' => $renameFile,
            ]);



            return "entro en zona de carga" . $ID . "<br>" . $ruta;
        }
    } // fin funcion edit pdf


    //==========================  [ filePond LISTA DE RAYA] ==================================//

    public function  listaRaya(Request $r, $rfc, $anio, $periodo)
    {




        if ($r->hasFile('listaRaya')) { /// se consulta hasFile 'editcheque'
            $file = $r->file('listaRaya');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo

            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);

            $renameFile = "NominaPeriodo" . $periodo . ".pdf";
            $ruta = "contarappv1_descargas/" . $rfc . "/" . $anio . "/Nomina/Periodo" . $periodo . "/Raya/";

            if (!Storage::disk('public2')->exists($ruta)) {



                // se guradan los documentos relacionados en la carpeta correspondiente al mes
                $file->storeAs($ruta, $renameFile, 'public2', 0775, true);

                return "entro en zona de carga <br>" . $ruta . "<br>" . $rfc . "<br>" . $anio;
            }
        } else {

            return "ya existe un archivo no se puede remplazar Eliminelo primero";
        }
    } // fin funcion edit pdf


    //==================================== Recibos de Nomna ==============================////

    public function  recibosNomina(Request $r, $rfc, $anio, $periodo)
    {




        if ($r->hasFile('recibosNomina')) { /// se consulta hasFile 'editcheque'
            $file = $r->file('recibosNomina');
            $filename = $file->getClientOriginalName(); // se obtine el nombre original de archivo

            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);

            $renameFile = "RecibosPeriodo" . $periodo . ".pdf";
            $ruta = "contarappv1_descargas/" . $rfc . "/" . $anio . "/Nomina/Periodo" . $periodo . "/RecibosNomina/";

            if (!Storage::disk('public2')->exists($ruta)) {



                // se guradan los documentos relacionados en la carpeta correspondiente al mes
                $file->storeAs($ruta, $renameFile, 'public2', 0775, true);

                return "entro en zona de carga <br>" . $ruta . "<br>" . $rfc . "<br>" . $anio;
            }
        } else {

            return "ya existe un archivo no se puede remplazar Eliminelo primero";
        }
    } // fin funcion edit pdf






    //Metodo para almacenar PDF volumetricos
    public function PDFVolu(Request $r, $id)
    {
        //Comprueba si el nombre recibido sea "volupdf"
        if ($r->hasFile('volupdf')) {
            $file = $r->file('volupdf');
            $filename = $file->getClientOriginalName(); //Obtiene el nombre original de archivo

            //Descomponemos el id para obtener los datos enviados
            $iddescompuestos = explode("&", $id);

            if (sizeof($iddescompuestos) > 2) {
                //Fecha
                $Fecha = $iddescompuestos[0];

                //RFC Empresa
                $RFCEmpre = $iddescompuestos[1];

                //RFC Sucursal
                $RFCSucur = $iddescompuestos[2];

                //Nombre Sucursal
                $NomSucur = $iddescompuestos[3];

                //Datos para nombrar el archivo
                $dtz = new DateTimeZone("America/Mexico_City");
                $dt = new DateTime("now", $dtz);
                $Id = $dt->format('Y\Hh\Mi\SsA');
                $Id2 = $dt->format('d');
                $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
                $anio = $dt->format('Y');
                $dateValue = strtotime($Fecha);
                $anio = date('Y', $dateValue);
                $mesfPago = date('m', $dateValue);
                $mesActual = date('m');

                $espa = new Cheques(); //Vamos a ocupas un metodo para 

                //Nombramos al archivo
                $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $RFCEmpre . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mesfPago) . "/" . $NomSucur . "/";

                //Condicional para obtener la extencion
                $fileextencion = $file->getClientOriginalExtension();

                //Condicional para saber si el archivo es extencion PDF
                if ($fileextencion == 'pdf' || $fileextencion == 'PDF') {
                    //Realizamos la consulta para agregar el dato de PDF
                    $infovolumetric = Volumetrico::where(['rfc' => $RFCSucur]);

                    //Obtenemos los datos de la consulta
                    $datavolumetric = $infovolumetric->get()->first();

                    if (empty($datavolumetric['volumetrico.' . $Fecha . '.PDFVolu'])) {
                        //Se guradan los documentos relacionados en la carpeta correspondiente al mes
                        $file->storeAs($ruta, $renameFile, 'public2');

                        //Almacenamos en la base de datos
                        $infovolumetric->update([
                            'rfc' => $RFCSucur,
                            'volumetrico.' . $Fecha . '.PDFVolu' => $renameFile,
                        ], ['upsert' => true]);
                    }
                }
            } else {
                //Fecha
                $Fecha = $iddescompuestos[0];

                //RFC Empresa
                $RFCEmpre = $iddescompuestos[1];

                //Datos para nombrar el archivo
                $dtz = new DateTimeZone("America/Mexico_City");
                $dt = new DateTime("now", $dtz);
                $Id = $dt->format('Y\Hh\Mi\SsA');
                $Id2 = $dt->format('d');
                $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
                $anio = $dt->format('Y');
                $dateValue = strtotime($Fecha);
                $anio = date('Y', $dateValue);
                $mesfPago = date('m', $dateValue);
                $mesActual = date('m');

                $espa = new Cheques(); //Vamos a ocupas un metodo para 

                //Nombramos al archivo
                $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $RFCEmpre . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mesfPago) . "/";

                //Condicional para obtener la extencion
                $fileextencion = $file->getClientOriginalExtension();

                //Condicional para saber si el archivo es extencion PDF
                if ($fileextencion == 'pdf' || $fileextencion == 'PDF') {
                    //Realizamos la consulta para agregar el dato de PDF
                    $infovolumetric = Volumetrico::where(['rfc' => $RFCEmpre]);

                    //Obtenemos los datos de la consulta
                    $datavolumetric = $infovolumetric->get()->first();

                    if (empty($datavolumetric['volumetrico.' . $Fecha . '.PDFVolu'])) {
                        //Se guradan los documentos relacionados en la carpeta correspondiente al mes
                        $file->storeAs($ruta, $renameFile, 'public2');

                        //Almacenamos en la base de datos
                        $infovolumetric->update([
                            'rfc' => $RFCEmpre,
                            'volumetrico.' . $Fecha . '.PDFVolu' => $renameFile,
                        ], ['upsert' => true]);
                    }
                }
            }
        }
    }

    //Metodo para almacenar PDF CRE
    public function PDFCRE(Request $r, $id)
    {
        //Comprueba si el nombre recibido sea "volupdf"
        if ($r->hasFile('volupdfcre')) {
            $file = $r->file('volupdfcre');
            $filename = $file->getClientOriginalName(); //Obtiene el nombre original de archivo

            //Descomponemos el id para obtener los datos enviados
            $iddescompuestos = explode("&", $id);

            if (sizeof($iddescompuestos) > 2) {
                //Fecha
                $Fecha = $iddescompuestos[0];

                //RFC Empresa
                $RFCEmpre = $iddescompuestos[1];

                //RFC Sucursal
                $RFCSucur = $iddescompuestos[2];

                //Nombre Sucursal
                $NomSucur = $iddescompuestos[3];

                //Datos para nombrar el archivo
                $dtz = new DateTimeZone("America/Mexico_City");
                $dt = new DateTime("now", $dtz);
                $Id = $dt->format('Y\Hh\Mi\SsA');
                $Id2 = $dt->format('d');
                $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
                $anio = $dt->format('Y');
                $dateValue = strtotime($Fecha);
                $anio = date('Y', $dateValue);
                $mesfPago = date('m', $dateValue);
                $mesActual = date('m');

                $espa = new Cheques(); //Vamos a ocupas un metodo para 

                //Nombramos al archivo
                $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $RFCEmpre . "/" . $anio . "/CRE/" . $espa->fecha_es($mesfPago) . "/" . $NomSucur . "/";

                //Condicional para obtener la extencion
                $fileextencion = $file->getClientOriginalExtension();

                //Condicional para saber si el archivo es extencion PDF
                if ($fileextencion == 'pdf' || $fileextencion == 'PDF') {
                    //Realizamos la consulta para agregar el dato de PDF
                    $infovolumetric = Volumetrico::where(['rfc' => $RFCSucur]);

                    //Obtenemos los datos de la consulta
                    $datavolumetric = $infovolumetric->get()->first();

                    if (empty($datavolumetric['volumetrico.' . $Fecha . '.PDFCRE'])) {
                        //Se guradan los documentos relacionados en la carpeta correspondiente al mes
                        $file->storeAs($ruta, $renameFile, 'public2');

                        //Almacenamos en la base de datos
                        $infovolumetric->update([
                            'rfc' => $RFCSucur,
                            'volumetrico.' . $Fecha . '.PDFCRE' => $renameFile,
                        ], ['upsert' => true]);
                    }
                }
            } else {
                //Fecha
                $Fecha = $iddescompuestos[0];

                //RFC Empresa
                $RFCEmpre = $iddescompuestos[1];

                //Datos para nombrar el archivo
                $dtz = new DateTimeZone("America/Mexico_City");
                $dt = new DateTime("now", $dtz);
                $Id = $dt->format('Y\Hh\Mi\SsA');
                $Id2 = $dt->format('d');
                $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
                $anio = $dt->format('Y');
                $dateValue = strtotime($Fecha);
                $anio = date('Y', $dateValue);
                $mesfPago = date('m', $dateValue);
                $mesActual = date('m');

                $espa = new Cheques(); //Vamos a ocupas un metodo para 

                //Nombramos al archivo
                $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $RFCEmpre . "/" . $anio . "/CRE/" . $espa->fecha_es($mesfPago) . "/";

                //Condicional para obtener la extencion
                $fileextencion = $file->getClientOriginalExtension();

                //Condicional para saber si el archivo es extencion PDF
                if ($fileextencion == 'pdf' || $fileextencion == 'PDF') {
                    //Realizamos la consulta para agregar el dato de PDF
                    $infovolumetric = Volumetrico::where(['rfc' => $RFCEmpre]);

                    //Obtenemos los datos de la consulta
                    $datavolumetric = $infovolumetric->get()->first();

                    if (empty($datavolumetric['volumetrico.' . $Fecha . '.PDFCRE'])) {
                        //Se guradan los documentos relacionados en la carpeta correspondiente al mes
                        $file->storeAs($ruta, $renameFile, 'public2');

                        //Almacenamos en la base de datos
                        $infovolumetric->update([
                            'rfc' => $RFCEmpre,
                            'volumetrico.' . $Fecha . '.PDFCRE' => $renameFile,
                        ], ['upsert' => true]);
                    }
                }
            }
        }
    }

    //Metodo para subir los acuses del modulo de expediente fiscal
    public function AcuseExpFisc(Request $r, $id)
    {
        //Comprueba si el nombre recibido sea "volupdf"
        if ($r->hasFile('acuse')) {
            $file = $r->file('acuse');
            $filename = $file->getClientOriginalName(); //Obtiene el nombre original de archivo

            //Descomponemos el id para obtener los datos enviados
            $iddescompuestos = explode("&", $id);

            //Tipo
            $Tipo = $iddescompuestos[0];

            //Empresa
            $Empresa = $iddescompuestos[1];

            //Mes
            $Mes = $iddescompuestos[2];

            //Año
            $Anio = $iddescompuestos[3];

            //Variables para sucursal

            //Matriz
            $Matriz = $iddescompuestos[4] ?? null;

            //Nombre
            $Nombre = $iddescompuestos[5] ?? null;

            //Datos para nombrar el archivo
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);
            $Id = $dt->format('Y\Hh\Mi\SsA');
            $Id2 = $dt->format('d');
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
            $mesActual = date('m');

            $espa = new Cheques();

            //Nombramos al archivo
            $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

            //Condicional para saber si existe una matriz (Sucursales)
            if (!empty($Matriz) || !empty($Nombre)) {
                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $Matriz . "/" . $Anio . "/Expediente_Fiscal/" . $Tipo . "/" . $Mes . "/" . $Nombre . '/';
                $rutaftp = 'FTP/' . $Tipo . "/";
            } else {
                //Ruta de descarga
                $ruta = "contarappv1_descargas/" . $Empresa . "/" . $Anio . "/Expediente_Fiscal/" . $Tipo . "/" . $Mes . "/";
                $rutaftp = 'FTP/' . $Tipo . "/";
            }

            //Condicional para obtener la extencion
            $fileextencion = $file->getClientOriginalExtension();

            //Condicional para saber si el archivo es extencion PDF
            if ($fileextencion == 'pdf' || $fileextencion == 'PDF') {
                //Realizamos la consulta para agregar el dato de PDF
                $infoacuse = ExpedFiscal::where(['rfc' => $Empresa]);

                //Se guradan los documentos relacionados en la carpeta correspondiente al mes
                $file->storeAs($ruta, $renameFile, 'public2');

                //Almacenamos el archivo que se enviara por FTP
                $file->storeAs($rutaftp, $Empresa . '.pdf', 'public2');

                //Almacenamos en la base de datos
                $infoacuse->update([
                    'rfc' => $Empresa,
                ], ['upsert' => true]);

                //Agregamos el archivo a la lista de acuses
                $infoacuse->pull('ExpedFisc.' . $Anio . '.' . $Tipo . '.' .  $Mes . '.Acuse', "");
                $infoacuse->push('ExpedFisc.' . $Anio . '.' . $Tipo . '.' .  $Mes . '.Acuse', $renameFile);
            }

            // //Envio de los datos por FTP y correo

            // //Condicional para identificar los impuestos que se enviara el correo
            // if ($Tipo == 'Impuestos_Federales' || $Tipo == 'Impuestos_Remuneraciones' || $Tipo == 'Impuestos_Hospedaje' || $Tipo == 'IMSS') {
            //     //Informacion de conexion FTP
            //     $server = 'correosecont.x10.mx';
            //     $ftp_user_name = 'lnrhdwjb';
            //     $ftp_user_pass = 'Tecnologi@1';

            //     /*Vamos a pasar por todas las carpetas que son requeridos
            //         - Impuestos federales
            //         - Impuestos sobre remuneraciones
            //         - Impuesto hospedaje
            //         - IMSS*/

            //     //Subimos los archivos a la servidor FTP y eliminamos los archivos en la carpeta local
            //     $files = glob('storage/FTP/' . $Tipo . "/*"); //Obtenemos todos los nombres de los ficheros
            //     foreach ($files as $file) {
            //         //FTP
            //         //Conexion FTP
            //         $ftpconect = \ftp_connect($server) or die("No se pudo conectar con el servidor: $server <br>");

            //         //Condicional para saber si se realizo una conexion exitosa
            //         if (@ftp_login($ftpconect, $ftp_user_name, $ftp_user_pass)) {
            //             //Mensaje de confirmacion
            //             echo "La conexion con $ftp_user_name@$server se realizo con exito <br>";

            //             //Obtenemos los datos de direccion de archivos
            //             $localFilePath  = $file; //Archivo local
            //             $remoteFilePath = $file; //Archivo remoto

            //             //Condicional para conocer el tamaño del archivo
            //             if (filesize($file) > 0) {
            //                 //Subimos los archivos al servidor FTP
            //                 if (ftp_put($ftpconect, $remoteFilePath, $localFilePath, FTP_BINARY)) {
            //                     echo "El archivo $localFilePath se subio exitosamente <br>";
            //                 } else {
            //                     echo "Hubo un error al subir el archivo $localFilePath <br>";
            //                 }
            //             } else {
            //                 //Si el archivo tiene un error vamos a volver a crearlo
            //                 //Obtenemos el RFC que esta en el nombre del archivo
            //                 $info = pathinfo($file);
            //                 $rfcerror =  basename($file, '.' . $info['extension']);

            //                 //Ejecutamos el metodo para crear el PDF 
            //                 $this->MakePDFError($rfcerror);

            //                 //Realizamos otra condicional para verififcar si no tiene errores
            //                 if (filesize($file) > 0) {
            //                     //Subimos los archivos al servidor FTP
            //                     if (ftp_put($ftpconect, $remoteFilePath, $localFilePath, FTP_BINARY)) {
            //                         echo "El archivo $localFilePath se subio exitosamente <br>";
            //                     } else {
            //                         echo "Hubo un error al subir el archivo $localFilePath <br>";
            //                     }
            //                 } else {
            //                     echo "El archivo $localFilePath tiene un error al crearse, favor de revisar <br>";
            //                 }
            //             }
            //         } else {
            //             echo "No se pudo conectar con el servidor: $server <br>";
            //         }

            //         //Cerramos la conexion 
            //         ftp_close($ftpconect);

            //         if (is_file($file)) {
            //             unlink($file); //Elimino el fichero
            //         }
            //     }

            //     //Arreglo donde alamacenaremos los correos electronico
            //     $mailinfo = [
            //         //CORREOS DE EMPRESAS

            //         //1. AHF060131G59
            //         ['rfc' => 'AHF060131G59', 'email' => 'servicio.hf@permergas.mx'],

            //         //1. AHF060131G60 (Copia contabilidad)
            //         ['rfc' => 'AHF060131G59', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //2. AFU1809135Y4 (Lorena)
            //         ['rfc' => 'AFU1809135Y4', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //2. AFU1809135Y4 (Auxiliar)
            //         ['rfc' => 'AFU1809135Y4', 'email' => 'auxiliar.5708@outlook.com'],

            //         //2. AFU1809135Y4 (Copia contabilidad)
            //         ['rfc' => 'AFU1809135Y4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //3. AIJ161001UD1 (Lorena)
            //         ['rfc' => 'AIJ161001UD1', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //3. AIJ161001UD1 (Auxiliar)
            //         ['rfc' => 'AIJ161001UD1', 'email' => 'auxiliar.5708@outlook.com'],

            //         //3. AIJ161001UD1 (Copia contabilidad)
            //         ['rfc' => 'AIJ161001UD1', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //4. AAE160217C36 (Lorena)
            //         ['rfc' => 'AAE160217C36', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //4. AAE160217C36 (Auxiliar)
            //         ['rfc' => 'AAE160217C36', 'email' => 'auxiliar.5708@outlook.com'],

            //         //4. AAE160217C36 (Copia contabilidad)
            //         ['rfc' => 'AAE160217C36', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //5. CDI1801116Y9
            //         ['rfc' => 'CDI1801116Y9', 'email' => 'motelpicassocircuito@gmail.com'],

            //         //5. CDI1801116Y9 (Copia contabilidad)
            //         ['rfc' => 'CDI1801116Y9', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //6. CAJ161001KF6 (Lorena)
            //         ['rfc' => 'CAJ161001KF6', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //6. CAJ161001KF6 (Auxiliar)
            //         ['rfc' => 'CAJ161001KF6', 'email' => 'auxiliar.5708@outlook.com'],

            //         //6. CAJ161001KF6 (Copia contabilidad)
            //         ['rfc' => 'CAJ161001KF6', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //7. COB191129AZ2 (Lorena)
            //         ['rfc' => 'COB191129AZ2', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //7. COB191129AZ2 (Auxiliar)
            //         ['rfc' => 'COB191129AZ2', 'email' => 'auxiliar.5708@outlook.com'],

            //         //7. COB191129AZ2 (Copia contabilidad)
            //         ['rfc' => 'COB191129AZ2', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //8. CGU111019TF2
            //         ['rfc' => 'CGU111019TF2', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //9. CGC111019330
            //         ['rfc' => 'CGC111019330', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //10. DOT1911294F3 (Lorena)
            //         ['rfc' => 'DOT1911294F3', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //10. DOT1911294F3 (Auxiliar)
            //         ['rfc' => 'DOT1911294F3', 'email' => 'auxiliar.5708@outlook.com'],

            //         //10. DOT1911294F3 (Copia contabilidad)
            //         ['rfc' => 'DOT1911294F3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //11. DRO191104EZ0 (Lorena)
            //         ['rfc' => 'DRO191104EZ0', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //11. DRO191104EZ0 (Auxiliar)
            //         ['rfc' => 'DRO191104EZ0', 'email' => 'auxiliar.5708@outlook.com'],

            //         //11. DRO191104EZ0 (Copia contabilidad)
            //         ['rfc' => 'DRO191104EZ0', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //12. DRO191129DK5 (Lorena)
            //         ['rfc' => 'DRO191129DK5', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //12. DRO191129DK5 (Auxiliar)
            //         ['rfc' => 'DRO191129DK5', 'email' => 'auxiliar.5708@outlook.com'],

            //         //12. DRO191129DK5 (Copia contabilidad)
            //         ['rfc' => 'DRO191129DK5', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //13. ERO1911044L4 (Lorena)
            //         ['rfc' => 'ERO1911044L4', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //13. ERO1911044L4 (Auxiliar)
            //         ['rfc' => 'ERO1911044L4', 'email' => 'auxiliar.5708@outlook.com'],

            //         //13. ERO1911044L4 (Copia contabilidad)
            //         ['rfc' => 'ERO1911044L4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //14. PERE9308105X4
            //         ['rfc' => 'PERE9308105X4', 'email' => 'servicio.chalco@permergas.mx'],

            //         //14. PERE9308105X4 (Copia contabilidad)
            //         ['rfc' => 'PERE9308105X4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //15. FGA980316918
            //         ['rfc' => 'FGA980316918', 'email' => 'servicio.figuergas@permergas.mx'],

            //         //15. FGA980316918 (Copia contabilidad)
            //         ['rfc' => 'FGA980316918', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //16. GPA161202UG8
            //         ['rfc' => 'GPA161202UG8', 'email' => 'servicio.paris@permergas.mx'],

            //         //16. GPA161202UG8 (Copia contabilidad)
            //         ['rfc' => 'GPA161202UG8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //17. GEM190507UW8
            //         ['rfc' => 'GEM190507UW8', 'email' => 'admin@jmbcontadores.mx'],

            //         //17. GEM190507UW8 (Copia contabilidad)
            //         ['rfc' => 'GEM190507UW8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //18. GPR020411182 (Lorena)
            //         ['rfc' => 'GPR020411182', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //18. GPR020411182 (Auxiliar)
            //         ['rfc' => 'GPR020411182', 'email' => 'auxiliar.5708@outlook.com'],

            //         //18. GPR020411182 (Copia contabilidad)
            //         ['rfc' => 'GPR020411182', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //19. HRU121221SC2
            //         ['rfc' => 'HRU121221SC2', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //20. IAB0210236I7
            //         ['rfc' => 'IAB0210236I7', 'email' => 'gcendon@hotmail.com'],

            //         //20. IAB0210236I7 (Copia contabilidad)
            //         ['rfc' => 'IAB0210236I7', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //21. JQU191009699
            //         ['rfc' => 'JQU191009699', 'email' => 'admin@jmbcontadores.mx'],

            //         //21. JQU191009699 (Copia contabilidad)
            //         ['rfc' => 'JQU191009699', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //22. JCO171102SI9 (Lorena)
            //         ['rfc' => 'JCO171102SI9', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //22. JCO171102SI9 (Auxiliar)
            //         ['rfc' => 'JCO171102SI9', 'email' => 'auxiliar.5708@outlook.com'],

            //         //22. JCO171102SI9 (Copia contabilidad)
            //         ['rfc' => 'JCO171102SI9', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //23. MEN171108IG6
            //         ['rfc' => 'MEN171108IG6', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //24. MAR191104R53 (Lorena)
            //         ['rfc' => 'MAR191104R53', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //24. MAR191104R53 (Auxiliar)
            //         ['rfc' => 'MAR191104R53', 'email' => 'auxiliar.5708@outlook.com'],

            //         //24. MAR191104R53 (Copia contabilidad)
            //         ['rfc' => 'MAR191104R53', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //25. MCA130429FM8
            //         ['rfc' => 'MCA130429FM8', 'email' => 'admin@jmbcontadores.mx'],

            //         //25. MCA130429FM8 (Copia contabilidad)
            //         ['rfc' => 'MCA130429FM8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //26. MCC140101RV3
            //         ['rfc' => 'MCC140101RV3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //27. MCA130827V4A
            //         ['rfc' => 'MCA130827V4A', 'email' => 'admin@jmbcontadores.mx'],

            //         //27. MCA130827V4A (Copia contabilidad)
            //         ['rfc' => 'MCA130827V4A', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //28. MOP18022474A
            //         ['rfc' => 'MOP18022474A', 'email' => 'dotero@thealest.com'],

            //         //28. MOP18022474A (Copia contabilidad)
            //         ['rfc' => 'MOP18022474A', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //29. MOBJ8502058A4
            //         ['rfc' => 'MOBJ8502058A4', 'email' => 'admin@jmbcontadores.mx'],

            //         //29. MOBJ8502058A4 (Copia contabilidad)
            //         ['rfc' => 'MOBJ8502058A4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //30. PEM180224742 (Lorena)
            //         ['rfc' => 'PEM180224742', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //30. PEM180224742 (Auxiliar)
            //         ['rfc' => 'PEM180224742', 'email' => 'auxiliar.5708@outlook.com'],

            //         //30. PEM180224742 (Copia contabilidad)
            //         ['rfc' => 'PEM180224742', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //31. PEMJ7110258J3
            //         ['rfc' => 'PEMJ7110258J3', 'email' => 'servicio.ruave@permergas.mx'],

            //         //31. PEMJ7110258J3 (Copia contabilidad)
            //         ['rfc' => 'PEMJ7110258J3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //32. PML170329AZ9
            //         ['rfc' => 'PML170329AZ9', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //33. PERA0009086X3
            //         ['rfc' => 'PERA0009086X3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //34. PER180309RB3 (Lorena)
            //         ['rfc' => 'PER180309RB3', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //34. PER180309RB3 (Auxiliar)
            //         ['rfc' => 'PER180309RB3', 'email' => 'auxiliar.5708@outlook.com'],

            //         //34. PER180309RB3 (Copia contabilidad)
            //         ['rfc' => 'PER180309RB3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //35. RUCE750317I21
            //         ['rfc' => 'RUCE750317I21', 'email' => 'servicio.sanjuan@permergas.mx'],

            //         //35. RUCE750317I21 (Copia contabilidad)
            //         ['rfc' => 'RUCE750317I21', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //36. SBE190522I97
            //         ['rfc' => 'SBE190522I97', 'email' => 'picasso.churubusco@gmail.com'],

            //         //36. SBE190522I97 (Copia contabilidad)
            //         ['rfc' => 'SBE190522I97', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //37. SGA1905229H3
            //         ['rfc' => 'SGA1905229H3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //38. SGA1410217U4
            //         ['rfc' => 'SGA1410217U4', 'email' => 'servicio.azcapotzalco@permergas.mx'],

            //         //38. SGA1410217U4 (Copia contabilidad)
            //         ['rfc' => 'SGA1410217U4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //39. SGT190523QX8
            //         ['rfc' => 'SGT190523QX8', 'email' => 'servicio.tlahuac@permergas.mx'],

            //         //39. SGT190523QX8 (Copia contabilidad)
            //         ['rfc' => 'SGT190523QX8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //40. SGX190523KA4
            //         ['rfc' => 'SGX190523KA4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //41. SGX160127MC4
            //         ['rfc' => 'SGX160127MC4', 'email' => 'servicio.xola@permergas.mx'],

            //         //41. SGX160127MC4 (Copia contabilidad)
            //         ['rfc' => 'SGX160127MC4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //42. STR9303188X3
            //         ['rfc' => 'STR9303188X3', 'email' => 'servicio.trece@permergas.mx'],

            //         //42. STR9303188X3 (Copia contabilidad)
            //         ['rfc' => 'STR9303188X3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //43. SVI831123632 (Lorena)
            //         ['rfc' => 'SVI831123632', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //43. SVI831123632 (Auxiliar)
            //         ['rfc' => 'SVI831123632', 'email' => 'auxiliar.5708@outlook.com'],

            //         //43. SVI831123632 (Copia contabilidad)
            //         ['rfc' => 'SVI831123632', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //44. SCT150918RC9
            //         ['rfc' => 'SCT150918RC9', 'email' => 'servicios.carranza@permergas.mx'],

            //         //44. SCT150918RC9 (Copia contabilidad)
            //         ['rfc' => 'SCT150918RC9', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //45. SAJ161001KC6
            //         ['rfc' => 'SAJ161001KC6', 'email' => 'motelpicassolerma@gmail.com'],

            //         //45. SAJ161001KC6 (Copia contabilidad)
            //         ['rfc' => 'SAJ161001KC6', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //46. SPE171102P94 (Lorena)
            //         ['rfc' => 'SPE171102P94', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //46. SPE171102P94 (Auxiliar)
            //         ['rfc' => 'SPE171102P94', 'email' => 'auxiliar.5708@outlook.com'],

            //         //46. SPE171102P94 (Copia contabilidad)
            //         ['rfc' => 'SPE171102P94', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //47. SCO1905221P2
            //         ['rfc' => 'SCO1905221P2', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //48. GMH1602172L8 (Lorena)
            //         ['rfc' => 'GMH1602172L8', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //48. GMH1602172L8 (Auxiliar)
            //         ['rfc' => 'GMH1602172L8', 'email' => 'auxiliar.5708@outlook.com'],

            //         //48. GMH1602172L8 (Copia contabilidad)
            //         ['rfc' => 'GMH1602172L8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //49. MGE1602172LA (Lorena)
            //         ['rfc' => 'MGE1602172LA', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //49. MGE1602172LA (Auxiliar)
            //         ['rfc' => 'MGE1602172LA', 'email' => 'auxiliar.5708@outlook.com'],

            //         //49. MGE1602172LA (Copia contabilidad)
            //         ['rfc' => 'MGE1602172LA', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //50. SIC111019LP4
            //         ['rfc' => 'SIC111019LP4', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //51. SAE191009dd8
            //         ['rfc' => 'SAE191009dd8', 'email' => 'admin@jmbcontadores.mx'],

            //         //51. SAE191009dd8 (Copia contabilidad)
            //         ['rfc' => 'SAE191009dd8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //52. SMA180913NK6 (Lorena)
            //         ['rfc' => 'SMA180913NK6', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //52. SMA180913NK6 (Auxiliar)
            //         ['rfc' => 'SMA180913NK6', 'email' => 'auxiliar.5708@outlook.com'],

            //         //52. SMA180913NK6 (Copia contabilidad)
            //         ['rfc' => 'SMA180913NK6', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //53. SST030407D77
            //         ['rfc' => 'SST030407D77J', 'email' => 'servicio.jet@permergas.mx'],

            //         //53. SST030407D77 (Copia contabilidad)
            //         ['rfc' => 'SST030407D77J', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //53. SST030407D77
            //         ['rfc' => 'SST030407D77M', 'email' => 'servicio.matlazincas@permergas.mx'],

            //         //53. SST030407D77 (Copia contabilidad)
            //         ['rfc' => 'SST030407D77M', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //54. TEL1911043PA (Lorena)
            //         ['rfc' => 'TEL1911043PA', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //54. TEL1911043PA (Auxiliar)
            //         ['rfc' => 'TEL1911043PA', 'email' => 'auxiliar.5708@outlook.com'],

            //         //54. TEL1911043PA (Copia contabilidad)
            //         ['rfc' => 'TEL1911043PA', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //55. TOVF901004DN5
            //         ['rfc' => 'TOVF901004DN5', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //56. VER191104SP3 (Lorena)
            //         ['rfc' => 'VER191104SP3', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //56. VER191104SP3 (Auxiliar)
            //         ['rfc' => 'VER191104SP3', 'email' => 'auxiliar.5708@outlook.com'],

            //         //56. VER191104SP3 (Copia contabilidad)
            //         ['rfc' => 'VER191104SP3', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //57. VPT050906GI8
            //         ['rfc' => 'VPT050906GI8', 'email' => 'villadelparquetoluca@hotmail.com'],

            //         //57. VPT050906GI8 (Copia contabilidad)
            //         ['rfc' => 'VPT050906GI8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //58. VCO990603D84
            //         ['rfc' => 'VCO990603D84', 'email' => 'serviciovillada0968@hotmail.com'],

            //         //58. VCO990603D84 (Copia contabilidad)
            //         ['rfc' => 'VCO990603D84', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //59. IAR010220GK5
            //         ['rfc' => 'IAR010220GK5', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //60. GRU210504TH9 (Lorena)
            //         ['rfc' => 'GRU210504TH9', 'email' => 'lorena_cisneros01@hotmail.com'],

            //         //60. GRU210504TH9 (Auxiliar)
            //         ['rfc' => 'GRU210504TH9', 'email' => 'auxiliar.5708@outlook.com'],

            //         //60. GRU210504TH9 (Copia contabilidad)
            //         ['rfc' => 'GRU210504TH9', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //61. GMG2101076W2
            //         ['rfc' => 'GMG2101076W2', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //62. JCO2105043Y1
            //         ['rfc' => 'JCO2105043Y1', 'email' => 'admin@jmbcontadores.mx'],

            //         //62. JCO2105043Y1 (Copia contabilidad)
            //         ['rfc' => 'JCO2105043Y1', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //63. SGP210107CE8
            //         ['rfc' => 'SGP210107CE8', 'email' => 'contabilidad@jmbcontadores.mx'],

            //         //63. GME210504KW1
            //         ['rfc' => 'GME210504KW1', 'email' => 'contabilidad@jmbcontadores.mx'],
            //     ];

            //     //Ciclo para pasar por todo el arreglo y encontrar las coincidencias para el envio de correos
            //     foreach ($mailinfo as $mail) {
            //         //Condicional para saber si los RFC coinciden
            //         if ($mail['rfc'] == $Empresa) {
            //             //Metodo para enviar el correo electronico al cliente
            //             Http::post('http://correosecont.x10.mx/emailexpedfisc.php?RFC=' . $Empresa . '&Tipo=' . $Tipo . '&Mail=' . $mail['email']);
            //         }
            //     }

            //     //Metodo para eliminar el archivo de la carpeta FTP
            //     Http::post('http://correosecont.x10.mx/emaildelete.php?Tipo=' . $Tipo);
            // }
        }
    }
}// fin clase
