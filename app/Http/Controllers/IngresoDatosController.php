<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CFDItoJSON;
use Cleaner;
use MongoDB;

class IngresoDatosController extends Controller
{
    public function index(Request $request)
    {
        $mes="Enero";
        $rfc="AHF060131G59";
        $anio="2021";
        $rfc = Auth::user()->RFC;
        $tipoF="Emitidas";

        $path= 'storage/contarappv1_descargas/AHF060131G59/2021/Descargas/1.Enero/Emitidos/XML';

        echo "<h1>Datos ingresados correctamente</h1>";
        foreach( glob("{$path}/*.xml") as $cfdi){


              $contents = file_get_contents($cfdi);

              if($contents){

              $json = CFDItoJSON::convertToJson($contents);
              echo $json;

              echo "<br>";
              echo "<br>";

              $array = json_decode($json,true);
              $TipoComprobante = $array["TipoDeComprobante"];
              $UUID=$array["Complemento"][0]["TimbreFiscalDigital"]["UUID"];
              echo $TipoComprobante;
              echo $UUID;
              echo "<br>";
              echo "<br>";

              $cliente = new MongoDB\Client("mongodb://localhost:27017");
              $pUUID = array('Complemento.0.TimbreFiscalDigital.UUID');

              switch($TipoComprobante){

                case 'I':
                    echo "El comprobante es de Ingreso";
                    echo "<br>";

                    // // insertar documento
                    $coleccion = $cliente->contarappv1->ingreso;
                    // $muuid = $coleccion->find($pUUID);
                    // if($muuid != $UUID){
                        $resultado = $coleccion->insertOne($array);
                    // }else {
                    //     echo "Aqui hay error";
                    // }

                    break;
                case 'E':
                    echo "El comprobante es de Egreso";
                    echo "<br>";

                    $coleccion = $cliente->contarappv1->egreso;


                        $resultado = $coleccion->insertOne($array);


                    break;
                case 'P':
                    echo "El comprobante es de Pago";
                    echo "<br>";
                    $coleccion = $cliente->contarappv1->pago;


                        $resultado = $coleccion->insertOne($array);

                    break;
                case 'N':
                    echo "El comprobante es de Nómina";
                    echo "<br>";
                    $coleccion = $cliente->contarappv1->nomina;

                        $resultado = $coleccion->insertOne($array);

                    break;

              }

              }

        }

    }
}
