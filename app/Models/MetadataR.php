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
        ->orWhere('emisorNombre', 'like', '%'.$search.'%');
    }
}
