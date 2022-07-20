<?php

namespace App\Http\Livewire;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Models\MetadataE;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class Chequesytransferencias extends Component
{
    use WithFileUploads;
    use WithPagination;
    public Cheques $ajusteCheque; // coneccion al model cheques
    public Cheques $Crear; // enlaza al modelo cheques
    public $datos;
    public float $ajuste;
    public  $users, $name, $email, $user_id, $fecha, $ajuste2, $datos1, $user, $ids=[],$uuid;
    public $cheque,$nom;
    public $importe = "";
    public $condicion;
    public $revisado;
    public $estatus;
    public $impresion;

    ///=======================variables nuevo-cheque=========================///
    public $Nuevo_numcheque, $Nuevo_tipomov, $Nuevo_fecha, $Nuevo_importecheque, $Nuevo_beneficiario,
        $Nuevo_tipoopera, $Nuevo_pdf, $relacionadosUp = [];

    ///======================= fin variables nuevo-cheque====================///

    public $mes;
    public $anio;
    public $todos;
    public $rfcEmpresa;

    protected $paginationTheme = 'bootstrap'; //para dar e estilo numerico al paginador

    public function mount()
    {
        $this->Crear = new Cheques();
        $this->anio = date("Y");
        $this->mes = date("m");

        if (auth()->user()->tipo) {
            $this->rfcEmpresa = '';
        } else {
            $this->rfcEmpresa = auth()->user()->RFC;
        }

        $this->importe = 0.0;
        $this->condicion = '>=';
    }

    public int $perPage = 20;
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /// Actualizar mes
    public function updatingMes()
    {
        $this->resetPage();
    }


    public function updatingImporte()
    {
        $this->resetPage();
    }

    protected $listeners = [
        'chequesRefresh' => '$refresh',
        'mostvincu' => 'mostmovivincu',
        'notivincu' => 'notivinculo',
        'vercheq' => 'vercheque',
        'chequesVi'=> 'chequesVinculos',

    ];

    public function chequesVinculos($rfc, $uuid){

        $this->rfcEmpresa = $rfc;
        $i=MetadataE::where('folioFiscal',$uuid)->first();
       foreach($i->cheques_id as $l){ $this->ids[]= $l; }




    }



    public function mostmovivincu($data)
    {
        $this->todos = 1;
        $this->search = $data['idmovi'];
        $this->rfcEmpresa = $data['empresa'];
    }

    public function notivinculo($id, $rfc)
    {
        $this->todos = 1;
        $this->search = $id;
        $this->rfcEmpresa = $rfc;
    }

    public function vercheque($rfc, $id)
    {
        $this->todos = 1;
        $this->rfcEmpresa = $rfc;
        $this->search = $id;
    }

    protected function rules()
    {
        return [
            'user_id' => '',
            'name' => '',
            'Nuevo_numcheque' => 'required',
            'Nuevo_tipomov' => '',
            'Nuevo_fecha' => '',
            'Nuevo_importecheque' => '',
            'Nuevo_beneficiario' => '',
            'estatus' => '',
            //======== modal ajuste =====//
        ];
    }

