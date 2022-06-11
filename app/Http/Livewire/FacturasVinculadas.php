<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use App\Models\MetadataR;
use Livewire\Component;

use App\Exports\FacturasExport;
use App\Models\Notificaciones;
use App\Models\ppdPendientesVincular;
use App\Models\XmlR;
use Maatwebsite\Excel\Facades\Excel;

class FacturasVinculadas extends Component
{



    public $checkedDesvincular = [];
    public float  $total = 0;

    public Cheques $facturaVinculada; /// modelo


    protected $listeners = [

        'refrescarModalFacturas' => '$refresh',

    ];


    public function mount()
    {

        $this->Pagos = '';
    }


    protected function rules()
    {

        return [

            'Pagos' => '',



            //======== modal ajuste =====//



        ];
    }


    public function render()
    {



        if ($this->checkedDesvincular) {

            $this->total = 1;
        } else {

            $this->total = 0;
        }

        $colM = MetadataR::where(['cheques_id' => $this->facturaVinculada->_id])->get();




        return view('livewire.facturas-vinculadas', ['colM' => $colM, 'datos' => $this->facturaVinculada, 'total' => $this->total, 'cheque_id' => $this->facturaVinculada->_id, 'Pagos' => $this->Pagos]);
    }


    public function desvincular()
    {

        $nXml = 0;
        // Revisa todos los UUID de los CFDI seleccionados y elimina la vinculación con cheques
        foreach ($this->checkedDesvincular as $i) {

            $xml_r = MetadataR::where('folioFiscal', $i)->first(); ///consulta a metadata_r
            $xmlppd = XmlR::where('UUID', $i)->get(); /// enlasar l xml del ppd


            $cheques = Cheques::where('_id', $xml_r->cheques_id)->first(); ///consulta cheques

            foreach ($xmlppd as $x) : ////se recorre el objeto con los CDFID pago

                if ($x->MetodoPago == 'PPD') {

                    /// se obtienen todos los metadatos que no tengan vinculo y que sean pagos
                    $metadataPago = MetadataR::where('cheques_id', $this->facturaVinculada->_id)->where('efecto', 'Pago')->where('estado', 'Vigente')->get();

                    if (count($metadataPago) != 0) {

                        foreach ($metadataPago as $meta) {
                            $foliosmetaSinVinculo[] = $meta->folioFiscal;
                        }
                        unset($meta); // rompe la referencia con el último elemento

                        $xmlPago = XmlR::whereIn('UUID', $foliosmetaSinVinculo)->get();





                        foreach ($xmlPago as $Pago) : ////se recorre el objeto con los CDFID pago

                            $complemento = $Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                            if (!isset($complemento)) {
                                $complemento = $Pago['Complemento.Pagos.Pago.0.DoctoRelacionado'];
                            }
                            ################ aqui se desviculuan los cheques id con los pagos ( con pull)

                            if (count($complemento) > 1) {

                                foreach ($complemento as $c) :
                                    $mayus = strtoupper($c['IdDocumento']);

                                    if ($mayus == $i) {
                                        $xp = MetadataR::where(['folioFiscal' => $Pago['UUID']])->get()->first();
                                        $xp->pull('cheques_id', $this->facturaVinculada->_id);
                                        ////quitar chues_id si el array se quedar vacio /////////////////////
                                        if (count($xp['cheques_id']) == 0) {
                                            $xp->unset('cheques_id');
                                        }
                                        /// actualiza el contador faltaxml descontando cada factura
                                        $cheques->update(['faltaxml' => $cheques->faltaxml - 1]);
                                    }

                                endforeach;
                            } else {
                                $uuid2 = strtoupper($Pago['Complemento.0.Pagos.Pago.0.DoctoRelacionado.0.IdDocumento']);
                                if ($uuid2 == $i) {

                                    // echo "aqui esta el folio fiscal ppd".$i."<br>";
                                    // echo "aqui esta el uuid".$uuid2."<br>";
                                    // echo "aqui esta el uui pago".$Pago['UUID'];
                                    $xp = MetadataR::where(['folioFiscal' => $Pago['UUID']])->get()->first();
                                    $xp->pull('cheques_id', $this->facturaVinculada->_id);
                                    ////quitar chues_id si el array se quedar vacio /////////////////////
                                    if (count($xp['cheques_id']) == 0) {
                                        $xp->unset('cheques_id');
                                    }



                                    /// actualiza el contador faltaxml descontando cada factura
                                    $cheques->update(['faltaxml' => $cheques->faltaxml - 1]);
                                }
                            }






                        endforeach;
                    }
                } //// fin del fi MetodoPago

            endforeach;




            if ($xml_r->efecto == "Egreso") {

                //// actualiza el importe descontando el importe del cheque del metadata_r
                $cheques->update(['importexml' => $cheques->importexml + $xml_r->total]);
            } else {

                //// actualiza el importe descontando el importe del cheque del metadata_r
                $cheques->update(['importexml' => $cheques->importexml - $xml_r->total]);
            }
            /// actualiza el contador faltaxml descontando cada factura
            $cheques->update(['faltaxml' => $cheques->faltaxml - 1]);


            /// desvincula las facturas generales
            MetadataR::where('folioFiscal', $i)
                ->update([
                    'cheques_id' => null,
                ]);


            ///desvincula Pagos//////


            $this->checkedDesvincular = []; /// reset array para evitar conflicto



        }



        if ($cheques->faltaxml == 0) {

            $this->dispatchBrowserEvent('cerrarFacturas', []); // cierra el modal si ya no hay facturas
        }



        $this->emitTo('chequesytransferencias', 'chequesRefresh'); //actualiza la tabla cheques y transferencias

        // // Actualiza el monto y cantidad de CFDIs desvinculados para actualizar la colección cheques
        // $totalXml = $this->facturaVinculada->totalxml;
        // $totalXml = substr($totalXml, 1);
        // $totalXml = (float)str_replace(',', '', $totalXml);
        // $cheque_tXml = Cheques::find($cheques_id);
        // $importeXml = $cheque_tXml->importexml - $totalXml;
        // $faltaxml = $cheque_tXml->faltaxml - $nXml;
        // $cheque_tXml->update([
        //     'importexml' => $importeXml,
        //     'faltaxml' => $faltaxml,
        // ]);



    }




    public function export($facturas)
    {
        $cheque = Cheques::where(['_id' => $facturas])->first();
        return Excel::download(new FacturasExport($facturas), $cheque->numcheque . 'FacturasVinculadas.xlsx');
    }
}
