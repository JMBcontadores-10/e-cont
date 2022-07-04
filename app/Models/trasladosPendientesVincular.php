<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class trasladosPendientesVincular extends Model
{
    use HasFactory;
    protected $primaryKey = '_id';

    protected $fillable = [

      'folioFiscalPago',
      'ppdRealcionados',


   ];

   protected $collection = 'trasladospendientesvincular';


}
