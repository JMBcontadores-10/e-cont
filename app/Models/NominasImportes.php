<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class NominasImportes extends Model
{
    use HasFactory;

    protected $collection = 'nominas_importes';
}
