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
// Route::get('/renombrarXml', [App\Http\Controllers\Prueba::class, 'renombrarXml'])->name('renombrarXml');
Route::get('/prueba', [App\Http\Controllers\Prueba::class, 'index'])->name('prueba');

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::post('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\Login1Controller::class, 'login'])->name('home');
Route::post('/home', [App\Http\Controllers\Login1Controller::class, 'login'])->name('home');
Route::get('/modules', [App\Http\Controllers\HomeController::class, 'index'])->name('modules');
Route::post('/modules', [App\Http\Controllers\HomeController::class, 'index'])->name('modules');

Route::get('/descargasv2', [App\Http\Controllers\DescargasControllerv2::class, 'index'])->name('descargasv2');
Route::post('/async', [App\Http\Controllers\Async::class, 'index'])->name('async');
Route::get('/cuentasporpagar', [App\Http\Controllers\CuentasPorPagar::class, 'index'])->name('cuentasporpagar');
Route::get('/detalles', [App\Http\Controllers\CuentasPorPagar::class, 'detalles'])->name('detalles');
Route::get('/cheques-transferencias', [App\Http\Controllers\ChequesYTransferenciasController::class, 'index'])->name('cheques-transferencias');
Route::post('/cheques-transferencias', [App\Http\Controllers\ChequesYTransferenciasController::class, 'index'])->name('cheques-transferencias');
Route::post('/vincular-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'vincularCheque'])->name('vincular-cheque');
Route::post('/delete-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'deleteCheque'])->name('delete-cheque');
Route::post('/archivo-pagar', [App\Http\Controllers\ChequesYTransferenciasController::class, 'createUpdateCheque'])->name('archivo-pagar');
Route::post('/agregar-xml-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'agregarXmlCheque'])->name('agregar-xml-cheque');
Route::post('/detallesCT', [App\Http\Controllers\ChequesYTransferenciasController::class, 'detallesCT'])->name('detallesCT');
Route::post('/desvincular-cheque', [App\Http\Controllers\ChequesYTransferenciasController::class, 'desvincularCheque'])->name('desvincular-cheque');
Route::get('/construccion', [App\Http\Controllers\ConstruccionController::class, 'index'])->name('construccion');
Route::post('/borrarArchivo', [App\Http\Controllers\ChequesYTransferenciasController::class, 'borrarArchivo'])->name('borrarArchivo');

// Rutas Ana


Route::get('/consultas', [App\Http\Controllers\ConsultasController::class, 'index'])->name('consultas');
Route::get('/consulta', [App\Http\Controllers\ConsultasController::class, 'consultas'])->name('consulta');
Route::get('/historial', [App\Http\Controllers\ConsultasController::class, 'historial'])->name('historial');
Route::get('/volumetrico', [App\Http\Controllers\VolumetricoController::class, 'index'])->name('volumetrico');
Route::post('/volumetrico1', [App\Http\Controllers\VolumetricoController::class, 'volumetrico1'])->name('volumetrico1');
Route::post('/convolumetrico', [App\Http\Controllers\VolumetricoController::class, 'convolu'])->name('convolu');
Route::post('/volumetrico2', [App\Http\Controllers\VolumetricoController::class, 'insertaDatos'])->name('insertaDatos');
Route::post('/volumetrico3', [App\Http\Controllers\VolumetricoController::class, 'updateDatos'])->name('updateDatos');
Route::post('/volumetrico4', [App\Http\Controllers\VolumetricoController::class, 'updatePrecio'])->name('updatePrecio');
Route::get('/volumetrico4', [App\Http\Controllers\VolumetricoController::class, 'updatePrecio'])->name('updatePrecio');
Route::get('/monitoreo', [App\Http\Controllers\MonitoreoController::class, 'index'])->name('monitoreo');
Route::post('/detallesfactura', [App\Http\Controllers\MonitoreoController::class, 'detallesfactura'])->name('detallesfactura');
Route::get('/auditoria', [App\Http\Controllers\AuditoriaController::class, 'index'])->name('auditoria');
Route::post('/auditoria1', [App\Http\Controllers\AuditoriaController::class, 'store'])->name('auditoria1');

Route::get('/', [App\Http\Controllers\Login1Controller::class, 'index'])->name('log');
// Route::get('/login', [App\Http\Controllers\Login1Controller::class, 'login'])->name('login');
Route::post('/consultas1', [App\Http\Controllers\ConsultasController::class, 'store'])->name('consultas1');
// Route::post('/consultas1', [App\Http\Controllers\ConsultasController::class, 'ingreso'])->name('ingreso');

Route::get('/script', [App\Http\Controllers\Scriptp::class, 'xmlLeer'])->name('xmlLeer');
Route::get('/scriptt', [App\Http\Controllers\Scriptp::class, 'xmlborrar'])->name('xmlborrar');
Route::post('/home2', [App\Http\Controllers\HomeController::class, 'home2'])->name('home2');
Route::get('/script1', [App\Http\Controllers\Script1::class, 'tipoUsuarios'])->name('tipoUsuarios');
Route::get('/dir', [App\Http\Controllers\Prueba::class, 'createdir'])->name('createdir');
//Crear directorios en volumetrico
Route::get('/dir1', [App\Http\Controllers\Prueba::class, 'createDir2'])->name('createDir2');
