<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Volumetrico;
use Livewire\Component;

class Volumedata extends Component
{
    //Variables globales
    public $dia;
    public $empresa;
    public $formdatavolu;

    //Variables del combustible que manejan
    public $Magna;
    public $Premium;
    public $Diesel;
    public $fecha;
    public $fechaayer;

    //Variable para activar el input
    //Volumetrico
    public $activem = "readonly";
    public $activep = "readonly";
    public $actived = "readonly";

    //Variales para almacenar en la base de datos
    //Volumetrico **************************************************************************************************************************************
    //Inventario inicial
    public $inventinicmagna;
    public $inventinicpremium;
    public $inventinicdiesel;

    //Compras
    public $compramagna;
    public $comprapremium;
    public $compradiesel;

    //Compras (Descuento)
    public $compradescmagna;
    public $compradescpremium;
    public $compradescdiesel;

    //Litros vendidos
    public $litvendmagna;
    public $litvendpremium;
    public $litvenddiesel;

    //Precio de compra
    public $preccompmagna;
    public $preccomppremium;
    public $preccompdiesel;

    //Precio venta
    public $precventmagna;
    public $precventpremium;
    public $precventdiesel;

    //Inventario real
    public $autostickmagna;
    public $autostickpremium;
    public $autostickdiesel;

    //Inventario determinado
    public $invdetermagna;
    public $invdeterpremium;
    public $invdeterdiesel;

    //Merma
    public $mermamagna;
    public $mermapremium;
    public $mermadiesel;
    //****************************************************************************************************************************************************

    //Cambio de precio ***********************************************************************************************************************************
    //Inventario inicial
    public $inventiniccambmagna;
    public $inventiniccambpremium;
    public $inventiniccambdiesel;

    //Compras
    public $compracambmagna;
    public $compracambpremium;
    public $compracambdiesel;

    //Compras (Descuento)
    public $compracambdescmagna;
    public $compracambdescpremium;
    public $compracambdescdiesel;

    //Litros vendidos
    public $litvendcambmagna;
    public $litvendcambpremium;
    public $litvendcambdiesel;

    //Precio de compra
    public $preccompcambmagna;
    public $preccompcambpremium;
    public $preccompcambdiesel;

    //Precio venta
    public $precventcambmagna;
    public $precventcambpremium;
    public $precventcambdiesel;

    //Inventario real
    public $autostickcambmagna;
    public $autostickcambpremium;
    public $autostickcambdiesel;

    //Inventario determinado
    public $invdetercambmagna;
    public $invdetercambpremium;
    public $invdetercambdiesel;

    //Merma
    public $mermacambmagna;
    public $mermacambpremium;
    public $mermacambdiesel;
    //****************************************************************************************************************************************************

    protected $listeners = ['NuevoVolu' => 'NuevoVolu'];

