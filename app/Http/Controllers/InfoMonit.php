<?php

namespace App\Http\Controllers;

use App\Models\MetadataE;
use App\Models\User;
use App\Models\XmlE;
use Exception;
use Illuminate\Support\Facades\App;

//Funcion para aumentar la ejecucion de los procesos, lo utilizaremos para las descargas ()
set_time_limit(3600); //Tiempo limite dado 1 hora
ini_set('memory_limit', '1024M'); //Incrementamos la memoria 

class InfoMonit extends Controller
{
    //Metodo para el envio de los correos (Este se ejecuta en el servidor de correos)
    public function SendEmail()
    {
        //Arreglo que contendra los datos de su correo y archivo
        $infomail = [
            //CORREOS DE EMPRESAS

            //1. SGA1410217U4
            ['rfc' => 'SGA1410217U4', 'email' => 'servicio.azcapotzalco@permergas.mx'],

            //2. AHF060131G59
            ['rfc' => 'AHF060131G59', 'email' => 'servicio.hf@permergas.mx'],

            //3. FGA980316918
            ['rfc' => 'FGA980316918', 'email' => 'servicio.figuergas@permergas.mx'],

            //4. PERE9308105X4
            ['rfc' => 'PERE9308105X4', 'email' => 'servicio.chalco@permergas.mx'],

            //5. SCT150918RC9
            ['rfc' => 'SCT150918RC9', 'email' => 'servicios.carranza@permergas.mx'],

            //6. SGX190523KA4
            // ['rfc' => 'SGX190523KA4', 'email' => ''], PENDIENTE DE OPERACION

            //7. GPA161202UG8
            ['rfc' => 'GPA161202UG8', 'email' => 'servicio.paris@permergas.mx'],

            //8. PEMJ7110258J3
            ['rfc' => 'PEMJ7110258J3', 'email' => 'servicio.ruave@permergas.mx'],

            //9. VCO990603D84
            ['rfc' => 'VCO990603D84', 'email' => 'serviciovillada0968@hotmail.com'],

            //10. STR9303188X3
            ['rfc' => 'STR9303188X3', 'email' => 'servicio.trece@permergas.mx'],

            //11. SGX160127MC4
            ['rfc' => 'SGX160127MC4', 'email' => 'servicio.xola@permergas.mx'],

            //12. SGT190523QX8
            ['rfc' => 'SGT190523QX8', 'email' => 'servicio.tlahuac@permergas.mx'],

            //13. SST030407D77
            ['rfc' => 'SST030407D77J', 'email' => 'servicio.jet@permergas.mx'],

            //14. SST030407D77
            ['rfc' => 'SST030407D77M', 'email' => 'servicio.matlazincas@permergas.mx'],

            //15. RUCE750317I21
            ['rfc' => 'RUCE750317I21', 'email' => 'servicio.sanjuan@permergas.mx'],



            //CORREOS DE CONFIMACION (VERIFICAR QUE SI SALIERON LOS CORREOS)

            //Confirmacion (Tecnologia)
            ['rfc' => 'SST030407D77M', 'email' => 'tecnologia@jmbcontadores.mx'],

            //Confirmacion (Tecnologia)
            ['rfc' => 'SST030407D77J', 'email' => 'tecnologia@jmbcontadores.mx'],

            //Confirmacion (Tecnologia)
            ['rfc' => 'PERE9308105X4', 'email' => 'tecnologia@jmbcontadores.mx'],

            //Confirmacion (Contabilidad)
            ['rfc' => 'SST030407D77J', 'email' => 'contabilidad@jmbcontadores.mx'],

            //Confirmacion (Tecnologia)
            ['rfc' => 'SST030407D77M', 'email' => 'contabilidad@jmbcontadores.mx'],

            //Confirmacion (Contabilidad)
            ['rfc' => 'SGA1410217U4', 'email' => 'contabilidad@jmbcontadores.mx'],
        ];

        //Bucle para enviar de forma automatica los correos
        foreach ($infomail as $datamail) {
            try {
                $filename = $datamail['rfc'] . '.pdf';
                $path = '../informonit';
                $file = $path . "/" . $filename;

                //Condicional si el archivo existe
                if (file_exists($file)) {
                    //Obtenemos la fecha del dia anterior
                    $diaanterior = date('Y-m-d', strtotime('-1 day'));

                    $mailto = $datamail['email'];
                    $subject = 'Reporte de facturación por clientes ' . $diaanterior;

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
                    $body .= "Se envía reporte de la facturación emitida del día de ayer, para su revisión." . $eol . $eol;
                    $body .= "Atentamente:" . $eol;
                    $body .= "JMB CONTADORES" . $eol;
                    $body .= "TEL. (55) 5536-0293, (55) 8662-3397" . $eol . $eol . $eol;
                    $body .= "*Este correo se genera automáticamente, favor de no responder a este correo*" . $eol . $eol;
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

        //Eliminamos los archivos en la carpeta local
        $files = glob('../informonit/*'); //Obtenemos todos los nombres de los ficheros
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); //Elimino el fichero
        }
    }


//**********************************************************************************************************************************************/


    //Metodo para recrear los PDF por si se crean con errores
    public function MakePDFError($rfcerror)
    {
        //Consultamos si el rfc tiene sucursales o no
        $sucurusu = User::where('RFC', $rfcerror)->first(['Sucursales']);

        //Obtenemos la fecha del dia anterior
        $diaanterior = date('Y-m-d', strtotime('-1 day'));

        //Condicional para saber si tiene sucursales
        if ($sucurusu['Sucursales']) {
            //Obtenemos los datos del cliete
            $consulclient = User::where('RFC', $rfcerror)->get()->first();

            //Condicional par saber si este tiene sucursal
            if ($consulclient['Sucursales']) {
                //Ciclo para obtener la clave
                foreach ($consulclient['Sucursales'] as $clavesucur) {
                    //Variables acumulatibas para los totales
                    $totalfactusucur = 0;
                    $totalmontosucur = 0;

                    //Obtenenmos los datos nencesario de clientes
                    $RFCSucur = $clavesucur['RFC']; //RFC
                    $Clave = $clavesucur['Clave']; //Clave

                    //Consulta para obtener la informacion XML
                    $consulxmlclient = XmlE::where('Emisor.Rfc', $rfcerror)
                        ->where('LugarExpedicion', $Clave)
                        ->whereBetween('Fecha',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                        ->where('TipoDeComprobante', '!=', 'N')
                        ->get();


                    //Condicional para saber si existen regisitros y aprovechar los 50 correos 
                    if (count($consulxmlclient) > 0) {
                        //Construimos la tabla
                        //Variables de contenedor
                        $datainfomonit = "";
                        $rowinfomonit = array();

                        //Obtenemos una lista de los receptores
                        $listarecept = []; //Arreglo que contedra los RFC recibidos

                        //Ciclo para extraer los datos
                        foreach ($consulxmlclient as $infoxmlclient) {
                            array_push($listarecept, ['RFC' => $infoxmlclient['Receptor.Rfc'], 'Nombre' => $infoxmlclient['Receptor.Nombre']]);
                        }

                        //Eliminamos los datos repetidos
                        $listareceptrfc = array_unique(array_column($listarecept, 'RFC')); //Eliminamos las columnas con RFC repetido
                        $listarecept = array_intersect_key($listarecept, $listareceptrfc); //Comparamos el arreglo original con el arreglo filtrado

                        //Ciclo para pasar por todos los RFC registrados en el arreglo
                        foreach ($listarecept as $datarecept) {
                            //Realizaremos otra consulta para obtener los totales
                            $consulxmlinfo = XmlE::where('Emisor.Rfc', $rfcerror)
                                ->where('Receptor.Rfc', $datarecept['RFC'])
                                ->where('LugarExpedicion', $Clave)
                                ->whereBetween('Fecha',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                                ->where('TipoDeComprobante', '!=', 'N')
                                ->get();

                            //Variables para obtener el total
                            $totalfactu = 0;
                            $totalmonto = 0;

                            //Ciclo para descomponer la consulta
                            foreach ($consulxmlinfo as $dataxmlinfo) {
                                $totalfactu++; //Cantidad de facturas
                                $totalmonto += $dataxmlinfo['Total']; //Monto

                                //Condicional para obtener la razon solcial (A veces obtiene un registro con una razon social vacia)
                                if (strlen($dataxmlinfo['Receptor.Nombre']) > 1) {
                                    $nombrerecpt = $dataxmlinfo['Receptor.Nombre'];
                                }
                            }

                            //Condicional para saber si tiene razon social
                            if (strlen($datarecept['Nombre']) < 1) {
                                $nombrerecep = $nombrerecpt;
                            } else {
                                $nombrerecep = $datarecept['Nombre'];
                            }

                            //Obtenemos los totales
                            $totalfactusucur += $totalfactu;
                            $totalmontosucur += $totalmonto;

                            //Ingresamos los datos requeridos

                            //RFC receptor 
                            $datainfomonit .= '<td>' . $datarecept['RFC'] . '</td>';

                            //Nombre receptor 
                            $datainfomonit .= '<td>' . $nombrerecep . '</td>';

                            //#Fact emitidas
                            $datainfomonit .= '<td>' . $totalfactu . '</td>';

                            //Monto
                            $datainfomonit .= '<td> $ ' . number_format($totalmonto, 2) . '</td>';

                            //Alamcenamos los datos en el arreglo
                            $rowinfomonit[$totalfactu . $datarecept['RFC']] =  '<tr>' . $datainfomonit . '</tr>';

                            //Vaciamos la variable para almacenar las otras
                            $datainfomonit = "";
                        }

                        //Ordenamos la tabla
                        krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

                        //Almacenamos los totales
                        $datainfomonit .= '<td></td>';
                        $datainfomonit .= '<td> <b> Total: </b> </td>';

                        //#Fact emitidas
                        $datainfomonit .= '<td>' . $totalfactusucur . '</td>';

                        //Monto
                        $datainfomonit .= '<td> $ ' . number_format($totalmontosucur, 2) . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowinfomonit[] =  '<tr>' . $datainfomonit . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $datainfomonit = "";


                        //Pasamos los datos de la tabla
                        $data = [
                            'fechamonit' => $diaanterior,
                            'rfcmonit' => $rfcerror,
                            'nommonit' => $clavesucur['Nombre'],
                            'infomonit' => $rowinfomonit
                        ];

                        //Ruta donde se localizara el archivo
                        $rutainformes = 'informonit/' . $RFCSucur . '.pdf';

                        //Creamos el objeto que creara el PDF
                        $pdf = App::make('dompdf.wrapper');

                        //Cargamos la vista que contendra la tabla que se exportara
                        $pdf->loadView('infomonit', $data);

                        //Configuaracion del documento
                        $pdf->setPaper('a3', 'landscape');

                        //Almacenamos los PDF en una carpeta
                        $pdf->save($rutainformes);
                    }
                }
            }
        } else {
            //Realizaremos una consulta a los metadatos y los XML de los RFC de cada empresa
            //Metadato (Ordenado)
            $consulmetamonitclient = MetadataE::select('receptorRfc', 'receptorNombre',)
                ->where('emisorRfc', $rfcerror)
                ->whereBetween('fechaEmision',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                ->where('efecto', '!=', 'Nómina')
                ->groupBy('receptorRfc')
                ->orderBy('receptorRfc', 'asc')
                ->get();

            //Metadato (Completo)
            $consulmetamonit = MetadataE::where('emisorRfc', $rfcerror)
                ->whereBetween('fechaEmision',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                ->where('efecto', '!=', 'Nómina')
                ->get();

            //Condicional para saber si existen regisitros y aprovechar los 50 correos 
            if (count($consulmetamonit) > 0) {
                //Construimos la tabla
                //Variables de contenedor
                $datainfomonit = "";
                $rowinfomonit = array();

                //Variables para obtener el total
                $totalfactu = 0;
                $totalmonto = 0;

                //Ciclo para obtener los datos de la consulta (Metadatos)
                foreach ($consulmetamonitclient as $infometamonit) {
                    //Variable de contenedor
                    $totalfactuclient = 0; //Cantidad de facturas
                    $montofactuclient = 0; //Cantodad del monto

                    //Ciclo para obtener la cantidad de facturas por cliente
                    foreach ($consulmetamonit as $infometa) {
                        if ($infometamonit['receptorRfc'] == $infometa['receptorRfc']) {
                            $totalfactuclient++;
                            $montofactuclient += $infometa['total'];
                        }
                    }

                    //Obtenemos el total
                    $totalfactu += $totalfactuclient; //Cantidad
                    $totalmonto += $montofactuclient; //Monto


                    //Ingresamos los datos requeridos

                    //RFC receptor 
                    $datainfomonit .= '<td>' . $infometamonit['receptorRfc'] . '</td>';

                    //Nombre receptor 
                    $datainfomonit .= '<td>' . $infometamonit['receptorNombre'] . '</td>';

                    //#Fact emitidas
                    $datainfomonit .= '<td>' . $totalfactuclient . '</td>';

                    //Monto
                    $datainfomonit .= '<td> $ ' . number_format($montofactuclient, 2) . '</td>';

                    //Alamcenamos los datos en el arreglo
                    $rowinfomonit[$totalfactuclient . $infometamonit['receptorRfc']] =  '<tr>' . $datainfomonit . '</tr>';

                    //Vaciamos la variable para almacenar las otras
                    $datainfomonit = "";
                }

                //Ordenamos la tabla
                krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

                //Almacenamos los totales
                $datainfomonit .= '<td></td>';
                $datainfomonit .= '<td> <b> Total: </b> </td>';

                //#Fact emitidas
                $datainfomonit .= '<td>' . $totalfactu . '</td>';

                //Monto
                $datainfomonit .= '<td> $ ' . number_format($totalmonto, 2) . '</td>';

                //Alamcenamos los datos en el arreglo
                $rowinfomonit[] =  '<tr>' . $datainfomonit . '</tr>';

                //Vaciamos la variable para almacenar las otras
                $datainfomonit = "";

                //Obtenemos la razon social del RFC
                $nomclient = User::where('RFC', $rfcerror)->get()->first();

                //Pasamos los datos de la tabla
                $data = [
                    'fechamonit' => $diaanterior,
                    'rfcmonit' => $rfcerror,
                    'nommonit' => $nomclient['nombre'],
                    'infomonit' => $rowinfomonit
                ];

                //Ruta donde se localizara el archivo
                $rutainformes = 'informonit/' . $rfcerror . '.pdf';

                //Creamos el objeto que creara el PDF
                $pdf = App::make('dompdf.wrapper');

                //Cargamos la vista que contendra la tabla que se exportara
                $pdf->loadView('infomonit', $data);

                //Configuaracion del documento
                $pdf->setPaper('a3', 'landscape');

                //Almacenamos los PDF en una carpeta
                $pdf->save($rutainformes);
            }
        }
    }



//*********************************************************************************************************************************************/



    //Metodo para enviar los PDF creados al servidro FTP
    public function SendFTP()
    {
        //Informacion de conexion FTP
        $server = 'files.000webhost.com';
        $ftp_user_name = 'correosecont';
        $ftp_user_pass = 'C04taD4n13l/*';

        //Subimos los archivos a la servidor FTP y eliminamos los archivos en la carpeta local
        $files = glob('informonit/*'); //Obtenemos todos los nombres de los ficheros
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



//*********************************************************************************************************************************************/



    //Metodo para contruir el PDF de monitoreo
    public function MakePDFMonit()
    {
        //Arreglo que contiene las empresas
        $rfcs = [
            'SGA1410217U4',
            'AHF060131G59',
            'FGA980316918',
            'PERE9308105X4',
            'SCT150918RC9',
            'SGX190523KA4',
            'GPA161202UG8',
            'PEMJ7110258J3',
            'VCO990603D84',
            'STR9303188X3',
            'SGX160127MC4',
            'SGT190523QX8',
            'RUCE750317I21',
        ];

        //Arreglo que contiene las empresa con sucursales
        $rfcssucur = [
            'SST030407D77',
        ];

        //Obtenemos la fecha del dia anterior
        $diaanterior = date('Y-m-d', strtotime('-1 day'));

        // //Ciclo para pasar por todos los RFC
        foreach ($rfcs as $rfc) {
            //Realizaremos una consulta a los metadatos y los XML de los RFC de cada empresa
            //Metadato (Ordenado)
            $consulmetamonitclient = MetadataE::select('receptorRfc', 'receptorNombre',)
                ->where('emisorRfc', $rfc)
                ->whereBetween('fechaEmision',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                ->where('efecto', '!=', 'Nómina')
                ->groupBy('receptorRfc')
                ->orderBy('receptorRfc', 'asc')
                ->get();

            //Metadato (Completo)
            $consulmetamonit = MetadataE::where('emisorRfc', $rfc)
                ->whereBetween('fechaEmision',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                ->where('efecto', '!=', 'Nómina')
                ->get();

            //Condicional para saber si existen regisitros y aprovechar los 50 correos 
            if (count($consulmetamonit) > 0) {
                //Construimos la tabla
                //Variables de contenedor
                $datainfomonit = "";
                $rowinfomonit = array();

                //Variables para obtener el total
                $totalfactu = 0;
                $totalmonto = 0;

                //Ciclo para obtener los datos de la consulta (Metadatos)
                foreach ($consulmetamonitclient as $infometamonit) {
                    //Variable de contenedor
                    $totalfactuclient = 0; //Cantidad de facturas
                    $montofactuclient = 0; //Cantodad del monto

                    //Ciclo para obtener la cantidad de facturas por cliente
                    foreach ($consulmetamonit as $infometa) {
                        if ($infometamonit['receptorRfc'] == $infometa['receptorRfc']) {
                            $totalfactuclient++;
                            $montofactuclient += $infometa['total'];
                        }
                    }

                    //Obtenemos el total
                    $totalfactu += $totalfactuclient; //Cantidad
                    $totalmonto += $montofactuclient; //Monto


                    //Ingresamos los datos requeridos

                    //RFC receptor 
                    $datainfomonit .= '<td>' . $infometamonit['receptorRfc'] . '</td>';

                    //Nombre receptor 
                    $datainfomonit .= '<td>' . $infometamonit['receptorNombre'] . '</td>';

                    //#Fact emitidas
                    $datainfomonit .= '<td>' . $totalfactuclient . '</td>';

                    //Monto
                    $datainfomonit .= '<td> $ ' . number_format($montofactuclient, 2) . '</td>';

                    //Alamcenamos los datos en el arreglo
                    $rowinfomonit[$totalfactuclient . $infometamonit['receptorRfc']] =  '<tr>' . $datainfomonit . '</tr>';

                    //Vaciamos la variable para almacenar las otras
                    $datainfomonit = "";
                }

                //Ordenamos la tabla
                krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

                //Almacenamos los totales
                $datainfomonit .= '<td></td>';
                $datainfomonit .= '<td> <b> Total: </b> </td>';

                //#Fact emitidas
                $datainfomonit .= '<td>' . $totalfactu . '</td>';

                //Monto
                $datainfomonit .= '<td> $ ' . number_format($totalmonto, 2) . '</td>';

                //Alamcenamos los datos en el arreglo
                $rowinfomonit[] =  '<tr>' . $datainfomonit . '</tr>';

                //Vaciamos la variable para almacenar las otras
                $datainfomonit = "";

                //Obtenemos la razon social del RFC
                $nomclient = User::where('RFC', $rfc)->get()->first();

                //Pasamos los datos de la tabla
                $data = [
                    'fechamonit' => $diaanterior,
                    'rfcmonit' => $rfc,
                    'nommonit' => $nomclient['nombre'],
                    'infomonit' => $rowinfomonit
                ];

                //Ruta donde se localizara el archivo
                $rutainformes = 'informonit/' . $rfc . '.pdf';

                //Creamos el objeto que creara el PDF
                $pdf = App::make('dompdf.wrapper');

                //Cargamos la vista que contendra la tabla que se exportara
                $pdf->loadView('infomonit', $data);

                //Configuaracion del documento
                $pdf->setPaper('a3', 'landscape');

                //Almacenamos los PDF en una carpeta
                $pdf->save($rutainformes);
            }
        }


        //Ciclo para pasar por todos los RFC (Con sucursal)
        foreach ($rfcssucur as $rfcsucur) {
            //Obtenemos los datos del cliete
            $consulclient = User::where('RFC', $rfcsucur)->get()->first();

            //Condicional par saber si este tiene sucursal
            if ($consulclient['Sucursales']) {
                //Ciclo para obtener la clave
                foreach ($consulclient['Sucursales'] as $clavesucur) {
                    //Variables acumulatibas para los totales
                    $totalfactusucur = 0;
                    $totalmontosucur = 0;

                    //Obtenenmos los datos nencesario de clientes
                    $RFCSucur = $clavesucur['RFC']; //RFC
                    $Clave = $clavesucur['Clave']; //Clave

                    //Consulta para obtener la informacion XML
                    $consulxmlclient = XmlE::where('Emisor.Rfc', $rfcsucur)
                        ->where('LugarExpedicion', $Clave)
                        ->whereBetween('Fecha',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                        ->where('TipoDeComprobante', '!=', 'N')
                        ->get();


                    //Condicional para saber si existen regisitros y aprovechar los 50 correos 
                    if (count($consulxmlclient) > 0) {
                        //Construimos la tabla
                        //Variables de contenedor
                        $datainfomonit = "";
                        $rowinfomonit = array();

                        //Obtenemos una lista de los receptores
                        $listarecept = []; //Arreglo que contedra los RFC recibidos

                        //Ciclo para extraer los datos
                        foreach ($consulxmlclient as $infoxmlclient) {
                            array_push($listarecept, ['RFC' => $infoxmlclient['Receptor.Rfc'], 'Nombre' => $infoxmlclient['Receptor.Nombre']]);
                        }

                        //Eliminamos los datos repetidos
                        $listareceptrfc = array_unique(array_column($listarecept, 'RFC')); //Eliminamos las columnas con RFC repetido
                        $listarecept = array_intersect_key($listarecept, $listareceptrfc); //Comparamos el arreglo original con el arreglo filtrado

                        //Ciclo para pasar por todos los RFC registrados en el arreglo
                        foreach ($listarecept as $datarecept) {
                            //Realizaremos otra consulta para obtener los totales
                            $consulxmlinfo = XmlE::where('Emisor.Rfc', $rfcsucur)
                                ->where('Receptor.Rfc', $datarecept['RFC'])
                                ->where('LugarExpedicion', $Clave)
                                ->whereBetween('Fecha',  [$diaanterior . 'T00:00:00', $diaanterior . 'T23:59:59'])
                                ->where('TipoDeComprobante', '!=', 'N')
                                ->get();

                            //Variables para obtener el total
                            $totalfactu = 0;
                            $totalmonto = 0;

                            //Ciclo para descomponer la consulta
                            foreach ($consulxmlinfo as $dataxmlinfo) {
                                $totalfactu++; //Cantidad de facturas
                                $totalmonto += $dataxmlinfo['Total']; //Monto

                                //Condicional para obtener la razon solcial (A veces obtiene un registro con una razon social vacia)
                                if (strlen($dataxmlinfo['Receptor.Nombre']) > 1) {
                                    $nombrerecpt = $dataxmlinfo['Receptor.Nombre'];
                                }
                            }

                            //Condicional para saber si tiene razon social
                            if (strlen($datarecept['Nombre']) < 1) {
                                $nombrerecep = $nombrerecpt;
                            } else {
                                $nombrerecep = $datarecept['Nombre'];
                            }

                            //Obtenemos los totales
                            $totalfactusucur += $totalfactu;
                            $totalmontosucur += $totalmonto;

                            //Ingresamos los datos requeridos

                            //RFC receptor 
                            $datainfomonit .= '<td>' . $datarecept['RFC'] . '</td>';

                            //Nombre receptor 
                            $datainfomonit .= '<td>' . $nombrerecep . '</td>';

                            //#Fact emitidas
                            $datainfomonit .= '<td>' . $totalfactu . '</td>';

                            //Monto
                            $datainfomonit .= '<td> $ ' . number_format($totalmonto, 2) . '</td>';

                            //Alamcenamos los datos en el arreglo
                            $rowinfomonit[$totalfactu . $datarecept['RFC']] =  '<tr>' . $datainfomonit . '</tr>';

                            //Vaciamos la variable para almacenar las otras
                            $datainfomonit = "";
                        }

                        //Ordenamos la tabla
                        krsort($rowinfomonit, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

                        //Almacenamos los totales
                        $datainfomonit .= '<td></td>';
                        $datainfomonit .= '<td> <b> Total: </b> </td>';

                        //#Fact emitidas
                        $datainfomonit .= '<td>' . $totalfactusucur . '</td>';

                        //Monto
                        $datainfomonit .= '<td> $ ' . number_format($totalmontosucur, 2) . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowinfomonit[] =  '<tr>' . $datainfomonit . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $datainfomonit = "";


                        //Pasamos los datos de la tabla
                        $data = [
                            'fechamonit' => $diaanterior,
                            'rfcmonit' => $rfcsucur,
                            'nommonit' => $clavesucur['Nombre'],
                            'infomonit' => $rowinfomonit
                        ];

                        //Ruta donde se localizara el archivo
                        $rutainformes = 'informonit/' . $RFCSucur . '.pdf';

                        //Creamos el objeto que creara el PDF
                        $pdf = App::make('dompdf.wrapper');

                        //Cargamos la vista que contendra la tabla que se exportara
                        $pdf->loadView('infomonit', $data);

                        //Configuaracion del documento
                        $pdf->setPaper('a3', 'landscape');

                        //Almacenamos los PDF en una carpeta
                        $pdf->save($rutainformes);
                    }
                }
            }
        }

        //Ejecutamos el metodo para enviar los PDF por FTP
        $this->SendFTP();

        //Retornamos un mensaje
        return "Finalizado";
    }
}
