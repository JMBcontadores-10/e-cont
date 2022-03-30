<div>
    @php
    //Llamamos a los modelos requeridos
    use App\Models\MetadataR;
    use App\Models\ListaNegra;
    use App\Models\XmlR;
    use App\Models\Cheques;
    //Obtenemos la clase para agregar a la tabla
    $rfc = Auth::user()->RFC;
    $class='';
    if(empty($class)){
      $class="table nowrap dataTable no-footer";
      }

    //Obtenemos el valor de la fecha del dia de hoy
    $date=date('Y-m-d');
    @endphp


    {{--Contenedor para mantener responsivo el contenido del modulo--}}
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-body">
          <section class="invoice-list-wrapper">
            {{--Aqui va el contenido del modulo--}}
            {{--Encabezado del modulo--}}
            <div class="justify-content-start">
              <h1 style="font-weight: bold">{{ ucfirst(Auth::user()->nombre) }}</h1>
              <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
            </div>

            <br>

            {{--Select para selccionar la empresa (Contadores)--}}
            @empty(!$empresas)
             {{--Mostramos el RFC de la empresa que se selecciona--}}
              <label for="inputState">Empresa: {{$empresa}}</label>
              <select wire:model="rfcEmpresa" id="inputState1" class="select form-control"  >
                <option  value="" >--Selecciona Empresa--</option>

                {{--Llenamos el select con las empresa vinculadas--}}
                <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                  echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                }?>
              </select>
            @endempty

            <br>

            {{--Botones para mas provvedores--}}
            <div class="row">
              <div class="col-4">
                {{--Boton para mostrar la columna de vincular mas--}}
                <div class="invoice-create-btn mb-1">
                  <a class="btn btn-primary button2" id="BtnMoreProv">Vincular Varios Proveedores</a>
                </div>
              </div>
              <div class="col-4">
                {{--Boton para mostrar la columna de detalles para varios proveedores--}}
                <div class="invoice-create-btn mb-1">
                  <button data-toggle="modal" data-target="#detalles" class="btn btn-secondary button2 DesatallesProv" id="BtnDetMoreProvUp" disabled>Detalles Varios Proveedores</button>
                </div>
              </div>
            </div>

            <br>

            {{--Mostrar tabla de contenido--}}
            {{--Filtros--}}
            <div class="form-inline mr-auto">
              <input wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">
            </div>

              <div wire:loading>
                <br>
                <div style="color: #3CA2DB" class="la-ball-clip-rotate-multiple">
                  <div></div>
                  <div></div>
                </div>
                <i class="fas fa-mug-hot"></i>&nbsp;Cargando datos por favor espere un momento....
                <br>
              </div>

              {{--Tabla--}}
              <div class="table-responsive">
                <table id="example" class="{{$class}}" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-center align-middle VincVarProv">Vincular Proveedores</th>
                      <th class="text-center align-middle">RFC Emisor</th>
                      <th class="text-center align-middle">Razón Social</th>
                      <th class="text-center align-middle">Lista Negra</th>
                      <th class="text-center align-middle">N° de CFDI's</th>
                      <th class="text-center align-middle">Total</th>
                      <th class="text-center align-middle">Detalles</th>
                      <th >...</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{--Contenido de nuestra tabla--}}
                    {{--Llenamos nuestra tabla con un foreach--}}

                    @foreach ($col as $i)
                    @php
                    //Variable para obtener el total


                    $SumTotal = 0;



                    //Obtener el numero de CFDI
                    $DatosMetaR = MetadataR::
                    select('total', 'efecto')
                    ->where('receptorRfc', $this -> rfcEmpresa)
                    ->where('emisorRfc', $i->emisorRfc)
                    ->where('estado', '<>', 'Cancelado')
                    ->whereNull('cheques_id')
                    ->get();

                    //Obtenemos el numero de CFDI
                    $NoCFDI = $DatosMetaR -> count();

                    //Calcular el total (Convierte el campo total en en float y negativo si es egreso)
                    foreach ($DatosMetaR as $Total) {
                      $TotalFloat = (float) $Total['total'];
                      if ($Total['efecto'] == 'Egreso'){
                        $TotalFloat = -1 * abs($TotalFloat);
                      }

                      $SumTotal = $SumTotal + $TotalFloat;
                    }
                    @endphp

                    {{--Condicional para saber si hay mas de un CFDI--}}
                    @if (!$NoCFDI == 0)
                    <tr>
                      {{--Contenido de la columna para vincular varios--}}
                      <td class="text-center align-middle VincVarProv">
                        <div id="checkbox-group" class="checkbox-group">
                          <input style="transform: scale(1.5);" class="mis-checkboxes ChkMasProv" type="checkbox" wire:model.defer="moreprov" value="{{$i->emisorRfc}}"/>
                        </div>
                      </td>
                      <td class="text-center align-middle">
                        <span style="color:#3498DB" class="invoice-amount">{{$i->emisorRfc}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{ Str::limit($i->emisorNombre, 20); }}</span>
                      </td>

                      {{-- Valida si existe una coincidencia de RFC en la lista negra --}}
                      @if (!DB::collection('lista_negra')->select('RFC')->where(['RFC' => $i['emisorRfc']])->exists())
                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima.png') }}" alt=""></td>
                      @else
                        <td class="td1 text-center align-middle"><img src="{{ asset('img/ima2.png') }}" alt=""></td>
                      @endif

                      <td class="text-center align-middle">
                        <span class="invoice-amount">{{$NoCFDI}}</span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="invoice-amount">${{number_format($SumTotal, 2)}}</span>
                      </td>
                      <td class="text-center align-middle">
                        {{--Boton para abrir el modal--}}
                        <a class="icons fas fa-eye" data-toggle="modal" data-target="#detalles{{$i->emisorRfc}}"></a>
                      </td>
                      <td class="text-center align-middle"></td>
                    </tr>
                    @endif

                    {{--Llamando a las vistas de otros componentes--}}
                    <livewire:detalles :factu=$i :wire:key="'user-profile-one-'.$i->emisorRfc" :rfcEmpresa=$empresa>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <br>
              {{--Boton para mostrar la columna de detalles para varios proveedores--}}
              <div class="invoice-create-btn mb-1">
                <button data-toggle="modal" data-target="#detalles" class="btn btn-secondary button2 DesatallesProv" id="BtnMoreProvDown" disabled>Destalles Varios Proveedores</button>
              </div>
          </section>
        </div>
    </div>
  </div>
</div>
