<?php

namespace App\Models;
use App\Models\XmlR;


use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\HasMany;


class MetadataR extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $collection = 'metadata_r';





//     public function xmlr(): HasMany
// {
//     return $this->hasMany(XmlR::class, 'folioFiscal', 'UUID');
// }


    public static function search($search)
    {   
        return empty($search) ? static::query()
        : static::query()->where('emisorRfc', 'like', '%'.$search.'%')
        ->orWhere('emisorNombre', 'like', '%'.$search.'%')
        ->orWhere('folioFiscal', 'like', '%'.$search.'%');
    }

    public static function searchxml($search)
    {   
        //Creamos un array vacio donde ingresaremos los UUID
        $Collect = [];

        //Realizaremos una consulta a los xml
        $XmlResult = XmlR::
        select('UUID')
        ->where('Emisor.Rfc', 'like', '%'.$search.'%')
        ->orwhere('Emisor.Nombre', 'like', '%'.$search.'%')
        ->orWhere('UUID', 'like', '%'.$search.'%')
        ->orWhere('Folio', 'like', '%'.$search.'%')
        ->orWhere('Complemento.Pagos.Pago.DoctoRelacionado.IdDocumento', 'like', '%'.$search.'%')
        ->orWhere('Complemento.Pagos.Pago.DoctoRelacionado', 'like', '%'.$search.'%')
        ->orWhere('CfdiRelacionados.CfdiRelacionado.UUID', 'like', '%'.$search.'%')
        ->orWhere('CfdiRelacionados.CfdiRelacionado', 'like', '%'.$search.'%')
        ->get();

        //Con un foreach metemos a nuestro arreglo los UUID que obtuvo de la consulta
        foreach ($XmlResult as $UUID) {
            array_push($Collect, $UUID->UUID);
        }

        //Retornamos los metadatos que el folio fiscal coincida con UUID recien obtenidos
        return empty($search) ? static::query()
        : static::query()->wherein('folioFiscal', $Collect);
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
