<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use App\Models\MetadataR;
use App\Models\ppdPendientesVincular;
use App\Models\XmlR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpCfdi\CfdiSatScraper\Metadata;

class vinculacionAutomaticaCfdi extends Controller
{

    //


    public function vincular(){


            set_time_limit(36000);
            ini_set('memory_limit', '-1');
            $num = 0;
            $rfcs = [/// array que contiene las empresas
                // '1',
               // 'AHF060131G59',
               'AHF060131G59',
               'AFU1809135Y4',
               'AIJ161001UD1',
               'AAE160217C36',
               'CDI1801116Y9',
               'COB191129AZ2',
               'DOT1911294F3',
               'DRO191104EZ0',
               'DRO191129DK5',
               'ERO1911044L4',
               'PERE9308105X4',
               'FGA980316918',
               'GPA161202UG8',
               'GEM190507UW8',
               'GPR020411182',
               'HRU121221SC2',
               'IAB0210236I7',
               'JQU191009699',
               'JCO171102SI9',
               'MEN171108IG6',
               'MAR191104R53',
               'MCA130429FM8',
               'MCA130827V4A',
               'MOP18022474A',
               'MOBJ8502058A4',
               'PEM180224742',
               'PEMJ7110258J3',
               'PML170329AZ9',
               'PERA0009086X3',
               'PER180309RB3',
               'RUCE750317I21',
               'SBE190522I97',
               'SGA1905229H3',
               'SGA1410217U4',
               'SGT190523QX8',
               'SGX190523KA4',
               'SGX160127MC4',
               'STR9303188X3',
               'SVI831123632',
               'SCT150918RC9',
               'SAJ161001KC6',
               'SPE171102P94',
               'SCO1905221P2',
               'GMH1602172L8',
               'MGE1602172LA',
               'SAE191009dd8',
               'SMA180913NK6',
               'SST030407D77',
               'TEL1911043PA',
               'TOVF901004DN5',
               'VER191104SP3',
               'VPT050906GI8',
               'VCO990603D84',
               'IAR010220GK5',
               'GRU210504TH9',
               'GMG21010706W2',
               'JCO2105043Y1',
            ];


          ////////DESVICULAR TODOS LOS PAGOS/////

        //     $desvicularTodos=MetadataR::whereNotNull('cheques_id')->where('efecto','Pago')->get();

        //    foreach($desvicularTodos as $d):
        //     $desvicular=MetadataR::where('folioFiscal',$d->folioFiscal)->where('efecto','Pago')->first();
        //     $desvicular->unset('cheques_id');
        //     echo $d->folioFiscal."&nbsp; Desvicnulado.. <br>";

        // endforeach;



#======================= se obtienen  xmlr PPD que estan vinculados a un cheque =================================================================#
            // $cheque=Cheques::whereIn('rfc',$rfcs)->where('faltaxml','!=',0)->select('_id')->get();
            // $ids=[];
            // $cheques_ids=[];
            // $foliosFm=[];
            // $foliosmetaSinVinculo=[];


            // foreach($cheque as $ch){  $ids[]=$ch->_id; } unset($ch); // rompe la referencia con el último elemento
            // $colM =MetadataR::whereIn('cheques_id' ,$ids)->get();

            // foreach($colM as $m){
            // $cheques_ids[]= $m->cheques_id."<br>";
            // $foliosFm[]=$m->folioFiscal;
            //  }unset($m); // rompe la referencia con el último elemento

            // $xmlPPD =XmlR::whereIn('UUID' ,$foliosFm)->where('MetodoPago','PPD')->get();

#========================================================================================#
                  /* OBTENCION DE PAGOS PARA VINCULAR */

     /// se obtienen todos los metadatos que no tengan vinculo y que sean pagos
            $metadataPago =MetadataR::whereNull('cheques_id')->where('efecto','Pago')->get();
     ////// se pasan los folios fiscales obtenidos aun arreglo
        foreach($metadataPago as $meta){ $foliosmetaSinVinculo[]=$meta->folioFiscal; }
                unset($meta); // rompe la referencia con el último elemento
     ///// se obtienen los correspondientes xml del metadato para sacar los uuid relacionados
     //// al pago


           $xmlPago =XmlR::whereIn('UUID' ,$foliosmetaSinVinculo)->get();
#=========================================================================================#
    /* VINCULACION DE LOS PAGOS ASU CORRESPONDIENTE PPD (Ya vinculado a un cheque)   */
           $color="white";
           foreach($xmlPago as $Pago):////se recorre el objeto con los CDFID pago

             echo $Pago->UUID."<br>" ;

             $te= ppdPendientesVincular::where('folioFiscalPago',$Pago->UUID)->unset('ppdRealcionados');
             $complemento=$Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];

