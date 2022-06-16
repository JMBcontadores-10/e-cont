<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Tareas extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'nomproyecto',
        'rfcproyecto',
        'fechaentrega',
        'prioridad',
        'frecuencia',
        'periodo',
        'colaboradores',
    ];

    protected $collection = 'tareas';
}
