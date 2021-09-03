<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Volumetrico extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [

        'num',
        'idV',
        'RFC',
        'fech1',
        'iiM',
        'iiP',
        'iiD',
        'cM',
        'cP',
        'cD',
        'vM',
        'vP',
        'vD',
        'aM',
        'aP',
        'aD',
        'pM',
        'pP',
        'pD',
    ];

    protected $collection = 'volumetrico';
}