        ///// si complemento no obtiene el dato del formato 1 original busca en el formato 2
           if (!isset($complemento)) {
                $complemento =$Pago['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado'];
                $color="red";
            }
      /////// si no se define  formato 1 ó 2 se establece el formato que no esta bien emitido
            if(!isset($complemento)){
                 $complemento=['Complemento.0.Pagos.Pago'];
                 $color="yellow";
            }
       /////// se imprime los datos cfdi relacionados
             echo "<div style='background-color:$color'>  tiene&nbsp;&nbsp;".count($complemento)."&nbsp;Id relacionados</div><br>";

             if($color =="white" && count($complemento)>1){

             foreach($complemento as $c): echo "UUIDrelacionado".strtoupper($c['IdDocumento'])."<br><br>";
$mayus=strtoupper($c['IdDocumento']);

            $ppdsinvinculo=MetadataR::where('folioFiscal',$mayus )->whereNull('cheques_id')->get();
            if(isset($ppdsinvinculo)){
                foreach($ppdsinvinculo as $vs):

                    $temporales= ppdPendientesVincular::updateOrCreate(
                        ['folioFiscalPago'=>$Pago->UUID],

                    )->push('ppdRealcionados',strtoupper($mayus));

               endforeach;



                }


                 $vinculopago=MetadataR::where('folioFiscal',$mayus)->whereNotNull('cheques_id')->get();


                 if(isset($vinculopago)){
                 foreach($vinculopago as $v): echo "PPD con vinculo&nbsp;&nbsp;".$v->folioFiscal."<br>chequeid:".$v->cheques_id."<br>";




                 ///vinculacion de Pago con cheques Id
                $vincularPago= MetadataR::where('folioFiscal',$Pago->UUID)->first();
                if($vincularPago->cheques_id==NULL){
                $vincularPago->unset('cheques_id');
                }
                $vincularPago->push('cheques_id', $v->cheques_id);

                /////temporales no vinculados



                endforeach;



                 }
            endforeach;

             }else{
                $uuid2=strtoupper($Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento']);
                echo "simple&nbsp; UUIDrelacionado".strtoupper($Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento'])."<br><br>";

                $vinculopago=MetadataR::where('folioFiscal',$uuid2 )->whereNotNull('cheques_id')->first();
                if($vinculopago){
                echo "idsimple:".$vinculopago->cheques_id."<br>";

                ///vinculacion de Pago con cheques Id
                $vincularPago= MetadataR::where('folioFiscal',$Pago->UUID)->first();
                if($vincularPago->cheques_id==NULL){
                $vincularPago->unset('cheques_id');
                }
                $vincularPago->push('cheques_id', $vinculopago->cheques_id);

                }

             }

             if($color =="red"){

            echo $id2= strtoupper($Pago['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado.IdDocumento'])."<br><br>";
            $vinculopago=MetadataR::where('folioFiscal',$id2)->whereNotNull('cheques_id')->first();
            if($vinculopago){
                echo "default:".$vinculopago->cheques_id."<br>";
                }


             }


             if($color=="yellow"){

                echo "Factura sin UUID Relacionado<br><br>";
             }





             echo "===========================================================<br>";
             $color="white";
             unset($complemento);
            endforeach;

///===================REVISAR LOS TEMPORALES (ppdpendientesvincular)======================///

   $UUIDtemporales=ppdPendientesVincular::get();
   echo "####################--- [TEMPORALES] ---##################################<br>";
   foreach($UUIDtemporales as $temp):

    if($temp->ppdRealcionados==NULL){
 ////////// se elimina el registro si ya no hay folios pendientes
$temp->delete();

   }


 $metas=MetadataR::whereIn('folioFiscal',$temp['ppdRealcionados'] )->whereNotNull('cheques_id')->first();


 if($metas){
echo "tiene ahora id: ".$metas->folioFiscal."<br>";
$vincularPago= MetadataR::where('folioFiscal',$temp->folioFiscalPago)->first();
if($vincularPago->cheques_id==''){
$vincularPago->unset('cheques_id');
}
$vincularPago->push('cheques_id', $metas->cheques_id);

 $temp->pull('ppdRealcionados', $metas->folioFiscal);



 }
   endforeach;














 echo  count($metadataPago)."<br>". count($xmlPago);






    }/// fin metodo vincular




}
