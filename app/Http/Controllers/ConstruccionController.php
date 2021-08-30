<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConstruccionController extends Controller
{
    public function index(){
        return view('construccion');
    }
}
