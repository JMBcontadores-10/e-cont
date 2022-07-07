<?php

namespace App\Http\Livewire;

use App\Models\ExpedFiscal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Expedientefiscal extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public $anioexpe;
    public $active = 'hidden';
    public $sucursal;
    public $idcomplem;

    //Variables para el formulario
    public $fechapresent;
    public $dataregistr;

    public $listeners = ['refreshexpedi' => '$refresh', 'uploadcomp' => 'uploadcomp'];


    public function mount()
    {
        $this->anioexpe =  date("Y");
    }

    //Metodo para enviar el identificador para subir los acuses
    public function SendDataAcuse($tipo, $empresa, $mes, $anio, $matriz, $nombre)
    {
        //Obtenemos los argumentos del metodo y costruimos el identificador
        $identacuse = $tipo . '&' . $empresa . '&' . $mes . '&' . $anio . '&' . $matriz . '&' . $nombre; //Los separamos con un caracter especial para identificar la separacion de cada dato

        //Emitimos el resultado al componente que se encargara de subr los acuses
        $this->emit('recidataacuse', $identacuse);

        //Almacenamos el identificador de los complementarios
        $this->idcomplem = $mes;
    }

    //Metodo para capturar la fecha de presentacion
    public function FechaPresent($Tipo, $Empresa, $Mes, $Anio)
    {
        //Condicional para saber si se selecciono una fecha
        if (!empty($this->fechapresent)) {
            //Lo alamacenamos en la base de datos
            ExpedFiscal::where('rfc', $Empresa)
                ->update([
                    'ExpedFisc.' . $Anio . '.' . $Tipo . '.' . $Mes . '.Declaracion' => $this->fechapresent,
                ], ['upsert' => true]);

            //Limpiamos los el registro de la fecha de presentacio
            $this->fechapresent = null;

            //Descomponesmos la cadena enviada (a un arreglo)
            $mesdescompuesto = explode('_', $Mes);

            //Emitimos una accion de JS para no cerrar los complementarios
            $this->dispatchBrowserEvent('noclosecomple', ['Mes' => $mesdescompuesto[0], 'TipoComp' => $mesdescompuesto[2] ?? $mesdescompuesto[1] ?? ""]);
        }
    }

    public function render()
    {
        //Abrimos los complementarios en caso de que se haya seleccionado uno
        if (!empty($this->idcomplem)) {
            //Descomponesmos la cadena enviada (a un arreglo)
            $mesdescompuesto = explode('_', $this->idcomplem);

            //Emitimos una accion de JS para mantener los complementarios abiertos
            $this->dispatchBrowserEvent('noclosecomple', ['Mes' => $mesdescompuesto[0], 'TipoComp' => $mesdescompuesto[2] ?? $mesdescompuesto[1] ?? ""]);
        }

        //Condicional para saber si se selecciono una empresa ara mostras los elementos de la tabla
        if (!empty($this->rfcEmpresa)) {
            $this->active = null; //Escondemos los elementos cuando no se selecciono una empresa
        } else {
            $this->active = 'hidden'; //Mostramos los elementos cuando se selecciono una empresa
        }

        //Condicional para obtener el tipo de usuario y almacenar las empresas viculadas de estas
        if (!empty(auth()->user()->tipo)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        //Arreglo (rango) del año actual al 2014
        $anios = range(2014, date('Y'));

        //Hacemos una consulta de la empresa para obtener la sucursal
        $this->infoempre = User::where('RFC', $this->rfcEmpresa)->get()->first();

        //Condicional para limpiar la variable de sucursal
        if (empty($this->infoempre['Sucursales'])) {
            $this->sucursal = "";
        }

        return view('livewire.expedientefiscal', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'anios' => $anios])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
