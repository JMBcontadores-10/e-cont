<?php

use App\Http\Controllers\UploadController;
use App\Http\Livewire\Auditoria;
use App\Http\Livewire\Chequesytransferencias;

use App\Http\Livewire\vinculacionAutomatica;
use App\Http\Livewire\CuentasPorpagar;
use App\Http\Livewire\Cheques;
use App\Http\Livewire\Descargas;
use App\Http\Livewire\Eliminar;
use App\Http\Livewire\Home;
use App\Http\Livewire\FacturasVinculadas;
use App\Http\Livewire\Modals\Editar;
use App\Http\Livewire\Pdfcheque;
use App\Http\Livewire\Volumepdf;
use App\Http\Livewire\Volumetrico;
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

Route::get('/registro', [App\Http\Controllers\Registro2Controller::class, 'index'])->name('registro');
Route::post('/registro', [App\Http\Controllers\Registro2Controller::class, 'store'])->name('registro-store');
// Route::get('/renombrarXml', [App\Http\Controllers\Prueba::class, 'renombrarXml'])->name('renombrarXml');
Route::get('/prueba', [App\Http\Controllers\Prueba::class, 'index'])->name('prueba');
Route::match(['get', 'post'], '/home', [App\Http\Controllers\Login1Controller::class, 'login'])->name('home');

Route::get('/descargasv2', [App\Http\Controllers\DescargasControllerv2::class, 'index'])->name('descargasv2');
Route::post('/async', [App\Http\Controllers\Async::class, 'index'])->name('async');
Route::get('/cuentaspagar', [App\Http\Controllers\CuentasPorPagar::class, 'index'])->name('cuentaspagar');
Route::get('/detalles', [App\Http\Controllers\CuentasPorPagar::class, 'detalles'])->name('detalles');
Route::match(['get', 'post'], '/cheques-transferencias', [App\Http\Controllers\ChequesYTransferenciasController::class, 'index'])->name('cheques-transferencias');
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
Route::match(['get', 'post'], '/volumetrico4', [App\Http\Controllers\VolumetricoController::class, 'updatePrecio'])->name('updatePrecio');
Route::get('/monitoreo', [App\Http\Controllers\MonitoreoController::class, 'index'])->name('monitoreo');
Route::post('/detallesfactura', [App\Http\Controllers\MonitoreoController::class, 'detallesfactura'])->name('detallesfactura');
Route::get('/auditoria2', [App\Http\Controllers\AuditoriaController::class, 'index'])->name('auditoria');
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


// Rutas Jose Segura

Route::get('/vinculacionAutomatica', [App\Http\Controllers\vinculacionAutomaticaCfdi::class, 'vincular'])->name('vincular');
Route::get('/script2', [App\Http\Controllers\RecarpetarCheques::class, 'archivar'])->name('archivar');
Route::view('editar','livewire.editar');
Route::post('/upload/{id}', [App\Http\Controllers\UploadController::class, 'store']);
Route::post('/upload2/{id}', [App\Http\Controllers\UploadController::class, 'store2']);
Route::post('/uploadEdit/{id}', [App\Http\Controllers\UploadController::class, 'storeEditPdf']);
Route::post('/uploadEdit2/{id}', [App\Http\Controllers\UploadController::class, 'storeEditPdf2']);
Route::post('/nuevoCheque/{id}', [App\Http\Controllers\UploadController::class, 'nuevoCheque']);
Route::get('/chequesytransferencias',Chequesytransferencias::class)->name('cheques');
// Route::get('facturasVinculadas',FacturasVinculadas::class)->name('vinculadas');
Route::get('/descargascfdi', [App\Http\Controllers\DescargascfdiController::class, 'index'])->name('descargascfdi');
Route::get('zip-download/{id}', [Eliminar::class, 'descargarZip']);
Route::get('/exportar/{facturas}', [FacturasVinculadas::class, 'export']);
Route::get('/descargasAutomaticas/{valor}', [App\Http\Controllers\DescargasAutomaticas::class, 'ConsultSAT'])->name('descargasAutomaticas');
// Route::get('/descargasAutomaticas1', [App\Http\Livewire\DescargasAutomaticas::class, 'index'])->name('descargasAutomaticas1');
Route::get('/auditoria',Auditoria::class)->name('auditoria');


// Rutas Angel :D

//Ruta de la vista de cuentas por pagar en livewire
Route::get('/cuentasporpagar', Cuentasporpagar::class)->name('cuentasporpagar');
//Ruta de la vista de descargar
Route::get('/descargas', Descargas::class)->name('descargas');
//Ruta de la vista de home (pagina de inicio)
Route::get('/modules', Home::class)->name('modules');
//Ruta de la vista volumetrico
Route::get('/volu', Volumetrico::class)->name('volu');
//Ruta de almacenamiento de PDF (Volumetricos)
Route::post('/pdfvolu/{id}', [Volumepdf::class, 'PDFVolu']);