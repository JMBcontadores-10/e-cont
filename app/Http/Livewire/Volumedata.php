<?php

namespace App\Http\Livewire;

use App\Models\Volumetrico;
use Livewire\Component;

class Volumedata extends Component
{
    //Variables globales
    public $dia;
    public $empresa;

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

    //Litros vendidos
    public $litvendmagna;
    public $litvendpremium;
    public $litvenddiesel;

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

    //Litros vendidos
    public $litvendcambmagna;
    public $litvendcambpremium;
    public $litvendcambdiesel;

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
    //****************************************************************************************************************************************************


    public $listeners = ['Volumdata' => 'Volumdata'];

    public function Volumdata()
    {
        //Hacemos una consulta
        $infovolu = Volumetrico::where(['rfc' => $this->empresa])->get()->first();

        if ($infovolu) {
            //Insertamos los datos en los campos necesarios
            //Vomumetricos *********************************************************************************************************

            //Magna
            $this->inventinicmagna = $infovolu['volumetrico.' . $this->dia . '.IventInicM'];
            $this->litvendmagna = $infovolu['volumetrico.' . $this->dia . '.LitVendM'];
            $this->compramagna = $infovolu['volumetrico.' . $this->dia . '.CompraM'];
            $this->precventmagna = $infovolu['volumetrico.' . $this->dia . '.PrecVentM'];
            $this->autostickmagna = $infovolu['volumetrico.' . $this->dia . '.AutoStickM'];
            $this->invdetermagna = $infovolu['volumetrico.' . $this->dia . '.InvDeterM'];

            //Premium
            $this->inventinicpremium = $infovolu['volumetrico.' . $this->dia . '.IventInicP'];
            $this->litvendpremium = $infovolu['volumetrico.' . $this->dia . '.LitVendP'];
            $this->comprapremium = $infovolu['volumetrico.' . $this->dia . '.CompraP'];
            $this->precventpremium = $infovolu['volumetrico.' . $this->dia . '.PrecVentP'];
            $this->autostickpremium = $infovolu['volumetrico.' . $this->dia . '.AutoStickP'];
            $this->invdeterpremium = $infovolu['volumetrico.' . $this->dia . '.InvDeterP'];

            //Diesel
            $this->inventinicdiesel = $infovolu['volumetrico.' . $this->dia . '.IventInicD'];
            $this->litvenddiesel = $infovolu['volumetrico.' . $this->dia . '.LitVendD'];
            $this->compradiesel = $infovolu['volumetrico.' . $this->dia . '.CompraD'];
            $this->precventdiesel = $infovolu['volumetrico.' . $this->dia . '.PrecVentD'];
            $this->autostickdiesel = $infovolu['volumetrico.' . $this->dia . '.AutoStickD'];
            $this->invdeterdiesel = $infovolu['volumetrico.' . $this->dia . '.InvDeterD'];

            //**********************************************************************************************************************

            //Cambio de precio *****************************************************************************************************

            //Magna
            $this->inventiniccambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicM'];
            $this->litvendcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendM'];
            $this->compracambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.CompraM'];
            $this->precventcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentM'];
            $this->autostickcambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickM'];
            $this->invdetercambmagna = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterM'];

            //Premium
            $this->inventiniccambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicP'];
            $this->litvendcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendP'];
            $this->compracambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.CompraP'];
            $this->precventcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentP'];
            $this->autostickcambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickP'];
            $this->invdetercambpremium = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterP'];

            //Diesel
            $this->inventiniccambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.IventInicD'];
            $this->litvendcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.LitVendD'];
            $this->compracambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.CompraD'];
            $this->precventcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.PrecVentD'];
            $this->autostickcambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.AutoStickD'];
            $this->invdetercambdiesel = $infovolu['volumetrico.' . $this->dia . "-C" . '.InvDeterD'];

            //**********************************************************************************************************************
        }
    }

