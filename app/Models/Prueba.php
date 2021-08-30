<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $collection = 'pruebaf';
}
