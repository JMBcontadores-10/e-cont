<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register'=>false]);

// Rutas Octavio
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/registro', [App\Http\Controllers\Registro2Controller::class, 'index'])->name('registro');
// Route::resource('/registro', 'App\Http\Controllers\Registro2Controller');
Route::get('/descargasv2', [App\Http\Controllers\DescargasControllerv2::class, 'index'])->name('descargasv2');
Route::post('/async', [App\Http\Controllers\Async::class, 'index'])->name('async');


// Rutas Ana
