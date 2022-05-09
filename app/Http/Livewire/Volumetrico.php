<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Volumetrico as VolumetricoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Week;

class Volumetrico extends Component
{
    //Variables globales
    public $rfcEmpresa;
    public $active = "hidden";

    //Variables para la seccion del calendario
    public $mescal;
    public $aniocal;

    //Variables para la informacion del cliente
    public $Magna;
    public $Premium;
    public $Diesel;

    //Variables para el modal de volumetricos
    public $fechainic;
    public $fechafin;

    //Variables historico
    public $historicomagna = [];
    public $historicopremium = [];
    public $historicodiesel = [];

    //Escuchar los emitidos de otros componentes
    public $listeners = ['volumrefresh' => '$refresh'];


    //Metodo para consultar historico
    public function ConsulHistoric()
    {
        //Condicional para verificar que las fechas estan puestas
        if (!empty($this->fechainic) && !empty($this->fechafin)) {
            //Almacenamos la consulta de todos los volumetricos para realizar el filtro
            $historico = VolumetricoModel::where('rfc', $this->rfcEmpresa)
                ->get()->first();

            //Separamos los datos por combustibles

            //Magna
            if ($this->Magna == 1) {
                //Variables de contenedor
                $volumetricomagna = "";
                $rowvolumetricomagna = array();

                //Ciclo para pasar por las fechas
                for ($i = $this->fechainic; $i <= $this->fechafin; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
                    //Volumetrico

                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.Fecha'] . '</td>';

                        //Inv. Inicial
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.IventInicM'] . '</td>';

                        //Compra
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.CompraM'] . '</td>';

                        //Lit. Vendidos
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.LitVendM'] . '</td>';

                        //Prec. Compra
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.PrecCompM'] . '</td>';

                        //Prec. Venta
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.PrecVentM'] . '</td>';

                        //Autostick
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.AutoStickM'] . '</td>';

                        //Inv. Determinado
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.InvDeterM'] . '</td>';

                        //Merma
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '.MermaM'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricomagna[] =  '<tr>' . $volumetricomagna . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricomagna = "";
                    }

                    //Cambio de precio
                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '-C.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.Fecha'] . ".1" . '</td>';

                        //Inv. Inicial
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.IventInicM'] . '</td>';

                        //Compra
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.CompraM'] . '</td>';

                        //Lit. Vendidos
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.LitVendM'] . '</td>';

                        //Prec. Compra 
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecCompM'] . '</td>';

                        //Prec. Venta
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecVentM'] . '</td>';

                        //Autostick
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.AutoStickM'] . '</td>';

                        //Inv. Determinado
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.InvDeterM'] . '</td>';

                        //Merma
                        $volumetricomagna .= '<td>' . $historico['volumetrico.' . $i . '-C.MermaM'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricomagna[] =  '<tr>' . $volumetricomagna . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricomagna = "";
                    }
                }

                $this->historicomagna = $rowvolumetricomagna;
            }

            //Premium
            if ($this->Premium == 1) {
                //Variables de contenedor
                $volumetricopremium = "";
                $rowvolumetricopremium = array();

                //Ciclo para pasar por las fechas
                for ($i = $this->fechainic; $i <= $this->fechafin; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
                    //Volumetrico

                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.Fecha'] . '</td>';

                        //Inv. Inicial
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.IventInicP'] . '</td>';

                        //Compra
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.CompraP'] . '</td>';

                        //Lit. Vendidos
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.LitVendP'] . '</td>';

                        //Prec. Compra
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.PrecCompP'] . '</td>';

                        //Prec. Venta
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.PrecVentP'] . '</td>';

                        //Autostick
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.AutoStickP'] . '</td>';

                        //Inv. Determinado
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.InvDeterP'] . '</td>';

                        //Merma
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '.MermaP'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricopremium[] =  '<tr>' . $volumetricopremium . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricopremium = "";
                    }

                    //Cambio de precio
                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '-C.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.Fecha'] . '.1' . '</td>';

                        //Inv. Inicial
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.IventInicP'] . '</td>';

                        //Compra
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.CompraP'] . '</td>';

                        //Lit. Vendidos
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.LitVendP'] . '</td>';

                        //Prec. Venta
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecCompP'] . '</td>';

