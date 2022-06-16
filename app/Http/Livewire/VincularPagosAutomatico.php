<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;

use App\Models\MetadataR;
use App\Models\ppdPendientesVincular;
use App\Models\XmlR;
use Illuminate\Support\Facades\DB;

class VincularPagosAutomatico extends Component
{

    public $numero;
    public $vinculos;
    public $vinculo1;

    protected $listeners = [

        'refreshPagoAutomatico' => '$refresh',

         ];


         public function mount(){

            $this->vinculos=[];
            $this->vinculo1=[];
            $this->numero=0;


            }

    public function render()
    {


        if(!empty(auth()->user()->tipo)){

            $e=array();
                  $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


                  for($i=0; $i <$largo; $i++) {

                  $rfc=auth()->user()->empresas[$i];
                   $e=DB::Table('clientes')
                   ->select('RFC','nombre')

                   ->where('RFC', $rfc)

                   ->get();

                   foreach($e as $em){


                   $emp[]= array( $em['RFC'],$em['nombre']);
                   }
                  }

                }elseif(!empty(auth()->user()->TipoSE)){

                    $e=array();
                          $largo=sizeof(auth()->user()->empresas);// obtener el largo del array empresas


                          for($i=0; $i <$largo; $i++) {

                          $rfc=auth()->user()->empresas[$i];
                           $e=DB::Table('clientes')
                           ->select('RFC','nombre')

                           ->where('RFC', $rfc)

                           ->get();

                           foreach($e as $em)


                           $emp[]= array( $em['RFC'],$em['nombre']);
                          }
                          }else{

            $emp='';
                          }
////====================================================================////////////////

if(count($this->vinculos)!==0){
foreach ($this->vinculos as $v):
    $vinculo=Cheques::where(['_id' => $v])->get()->first();

    $this->vinculo1[]= $vinculo['rfc']."&nbsp;->".$vinculo['numcheque']."&nbsp;->".$vinculo['_id'];
endforeach;
}


        return view('livewire.vincular-pagos-automatico',['numero'=>$this->numero,'empresas'=>$emp,'vinculos'=>$this->vinculos,'vinculo1'=>$this->vinculo1] )
        ->extends('layouts.livewire-layout')
        ->section('content');

    }


public function vincularAutomatico($rfc){
           $this->vinculos=[];
            $this->vinculo1=[];


    set_time_limit(36000);
    ini_set('memory_limit', '-1');

#========================================================================================#
          /* OBTENCION DE PAGOS PARA VINCULAR */

      if ($rfc =="contador") {




/// se obtienen todos los metadatos que no tengan vinculo y que sean pagos
$metadataPago =MetadataR::whereNull('cheques_id')->where('efecto','Pago')->where('estado','Vigente')->whereIn('receptorRfc',auth()->user()->empresas)->get();

      }else{

/// se obtienen todos los metadatos que no tengan vinculo y que sean pagos
    $metadataPago =MetadataR::whereNull('cheques_id')->where('efecto','Pago')->where('estado','Vigente')->where('receptorRfc',$rfc)->get();

      }
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

    //  echo $Pago->UUID."<br>" ;

     $te= ppdPendientesVincular::where('folioFiscalPago',$Pago->UUID)->unset('ppdRealcionados');
     $complemento=$Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];

///// si complemento no obtiene el dato del formato 1 original busca en el formato 2
   if (!isset($complemento)) {
        $complemento =$Pago['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado'];
        $color="red";
    }


      ///// si complemento no obtiene el dato del formato 1 Y 2   busca en el formato 3 [VERSION : 4.0]
  ////////////////[VERSION : 4.0]////////////////////////////
  if (!isset($complemento)) {
    $complemento =$Pago['Complemento.Pagos.Pago.0.DoctoRelacionado'];
      $color="blue";
       }

/////// si no se define  formato 1 ,2  ó 3 se establece el formato que no esta bien emitido
    if(!isset($complemento)){
         $complemento=['Complemento.0.Pagos.Pago'];
         $color="yellow";
    }
/////// se imprime los datos cfdi relacionados
    //  echo "<div style='background-color:$color'>  tiene&nbsp;&nbsp;".count($complemento)."&nbsp;Id relacionados</div><br>";

