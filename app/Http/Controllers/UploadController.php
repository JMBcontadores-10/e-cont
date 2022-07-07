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

            //Funcion para obtener la informacion de la carpeta
            $carpeta = @scandir('/home/lnrhdwjb/storage/FTP/' . $_GET['Tipo'] . '/');

            //Condicional para saber si la caperta tiene archivos contenido
            if (count($carpeta) > 2) {
                //Informacion del correo
                $mailto = $_GET['Mail'];
                $subject = 'Línea de captura ' . $asunto;

                //Mensaje del correo
                $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                "http://www.w3.org/TR/html4/loose.dtd">
                <html><head></head><body>
                <p>Buen dia, Se envía línea de captura para el pago de ' . $asunto . ' del mes</p>
                <p>Atentamente:</p>
                <p>JMB CONTADORES</p>
                <p>TEL. (55) 5536-0293, (55) 8662-3397</p>
                <p>*Favor de no responder a este correo, ya que se genera automáticamente. Si deseas comunicarte con nosotros hazlo a través de los teléfonos de oficina o al correo contabilidad@jmbcontadores.mx*</p><br>
                <p>La Información contenida en este correo electrónico y anexos es confidencial. Está dirigido únicamente para el uso del individuo o entidad a la que fue dirigida y puede contener información propietaria que no es del dominio público. Si has recibido este correo por error o no eres el destinatario al que fue enviado, por favor notificar al remitente de inmediato y borra este mensaje de tú computadora. Cualquier uso, distribución o reproducción de este correo que no sea por el destinatario queda prohibido.</p></body></html>';

                //Separador de contenido con el mensaje
                $separator = md5(time());

                //Saltos de pagina
                $eol = "\r\n";

                //Encabezado
                $headers = "From: Econt <Econt@e-cont.com>" . $eol;
                $headers .= "MIME-Version: 1.0" . $eol;
                $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;

                //Mensaje
                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: text/html; charset=UTF-8" . $eol;
                $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
                $body .= $message . $eol . $eol;

                //Obtener los archivos de la carpeta
                $files = glob('/home/lnrhdwjb/storage/FTP/' . $_GET['Tipo'] . '/*'); //Obtenemos todos los nombres de los ficheros
                foreach ($files as $file) {

                    $content = file_get_contents($file);
                    $content = chunk_split(base64_encode($content));

                    //Archivo adjunto
                    $body .= "--" . $separator . $eol;
                    $body .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" .
                        "Content-Description: " . basename($file) . "\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $content . "\n\n";
                    $body .= $content . $eol;
                }

                //Envio de correo
                if (mail($mailto, $subject, $body, $headers)) {
                    echo "Correo:" . $mailto . " , enviado satisfactoriamente";
                } else {
                    echo "Correo:" . $mailto . ", no se envio, hubo un error";
                    print_r(error_get_last());
                }
            } else {
                echo "Carpeta vacia";
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
                $file->storeAs($rutaftp, $filename, 'public2');

                //Almacenamos en la base de datos
                $infoacuse->update([
                    'rfc' => $Empresa,
                ], ['upsert' => true]);

                //Agregamos el archivo a la lista de acuses
                $infoacuse->pull('ExpedFisc.' . $Anio . '.' . $Tipo . '.' .  $Mes . '.Acuse', "");
                $infoacuse->push('ExpedFisc.' . $Anio . '.' . $Tipo . '.' .  $Mes . '.Acuse', $renameFile);
            }

            //Envio de los datos por FTP y correo

            //Condicional para identificar los impuestos que se enviara el correo
            if ($Tipo == 'Impuestos_Federales' || $Tipo == 'Impuestos_Remuneraciones' || $Tipo == 'Impuestos_Hospedaje' || $Tipo == 'IMSS') {
                //Informacion de conexion FTP
                $server = 'correosecont.x10.mx';
                $ftp_user_name = 'lnrhdwjb';
                $ftp_user_pass = 'Tecnologi@1';

                /*Vamos a pasar por todas las carpetas que son requeridos
                    - Impuestos federales
                    - Impuestos sobre remuneraciones
                    - Impuesto hospedaje
                    - IMSS*/

                //Subimos los archivos a la servidor FTP y eliminamos los archivos en la carpeta local
                $files = glob('storage/FTP/' . $Tipo . "/*"); //Obtenemos todos los nombres de los ficheros
                foreach ($files as $file) {
                    //FTP
                    //Conexion FTP
                    $ftpconect = \ftp_connect($server) or die("No se pudo conectar con el servidor: $server <br>");

                    //Condicional para saber si se realizo una conexion exitosa
                    if (@ftp_login($ftpconect, $ftp_user_name, $ftp_user_pass)) {
                        //Mensaje de confirmacion
                        echo "La conexion con $ftp_user_name@$server se realizo con exito <br>";

                        //Obtenemos los datos de direccion de archivos
                        $localFilePath  = $file; //Archivo local
                        $remoteFilePath = $file; //Archivo remoto

                        //Condicional para conocer el tamaño del archivo
                        if (filesize($file) > 0) {
                            //Subimos los archivos al servidor FTP
                            if (ftp_put($ftpconect, $remoteFilePath, $localFilePath, FTP_BINARY)) {
                                echo "El archivo $localFilePath se subio exitosamente <br>";
                            } else {
                                echo "Hubo un error al subir el archivo $localFilePath <br>";
                            }
                        } else {
                            //Si el archivo tiene un error vamos a volver a crearlo
                            //Obtenemos el RFC que esta en el nombre del archivo
                            $info = pathinfo($file);
                            $rfcerror =  basename($file, '.' . $info['extension']);

                            //Ejecutamos el metodo para crear el PDF 
                            $this->MakePDFError($rfcerror);

                            //Realizamos otra condicional para verififcar si no tiene errores
                            if (filesize($file) > 0) {
                                //Subimos los archivos al servidor FTP
                                if (ftp_put($ftpconect, $remoteFilePath, $localFilePath, FTP_BINARY)) {
                                    echo "El archivo $localFilePath se subio exitosamente <br>";
                                } else {
                                    echo "Hubo un error al subir el archivo $localFilePath <br>";
                                }
                            } else {
                                echo "El archivo $localFilePath tiene un error al crearse, favor de revisar <br>";
                            }
                        }
                    } else {
                        echo "No se pudo conectar con el servidor: $server <br>";
                    }

                    //Cerramos la conexion 
                    ftp_close($ftpconect);

                    if (is_file($file)) {
                        unlink($file); //Elimino el fichero
                    }
                }
            }
        }
    }
}// fin clase
