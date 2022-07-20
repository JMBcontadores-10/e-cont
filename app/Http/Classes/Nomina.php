<?php

namespace App\Http\Classes;

use App\Models\XmlE;
use App\Models\MetadataE;
use App\Models\Cheques;
use Illuminate\Support\Facades\DB;

/////[ Metodos Indice ] /////////

/* {nominas.blade} -- Vista general Nominas  */
/* {modal asignar-cheque.blade} -- Modal asignar cheques */


///// clase nomina para almancenar los metodos
class Nomina
{

  //************** METODOS PARA LAS VISTAS  *******************/


    //--> [ metodos para la vista {nominas.blade}  ] <--//


    //===={ Obtener el total pagado de los CFDI'S Nomina }

    public function TotalPagado($rfc, $anio, $folio,$tipoNomina)
    {

        /// se obtienen los metadatos de los cfdi´s para filtrar los
        /// cancelados

        $metadata = MetadataE:: // consulta a MetadataE
            where('emisorRfc', $rfc)
            ->where('estado', '!=', 'Cancelado')
            ->where('efecto', 'Nómina')
            ->select('folioFiscal', 'cheques_id')
            ->project(['_id' => 0])
            ->get();

        // se almacenan los foliosfiscales en un arreglo
        foreach ($metadata as $m) {
            $cont[] = $m->folioFiscal;
        }

        //// retorna  la consulta con la suma  de total pagado filtrado
        return DB::Table('xmlemitidos')
            ->whereIn('UUID', $cont)
            ->where('Emisor.Rfc', $rfc)
            ->where('TipoDeComprobante', 'N')
            ->where('Complemento.0.Nomina.TipoNomina',$tipoNomina)
            ->where('Serie', $anio)
            ->where('Folio', $folio)
            ->get()->sum('Total');
    }



    //===== { Obtención del total del ISR  de los CFDI´S Nomina }===//

    ##############################################

    public function ISR($rfc, $anio, $folio, $tipoNomina){
 /// se obtienen los metadatos de los cfdi´s para filtrar los
        /// cancelados
        $sumaIsr=0;
        $metadata = MetadataE:: // consulta a MetadataE
            where('emisorRfc', $rfc)
            ->where('estado', '!=', 'Cancelado')
            ->where('efecto', 'Nómina')
            ->select('folioFiscal', 'cheques_id')
            ->project(['_id' => 0])
            ->get();

        // se almacenan los foliosfiscales en un arreglo
        foreach ($metadata as $m) {
            $cont[] = $m->folioFiscal;
        }
    $xmlIsr= XmlE::
     whereIn('UUID',$cont)
     ->where('Emisor.Rfc',$rfc)
     ->where('TipoDeComprobante','N')
     ->where('Complemento.0.Nomina.TipoNomina',$tipoNomina)
     ->where('Serie', $anio)
     ->where('Folio',$folio)
     // ->select('Fecha','Complemento','Total')
    //  ->sum('Complemento.0.Nomina.Deducciones.Deduccion.1.Importe')
     ->get();


    //  echo "count".count(  $xmlIsr)."<br>";

     foreach($xmlIsr as $dd){



        $deduccion= XmlE::where(['UUID' => $dd->UUID])
        ->where([
            "Complemento.0.Nomina.Deducciones.Deduccion" =>
            [
                '$elemMatch' =>
                [
                    "TipoDeduccion" =>"002"
                ]
                ]
        ])->first();


        if($deduccion!=NULL){

            foreach($deduccion['Complemento.0.Nomina.Deducciones.Deduccion'] as $d)

            {

                if($d['TipoDeduccion']=="002" && $dd['Complemento.0.Nomina.TipoNomina'] == "E"){


                    $sumaIsr+=  $d['Importe'] ;
                }
               else if ($d['TipoDeduccion']=="002" && $d['Concepto'] == "ISR mes"){

                $sumaIsr+=  $d['Importe'] ;

               }


        }





    }





}


return  $sumaIsr;



    }

    ##############################################

           //--> [ {modal asignar-cheque.blade} ] <--//
  /////==========={ sacar el total pagado } =======///////