     if($color=="blue" ||  $color =="white" && count($complemento)>1){

     foreach($complemento as $c):
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
         foreach($vinculopago as $v):




         ///vinculacion de Pago con cheques Id
        $vincularPago= MetadataR::where('folioFiscal',$Pago->UUID)->first();
        if($vincularPago->cheques_id==NULL){
        $vincularPago->unset('cheques_id');
        }
        $vincularPago->push('cheques_id', $v->cheques_id);

        /// se agregan Id´s al arreglo para mostrarlos cheques en la vista
        $this->vinculos[]= $v->cheques_id;

        /////temporales no vinculados



        endforeach;



         }
    endforeach;

     }else{
        $uuid2=strtoupper($Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento']);
        // echo "simple&nbsp; UUIDrelacionado".strtoupper($Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento'])."<br><br>";

        $vinculopago=MetadataR::where('folioFiscal',$uuid2 )->whereNotNull('cheques_id')->first();
        if($vinculopago){
        // echo "idsimple:".$vinculopago->cheques_id."<br>";






        ///vinculacion de Pago con cheques Id
        $vincularPago= MetadataR::where('folioFiscal',$Pago->UUID)->first();
        if($vincularPago->cheques_id==NULL){
        $vincularPago->unset('cheques_id');
        }
        $vincularPago->push('cheques_id', $vinculopago->cheques_id);

        /// se agregan Id´s al arreglo para mostrarlos cheques en la vista
        $this->vinculos[]= $vinculopago->cheques_id;

        }

     }

     if($color =="red")
     {

 $id2= strtoupper($Pago['Complemento.0.default:Pagos.default:Pago.default:DoctoRelacionado.IdDocumento'])."<br><br>";
    $vinculopago=MetadataR::where('folioFiscal',$id2)->whereNotNull('cheques_id')->first();
    if($vinculopago){
        // echo "default:".$vinculopago->cheques_id."<br>";

         ///vinculacion de Pago con cheques Id
         $vincularPago= MetadataR::where('folioFiscal',$Pago->UUID)->first();
         if($vincularPago->cheques_id==NULL){
         $vincularPago->unset('cheques_id');
         }
         $vincularPago->push('cheques_id', $vinculopago->cheques_id);
          /// se agregan Id´s al arreglo para mostrarlos cheques en la vista
 $this->vinculos[]= $vinculopago->cheques_id;

        }


     }


     if($color=="yellow"){

        // echo "Factura sin UUID Relacionado<br><br>";
     }





    //  echo "===========================================================<br>";
     $color="white";
     unset($complemento);
    endforeach;

///===================REVISAR LOS TEMPORALES (ppdpendientesvincular)======================///

// $UUIDtemporales=ppdPendientesVincular::get();
$UUIDtemporales=DB::table('ppdPendientesVincular')->get();
// echo "####################--- [TEMPORALES] ---##################################<br>";
foreach($UUIDtemporales as $temp):

if($temp->ppdRealcionados==NULL){
////////// se elimina el registro si ya no hay folios pendientes
$temp->delete();

}


$metas=MetadataR::whereIn('folioFiscal',$temp['ppdRealcionados'] )->whereNotNull('cheques_id')->first();


if($metas){
// echo "tiene ahora id: ".$metas->folioFiscal."<br>";
$vincularPago= MetadataR::where('folioFiscal',$temp->folioFiscalPago)->first();
if($vincularPago->cheques_id==''){
$vincularPago->unset('cheques_id');
}
$vincularPago->push('cheques_id', $metas->cheques_id);

$temp->pull('ppdRealcionados', $metas->folioFiscal);



}
endforeach;


$this->numero=1;

// echo  count($metadataPago)."<br>". count($xmlPago);


//exit;
}






}
