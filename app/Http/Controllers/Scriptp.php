<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use App\Models\XmlE;
use App\Models\XmlR;
use App\Models\Prueba;
use App\Models\MetadataR;
use DirectoryIterator;
use Exception;
use PhpCfdi\CfdiToJson\JsonConverter;
use PhpCfdi\CfdiCleaner\Cleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Scriptp extends Controller
{
    public function cfdi_to_json(string $contents) : string
    {
        $document = new \DOMDocument();
        @$document-> loadXML($contents);

        $factory = new \PhpCfdi\CfdiToJson\Factory();
        $converter = $factory->createConverter();
        $node = $converter->convertXmlDocument($document);

        return \json_encode($node->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }

    public function xmlLeer()
    {


//         set_time_limit(900);
//       $cheques=Cheques::get();
//      echo count($cheques)."registros para actualizar<br><br>";
//     $n=1;
//     foreach($cheques as $c){
//           /// obtiene el total de facturas vinculadas y suma el total
//           $colM =MetadataR::where(['cheques_id' => $c->_id])->get()->sum('total');
//           $numT=round($colM, 2);
//                 /// inserta el total de la suma de los cfdis  en importexml para corregir
//                 //  los importes con error
//          Cheques::where('_id', $c->_id)->update(['importexml' => $numT]);

//          echo $n."-".$c->_id.":"."importe xml actualizado<br>";


//   $n++;
//     }



        try{
            $anio = date('Y');

            set_time_limit(36000);
            $num = 0;
            $rfcs = [
                // '1',
               // 'AHF060131G59',
            //    'AHF060131G59',
            //    'AFU1809135Y4',
            //    'AIJ161001UD1',
            //    'AAE160217C36',
            //    'CDI1801116Y9',
            //    'COB191129AZ2',
            //    'DOT1911294F3',
            //    'DRO191104EZ0',
            //    'DRO191129DK5',
            //    'ERO1911044L4',
            //    'PERE9308105X4',
            //    'FGA980316918',
            //    'GPA161202UG8',
            //    'GEM190507UW8',
            //    'GPR020411182',
            //    'HRU121221SC2',
            //    'IAB0210236I7',
            //    'JQU191009699',
            //    'JCO171102SI9',
            //    'MEN171108IG6',
            //    'MAR191104R53',
            //    'MCA130429FM8',
            //    'MCA130827V4A',
            //    'MOP18022474A',
            //    'MOBJ8502058A4',
            //    'PEM180224742',
            //    'PEMJ7110258J3',
            //    'PML170329AZ9',
            //    'PERA0009086X3',
            //    'PER180309RB3',
            //    'RUCE750317I21',
            //    'SBE190522I97',
            //    'SGA1905229H3',
            //    'SGA1410217U4',
               'SGT190523QX8',
    //            'SGX190523KA4',
    //            'SGX160127MC4',
    //            'STR9303188X3',
    //            'SVI831123632',
    //            'SCT150918RC9',
    //            'SAJ161001KC6',
    //            'SPE171102P94',
    //            'SCO1905221P2',
    //            'GMH1602172L8',
    //            'MGE1602172LA',
    //            'SAE191009dd8',
    //            'SMA180913NK6',
    //            'SST030407D77',
    //            'TEL1911043PA',
    //            'TOVF901004DN5',
    //            'VER191104SP3',
    //            'VPT050906GI8',
    //            'VCO990603D84',
    //            'IAR010220GK5',
    // 'GRU210504TH9',
    // 'GMG21010706W2',
    // 'JCO2105043Y1',
            ];
            foreach ($rfcs as $e) {
                $meses = [
                    //'1.Enero',
                    '2.Febrero',
                   // '3.Marzo',
                    // '4.Abril',
                    // '5.Mayo',
                    // '6.Junio',
                    // '7.Julio',
                    //'8.Agosto',
                    // '9.Septiembre',
                    // '10.Octubre',
                     //'11.Noviembre',
                    //'12.Diciembre',
                ];
                foreach ($meses as $m) {
                    $rutas =
                    [
                        //'Emitidos',
                        'Recibidos'
                    ];
                    foreach ($rutas as $r) {
                        $num++;
                        $n = 0;
                        $ruta = "storage/contarappv1_descargas/$e/$anio/Descargas/$m/$r/XML";
                        $dir = new DirectoryIterator($ruta);
                        echo "$num - $ruta <br><br>";
                        foreach ($dir as $fileinfo) {
                            $fileName = $fileinfo->getFilename();
                            $fileExt = $fileinfo->getExtension();
                            $fileBaseName = $fileinfo->getBasename(".$fileExt");
                            $filePathname = $fileinfo->getPathname();
                            echo $filePathname;
                            echo "<br>";
                            if (!$fileinfo->isDot()) {
                                ++$n;

                                $contents = file_get_contents($filePathname);
                                $cleaner = Cleaner::staticClean($contents);

                                $script = new Scriptp;
                                $cfdi = $script->cfdi_to_json($cleaner);
                                // $json = JsonConverter::convertToJson($cfdi);
                                $array = json_decode($cfdi, true);

                                if ($r == 'Recibidos') {

                                    XmlR::where(['UUID' => $fileBaseName])
                                        ->update(
                                            $array,
                                            ['upsert' => true]
                                        );
                                } else {
                                    XmlE::where(['UUID' => $fileBaseName])
                                        ->update(
                                            $array,
                                            ['upsert' => true]
                                        );
                                }
                            }
                        }
                    }
                }
            }

            //Muestra un archivo con un mansaje de descarga satisfactoria
            $f =fopen("/home/econt/XmlRecibidos"."txt","w");
            fwrite($f,"Se descargo exitosamente");
            fclose($f);

        }catch(Exception $e){
            //Muestra un archivo con un mansaje de descarga satisfactoria
            $f =fopen("/home/econt/XmlRecibidos"."txt","w");
            fwrite($f,"Hubo un error".$e);
            fclose($f);
        }
    }

}
