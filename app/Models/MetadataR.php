<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class MetadataR extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [
        'vinculado',
    ];

    protected $collection = 'metadata_r';

    public function cheques(){
        return $this->belongsTo(Cheques::class);
    }
}
