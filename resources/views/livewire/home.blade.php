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

                {{--SECCION PARA MOSTRAR LOS PENDIENTES DE CHEQUES Y TRANSFERENCIAS--}}
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-xl-5 col-xl-5 mb-5">
                        <div class="card">
                          <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0"><b>Cheques y transferencias</b></h5>
                            <div class="row">
                                <div class="col-7">
                                  @if(Auth::user()->tipo)
                                    <label for="inputState">Empresa: {{$empresa}}</label>
                                    <select wire:model="rfcEmpresa" id="inputState1" class="select form-control"  >
                                        <option  value="" >--Selecciona Empresa--</option>
                                        {{--Llenamos el select con las empresa vinculadas--}}
                                        <?php $rfc=0; $rS=1;foreach($empresas as $fila){
                                        echo '<option value="' . $fila[$rfc] . '">'. $fila[$rS] . '</option>';
                                        }?>
                                    </select>
                                    @endif
                                </div>
                                <div class="col">
                                    <label>Año</label>
                                    <select wire:model="anio" id="inputState2" class="select form-control">
                                      <option  value="" >--Año--</option>
                                      <?php foreach (array_reverse($anios) as $value) {
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                      }?>
                                    </select>
                                </div>
                            </div>
                          </div>
                          <div class="card-body">
                            @php
                              //Vamos a contabilizar cada aspecto faltante
                              //Faltantes
                              $TotalPendientes = 0;

                              //No revisados
                              $TotalNoRevisado = 0;

                              //No contabilizados
                              $TotalNoConta = 0;

                              foreach ($pendientes as $ContaCheq) {
                                if ($ContaCheq->pendi == 1) {
                                  $TotalPendientes = ++$TotalPendientes;
                                }

                                if ($ContaCheq->verificado == 0) {
                                    $TotalNoRevisado = ++$TotalNoRevisado;
                                    $TotalNoConta = ++$TotalNoConta;
                                }else {
                                  if ($ContaCheq->conta == 0) {
                                    $TotalNoConta = ++$TotalNoConta;
                                  }
                                }
                              }
                            @endphp

                            {{--Movimientos pendientes--}}
                            <div class="row" style="text-align: center">
                              <div class="col-1">
                                <i class="fas fa-exclamation fa-2x" style="color: red"></i>
                              </div>
                              <div class="col">
                                <h6><b>Total de <br> pendientes</b></h6>
                              </div>
                              <div class="col-2">
                                <h6>{{$TotalPendientes}}</h6>
                              </div>
                            </div>

                            @if(Auth::user()->tipo)
                             {{--Movimientos sin revisar--}}
                            <br>
                            <div class="row" style="text-align: center">
                              <div class="col-1">
                                <i class="fas fa fa-check fa-2x" style="color: green"></i>
                              </div>
                              <div class="col">
                                <h6><b>Total de <br> no revisados</b></h6>
                              </div>
                              <div class="col-2">
                                <h6>{{$TotalNoRevisado}}</h6>
                              </div>
                            </div>
                            <br>
                            {{--Movimientos sin contabilizar--}}
                            <div class="row" style="text-align: center">
                              <div class="col-1">
                                <i class="fas fa-calculator fa-2x" style="color: blue"></i>
                              </div>
                              <div class="col">
                                <h6><b>Total de <br> no contabilizados</b></h6>
                              </div>
                              <div class="col-2">
                                <h6>{{$TotalNoConta}}</h6>
                              </div>
                            </div>
                            @endif
                            <br>
                            <a class="btn btn-primary shadow mr-1 mb-1 BtnVinculadas" href="{{ url('chequesytransferencias') }}">Ir a Cheques y transferencias</a>
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