    //Metodo para saber que combustibles maneja
    public function InfoGas()
    {
        //Hacemos una consulta de la empresa para saber que datos vamos a mostrar
        $infogas = User::where('RFC', $this->empresa)->get()->first();

        //Obtenemos los datos requeridos
        if (!empty($infogas)) {
            $this->Magna = $infogas['TipoM'];
            $this->Premium = $infogas['TipoP'];
            $this->Diesel = $infogas['TipoD'];
        } else {
            //De lo contrario los declaramos vacios
            $this->Magna = '';
            $this->Premium = '';
            $this->Diesel = '';
        }

        //Consultamos lo datos de los volumetricos
        $datavolum = Volumetrico::where(['rfc' => $this->empresa])
            ->get()
            ->first();

        //Obtenemos el dia anterior
        $diaanterior = date('Y-m-d', strtotime($this->dia . '- 1 days'));

        if ($datavolum) {
            $this->fechaayer = $datavolum['volumetrico.' . $diaanterior . '.Fecha'];
        }

        //Hacemos una consulta
        $infovolu = Volumetrico::where(['rfc' => $this->empresa])->get()->first();

        if ($infovolu) {
            //Insertamos los datos en los campos necesarios

            //Fecha
            $this->fecha = $infovolu['volumetrico.' . $this->dia . '.Fecha'];

            //Magna
            if ($this->Magna == 1) {
                //Magna Vomumetricos
                $this->inventinicmagna = $infovolu['volumetrico.' . $this->dia . '.IventInicM'];
                $this->compramagna = $infovolu['volumetrico.' . $this->dia . '.CompraM'];

                //Condicional para establecer el valor al precio  de compra y con descuento
                if (floatval($this->compramagna) > 0) {
                    //Si hay datos
                    $this->preccompmagna = $infovolu['volumetrico.' . $this->dia . '.PrecCompM'];
                    $this->compradescmagna = $infovolu['volumetrico.' . $this->dia . '.CompraDescM'];
                    $this->activem = null;
                } else {
                    //Si no hay datos
                    $this->preccompmagna = "0";
                    $this->compradescmagna = "0";
                    $this->activem = "readonly";
                }

                $this->litvendmagna = $infovolu['volumetrico.' . $this->dia . '.LitVendM'];
                $this->precventmagna = $infovolu['volumetrico.' . $this->dia . '.PrecVentM'];
                $this->autostickmagna = $infovolu['volumetrico.' . $this->dia . '.AutoStickM'];
                $this->invdetermagna = $infovolu['volumetrico.' . $this->dia . '.InvDeterM'];
                $this->mermamagna = $infovolu['volumetrico.' . $this->dia . '.MermaM'];



                //Magna Cambio de precio
                $this->inventiniccambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicM'];
                $this->compracambmagna = "0";
                $this->preccompcambmagna = "0";
                $this->compracambdescmagna = "0";
                $this->litvendcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendM'];
                $this->precventcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentM'];
                $this->autostickcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickM'];
                $this->invdetercambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterM'];
                $this->mermacambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.MermaM'];
            }

            //Premium
            if ($this->Premium == 1) {
                //Premium Vomumetricos
                $this->inventinicpremium = $infovolu['volumetrico.' . $this->dia . '.IventInicP'];
                $this->comprapremium = $infovolu['volumetrico.' . $this->dia . '.CompraP'];

                //Condicional para establecer el valor al precio  de compra y con descuento
                if (floatval($this->comprapremium) > 0) {
                    //Si hay datos
                    $this->preccomppremium = $infovolu['volumetrico.' . $this->dia . '.PrecCompP'];
                    $this->compradescpremium = $infovolu['volumetrico.' . $this->dia . '.CompraDescP'];
                    $this->activep = null;
                } else {
                    //Si no hay datos
                    $this->preccomppremium = "0";
                    $this->compradescpremium = "0";
                    $this->activep = "readonly";
                }

                $this->litvendpremium = $infovolu['volumetrico.' . $this->dia . '.LitVendP'];
                $this->precventpremium = $infovolu['volumetrico.' . $this->dia . '.PrecVentP'];
                $this->autostickpremium = $infovolu['volumetrico.' . $this->dia . '.AutoStickP'];
                $this->invdeterpremium = $infovolu['volumetrico.' . $this->dia . '.InvDeterP'];
                $this->mermapremium = $infovolu['volumetrico.' . $this->dia . '.MermaP'];




                //Premium Cambio de precio
                $this->inventiniccambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicP'];
                $this->compracambpremium = "0";
                $this->preccompcambpremium = "0";
                $this->compracambdescpremium = "0";
                $this->litvendcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendP'];
                $this->precventcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentP'];
                $this->autostickcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickP'];
                $this->invdetercambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterP'];
                $this->mermacambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.MermaP'];
            }

            //Diesel
            if ($this->Diesel == 1) {
                //Diesel Vomumetricos
                $this->inventinicdiesel = $infovolu['volumetrico.' . $this->dia . '.IventInicD'];
                $this->compradiesel = $infovolu['volumetrico.' . $this->dia . '.CompraD'];

                //Condicional para establecer el valor al precio  de compra y con descuento
                if (floatval($this->compradiesel) > 0) {
                    //Si hay datos
                    $this->preccompdiesel = $infovolu['volumetrico.' . $this->dia . '.PrecCompD'];
                    $this->compradescdiesel = $infovolu['volumetrico.' . $this->dia . '.CompraDescD'];
                    $this->actived = null;
                } else {
                    //Si no hay datos
                    $this->preccompdiesel = "0";
                    $this->compradescdiesel = "0";
                    $this->actived = "readonly";
                }

                $this->litvenddiesel = $infovolu['volumetrico.' . $this->dia . '.LitVendD'];
                $this->precventdiesel = $infovolu['volumetrico.' . $this->dia . '.PrecVentD'];
                $this->autostickdiesel = $infovolu['volumetrico.' . $this->dia . '.AutoStickD'];
                $this->invdeterdiesel = $infovolu['volumetrico.' . $this->dia . '.InvDeterD'];
                $this->mermadiesel = $infovolu['volumetrico.' . $this->dia . '.MermaD'];

                //Diesel Cambio de precio
                $this->inventiniccambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicD'];
                $this->compracambdiesel = "0";
                $this->preccompcambdiesel = "0";
                $this->compracambdescdiesel = "0";
                $this->litvendcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendD'];
                $this->precventcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentD'];
                $this->autostickcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickD'];
                $this->invdetercambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterD'];
                $this->mermacambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.MermaD'];
            }
        }
    }

