<?php

namespace App\Http\Controllers;

use App\Models\XmlE;
use App\Models\XmlR;
use App\Models\Prueba;
use App\Models\MetadataR;
use DirectoryIterator;
use PhpCfdi\CfdiToJson\JsonConverter;
use PhpCfdi\CfdiCleaner\Cleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RecarpetarCheques extends Controller


{






    public function archivar(){

    $dir_path = public_path() . '/storage/contarappv1_descargas/PERE9308105X4/2021/Cheques_Transferencias/';
   $dir = new DirectoryIterator($dir_path);
  foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {

        echo $dir."<br>";


    }
    else {

    }
}

    }//end archivar()



}
