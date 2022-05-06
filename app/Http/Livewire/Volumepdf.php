<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use App\Models\Volumetrico;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Volumepdf extends Component
{
    //Variables globales
    public $dia;
    public $empresa;

    protected $listeners = ['refrashpdfvolu' => '$refresh'];

    public function PDFVolu(Request $r, $id)
    {
        //Comprueba si el nombre recibido sea "volupdf"
        if ($r->hasFile('volupdf')) {
            $file = $r->file('volupdf');
            $filename = $file->getClientOriginalName(); //Obtiene el nombre original de archivo

            //Descomponemos el id para obtener los datos enviados
            $iddescompuestos = explode("&", $id);

            //RFC 
            $RFC = $iddescompuestos[1];

            //Fecha
            $Fecha = $iddescompuestos[0];

            //Datos para nombrar el archivo
            $dtz = new DateTimeZone("America/Mexico_City");
            $dt = new DateTime("now", $dtz);
            $Id = $dt->format('Y\Hh\Mi\SsA');
            $Id2 = $dt->format('d');
            $nombreArchivo = preg_replace('/[^A-z0-9.-]+/', '', $filename);
            $anio = $dt->format('Y');
            $dateValue = strtotime($Fecha);
            $anio = date('Y', $dateValue);
            $mesfPago = date('m', $dateValue);
            $mesActual = date('m');

            $espa = new Cheques(); //Vamos a ocupas un metodo para 

            //Nombramos al archivo
            $renameFile = $Id2 . $espa->fecha_es($mesActual) . $Id . "&" . $nombreArchivo;

            //Ruta de descarga
            $ruta = "contarappv1_descargas/" . $RFC . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mesfPago) . "/";

            //Se guradan los documentos relacionados en la carpeta correspondiente al mes
            $file->storeAs($ruta, $renameFile, 'public2');

            //Almacenamos en la base de datos
            Volumetrico::where(['rfc' => $RFC])
                ->update([

                    'rfc' => $RFC,
                    //Inventario inicial
                    'volumetrico.' . $Fecha . '.PDFVolu' => $renameFile,

                ], ['upsert' => true]);
        }
    }

    //Metdodo para eliminar el PDF
    public function EmlimPDFVolu()
    {
        $mes = date('m', strtotime($this->dia));
        $anio = date('Y', strtotime($this->dia));
        $espa = new Cheques();

        //Consultamos lo datos de los volumetricos
        $datavolum = Volumetrico::where(['rfc' => $this->empresa])
            ->get()
            ->first();

        $path = "contarappv1_descargas/" . $this->empresa . "/" . $anio . "/Volumetricos/" . $espa->fecha_es($mes) . "/" . $datavolum['volumetrico.' . $this->dia . '.PDFVolu'];

        Volumetrico::where('rfc', $this->empresa)
            ->update([
                'volumetrico.' . $this->dia . '.PDFVolu' => null,
            ]);

        //Elimina el pdf de la carpeta correspondiente
        Storage::disk('public2')->delete($path);

        $this->dispatchBrowserEvent('CerrarVoluPDF', ["dia" => $this->dia]);

        //Emitimos el metodo de refrescar la pagina
        $this->emitUp('volumrefresh');
    }

    //Metodo para vaciar las variables del modal
    public function Refresh()
    {
        //Emitimos el metodo de refrescar la pagina
        $this->emitUp('volumrefresh');
    }

    public function render()
    {
        //Consultamos lo datos de los volumetricos
        $datavolum = Volumetrico::where(['rfc' => $this->empresa])
            ->get()
            ->first();

        if ($datavolum) {
            //Consultamos si existe un PDF cargado
            $volupdf = $datavolum['volumetrico.' . $this->dia . '.VoluPDF'];
        } else {
            $volupdf = 0;
        }

        return view('livewire.volumepdf', ['volupdf' => $volupdf]);
    }
}
