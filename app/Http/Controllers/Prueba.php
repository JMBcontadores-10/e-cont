<?php

namespace App\Http\Controllers;

use DirectoryIterator;
use App\Models\Cheques;
use App\Models\MetadataR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Prueba extends Controller
{
    public function index(Request $r)
    {
    }

    public function renombrarXml()
    {
        $num = 0;
        $rfcs = [
            // 'AHF060131G59',
            // 'AFU1809135Y4',
            // 'AIJ161001UD1',
            // 'AAE160217C36',
            // 'CDI1801116Y9',
            // 'COB191129AZ2',
            // 'DOT1911294F3',
            // 'DRO191104EZ0',
            // 'DRO191129DK5',
            // 'ERO1911044L4',
            // 'PERE9308105X4',
            'FGA980316918',
            // 'GPA161202UG8',
            // 'GEM190507UW8',
            // 'GPR020411182',
            // 'HRU121221SC2',
            // 'IAB0210236I7',
            // 'JQU191009699',
            // 'JCO171102SI9',
            // 'MEN171108IG6',
            // 'MAR191104R53',
            // 'MCA130429FM8',
            // 'MCA130827V4A',
            // 'MOP18022474A',
            // 'MOBJ8502058A4',
            // 'PEM180224742',
            // 'PEMJ7110258J3',
            // 'PML170329AZ9',
            // 'PERA0009086X3',
            // 'PER180309RB3',
            // 'RUCE750317I21',
            // 'SBE190522I97',
            // 'SGA1905229H3',
            // 'SGA1410217U4',
            // 'SGT190523QX8',
            // 'SGX190523KA4',
            // 'SGX160127MC4',
            // 'STR9303188X3',
            // 'SVI831123632',
            // 'SCT150918RC9',
            // 'SAJ161001KC6',
            // 'SPE171102P94',
            // 'SCO1905221P2',
            // 'GMH1602172L8',
            // 'MGE1602172LA',
            // 'SAE191009dd8',
            // 'SMA180913NK6',
            // 'SST030407D77',
            // 'TEL1911043PA',
            // 'TOVF901004DN5',
            // 'VER191104SP3',
            // 'VPT050906GI8',
            // 'VCO990603D84',
            // 'IAR010220GK5',
        ];
        foreach ($rfcs as $e) {
            $meses = [
                '1.Enero',
                '2.Febrero',
                '3.Marzo',
                '4.Abril',
                '5.Mayo',
                '6.Junio',
                '7.Julio',
                '8.Agosto',
                // '9.Septiembre',
                // '10.Octubre',
                // '11.Noviembre',
                // '12.Diciembre',
            ];
            foreach ($meses as $m) {
                $rutas = [
                    'Emitidos',
                    'Recibidos'
                ];
                foreach ($rutas as $r) {
                    $num++;
                    $n = 0;
                    $ruta = "C:/laragon/www/contarappv1/public/storage/contarappv1_descargas/$e/2021/Descargas/$m/$r/XML/";
                    $dir = new DirectoryIterator($ruta);
                    echo "$num - $ruta <br><br>";
                    foreach ($dir as $fileinfo) {
                        // $fileName = $fileinfo->getFilename();
                        $fileExt = $fileinfo->getExtension();
                        $fileBaseName = $fileinfo->getBasename(".$fileExt");
                        $filePathname = $fileinfo->getPathname();
                        if (!$fileinfo->isDot()) {
                            ++$n;
                            // $newFile = strtoupper(str_replace('@1000000000XX0', '', $fileBaseName)) . ".$fileExt";
                            // $newFile = strtoupper(str_replace('@2000000000XX0', '', $fileBaseName)) . ".$fileExt";
                            $newFile = strtoupper(str_replace('@3000000000XX0', '', $fileBaseName)) . ".$fileExt";
                            $rutaXml = dirname($filePathname) . '/' . $newFile;
                            // echo "$n - $fileBaseName <br>";
                            // echo "$n - $newFile <br>";
                            // echo "$n - $filePathname <br>";
                            // echo "$n - $rutaXml <br>";
                            rename($filePathname, $rutaXml);
                        }
                    }
                }
            }
        }
    }

    public function createDir()
    {
        $ruta = 'C:/laragon/www/contarappv1/public/storage/contarappv1_descargas/';
        $dir = new DirectoryIterator($ruta);
        foreach ($dir as $fileinfo) {
            $fileName = $fileinfo->getFilename();
            $rutaDir = $ruta . "$fileName/2021/Auditoria";
            if (!$fileinfo->isDot() and $fileinfo->isDir()) {
                mkdir($rutaDir);
            }
        }
    }
}
