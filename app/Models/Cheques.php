<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Cheques extends Model
{
    use HasFactory;



    protected $primaryKey = '_id';

    protected $fillable = [
        '_id',
        'Id',
        'tipomov',
        'numcheque',
        'fecha',
        'importecheque',
        'Beneficiario',
        'tipoopera',
        'rfc',
        'nombrec',
        'rnfcrep',
        'importexml',
        'verificado',
        'faltaxml',
        'conta',
        'pendi',
        'lista',
        'ajuste',
        'doc_relacionados'
    ];

    protected $collection = 'cheques';

    public function metadata_r(){
        return $this->hasMany(MetadataR::class);
    }

public function fecha_es($mes){


    $mes=$mes;


// swich para convertir Int mes en String
    switch ($mes){

     case 1 :
        $mes="Enero";
         break;
     case 2 :
        $mes="Febrero";
         break;
    case  3 :
        $mes="Marzo";
        break;
    case  4 :
        $mes="Abril";
        break;
    case  5 :
        $mes="Mayo";
         break;
    case  6 :
        $mes="Junio";
        break;
    case  7 :
        $mes="Julio";
        break;
    case  8 :
        $mes="Agosto";
        break;
    case  9 :
        $mes="Septiembre";
            break;
    case 10 :
        $mes="Octubre";
        break;
    case 11 :
        $mes="Noviembre";
        break;
    case 12 :
         $mes="Diciembre";
        break;
}
return $mes;
}








}


