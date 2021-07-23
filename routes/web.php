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

// Route::get('/registro', [App\Http\Controllers\Registro2Controller::class, 'index'])->name('registro');
// Route::post('/registro', [App\Http\Controllers\Registro2Controller::class, 'store'])->name('registro-store');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/descargasv2', [App\Http\Controllers\DescargasControllerv2::class, 'index'])->name('descargasv2');
Route::post('/async', [App\Http\Controllers\Async::class, 'index'])->name('async');
Route::get('/cuentasporpagar', [App\Http\Controllers\CuentasPorPagar::class, 'index'])->name('cuentasporpagar');
Route::post('/detalles', [App\Http\Controllers\CuentasPorPagar::class, 'detalles'])->name('detalles');
Route::get('/cheques-transferencias', [App\Http\Controllers\ChequesYTransferenciasController::class, 'index'])->name('cheques-transferencias');
Route::post('/cheques-transferencias', [App\Http\Controllers\ChequesYTransferenciasController::class, 'index'])->name('cheques-transferencias');
Route::get('/vincular-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'vincularCheque'])->name('vincular-cheque');
Route::post('/delete-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'deleteCheque'])->name('delete-cheque');
Route::post('/archivo-pagar', [App\Http\Controllers\ChequesYTransferenciasController::class, 'archivoPagar'])->name('archivo-pagar');


// Rutas Ana

Route::get('/consultas', [App\Http\Controllers\ConsultasController::class, 'index'])->name('consultas');
Route::get('/volumetrico', [App\Http\Controllers\VolumetricoController::class, 'index'])->name('volumetrico');
Route::get('/convertirXML', [App\Http\Controllers\IngresoDatosController::class, 'index']);
