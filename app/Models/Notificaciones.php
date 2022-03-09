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
        'fechaCancelacion',
        'importecheque',
        'Beneficiario',
        'tipoopera',
        'rfc',
        'read_at',
        'tipo',
        'folioFiscal',
        'cheques_id',
        'emisorMensaje',
        'receptorMensaje',
        'tipo',
    ];

    protected $collection = 'notificaciones';
}