  public function TotalPago($rfc,$anio,$folio, $mes, $tipoNomina , $data = null){
 /**  Declaración de Variables */
$suma=0;
$uuid=[];
$nomi="nomina".$anio.$folio;

////// se filtran los cancelados de los xml este
///campo cancelado solo esta en metadatos

  /// se obtienen los metadatos de los cfdi´s para filtrar los
        /// cancelados
        $metadata1 = MetadataE:: // consulta a MetadataE
        where('emisorRfc', $rfc)
        ->where('estado', '!=', 'Cancelado')
        ->where('efecto', 'Nómina')
        ->select('folioFiscal', 'cheques_id')
        ->project(['_id' => 0])
        ->get();

  // se almacenan los foliosfiscales en un arreglo
  foreach ($metadata1 as $m) {
    $cont[] = $m->folioFiscal;
}


////// se obtienen los cfdi  xml ya filtrados sin cancelados
$nominas=XmlE::

whereIn('UUID',$cont)
    ->where('TipoDeComprobante','N')
    ->where('Serie', $anio)
    ->where('Folio',$folio)
    ->where('Complemento.0.Nomina.FechaPago','like','%' ."-".$mes."-".'%')
    ->where('Complemento.0.Nomina.TipoNomina',$tipoNomina)
    ->select('Fecha','Complemento','Total','Emisor','Serie','UUID')
    ->groupBy('Folio')
    ->orderBy('Folio','Asc')
    ->get();

    foreach($nominas as $nomina){ $uuid[]= $nomina['UUID'];}

/// para obtener el total de l saldo hay que verificar si hay cheques asociados
/// en los metadatos y obtner los importes ha descontar de el totalPago zprincipal




/// se obtienen los metadatos de los cfdi´s para filtrar los
/// cancelados

        $metadata = MetadataE:: // consulta a MetadataE
             where('folioFiscal', $uuid[0])
            ->where('efecto', 'Nómina')
            ->first();

//// suma del total de todos los cdfi nominas
   $nominaTotal = Nomina::TotalPagado($rfc, $anio, $folio,$tipoNomina);

   if ($data == null) //// si existe un parametro para uuid
   {


    if(isset($metadata->cheques_id)){

    /// se obtienen todos lo cheques  vinculados
$cheques=Cheques::
      whereIn('_id', $metadata->cheques_id)->get();

//// se clasifican los cheques para saber si se sumara saldo / nomina.serie.folio ó importe
   foreach($cheques as $cheque){


    ///// suma campo nomina.serie.folio si existe

    if (isset($cheque->$nomi))
    {
          $suma += $cheque->$nomi;

           ////////////[ SE SUMA EL AJUSTE SI EXISTE ]////////////
   if($cheque->ajuste > 0){

    $suma += $cheque->ajuste;

   }

    /// suma campo saldo si nomina.serie.folio no existe y saldo si
   }else if(!isset($cheque->$nomi) && $cheque->saldo){

           $suma+=$cheque->saldo;
        ////////////[ SE SUMA EL AJUSTE SI EXISTE ]////////////
   if($cheque->ajuste > 0){

    $suma += $cheque->ajuste;

   }

   }elseif(isset($cheque->nominaAsignada) && $cheque->saldo !=0){

             $suma+=$cheque->saldo;
              ////////////[ SE SUMA EL AJUSTE SI EXISTE ]////////////
   if($cheque->ajuste > 0){

    $suma += $cheque->ajuste;

   }
   }else{

           $suma += $cheque->importecheque;
            ////////////[ SE SUMA EL AJUSTE SI EXISTE ]////////////
   if($cheque->ajuste > 0){

    $suma += $cheque->ajuste;

   }

   }


       }/// fin de foreach $cheques



  return $nominaTotal -$suma;

    } else {// fin del isset $metadata

        return  $nominaTotal ;
    }/// fi del isset metadata

}else{///// fin del if data = null


    return $uuid[0] ; // retorna el uuid asociado
}



  }/// fin de metodoto TotalPago


//////////////////////// Metodo Buscar en array de los

public function importesTemporales($arreglo, $idCheque){
 $importe =0;
    foreach ($arreglo as $item){

        if ($item['uuid'] == $idCheque ){

           return $importe =  $item['importe'];
        }

}

}


}

    //--> [ metodos para los componenetes/controladores  ] <--//






