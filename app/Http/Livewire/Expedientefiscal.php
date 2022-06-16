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

    //Variables para el formulario
    public $fechapresent;
    public $dataregistr;

    public $listeners = ['refreshexpedi' => '$refresh', 'uploadcomp' => 'uploadcomp'];


    public function mount()
    {
        $this->anioexpe =  date("Y");
    }

    //Metodo para no cerrar la fila de complementos
    public function uploadcomp($Mes)
    {
        //Vamos a buscar si existe los caracteres "_C" en el mes para arrojar una funcion JS
        $filtromes = strpos($Mes, '_C');

        if ($filtromes !== false) {
            //Aqui llamamos a la funcion JS
            $this->dispatchBrowserEvent('addcomplement', ['idfecha' => $Mes]);
        } else {
            //Aqui llamamos a la funcion JS
            $this->dispatchBrowserEvent('addcomplement', ['idfecha' => "0"]);
        }
    }

    //Metodo para enviar el identificador para subir los acuses
    public function SendDataAcuse($tipo, $empresa, $mes, $anio, $matriz, $nombre)
    {
        //Obtenemos los argumentos del metodo y costruimos el identificador
        $identacuse = $tipo . '&' . $empresa . '&' . $mes . '&' . $anio . '&' . $matriz . '&' . $nombre; //Los separamos con un caracter especial para identificar la separacion de cada dato

        //Emitimos el resultado al componente que se encargara de subr los acuses
        $this->emit('recidataacuse', $identacuse);

        //Vamos a buscar si existe los caracteres "_C" en el mes para arrojar una funcion JS
        $filtromes = strpos($mes, '_C');

        if ($filtromes !== false) {
            //Aqui llamamos a la funcion JS
            $this->dispatchBrowserEvent('addcomplement', ['idfecha' => $mes]);
        } else {
            //Aqui llamamos a la funcion JS
            $this->dispatchBrowserEvent('addcomplement', ['idfecha' => "0"]);
        }
    }

    //Metodo para capturar la fecha de presentacion
    public function FechaPresent()
    {
        //Condicional para saber si se selecciono una fecha
        if (!empty($this->fechapresent)) {
            //Descomponemos los valores para alamcenar en la base de datos
            $dataexpdecom = explode("-", $this->dataregistr);

            //Obtenemos los datos del arreglo creado
            //Tipo
            $Tipo = $dataexpdecom[0];

            //Empresa
            $Empresa = $dataexpdecom[1];

            //Mes
            $Mes = $dataexpdecom[2];

            //Año
            $Año = $dataexpdecom[3];

            //Lo alamacenamos en la base de datos
            ExpedFiscal::where('rfc', $Empresa)
                ->update([
                    'ExpedFisc.' . $Año . '.' . $Tipo . '.' . $Mes . '.Declaracion' => $this->fechapresent,
                ], ['upsert' => true]);

            //Limpiamos los el registro de la fecha de presentacio
            $this->fechapresent = null;

            //Vamos a buscar si existe los caracteres "_C" en el mes para arrojar una funcion JS
            $filtromes = strpos($Mes, '_C');

            if ($filtromes !== false) {
                //Aqui llamamos a la funcion JS
                $this->dispatchBrowserEvent('addcomplement', ['idfecha' => $Mes]);
            } else {
                //Aqui llamamos a la funcion JS
                $this->dispatchBrowserEvent('addcomplement', ['idfecha' => "0"]);
            }
        }
    }

    public function render()
    {
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
