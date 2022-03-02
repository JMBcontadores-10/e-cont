<div>

    @php

use Livewire\Component;
use App\Models\Cheques;
use App\Http\Controllers\Livewire\CuentasPorpagar;
use App\Models\MetadataR;
use App\Models\ListaNegra;
@endphp


        @php
       $rfc = Auth::user()->RFC;

       $class='';
        if(empty($class)){
           $class="table nowrap dataTable no-footer";

        }

     @endphp


          <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body"><!-- invoice list -->
  <section class="invoice-list-wrapper">
    <!-- create invoice button-->
    <div class="invoice-create-btn mb-1">

    </div>

        @csrf

        @csrf



@empty(!$empresas)

 <h4>{{$empresas[3][0]}}</h4>

{{$empresa}}

<br><br>

{{--@php
foreach($empresas as $fila)
{
    foreach($fila as $nombre)
    {
	echo " $nombre ";
    }

	echo "<br>";
}
@endphp--}}
            <label for="inputState">Empresa</label>
            <select wire:model="rfcEmpresa" id="inputState1" class=" select form-control"  >
                <option  value="00" >--Selecciona Empresa--</option>
                <?php $rfc=0; $rS=1;foreach($empresas as $fila)
                {

                    echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';

          }
                ?>
            </select>

            &nbsp;&nbsp;<br>
@endempty


<table class="table table-sm table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="text-center align-middle">N°</th>
            <th id="vinp" class="text-center align-middle">Vincular Proveedores</th>
            <th class="text-center align-middle">RFC Emisor</th>
            <th class="text-center align-middle">Razón Social</th>
            <th class="text-center align-middle">Lista Negra</th>
            <th class="text-center align-middle">N° de CFDI's</th>
            <th class="text-center align-middle">Total</th>
            <th class="text-center align-middle">Detalles</th>

        </tr>
    </thead>
    <tbody class="buscar">






        @foreach ($col as $i)
            @php


                $sum = 0;
                $nXml = 0;

                // Consulta para obtener el total de monto y cantidad de CFDI por empresa emisora
                // Se aplicó un indice para agilizar la velocidad de consulta


                $colT = DB::collection('metadata_r')
                    ->select('total', 'efecto')
                    ->where('receptorRfc', $rfcEmpresa)
                    ->where('emisorRfc', $i['emisorRfc'])
                    ->where('estado', '<>', 'Cancelado')
                    ->whereNull('cheques_id')
                    ->get();
                $nXml = $colT->count();
                // Convierte el campo total en en float y negativo si es egreso
                foreach ($colT as $v) {
                    $var = (float) $v['total'];
                    if ($v['efecto'] == 'Egreso') {
                        $var = -1 * abs($var);
                    }
                    $sum = $sum + $var;
                }
                $tXml = $tXml + $nXml;
                $tTabla = $tTabla + $sum;
            @endphp
            {{-- Valida si existe al menos un CFDI para crear la fila --}}
            @if (!$nXml == 0)


                <tr>
                    <td class="text-center align-middle">{{ ++$n }}</td>
                    <td id="vinp" class="text-center align-middle">
                        <div id="checkbox-group" class="checkbox-group">
                            <input class="mis-checkboxes" type="checkbox" id="allcheck" name="allcheck[]"
                                value="{{ $i['emisorRfc'] }}" />
                        </div>
                    </td>
                    <td class="text-center align-middle">{{ $i['emisorRfc'] }}</td>
                    <td class="align-middle">{{ $i['emisorNombre'] }}</td>
                    {{-- Valida si existe una coincidencia de RFC en la lista negra --}}
                    @if (!DB::collection('lista_negra')->select('RFC')->where(['RFC' => $i['emisorRfc']])->exists())
                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima.png') }}"
                                alt="">
                        </td>
                    @else


                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima2.png') }}"
                                alt="">
                        </td>
                    @endif
                    <td class="text-center align-middle">{{ $nXml }}</td>
                    <td class="text-center align-middle">${{ number_format($sum, 2) }}</td>
                    <td class="text-center align-middle">
                        <form action="detalles" method="GET">
                            <input type="hidden" name="emisorRfc" value="{{ $i['emisorRfc'] }}">
                            <input type="hidden" name="emisorNombre" value="{{ $i['emisorNombre'] }}">
                            <input type=submit value=Ver>
                        </form>
                    </td>
                </tr>





            @endif

        @endforeach



        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="vinp"></td>
            <td class="text-center text-bold">Total:</td>
            <td class="text-center text-bold">{{ $tXml }}</td>
            <td id="bottom" class="text-center text-bold">${{ number_format($tTabla, 2) }}</td>
        </tr>
    </tbody>


</table>


</div>


</section>



</div>


</div>


