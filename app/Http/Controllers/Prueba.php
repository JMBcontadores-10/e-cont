<?php

namespace App\Http\Controllers;

use App\Models\Cheques;
use App\Models\MetadataR;
use Illuminate\Http\Request;

class Prueba extends Controller
{
    public function index(Request $r)
    {
        // $c = Cheques::find('60f9b22bd45500007a005017');
        // $col = $c->metadata_r;
        // foreach ($col as $i) {
        //     echo $i->folioFiscal."<br>";
        //     echo $i->efecto."<br>";
        // }

        // $allcheck = $r->allcheck;
        // foreach ($allcheck as $i) {
        //     $metar = MetadataR::where('folioFiscal', $i)->first();
        //     $cheque = Cheques::find('60f9b22bd45500007a005017');
        //     $cheque->metadata_r()->save($metar);
        // }
    }
}
