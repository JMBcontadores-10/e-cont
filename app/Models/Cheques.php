<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Cheques extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $collection = 'cheques';
}
