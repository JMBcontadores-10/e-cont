<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Jenssegers\Mongodb\Eloquent\Model;

class Notificaciones extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

     protected $fillable = [
       '_id',
        'numcheque',
        'fecha',
        'importecheque',
        'Beneficiario',
        'tipoopera',
        'rfc',
    ];

    protected $collection = 'notificaciones';
}
