@extends('layouts.app')

@php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheques;
use App\Http\Controllers\ChequesYTransferenciasController;
@endphp

@section('content')

    <div class="container" >
        <div class="row justify-content-center">
            <div class="inicio" align="center">
              @if (Session::get('tipoU') == '3')
                    <h4>Esta entrando como contador a la</h4>
                @endif
                <h4>Sesión de:</h4>








                <br>

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
    </div>



@endsection




<!-- en el @seccion

<div class="row" style="justify-content: center;">
    <div class="col">
        <form action="{{ url('descargasv2') }}">
            {{-- <input type="hidden" name="accion" value="login_fiel" /> --}}
            <input type="hidden" id="user" value="{{$tipo}}">
            <button onclick="fun1();" id="function1" class="btnModulo" type="submit" value="Descargas" style="font-size: 12pt">
                <img style="float:left;" src="img/boton.png" width="25px" height="25px" alt=""> Descargas
            </button>
            <div id="noDis1"></div>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('consultas') }}">
            <input type="hidden" id="user" value="{{$tipo}}">
            <button onclick="fun1();" id="function2" class="btnModulo" type="submit" value="Consultas" style="font-size: 12pt">
                <img style="float:left;" src="img/lupa.png" width="25px" height="25px" alt=""> Consultas
            </button>
            <div id="noDis2"></div>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('construccion') }}">
            <button class="btnModulo" type="submit" value="Expediente Digital" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/archivo(1).png" width="25px" height="25px" alt="">
                Expediente Digital
            </button>
        </form>
    </div>
        <div class="col">
            <form action="{{ url('volumetrico') }}">
                <button class="btnModulo" type="submit" value="Expediente Digital"
                    {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                    <img style="float:left;" src="img/estadisticas.png" width="25px" height="25px" alt="">
                    Control Volumétrico
                </button>
            </form>
        </div>

</div>
<br>
<div class="row" style="justify-content: center;">
    <div class="col">
        <form action="{{ url('cuentasporpagar') }}">
            <button class="btnModulo" type="submit" value="Cuentas por pagar" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/cuenta.png" width="25px" height="25px" alt=""> Cuentas por
                pagar
            </button>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('cheques-transferencias') }}">
            <button class="btnModulo" type="submit" value="Cheques y Transferencias"
                style="height:55px; font-size: 9pt">
                <img style="float:left;" src="img/cheque.png" width="20px" height="20px" alt=""> Cheques y
                Transferencias
            </button>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('construccion') }}">
            <button class="btnModulo" type="submit" value="Expediente Fiscal" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/fiscal.png" width="25px" height="25px" alt=""> Expediente
                Fiscal
            </button>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('construccion') }}">
            <button class="btnModulo" type="submit" value="Nomina" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/salario.png" width="25px" height="25px" alt=""> Nómina
            </button>
        </form>
    </div>
</div>
<br>
<div class="row" style="justify-content: center;">
    <div class="col">
        <form action="{{ url('monitoreo') }}">
            <input type="hidden" id="user" value="{{$tipo}}">
            <button onclick="fun1();" id="function3" class="btnModulo" type="submit" value="Nomina" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/monitoreo.png" width="25px" height="25px" alt="">
                Monitoreo Facturación
            </button>
            <div id="noDis3"></div>
        </form>
    </div>
    <div class="col">
        <form action="{{ url('auditoria') }}">
            <input type="hidden" id="user" value="{{$tipo}}">
            <button onclick="fun1();" id="function4" class="btnModulo" type="submit" value="Nomina" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                <img style="float:left;" src="img/searcher.png" width="25px" height="25px" alt="">
                Auditoría
            </button>
            <div id="noDis4"></div>
        </form>
    </div>
</div>
<br><br>



<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="exampleModalLongTitle">Atención:</h4>
&nbsp;<h6>Tienes asuntos pendientes en modulo Cheques y Transferencias  </h6>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
@php



@endphp
<h4> <img src="img/warning.png" style="width:30px;">  Tienes: @php  $p =new ChequesYTransferenciasController();  echo $p->pendientes($rfc); @endphp pendientes   </h4><br>
<h4> <img src="img/sin_verificar.png" style="width:30px;">  Tienes: @php  $verif =new ChequesYTransferenciasController();  echo $verif->verificado($rfc); @endphp sin revisar  </h4><br>
<h4> <img src="img/sin_contabilizar.png" style="width:30px;">   Tienes: @php  $cont =new ChequesYTransferenciasController();  echo $cont->contabilizado($rfc); @endphp sin contabilizar  </h4>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
<form action="{{ url('chequesytransferencias') }}">
<button class="btn btn-primary" type="submit" value="Cheques y Transferencias"
>Ir al modulo
</button>
</form>


</div>
</div>
</div>
</div>





<div class="row" style="justify-content: center;">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <input class="cerrarSesion" type="submit" value="Cerrar Sesión"
            style="BORDER: #0055FF 1px solid; FONT-SIZE: 10pt">
    </form>
</div>


-->
