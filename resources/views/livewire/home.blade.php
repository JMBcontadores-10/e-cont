<div>
    @php
    use App\Models\Cheques;
    use Illuminate\Support\Facades\DB;

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
                    <div class="container" >
                        <div class="row justify-content-center">
                    <div class="inicio" align="center">
                        @php
                        $rfc = Auth::user()->RFC;
                        $tipo = Session::get('tipoU');
                        @endphp
        
                        <h2 id="txtsaludo">Bienvenid@</h2>
                        @if(!empty(auth()->user()->tipo))
                        <h5>Contador@</h5>
                        @endif
        
                        @if(Auth::check())
        
                        <h6>{{auth()->user()->RFC}}</h6>
                        
                                @endif
                               {{----------contenido seccion---------}}
        
         {{----------contenido seccion---------}}
                </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-xl-8 col-xl-8 mb-8">
                        <div class="card">
                          <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0"><b>Pendientes</b></h5>
                            <div class="row">
                                <div class="col">
                                    <label>Año</label>
                                    <select wire:model="anio" id="inputState2" class="select form-control">
                                        <?php foreach (array_reverse($anios) as $value) {
                                          echo '<option value="' . $value . '">' . $value . '</option>';
                                        }?>
                                      </select>
                                </div>
                                @if(Auth::user()->tipo)
                                <div class="col-7">
                                    <label for="inputState">Empresa: {{$empresa}}</label>
                                    <select wire:model="rfcEmpresa" id="inputState1" class="select form-control"  >
                                        <option  value="" >--Selecciona Empresa--</option>
                                        {{--Llenamos el select con las empresa vinculadas--}}
                                        <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                                        echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                                        }?>
                                    </select>
                                </div>
                                @endif
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="{{$class}}" style="width:100%">
                                  <thead>
                                    <tr>
                                      <th>
                                        <span class="align-middle">fecha </span>
                                      </th>
                                      <th>Factura#</th>
                                      <th>beneficiario</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      {{--Vamos a recorrer la lista de cheques--}}
                                      @foreach ($pendientes as $i)
                                        {{--Cuerpo de la tabla--}}
                                        <tr>
                                            {{--Fecha--}}
                                            <td>
                                                <a style="color:red; padding: 0px 5px 0px 5px;"  class="parpadea fas fa-exclamation"></a> {{$i->fecha}}
                                            </td>
                                            {{--Numero de factura--}}
                                            <td>
                                                <label>{{$i->numcheque}}</label>
                                            </td>
                                            {{--Npmbre del beneficiario--}}
                                            <td>
                                                <label>{{$i->Beneficiario}}</label>
                                            </td>
                                        </tr>
                                      @endforeach        
                                  </tbody>
                                </table>
                                {{ $pendientes->links() }}
                                {{--Ir a cheques y trnasferencia--}}
                                <br>
                                <a class="btn btn-primary shadow mr-1 mb-1 BtnVinculadas" href="{{ url('chequesytransferencias') }}">Ir a Cheques y transferencias</a>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
    </div>
            </section>
          </div>
        </div>
    </div>
</div>