    public function render()
    {
        if (Auth::check()) { /// autentica si se incio session
            auth()->user();
        }

        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');


    if($this->ids == ""){

        Session::forget('idnominas');
     Session::forget('rfcnomina');


    }

if ($this->ids){
    $cheque = Cheques::
    whereIn('_id',$this->ids)
    ->where('rfc', $this->rfcEmpresa)
    ->orderBy('fecha', 'desc')
    ->orderBy('updated_at', 'desc')
    ->paginate($this->perPage);

    $this->ids="";


}
        //Condicional para saber si de va a buscar todos los registros o se aplicacran los filtros
        elseif ($this->todos) {
            //Consulta para mostrar todos los registros
            $cheque = Cheques::search($this->search)
                ->where('rfc', $this->rfcEmpresa)
                ->orderBy('fecha', 'desc')
                ->orderBy('updated_at', 'desc')
                ->paginate($this->perPage);
        } else {
            //Convertimos el importe en flotante
            $this->importe = floatval($this->importe);

            //Condicional para saber si se selecciono todos los meses
            if ($this->mes == "00") {
                //Construimos la fecha a consultar

                //Obtenemos el total de dias
                $fechaselect = strtotime($this->anio . "-12" . '-01'); //Numero de dias del mes
                $totaldias = date('t', $fechaselect); //Obtenemos el tota de dias

                //Construimos la fecha inicial y la fecha final
                $fechainic = $this->anio . '-01-01';
                $fechafin = $this->anio . '-12-' . $totaldias;
            } else {
                //Construimos la fecha a consultar

                //Obtenemos el total de dias
                $fechaselect = strtotime($this->anio . "-" . $this->mes . '-01'); //Numero de dias del mes
                $totaldias = date('t', $fechaselect); //Obtenemos el tota de dias

                //Construimos la fecha inicial y la fecha final
                $fechainic = $this->anio . '-' . $this->mes . '-01';
                $fechafin = $this->anio . '-' . $this->mes . '-' . $totaldias;
            }

            //switch para caer en los fitros si no esta seleccionado la opcion de todos
            switch ($this->estatus) {
                case 'sin_conta':
                    $cheque = Cheques::search($this->search)
                        ->where('rfc', $this->rfcEmpresa)
                        ->where('importecheque', $this->condicion, $this->importe)
                        ->whereBetween('fecha',  [$fechainic, $fechafin])
                        ->where('conta', 0)
                        ->orderBy('fecha', 'desc')
                        ->paginate($this->perPage);
                    break;

                case 'sin_revisar':
                    $cheque = Cheques::search($this->search)
                        ->where('rfc', $this->rfcEmpresa)
                        ->where('importecheque', $this->condicion, $this->importe)
                        ->whereBetween('fecha',  [$fechainic, $fechafin])
                        ->where('verificado', 0)
                        ->orderBy('fecha', 'desc')
                        ->paginate($this->perPage);
                    break;

                case 'pendi':
                    $cheque = Cheques::search($this->search)
                        ->where('rfc', $this->rfcEmpresa)
                        ->where('importecheque', $this->condicion, $this->importe)
                        ->whereBetween('fecha',  [$fechainic, $fechafin])
                        ->where('pendi', 1)
                        ->orderBy('fecha', 'desc')
                        ->paginate($this->perPage);
                    break;

                default:
                    $cheque = Cheques::search($this->search)
                        ->where('rfc', $this->rfcEmpresa)
                        ->where('importecheque', $this->condicion, $this->importe)
                        ->whereBetween('fecha',  [$fechainic, $fechafin])
                        ->orderBy('fecha', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate($this->perPage);
                    break;
            }
        }

        if (!empty(auth()->user()->tipo)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas); // obtener el largo del array empresas

            for ($i = 0; $i < $largo; $i++) {
                $rfc = auth()->user()->empresas[$i];
                $e = DB::Table('clientes')
                    ->select('RFC', 'nombre')

                    ->where('RFC', $rfc)

                    ->get();

                foreach ($e as $em) {
                    $emp[] = array($em['RFC'], $em['nombre']);
                }
            }
        } elseif (!empty(auth()->user()->TipoSE)) {
            $e = array();
            $largo = sizeof(auth()->user()->empresas); // obtener el largo del array empresas

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
        } //end if

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

        $anios = range(2014, date('Y'));

        if ($this->revisado) {
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);

            Cheques::where('_id', $this->revisado)->update([
                'verificado' => 1,
                'pendi' => 0,
                'revisado_fecha' => $dt->format('Y-m-d\TH:i:s'),
            ]);

            $this->revisado = '';
            $this->emitTo('chequesytransferencias', 'chequesRefresh');
        }

        if ($this->impresion) {
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);

            Cheques::where('_id', $this->impresion)->update([
                'impresion' => 'on',

            ]);

            $this->impresion = '';
            $this->emitTo('chequesytransferencias', 'chequesRefresh');
        }



        return view('livewire.chequesytransferencias', [
            'colCheques' => $cheque,
            'meses' => $meses,
            'anios' => $anios,
            'empresa' => $this->rfcEmpresa,
            'empresas' => $emp,
            'estatus' => $this->estatus,
            ])
            ->extends('layouts.livewire-layout')
            ->section('content');
    }

    public function buscar()
    {
        $dtz = new DateTimeZone("America/Mexico_City");
        $dt = new DateTime("now", $dtz);
        $rfc = Auth::user()->RFC;
        $anio = $dt->format('Y');
        $cheque = Cheques::search($this->search)
            ->where('rfc', $rfc)
            ->paginate($this->perPage);
        $class = "table nowrap dataTable no-footer"; // clase para la tabla de cheques y tranferencias
        // $this->dispatchBrowserEvent('hola', []);
    }

    public function edit($id)
    {
    }

    public function actualizar()
    {
        $this->emitTo('chequesytransferencia', 'chequesRefresh');
    }

    public function refeshModal()
    {
        $this->emitTo('pdfcheque', 'refreshpdf'); //actualiza la tabla cheques y transferencias
    }

    public function revisado($id)
    {
        $this->emit('uploadrelacionados', $id);
    }

//// metodo pendientes







}/// fin de la clase principal
