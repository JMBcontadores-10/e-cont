<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use App\Models\MetadataR;
use App\Models\XmlR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Prueba extends Controller
{
    public function index(Request $r)
    {
        // $col = XmlR::where('UUID','00DD2FED-1794-46BF-882B-8070D4780CFF')->get();
        // $uuidRef = array();
        // foreach ($col as $i) {
        //     $var = $i['0.Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
        //     foreach ($var as $j) {
        //         $uuidRef[] = $j['IdDocumento'];
        //     }
        // }
        // dd($uuidRef);
    }
}
