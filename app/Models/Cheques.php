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
    ];

    protected $collection = 'cheques';

    public function metadata_r(){
        return $this->hasMany(MetadataR::class);
    }
}