    //Metodo para actualizar el inventario determinado del dia siguiente
    public function ActuInvenDeter($diasiguiente)
    {
        //Hacemos una consulta
        $infovolu = Volumetrico::where(['rfc' => $this->empresa])->get()->first();

        //Si existe datos del dia siguiente se actualiza con los capturados en el anterior (Inventario desterminado)
        if ($infovolu) {
            //Magna
            $inventinicmagna = $infovolu['volumetrico.' . $diasiguiente . '.IventInicM'];
            $litvendmagna = $infovolu['volumetrico.' . $diasiguiente . '.LitVendM'];
            $compramagna = $infovolu['volumetrico.' . $diasiguiente . '.CompraM'];

            //Premium
            $inventinicpremium = $infovolu['volumetrico.' . $diasiguiente . '.IventInicP'];
            $litvendpremium = $infovolu['volumetrico.' . $diasiguiente . '.LitVendP'];
            $comprapremium = $infovolu['volumetrico.' . $diasiguiente . '.CompraP'];

            //Diesel
            $inventinicdiesel = $infovolu['volumetrico.' . $diasiguiente . '.IventInicD'];
            $litvenddiesel = $infovolu['volumetrico.' . $diasiguiente . '.LitVendD'];
            $compradiesel = $infovolu['volumetrico.' . $diasiguiente . '.CompraD'];

            //Magna
            //Condicional para saber si existe datos
            if (!empty($inventinicmagna) && !empty($litvendmagna) && !empty($compramagna)) {
                //Sacamos el inventario determinado
                $inventdertermarga = (floatval($inventinicmagna) + floatval($compramagna)) - floatval($litvendmagna);
            }

            //Premium
            //Condicional para saber si existe datos
            if (!empty($inventinicpremium) && !empty($litvendpremium) && !empty($comprapremium)) {
                //Sacamos el inventario determinado
                $inventderterpremium = (floatval($inventinicpremium) + floatval($comprapremium)) - floatval($litvendpremium);
            }

            //Diesel
            //Condicional para saber si existe datos
            if (!empty($inventinicdiesel) && !empty($litvenddiesel) && !empty($compradiesel)) {
                //Sacamos el inventario determinado
                $inventderterdiesel = (floatval($inventinicdiesel) + floatval($compradiesel)) - floatval($litvenddiesel);
            }

            //Condicional si existen datos en las variables
            if (!empty($inventdertermarga) || !empty($inventderterpremium) || !empty($inventderterdiesel)) {
                //Buscamos el RFC de la empresa
                Volumetrico::where(['rfc' => $this->empresa])
                    ->update([
                        //Actualizamos el inventario determinado
                        'volumetrico.' . $diasiguiente . '.InvDeterM' => strval(round($inventdertermarga, 2)),
                        'volumetrico.' . $diasiguiente . '.InvDeterP' => strval(round($inventderterpremium, 2)),
                        'volumetrico.' . $diasiguiente . '.InvDeterD' => strval(round($inventderterdiesel, 2)),

                    ], ['upsert' => true]);
            }
        }
    }

    //Metodo para almacenar/editar un volumetrico
    public function NuevoVolu()
    {
        //Sacamos el dia siguiente
        $diasiguiente = date("Y-m-d", strtotime($this->dia . "+ 1 days"));

        //Buscamos el RFC de la empresa
        Volumetrico::where(['rfc' => $this->empresa])
            ->update([

                'rfc' => $this->empresa,
                //Inventario inicial
                'volumetrico.' . $this->dia . '.IventInicM' => $this->inventinicmagna,
                'volumetrico.' . $this->dia . '.IventInicP' => $this->inventinicpremium,
                'volumetrico.' . $this->dia . '.IventInicD' => $this->inventinicdiesel,

                //Compras
                'volumetrico.' . $this->dia . '.CompraM' => $this->compramagna,
                'volumetrico.' . $this->dia . '.CompraP' => $this->comprapremium,
                'volumetrico.' . $this->dia . '.CompraD' => $this->compradiesel,

                //Litros vendidos
                'volumetrico.' . $this->dia . '.LitVendM' => $this->litvendmagna,
                'volumetrico.' . $this->dia . '.LitVendP' => $this->litvendpremium,
                'volumetrico.' . $this->dia . '.LitVendD' => $this->litvenddiesel,

                //Precio venta
                'volumetrico.' . $this->dia . '.PrecVentM' => $this->precventmagna,
                'volumetrico.' . $this->dia . '.PrecVentP' => $this->precventpremium,
                'volumetrico.' . $this->dia . '.PrecVentD' => $this->precventdiesel,

                //Inventario real
                'volumetrico.' . $this->dia . '.AutoStickM' => $this->autostickmagna,
                'volumetrico.' . $this->dia . '.AutoStickP' => $this->autostickpremium,
                'volumetrico.' . $this->dia . '.AutoStickD' => $this->autostickdiesel,

                //Inventario determinado
                'volumetrico.' . $this->dia . '.InvDeterM' => $this->invdetermagna,
                'volumetrico.' . $this->dia . '.InvDeterP' => $this->invdeterpremium,
                'volumetrico.' . $this->dia . '.InvDeterD' => $this->invdeterdiesel,

                //Obtenemos el inicial del siguiente dia
                'volumetrico.' . $diasiguiente . '.IventInicM' => $this->autostickmagna,
                'volumetrico.' . $diasiguiente . '.IventInicP' => $this->autostickpremium,
                'volumetrico.' . $diasiguiente . '.IventInicD' => $this->autostickdiesel,

            ], ['upsert' => true]);

        //Modificamos el dia siguiente
        $this->ActuInvenDeter($diasiguiente);

        //Emitimos el cierre de la modal
        $this->dispatchBrowserEvent('SuccessVolum', []);
    }

