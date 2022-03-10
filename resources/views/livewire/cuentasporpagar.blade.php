<div>
  @php
  //Llamamos a los modelos requeridos
  use App\Models\MetadataR;
  use App\Models\ListaNegra;

  //Obtenemos la clase para agregar a la tabla
  $rfc = Auth::user()->RFC;
  $class='';
  if(empty($class)){
    $class="table nowrap dataTable no-footer";
    }
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

          {{--Boton para mostrar la columna de vincular mas--}}
          <div class="invoice-create-btn mb-1">
            <a class="btn btn-primary button2" id="vinpbtn">Vincular Varios Proveedores</a>
          </div>

          <br>


          {{--Mostrar tabla de contenido--}}
          {{--Filtros--}}
          <div class="form-inline mr-auto">
            <input wire:model.debounce.300ms="search" class="form-control" type="text" placeholder="Filtro" aria-label="Search">    
              
              &nbsp;&nbsp;
                
              <label for="inputState">Mes</label>
              <select wire:model="mes" id="inputState1" class=" select form-control"  >
                <option  value="00" >Todos</option>
                <?php foreach ($meses as $key => $value) {
                  echo '<option value="' . $key . '">' . $value . '</option>';
                }?>
              </select>
                
              &nbsp;&nbsp;
                
              <label for="inputState">Año</label>
              <select wire:model="anio" id="inputState2" class="select form-control">
                <?php foreach (array_reverse($anios) as $value) {
                  echo '<option value="' . $value . '">' . $value . '</option>';
                }?>
              </select>

              &nbsp;&nbsp;

              <fieldset>
                <div class="custom-control custom-checkbox">
                  <input wire:model="todos" type="checkbox" class="custom-control-input bg-danger" checked name="customCheck" id="customColorCheck4">
                  <label class="custom-control-label" for="customColorCheck4">Todos</label>
                </div>
              </fieldset>

              &nbsp;&nbsp;

              <input  wire:model.debounce.300ms="importe" class="form-control"  placeholder="Importe $"  type="number"  step="0.01" aria-label="importe" style="width:110px;" >
              
              &nbsp;&nbsp;

              <select wire:model="condicion" id="inputState1" class=" select form-control"  >
                <option  value=">=" >--Condición--</option>
                <option value="=" >igual</option>
                <option value=">" >mayor que</option>
                <option value="<" >menor que</option>
              </select>
              
              &nbsp;&nbsp;
              
              <select wire:model="estatus" id="inputState1" class=" select form-control"  >
                <option  value="" >--Estatus--</option>
                <option value="pendi" >Pendientes</option>
                @if(auth()->user()->tipo)
                  <option value="sin_revisar" >Sin Revisar</option>
                  <option value="sin_conta" >Sin Contabilizar</option>
                @endif
              </select>
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
                  ->where('emisorRfc', $i -> emisorRfc)
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

                  //Metemos los datos en variables
                  $CFDIid = $i->_id;
                  $RFCEmisor = $i->emisorRfc;
                  $RazonSoc = $i->emisorNombre;
                  @endphp

                  {{--Condicional para saber si hay mas de un CFDI--}}
                  @if (!$NoCFDI == 0)
                  
                  <tr>
                    {{--Contenido de la columna para vincular varios--}}
                    <td class="text-center align-middle VincVarProv">
                      <div id="checkbox-group" class="checkbox-group">
                        <input style="transform: scale(1.5);" class="mis-checkboxes ChkMasProv" type="checkbox" id="allcheck" name="allcheck[]"value="{{$i->emisorRfc}}" />
                      </div>
                    </td>
                    <td class="text-center align-middle">
                      <span class="invoice-amount">{{$RFCEmisor}}</span>
                    </td>
                    <td class="text-center align-middle">
                      <span class="invoice-amount">{{$RazonSoc}}</span>
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
                      <a data-toggle="modal" data-target="#detalles{{$CFDIid}}" class="icons fas fa-eye"></a>
                    </td>
                    <td class="text-center align-middle"></td>
                  </tr>
                  @endif

                  {{--Llamamos a los detalles con el Id--}}
                  <livewire:detalles :MetaDatos=$i :wire:key="'user-profile-one-'.$i->_id">
                  @endforeach
                </tbody>
              </table>
            </div>
        </section>
      </div>
  </div>
</div>
</div>