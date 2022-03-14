<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class MetadataR extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $collection = 'metadata_r';

    public function cheques(){
        return $this->belongsTo(Cheques::class);
    }

    public static function search($search)
    {
        return empty($search) ? static::query()
        : static::query()->where('emisorRfc', 'like', '%'.$search.'%')
        ->orWhere('emisorNombre', 'like', '%'.$search.'%');
    }
}
