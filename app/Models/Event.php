<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Event extends Model
{
    protected $primarykey = '_id';
    protected $fillable = [ '_id', 'descargasEmitidos', 'fechaDescarga', 'fechaDescargaF'];
    protected $collection = 'calendario_e';
}