                        //Prec. Venta
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecVentP'] . '</td>';

                        //Autostick
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.AutoStickP'] . '</td>';

                        //Inv. Determinado
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.InvDeterP'] . '</td>';

                        //Merma
                        $volumetricopremium .= '<td>' . $historico['volumetrico.' . $i . '-C.MermaP'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricopremium[] =  '<tr>' . $volumetricopremium . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricopremium = "";
                    }
                }

                $this->historicopremium = $rowvolumetricopremium;
            }

            //Diesel
            if ($this->Diesel == 1) {
                //Variables de contenedor
                $volumetricodiesel = "";
                $rowvolumetricodiesel = array();

                //Ciclo para pasar por las fechas
                for ($i = $this->fechainic; $i <= $this->fechafin; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
                    //Volumetrico

                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.Fecha'] . '</td>';

                        //Inv. Inicial
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.IventInicD'] . '</td>';

                        //Compra
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.CompraD'] . '</td>';

                        //Lit. Vendidos
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.LitVendD'] . '</td>';

                        //Prec. Venta
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.PrecCompD'] . '</td>';

                        //Prec. Venta
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.PrecVentD'] . '</td>';

                        //Autostick
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.AutoStickD'] . '</td>';

                        //Inv. Determinado
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.InvDeterD'] . '</td>';

                        //Merma
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '.MermaD'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricodiesel[] =  '<tr>' . $volumetricodiesel . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricodiesel = "";
                    }

                    //Cambio de precio
                    //Condicional para saber si exite la fecha (el identificador)
                    if (!empty($historico['volumetrico.' . $i . '-C.Fecha'])) {
                        //Almacenamos los datos en la primer variable
                        //Fecha
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.Fecha'] . ".1" . '</td>';

                        //Inv. Inicial
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.IventInicD'] . '</td>';

                        //Compra
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.CompraD'] . '</td>';

                        //Lit. Vendidos
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.LitVendD'] . '</td>';

                        //Prec. Venta
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecCompD'] . '</td>';

                        //Prec. Venta
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.PrecVentD'] . '</td>';

                        //Autostick
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.AutoStickD'] . '</td>';

                        //Inv. Determinado
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.InvDeterD'] . '</td>';

                        //Merma
                        $volumetricodiesel .= '<td>' . $historico['volumetrico.' . $i . '-C.MermaD'] . '</td>';

                        //Alamcenamos los datos en el arreglo
                        $rowvolumetricodiesel[] =  '<tr>' . $volumetricodiesel . '</tr>';

                        //Vaciamos la variable para almacenar las otras
                        $volumetricodiesel = "";
                    }
                }

                $this->historicodiesel = $rowvolumetricodiesel;
            }

            //Activamos los botones de exportacion
            $this->active = null;
        };
    }

    //Metodo para crear el calendario
    public function Calendario()
    {
        //Calendario
        //Obtenemos la zona horaria
        date_default_timezone_set('America/Mexico_City');

        //Condicional para saber si el mes y año tiene algun valor
        if (isset($this->aniocal) || isset($this->mescal)) {
            //Si tiene algo obtenemos el valor de las variables
            $ym = $this->aniocal . "-" . $this->mescal;
        } else {
            //De lo contario no vamos al mes y año actual
            $ym = date('Y-m');
        }

        //Establecemos el inicio del calendario
        $timestamp = strtotime($ym . '-01');
        if ($timestamp === false) {
            $ym = date('Y-m');
            $timestamp = strtotime($ym . '-01');
        }

        //Obtenemos el dia de hoy
        $today = date('Y-m-d', time());

        //Obtenemos lo dias que tiene el mes
        $day_count = date('t', $timestamp);

        // 0:Sun 1:Mon 2:Tue ...
        $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));

        //Variables para la creacion del calendario
        $weeks = array();
        $week = '';

        //Campos vacios
        $week .= str_repeat('<td></td>', $str);

        //Vamos a hacer una consulta a los volumetricos para obtener
        $voludata = VolumetricoModel::where('rfc', $this->rfcEmpresa)
            ->get()
            ->first();

        //Ciclo for para llenar los campos con los dias que le pertenece
        for ($day = 01; $day <= $day_count; $day++, $str++) {
            //Formamos la fecha completa
            if ($day < 10) {
                $date = $ym . '-0' . $day;
            } else {
                $date = $ym . '-' . $day;
            }

            //Switch para marcar el dia de hoy
            switch ($date) {
                case $today:
                    $week .= '<td class="hoy" style="color:white">' . $day;

                    if ($this->rfcEmpresa) {
                        //Mensaje de cambio de precio
                        if (!empty($voludata['volumetrico.' . $date . '-C.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '-C.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '-C.InvDeterD'])) {
                            $week .= '<br> Cambio de precio <br>';
                        } else {
                            $week .=  "<br><br>";
                        }

                        //Condicional para limitar la captura
                        if (auth()->user()->tipo) {
                            //Condicional para marcar que se agrego un volumetrico
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                //Boton para insertar o editar datos
                                $week .= '<a class="selectfecha icons fas fa-edit content_true" data-toggle="modal" 
                                data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                                data-keyboard="false" fecha="' . $date . '"></a> &nbsp;&nbsp;';
                            } else {
                                //Boton para insertar o editar datos
                                $week .= '<a class="selectfecha icons fas fa-edit" data-toggle="modal" 
                                data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                                data-keyboard="false" fecha="' . $date . '"></a> &nbsp;&nbsp;';
                            }

                            if (!empty($voludata['volumetrico.' . $date . '.PDFCRE'])) {
                                //Boton para subir archivos de CRE
                                $week .= '<a class="selectfechacre iconscre demo-icon icon-cre content_true_cre" data-toggle="modal" 
                                data-target="#volupdfcremodal' . $date . '" data-backdrop="static" data-keyboard="false" 
                                fecha="' . $date . '">&#xe801;</a> &nbsp;&nbsp;';
                            } else {
                                //Boton para subir archivos de CRE
                                $week .= '<a class="selectfechacre iconscre demo-icon icon-cre" data-toggle="modal" 
                                data-target="#volupdfcremodal' . $date . '" data-backdrop="static" data-keyboard="false" 
                                fecha="' . $date . '">&#xe801;</a> &nbsp;&nbsp;';
                            }
                        }

                        //Boton para mostrar el PDF
                        if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                            $week .= '<a class="selectfecha icons fas fa-file-pdf content_true_pdf" data-toggle="modal" 
                            data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                            data-keyboard="false" fecha="' . $date . '"></a>';
                        } else {
                            $week .= '<a class="selectfecha icons fas fa-file-pdf" data-toggle="modal" 
                            data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                            data-keyboard="false" fecha="' . $date . '"></a>';
                        }
                    }
                    break;
                default:
                    $week .= '<td>' . $day;

                    if ($this->rfcEmpresa) {
                        //Mensaje de cambio de precio
                        if (!empty($voludata['volumetrico.' . $date . '-C.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '-C.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '-C.InvDeterD'])) {
                            $week .= '<br> Cambio de precio <br>';
                        } else {
                            $week .=  "<br><br>";
                        }

                        //Condicional para limitar la captura
                        if (auth()->user()->tipo) {
                            //Condicional para marcar que se agrego un volumetrico
                            if (!empty($voludata['volumetrico.' . $date . '.InvDeterM']) || !empty($voludata['volumetrico.' . $date . '.InvDeterP']) || !empty($voludata['volumetrico.' . $date . '.InvDeterD'])) {
                                //Boton para insertar o editar datos
                                $week .= '<a class="selectfecha icons fas fa-edit content_true" data-toggle="modal" 
                                data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                                data-keyboard="false" fecha="' . $date . '"></a> &nbsp;&nbsp;';
                            } else {
                                //Boton para insertar o editar datos
                                $week .= '<a class="selectfecha icons fas fa-edit" data-toggle="modal" 
                                data-target="#volucaptumodal' . $date . '" data-backdrop="static"
                                data-keyboard="false" fecha="' . $date . '"></a> &nbsp;&nbsp;';
                            }

                            if (!empty($voludata['volumetrico.' . $date . '.PDFCRE'])) {
                                //Boton para subir archivos de CRE
                                $week .= '<a class="selectfechacre iconscre demo-icon icon-cre content_true_cre" data-toggle="modal" 
                                data-target="#volupdfcremodal' . $date . '" data-backdrop="static" data-keyboard="false" 
                                fecha="' . $date . '">&#xe801;</a> &nbsp;&nbsp;';
                            } else {
                                //Boton para subir archivos de CRE
                                $week .= '<a class="selectfechacre iconscre demo-icon icon-cre" data-toggle="modal" 
                                data-target="#volupdfcremodal' . $date . '" data-backdrop="static" data-keyboard="false" 
                                fecha="' . $date . '">&#xe801;</a> &nbsp;&nbsp;';
                            }
                        }

                        //Boton para mostrar el PDF
                        if (!empty($voludata['volumetrico.' . $date . '.PDFVolu'])) {
                            $week .= '<a class="selectfecha icons fas fa-file-pdf content_true_pdf" data-toggle="modal" 
                            data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                            data-keyboard="false" fecha="' . $date . '"></a>';
                        } else {
                            $week .= '<a class="selectfecha icons fas fa-file-pdf" data-toggle="modal" 
                            data-target="#volupdfmodal' . $date . '" data-backdrop="static"
                            data-keyboard="false" fecha="' . $date . '"></a>';
                        }
                    }
                    break;
            }

            //Cerramos la celda que pertenece el dia
            $week .= '</td>';

            //Condicional para saber si llegamos el final de la semana o mes
            if ($str % 7 == 6 || $day == $day_count) {

                //Condicion para saber si el dia pertenece al final de los dias contados
                if ($day == $day_count) {
                    //Agregamos un campo vacio
                    $week .= str_repeat('<td></td>', 6 - ($str % 7));
                }

                //Todas las semanas las agregas en un arreglo
                $weeks[] = '<tr>' . $week . '</tr>';

                //Limpiamos la variable para agregar ora semana
                $week = '';
            }
        }

        //Retornamos el valor de las semanas
        return $weeks;
    }

    //Metodo para refrescar la pagina
    public function Refresh()
    {
        //Limpiamos las fechas
        $this->fechainic = "";
        $this->fechafin = "";

        //Limpiamos las tablas
        $this->historicomagna = [];
        $this->historicopremium = [];
        $this->historicodiesel = [];

        //Ocultamos los botonos de exportacion
        $this->active = "hidden";

        //Refrescamos la pagina
        $this->emit("volumrefresh");
    }

    //Metodo para preparar procesos antes de iniciar
    public function mount()
    {
        //Condicional para saber si es una cuenta de contador o empresa
        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

        //El mes y año iniciamos con los de hoy (calendario)
        $this->aniocal = date("Y");
        $this->mescal = date("m");


        //Agregamos un valor inicial
        $this->HistoryResul = "";
    }

    public function render()
    {
        //Obtenemos el valor del metodo del calendario
        $weeks = $this->Calendario();

        //Arreglo de los meses
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );

        //Arreglo (rango) del año actual al 2014
        $anios = range(2014, date('Y'));

        //Condicional para obtener el tipo de usuario y almacenar las empresas viculadas de estas
        if (!empty(auth()->user()->tipo)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->where('gas', "1")
                    ->get();

                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else if (!empty(auth()->user()->TipoSE)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas);
            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];

                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')
                    ->where('RFC', $rfc)
                    ->where('gas', "1")
                    ->get();


                foreach ($e as $em)
                    $emp[] = array($em['RFC'], $em['nombre']);
            }
        } else {
            $emp = '';
        }

        //Hacemos una consulta de la empresa para saber que datos vamos a mostrar
        $infogas = User::where('RFC', $this->rfcEmpresa)->get();

        //Obtenemos los datos requeridos
        if (count($infogas) > 0) {
            //Recorremos la consulta para obtener los datos
            foreach ($infogas as $datagas) {
                //Obtenemos los tipo de combustible que maneja las gasolineras
                $this->Magna = $datagas->TipoM;
                $this->Premium = $datagas->TipoP;
                $this->Diesel = $datagas->TipoD;
            }
        } else {
            //De lo contrario los declaramos vacios
            $this->Magna = '';
            $this->Premium = '';
            $this->Diesel = '';
        }

        return view('livewire.volumetrico', ['empresa' => $this->rfcEmpresa, 'empresas' => $emp, 'weeks' => $weeks, 'meses' => $meses, 'anios' => $anios])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