    //Metodo para actualizar Invet. Determinado y Merma del dia siguiente (cuando se actualice los precios)
    public function ActuDiaSig()
    {
        // Sacamos el dia siguiente
        $diasiguiente = date("Y-m-d", strtotime($this->dia . "+ 1 days"));

        //Realizamos una consulta a los volumetricos
        $consulvolumetric = Volumetrico::where(['rfc' => $this->empresa]);
        $infovolumetric = $consulvolumetric->get()->first();

        //Condicional para comporbar si existe informacion en el dia anterior (Para esto utilizaremos el campo fecha)
        if (!empty($infovolumetric['volumetrico.' . $diasiguiente . '.Fecha'])) {
            //Magna
            if ($this->Magna == 1) {
                //Obtenemos los datos necesarios
                //Condicional para verificar si hay datos en la Cambio de precio
                if (!empty($infovolumetric['volumetrico.' . $diasiguiente . '-C.Fecha'])) {
                    //Inventarios inicial
                    $InventInicM = $infovolumetric['volumetrico.' . $diasiguiente . '-C.IventInicM'];

                    //Compra
                    $CompraM = $infovolumetric['volumetrico.' . $diasiguiente . '-C.CompraM'];

                    //Lit. Vendidos
                    $LitVentM = $infovolumetric['volumetrico.' . $diasiguiente . '-C.LitVendM'];

                    //Autostick
                    $AutoStickM = $infovolumetric['volumetrico.' . $diasiguiente . '-C.AutoStickM'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterM = (floatval($InventInicM) + floatval($CompraM)) - floatval($LitVentM);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaM =  floatval($InventDeterM) - floatval($AutoStickM);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterM' => $InventDeterM,
                        'volumetrico.' . $diasiguiente . '.MermaM' => $MermaM,
                        'volumetrico.' . $diasiguiente . '-C.InvDeterM' => $InventDeterM,
                        'volumetrico.' . $diasiguiente . '-C.MermaM' => $MermaM
                    ], ['upsert' => true]);
                } else {
                    //Inventarios inicial
                    $InventInicM = $infovolumetric['volumetrico.' . $diasiguiente . '.IventInicM'];

                    //Compra
                    $CompraM = $infovolumetric['volumetrico.' . $diasiguiente . '.CompraM'];

                    //Lit. Vendidos
                    $LitVentM = $infovolumetric['volumetrico.' . $diasiguiente . '.LitVendM'];

                    //Autostick
                    $AutoStickM = $infovolumetric['volumetrico.' . $diasiguiente . '.AutoStickM'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterM = (floatval($InventInicM) + floatval($CompraM)) - floatval($LitVentM);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaM =  floatval($InventDeterM) - floatval($AutoStickM);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterM' => $InventDeterM,
                        'volumetrico.' . $diasiguiente . '.MermaM' => $MermaM
                    ], ['upsert' => true]);
                }
            }