    //Metodo para el cambio de precio
    public function CambioPrec()
    {
        //Sacamos el dia siguiente
        $diasiguiente = date("Y-m-d", strtotime($this->dia . "+ 1 days"));

        //Buscamos el RFC de la empresa
        Volumetrico::where(['rfc' => $this->empresa])
            ->update([

                'rfc' => $this->empresa,
                //Inventario inicial
                'volumetrico.' . $this->dia . "-C" . '.IventInicM' => $this->inventiniccambmagna,
                'volumetrico.' . $this->dia . "-C" . '.IventInicP' => $this->inventiniccambpremium,
                'volumetrico.' . $this->dia . "-C" . '.IventInicD' => $this->inventiniccambdiesel,

                //Compras
                'volumetrico.' . $this->dia . "-C" . '.CompraM' => $this->compracambmagna,
                'volumetrico.' . $this->dia . "-C" . '.CompraP' => $this->compracambpremium,
                'volumetrico.' . $this->dia . "-C" . '.CompraD' => $this->compracambdiesel,

                //Litros vendidos
                'volumetrico.' . $this->dia . "-C" . '.LitVendM' => $this->litvendcambmagna,
                'volumetrico.' . $this->dia . "-C" . '.LitVendP' => $this->litvendcambpremium,
                'volumetrico.' . $this->dia . "-C" . '.LitVendD' => $this->litvendcambdiesel,

                //Precio venta
                'volumetrico.' . $this->dia . "-C" . '.PrecVentM' => $this->precventcambmagna,
                'volumetrico.' . $this->dia . "-C" . '.PrecVentP' => $this->precventcambpremium,
                'volumetrico.' . $this->dia . "-C" . '.PrecVentD' => $this->precventcambdiesel,

                //Inventario real
                'volumetrico.' . $this->dia . "-C" . '.AutoStickM' => $this->autostickcambmagna,
                'volumetrico.' . $this->dia . "-C" . '.AutoStickP' => $this->autostickcambpremium,
                'volumetrico.' . $this->dia . "-C" . '.AutoStickD' => $this->autostickcambdiesel,

                //Inventario determinado
                'volumetrico.' . $this->dia . "-C" . '.InvDeterM' => $this->invdetercambmagna,
                'volumetrico.' . $this->dia . "-C" . '.InvDeterP' => $this->invdetercambpremium,
                'volumetrico.' . $this->dia . "-C" . '.InvDeterD' => $this->invdetercambdiesel,

                //Obtenemos el inicial del siguiente dia
                'volumetrico.' . $diasiguiente . '.IventInicM' => $this->autostickcambmagna,
                'volumetrico.' . $diasiguiente . '.IventInicP' => $this->autostickcambpremium,
                'volumetrico.' . $diasiguiente . '.IventInicD' => $this->autostickcambdiesel,

            ], ['upsert' => true]);


        //Modificamos el dia siguiente
        $this->ActuInvenDeter($diasiguiente);

        //Emitimos el cierre de la modal
        $this->dispatchBrowserEvent('SuccessVolum', []);
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

        //Litros vendidos
        $this->litvendmagna = "";
        $this->litvendpremium = "";
        $this->litvenddiesel = "";

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

        //Litros vendidos
        $this->litvendcambmagna = "";
        $this->litvendcambpremium = "";
        $this->litvendcambdiesel = "";

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
        // *************************************************************************************************************************

        $this->emit('volumrefresh'); //Emitimos el metodo de refrescar la pagina
    }

    public function render()
    {
        return view('livewire.volumedata');
    }
}
