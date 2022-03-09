<?php

namespace App\Exports;

use App\Models\MetadataR;
use App\Models\XmlR;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;/// para implementar tamaño alas celdas
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;// para dar estilo a als celdas y hoja en general
use Maatwebsite\Excel\Concerns\WithStyles;


class FacturasExport implements FromCollection,WithHeadings,WithColumnWidths,WithStyles
{
   private $facturas;
    // ,$folioFiscal;

    public function __construct($facturas)
    {
        $this->facturas=$facturas;
        // $this->folioFiscal=$folioFiscal;


    }


    public function columnWidths(): array// funcion para tamaño de cledas
    {
        return [
            'A' => 45,
            'B' => 20,
            'C' => 70,
            'D' => 70,
            'E' => 20,
            'F' => 20,
            'G' => 70,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }

    public function styles(Worksheet $sheet)// funcion para dar estilo alas celdas y hoja en general
    {
        return [


            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],



        ];
    }



    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [

'UUID',
'Fecha Emision',
'Emisor',
'Concepto',
'Folio',
'Metodo de Pago',
'UUID Relacionado',
'Efecto',
'Sub Total',
'IVA',
'Total',
'Estado',


        ];
    }
    public function collection()
    {


        $colM=array();
        $xml=array();

         $colM =MetadataR::where(['cheques_id' => $this->facturas])->get();


//          foreach($colM as $f){
// $folioF= $f->folioFiscal;

// }

//         // $users = User::join('posts', 'users.id', '=', 'posts.user_id')
//         // ->get(['users.*', 'posts.descrption']);

//         // $metadata = DB::table('metadata_r')->where('cheques_id',$this->facturas)

//         //  ->get();
//         // $xml =XMLR::where(['UUID'=>$folioF])

//         // ->get();

//    //  ->get();
//     $nCon=0;

//    if (!$xml->isEmpty()){

//    foreach ($xml as $v) {

//     $concepto = $v['Conceptos.Concepto'];

//    }

//    $num=count($concepto);
// for($i=0; $i<= $num ; $i++){

// foreach($concepto as $c){
//     $conceptos[]=$nCon++.$c['Descripcion'];

// }

//     }

$nCon=0;
$a=[];
$t=[];
$egreso=[];
$sub_Egreso=[];
$iva_Egreso =[];
$Iva=[];
$Iv = [];
foreach($colM as $em){
    $conceptos=array();/// colocar al incio del ciclo para restablecer la matriz de lo contrario causa error
    $docRel=array();
    $xml =XMLR::where(['UUID'=>$em['folioFiscal']])->get();//enlazar los xml a los metadatos

    $efecto = $em['efecto'];/// seleccionar efecto de metadata_r


foreach($xml as $x){

    $concepto = $x['Conceptos.Concepto'];
    $num=count($concepto);
if($num >1){

    foreach($concepto as $c){
        $conceptos[]= $nCon++.$c['Descripcion'];

    }

}else{

    $conceptos=$x['Conceptos.Concepto.0.Descripcion'];
}


$metodoPago=$x['MetodoPago'];



if($efecto == 'Pago'){
   // $docRel = $x['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
    $metodoPago = '-';
  $iva=0;
    $doc = $x['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
    if (isset($doc)){
    $numPagos=count($doc);
    }else{
$numPagos=0;

    }
    if($numPagos >1){

        foreach($doc as $dr){


            $docRel[]=$dr['IdDocumento'];
        }


        }else{


        $docRel[]=$x['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento'];

    }



} elseif ($efecto == 'Egreso' or $efecto == 'Ingreso') {
    $docRel[] = $x['CfdiRelacionados.CfdiRelacionado'];
    $iva=$x['Impuestos.Traslados.Traslado.0.Importe']; // Imprimir el IVA .16% "002"
}








    $emp[]= array( $em['folioFiscal'],$em['fechaEmision'],$em['emisorNombre'],$conceptos,$x['Folio'],$metodoPago,$docRel,$efecto,$x['SubTotal'],$iva,$x['Total'],$em['estado']);


}

}





        return new Collection([
           $emp
        ]);

    }



}