            //Premium
            if ($this->Premium == 1) {
                //Obtenemos los datos necesarios
                //Condicional para verificar si hay datos en la Cambio de precio
                if (!empty($infovolumetric['volumetrico.' . $diasiguiente . '-C.Fecha'])) {
                    //Inventarios inicial
                    $InventInicP = $infovolumetric['volumetrico.' . $diasiguiente . '-C.IventInicP'];

                    //Compra
                    $CompraP = $infovolumetric['volumetrico.' . $diasiguiente . '-C.CompraP'];

                    //Lit. Vendidos
                    $LitVentP = $infovolumetric['volumetrico.' . $diasiguiente . '-C.LitVendP'];

                    //Autostick
                    $AutoStickP = $infovolumetric['volumetrico.' . $diasiguiente . '-C.AutoStickP'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterP = (floatval($InventInicP) + floatval($CompraP)) - floatval($LitVentP);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaP =  floatval($InventDeterP) - floatval($AutoStickP);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterP' => $InventDeterP,
                        'volumetrico.' . $diasiguiente . '.MermaP' => $MermaP,
                        'volumetrico.' . $diasiguiente . '-C.InvDeterP' => $InventDeterP,
                        'volumetrico.' . $diasiguiente . '-C.MermaP' => $MermaP,
                    ], ['upsert' => true]);
                } else {
                    //Inventarios inicial
                    $InventInicP = $infovolumetric['volumetrico.' . $diasiguiente . '.IventInicP'];

                    //Compra
                    $CompraP = $infovolumetric['volumetrico.' . $diasiguiente . '.CompraP'];

                    //Lit. Vendidos
                    $LitVentP = $infovolumetric['volumetrico.' . $diasiguiente . '.LitVendP'];

                    //Autostick
                    $AutoStickP = $infovolumetric['volumetrico.' . $diasiguiente . '.AutoStickP'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterP = (floatval($InventInicP) + floatval($CompraP)) - floatval($LitVentP);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaP =  floatval($InventDeterP) - floatval($AutoStickP);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterP' => $InventDeterP,
                        'volumetrico.' . $diasiguiente . '.MermaP' => $MermaP,
                    ], ['upsert' => true]);
                }
            }

            //Diesel
            if ($this->Diesel == 1) {
                //Obtenemos los datos necesarios
                //Condicional para verificar si hay datos en la Cambio de precio
                if (!empty($infovolumetric['volumetrico.' . $diasiguiente . '-C.Fecha'])) {
                    //Inventarios inicial
                    $InventInicD = $infovolumetric['volumetrico.' . $diasiguiente . '-C.IventInicD'];

                    //Compra
                    $CompraD = $infovolumetric['volumetrico.' . $diasiguiente . '-C.CompraD'];

                    //Lit. Vendidos
                    $LitVentD = $infovolumetric['volumetrico.' . $diasiguiente . '-C.LitVendD'];

                    //Autostick
                    $AutoStickD = $infovolumetric['volumetrico.' . $diasiguiente . '-C.AutoStickD'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterD = (floatval($InventInicD) + floatval($CompraD)) - floatval($LitVentD);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaD =  floatval($InventDeterD) - floatval($AutoStickD);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterD' => $InventDeterD,
                        'volumetrico.' . $diasiguiente . '.MermaD' => $MermaD,
                        'volumetrico.' . $diasiguiente . '-C.InvDeterD' => $InventDeterD,
                        'volumetrico.' . $diasiguiente . '-C.MermaD' => $MermaD
                    ], ['upsert' => true]);
                } else {
                    //Inventarios inicial
                    $InventInicD = $infovolumetric['volumetrico.' . $diasiguiente . '.IventInicD'];

                    //Compra
                    $CompraD = $infovolumetric['volumetrico.' . $diasiguiente . '.CompraD'];

                    //Lit. Vendidos
                    $LitVentD = $infovolumetric['volumetrico.' . $diasiguiente . '.LitVendD'];

                    //Autostick
                    $AutoStickD = $infovolumetric['volumetrico.' . $diasiguiente . '.AutoStickD'];



                    //Inventario determinado
                    //Realzamos el calculo del Invent. Determinado
                    $InventDeterD = (floatval($InventInicD) + floatval($CompraD)) - floatval($LitVentD);

                    //Merma
                    //Realizamos el calculo para sacar Merma
                    $MermaD =  floatval($InventDeterD) - floatval($AutoStickD);

                    //Actualizamos la base de datos
                    $consulvolumetric->update([
                        'volumetrico.' . $diasiguiente . '.InvDeterD' => $InventDeterD,
                        'volumetrico.' . $diasiguiente . '.MermaD' => $MermaD,
                    ], ['upsert' => true]);
                }
            }
        }
    }

    //Metodo para almacenar/editar un volumetrico
    public function NuevoVolu()
    {
        // Sacamos el dia siguiente
        $diasiguiente = date("Y-m-d", strtotime($this->dia . "+ 1 days"));

        //Creamos una consulta
        $volumetric = Volumetrico::where(['rfc' => $this->empresa]);

        //Vamos a almacenar los datos en la base
        $infovolu = $volumetric->get()->first();

        //Agregamos un nuevo volumetrico
        $volumetric->update([
            'rfc' => $this->empresa,
            'volumetrico.' . $this->dia => $this->formdatavolu,

            //Obtenemos el inicial del siguiente dia
            'volumetrico.' . $diasiguiente . '.IventInicM' => $this->autostickmagna,
            'volumetrico.' . $diasiguiente . '.IventInicP' => $this->autostickpremium,
            'volumetrico.' . $diasiguiente . '.IventInicD' => $this->autostickdiesel,

            //Obtenemos el inicial del cambio de precio
            'volumetrico.' . $this->dia . '-C.IventInicM' => $this->invdetermagna,
            'volumetrico.' . $this->dia . '-C.IventInicP' => $this->invdeterpremium,
            'volumetrico.' . $this->dia . '-C.IventInicD' => $this->invdeterdiesel,

            //Obtenemos el autostick del cambio de precio
            'volumetrico.' . $this->dia . '-C.AutoStickM' => $this->autostickmagna,
            'volumetrico.' . $this->dia . '-C.AutoStickP' => $this->autostickpremium,
            'volumetrico.' . $this->dia . '-C.AutoStickD' => $this->autostickdiesel,

        ], ['upsert' => true]);

        //Agregamos el PDF si este existe
        if (!empty($infovolu['volumetrico.' . $this->dia . '.PDFVolu'])) {
            $volumetric->update([
                'rfc' => $this->empresa,
                'volumetrico.' . $this->dia . '.PDFVolu' => $infovolu['volumetrico.' . $this->dia . '.PDFVolu'],
            ], ['upsert' => true]);
        }

        //Agregamos el PDF CRE si este existe
        if (!empty($infovolu['volumetrico.' . $this->dia . '.PDFCRE'])) {
            $volumetric->update([
                'rfc' => $this->empresa,
                'volumetrico.' . $this->dia . '.PDFCRE' => $infovolu['volumetrico.' . $this->dia . '.PDFCRE'],
            ], ['upsert' => true]);
        }

        //Metodo para la actualizacion del siguiente precio
        $this->ActuDiaSig();

        //Cerramos el modal al terminar
        $this->dispatchBrowserEvent('CerrarVoluData', ["dia" => $this->dia]);

        //Hacemos un refresh a la pagina
        $this->emit('volumrefresh');
        //Emitimos el metodo de refrescar la pagina
        $this->emit('refrashpdfvolu');
    }

    //Metodo para el cambio de precio
    public function CambioPrec()
    {
        // Sacamos el dia siguiente
        $diasiguiente = date("Y-m-d", strtotime($this->dia . "+ 1 days"));

        //Creamos una consulta
        $volumetric = Volumetrico::where(['rfc' => $this->empresa]);

        //Vamos a almacenar los datos en la base
        $infovolu = $volumetric->get()->first();

        $volumetric->update([
            'rfc' => $this->empresa,
            'volumetrico.' . $this->dia . "-C" => $this->formdatavolu,

            //Obtenemos el inicial del siguiente dia
            'volumetrico.' . $diasiguiente . '.IventInicM' => $this->autostickcambmagna,
            'volumetrico.' . $diasiguiente . '.IventInicP' => $this->autostickcambpremium,
            'volumetrico.' . $diasiguiente . '.IventInicD' => $this->autostickcambdiesel,
        ], ['upsert' => true]);

        //Agregamos el PDF si este existe
        if (!empty($infovolu['volumetrico.' . $this->dia . '.PDFVolu'])) {
            $volumetric->update([
                'rfc' => $this->empresa,
                'volumetrico.' . $this->dia . '.PDFVolu' => $infovolu['volumetrico.' . $this->dia . '.PDFVolu'],
            ], ['upsert' => true]);
        }

        //Agregamos el PDF CRE si este existe
        if (!empty($infovolu['volumetrico.' . $this->dia . '.PDFCRE'])) {
            $volumetric->update([
                'rfc' => $this->empresa,
                'volumetrico.' . $this->dia . '.PDFCRE' => $infovolu['volumetrico.' . $this->dia . '.PDFCRE'],
            ], ['upsert' => true]);
        }

        //Metodo para la actualizacion del siguiente precio
        $this->ActuDiaSig();

        //Cerramos el modal al terminar
        $this->dispatchBrowserEvent('CerrarVoluData', ["dia" => $this->dia]);

        //Hacemos un refresh a la pagina
        $this->emit('volumrefresh');
        //Emitimos el metodo de refrescar la pagina
        $this->emit('refrashpdfvolu');
    }

    //Metodo para vaciar las variables del modal
    public function Refresh()
    {
        //Limpiamos las variables
        //Volumetrico **************************************************************************************************************
        //Inventario inicial
        $this->inventinicmagna = "";
        $this->inventinicpremium = "";
        $this->inventinicdiesel = "";

        //Compras
        $this->compramagna = "";
        $this->comprapremium = "";
        $this->compradiesel = "";

        //Compras (Descuento)
        $this->compradescmagna = "";
        $this->compradescpremium = "";
        $this->compradescdiesel = "";

        //Litros vendidos
        $this->litvendmagna = "";
        $this->litvendpremium = "";
        $this->litvenddiesel = "";

        //Precio de compra
        $this->preccompmagna = "";
        $this->preccomppremium = "";
        $this->preccompdiesel = "";

        //Precio venta
        $this->precventmagna = "";
        $this->precventpremium = "";
        $this->precventdiesel = "";

        //Inventario real
        $this->autostickmagna = "";
        $this->autostickpremium = "";
        $this->autostickdiesel = "";

        //Inventario determinado
        $this->invdetermagna = "";
        $this->invdeterpremium = "";
        $this->invdeterdiesel = "";

        //Merma
        $this->mermamagna = "";
        $this->mermapremium = "";
        $this->mermadiesel = "";
        // *************************************************************************************************************************

        //Cambio de precio *********************************************************************************************************
        //Inventario inicial
        $this->inventiniccambmagna = "";
        $this->inventiniccambpremium = "";
        $this->inventiniccambdiesel = "";

        //Compras
        $this->compracambmagna = "";
        $this->compracambpremium = "";
        $this->compracambdiesel = "";

        //Compras (Descuento)
        $this->compracambdescmagna = "";
        $this->compracambdescpremium = "";
        $this->compracambdescdiesel = "";

        //Litros vendidos
        $this->litvendcambmagna = "";
        $this->litvendcambpremium = "";
        $this->litvendcambdiesel = "";

        //Precio de compra
        $this->preccompcambmagna = "";
        $this->preccompcambpremium = "";
        $this->preccompcambdiesel = "";

        //Precio venta
        $this->precventcambmagna = "";
        $this->precventcambpremium = "";
        $this->precventcambdiesel = "";

        //Inventario real
        $this->autostickcambmagna = "";
        $this->autostickcambpremium = "";
        $this->autostickcambdiesel = "";

        //Inventario determinado
        $this->invdetercambmagna = "";
        $this->invdetercambpremium = "";
        $this->invdetercambdiesel = "";

        //Merma
        $this->mermacambmagna = "";
        $this->mermacambpremium = "";
        $this->mermacambdiesel = "";
        // *************************************************************************************************************************

        //Emitimos el metodo de refrescar la pagina
        $this->emit('volumrefresh');
    }

    public function render()
    {
        //Ejecutamos el metodo para obtener la informacion del cliente
        $this->InfoGas();

        return view('livewire.volumedata');
    }
}
