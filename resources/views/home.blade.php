@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="inicio" align="center">
                <h4>Sesión de:</h4>
                <h2>{{ Auth::user()->nombre }}</h2>
                <h4>{{ Auth::user()->RFC }}</h4>
                <br>
                @php
                    $rfc = Auth::user()->RFC;
                @endphp

                @switch($rfc)
                    @case('ADMINISTRADOR')
                        <form method="POST" action="{{route('home2')}}">
                        @csrf
                        @php
                            $clientes = DB::table('clientes')
                                ->select('RFC', 'nombre', 'Password')
                                ->where('RFC' != $rfc)
                                ->orderBy('RFC')
                                ->get();

                            echo "<select id='sele' name='usuarios'>";

                            foreach ($clientes as $cli) {
                                $RFCli = $cli['RFC'];
                                $nombre = $cli['nombre'];
                                $pass = $cli['Password'];

                                echo "<option id='opt' value=$RFCli>$nombre</option>";
                            }
                        @endphp

                        </select>
                        <br>
                        <br>
                        <input class="btnModulo" type="submit" value="Entrar empresa"
                            style="BORDER: #0055FF 1px solid; FONT-SIZE: 10pt">
                        </form>

                </div>
                <br>
                <br>
                @break
                @case('NOMINAS')

                @break
                @default
                    <h2>Módulos disponibles</h2>
                    <br>

                    <div class="row" style="justify-content: center;">
                        <div class="col">
                            <form action="{{ url('descargasv2') }}">
                                {{-- <input type="hidden" name="accion" value="login_fiel" /> --}}
                                <button class="btnModulo" type="submit" value="Descargas" style="font-size: 12pt">
                                    <img style="float:left;" src="img/boton.png" width="25px" height="25px" alt=""> Descargas
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <form action="{{ url('consultas') }}">
                                <button class="btnModulo" type="submit" value="Consultas" style="font-size: 12pt">
                                    <img style="float:left;" src="img/lupa.png" width="25px" height="25px" alt=""> Consultas
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <form action="{{url('construccion')}}">
                                <button class="btnModulo" type="submit" value="Expediente Digital" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                                    <img style="float:left;" src="img/archivo(1).png" width="25px" height="25px" alt="">
                                    Expediente Digital
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <form action="{{ url('volumetrico') }}">
                                <button class="btnModulo" type="submit" value="Expediente Digital" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                                    <img style="float:left;" src="img/estadisticas.png" width="25px" height="25px" alt="">
                                    Control Volumétrico
                                </button>
                            </form>
                        </div>
                    </div>

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
                            <form action="{{url('construccion')}}">
                                <button class="btnModulo" type="submit" value="Expediente Fiscal" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                                    <img style="float:left;" src="img/fiscal.png" width="25px" height="25px" alt=""> Expediente
                                    Fiscal
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <form action="{{url('construccion')}}">
                                <button class="btnModulo" type="submit" value="Nomina" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                                    <img style="float:left;" src="img/salario.png" width="25px" height="25px" alt=""> Nómina
                                </button>
                            </form>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="justify-content: center;">
                        <form action="{{ url('monitoreo') }}">
                            <button class="btnModulo" type="submit" value="Nomina" {{-- style="border-radius: 10px 10px 10px 10px; color:white; BORDER: #0055FF 1px solid; FONT-SIZE: 10pt; BACKGROUND-COLOR: #0055FF" --}}>
                                <img style="float:left;" src="img/monitoreo.png" width="25px" height="25px" alt=""> Monitoreo
                                Facturación
                            </button>
                        </form>
                    </div>
                    <br><br>
            @endswitch


            <div class="row" style="justify-content: center;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input class="cerrarSesion" type="submit" value="Cerrar Sesión"
                        style="BORDER: #0055FF 1px solid; FONT-SIZE: 10pt">
                </form>
            </div>

        </div>
    </div>
    </div>
@endsection
