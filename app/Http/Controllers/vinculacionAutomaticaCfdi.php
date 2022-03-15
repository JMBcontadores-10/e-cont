<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use App\Models\MetadataR;
use App\Models\XmlR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpCfdi\CfdiSatScraper\Metadata;

class vinculacionAutomaticaCfdi extends Controller
{

    //


    public function vincular(){


// $colM =MetadataR::where(['cheques_id' => $this->facturaVinculada->_id])->get();
$colM =MetadataR::whereNotNull('cheques_id')->get();

        foreach ($colM as $i){


            // $emisorRfc = $i->emisorRfc;
            // $arrRfc[] = $emisorRfc;
            // $emisorNombre = $i->emisorNombre;
            $folioF = $i->folioFiscal;

            echo $i->cheques_id."<br>";

           // coincidencia de metadata con el contenido xml para obtener los campos
           $colX = XmlR::where(['UUID' => $folioF])->where(['MetodoPago'=>'PPD'])->get();




               foreach ($colX as $v) {

                //    $concepto = $v['Conceptos.Concepto'];
                   echo  $v['MetodoPago']."<br>";
                   echo $v['UUID']."<br>";
                    //  $subtotal =$v['SubTotal'];
        echo "===============================================";







            }




               }




        // //=========================================//






        ////=========================================//

    }/// fin metodo vincular




}
