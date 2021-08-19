<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\XmlE;
?>


@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="container">
            <div class="float-md-left">
                <a class="b3" href="javascript:javascript:history.go(-1)">
                    << Regresar</a>
            </div>
            <div class="float-md-right">
                <p class="label2">Consultas</p>
            </div>
            <br>
            <hr style="border-color:black; width:100%;">
            <div class="justify-content-start">
                <label class="label1" style="font-weight: bold"> Sesión de: </label>
                <h1 style="font-weight: bold">{{ Auth::user()->nombre }}</h1>
                <h5 style="font-weight: bold">{{ Auth::user()->RFC }}</h5>
                <hr style="border-color:black; width:100%;">
            </div>
        </div>
        @php
            $rfc = Auth::user()->RFC;
        @endphp

        <h1>Facturas {{ $tipodes }} </h1>
        <h1>Tipo {{ $tipoFac }}</h1>
        <h1>Periodo del {{ $fecha1 }} al {{ $fecha2 }}</h1>

        <div class="input-group">
            <span class="input-group-text">Buscar</span>
            <input id="filtrar" type="text" class="form-control" placeholder="Buscar">
        </div><br>

        @switch($tipoFac)
            @case('I')
                <div id="div1">
                    <form method="POST" class="descargaR-form">
                        <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th scope="col">Estado SAT</th>
                                    <th scope="col">No.Certificado</th>
                                    <th scope="col">Tipo Comprobante</th>
                                    <th scope="col">Fecha Emisión</th>
                                    <th scope="col">Fecha Timbrado</th>
                                    <th scope="col">Serie</th>
                                    <th scope="col">Folio</th>
                                    <th scope="col">UUID</th>
                                    <th scope="col">RFC Emisor</th>
                                    <th scope="col">Nombre Emisor</th>
                                    <th scope="col">Regimen Fiscal</th>
                                    <th scope="col">Lugar Expedición</th>
                                    <th scope="col">RFC Receptor</th>
                                    <th scope="col">Nombre Receptor</th>
                                    <th scope="col">Uso CFDI</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">IVA 16%</th>
                                    <th scope="col">Descripcion (Conceptos)</th>

                                    <th scope="col">FormaPago</th>

                                    <th scope="col">Método Pago</th>
                                    <th scope="col">Moneda</th>
                                    <th scope="col">Tipo Cambio</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Versión</th>
                                    {{-- <td scope="col">Sello</td> --}}

                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Clave Producto/Servicio</th>
                                    <th scope="col">Clave Unidad</th>

                                    <th scope="col">Importe</th>
                                    <th scope="col">No. Identificacion</th>
                                    <th scope="col">Unidad</th>
                                    <th scope="col">Valor Unitario</th>
                                    <th scope="col">Base</th>

                                    <th scope="col">Impuesto</th>
                                    <th scope="col">Tasa o Cuota</th>
                                    <th scope="col">Tipo Factor</th>
                                    <th scope="col">Total Impuestos Otros Traslados</th>
                                    <th scope="col">Traslado (Importe) </th>
                                    <th scope="col">Traslado (Impuesto)</th>
                                    <th scope="col">Traslado (TasaOCuota)</th>
                                    <th scope="col">Traslado (Tipo Factor)</th>
                                    {{-- <th scope="col">Timbre Fiscal Digital</th> --}}

                                    <th scope="col">Rfc Proveedor Certificado</th>

                                    <th scope="col">No. Certificado SAT</th>
                                    {{-- <th scope="col">Sello CFD</th>--}
                                {{--<th scope="col">Sello SAT</th> --}}
                                    <th scope="col">Versión</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                @foreach ($colI as $i)
                                    @php
                                        $nCon = 0;
                                        $concepto = $i['Conceptos.Concepto'];
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <td>{{ $i['NoCertificado'] }}</td>
                                        <td>{{ $i['TipoDeComprobante'] }}</td>
                                        <td>{{ $i['Fecha'] }}</td>
                                        <td>{{ $i['Complemento.0.TimbreFiscalDigital.FechaTimbrado'] }}</td>
                                        <td>{{ $i['Serie'] }}</td>
                                        <td>{{ $i['Folio'] }}</td>
                                        <td>{{ $i['Complemento.0.TimbreFiscalDigital.UUID'] }}</td>
                                        <td>{{ $i['Emisor.Rfc'] }}</td>
                                        <td>{{ $i['Emisor.Nombre'] }}</td>
                                        <td>{{ $i['Emisor.RegimenFiscal'] }}</td>
                                        <td>{{ $i['LugarExpedicion'] }}</td>
                                        <td>{{ $i['Receptor.Rfc'] }}</td>
                                        <td>{{ $i['Receptor.Nombre'] }}</td>
                                        <td>{{ $i['Receptor.UsoCFDI'] }}</td>
                                        <td>{{ $i['SubTotal'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Importe'] }}</td>
                                        <td>
                                            @foreach ($concepto as $c)
                                                {{ ++$nCon }}. {{ $c['Descripcion'] }}/
                                            @endforeach
                                        </td>
                                        <td>{{ $i['FormaPago'] }}</td>
                                        <td>{{ $i['MetodoPago'] }}</td>
                                        <td>{{ $i['Moneda'] }}</td>
                                        <td>{{ $i['TipoCambio'] }}</td>
                                        <td>{{ $i['Total'] }}</td>
                                        <td>{{ $i['Version'] }}</td>


                                        <td>{{ $i['Conceptos.Concepto.0.Cantidad'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.ClaveProdServ'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.ClaveUnidad'] }}</td>

                                        <td>{{ $i['Conceptos.Concepto.0.Importe'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.NoIdentificacion'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.Unidad'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.ValorUnitario'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Base'] }}</td>

                                        <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                        <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>
                                        <td>{{ $i['Impuestos.TotalImpuestosTrasladados'] }}</td>
                                        <td>{{ $i['Impuestos.Traslados.Traslado.0.Importe'] }}</td>
                                        <td>{{ $i['Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                        <td>{{ $i['Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                        <td>{{ $i['Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>

                                        <td>{{ $i['Complemento.0.TimbreFiscalDigital.RfcProvCertif'] }}</td>

                                        <td>{{ $i['Complemento.0.TimbreFiscalDigital.NoCertificadoSAT'] }}</td>
                                        <td>{{ $i['Complemento.0.TimbreFiscalDigital.Version'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <input type="button" name="btnExportar" id="btnExportar" class="btn btn-primary" value="Exportar a Excel"
                    onclick="ExportToExcel(jQuery('#tabla').prop('outerHTML'))" />
            @break
            @case('E')
                <div id="div1">
                    <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Fecha</th>
                                <th scope="col">Lugar Expedición</th>
                                <th scope="col">Método Pago</th>
                                <th scope="col">Tipo De Comprobante</th>
                                <th scope="col">Total</th>
                                <th scope="col">Moneda</th>
                                {{-- <th scope="col">Certificado</th> --}}
                                <th scope="col">Subtotal</th>
                                <th scope="col">No. Certificado</th>
                                <th scope="col">Forma Pago</th>
                                {{-- <th scope="col">Sello</th> --}}
                                <th scope="col">Versión</th>
                                <th scope="col">Rfc Emisor</th>
                                <th scope="col">Nombre Emisor</th>
                                <th scope="col">Regimen Fiscal Emisor</th>
                                <th scope="col">Rfc Receptor</th>
                                <th scope="col">Nombre Receptor</th>
                                <th scope="col">Uso CFDI Receptor</th>
                                <th scope="col">Clave Producto/Servicio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Clave Unidad</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Valor Unitario</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Impuesto Base</th>
                                <th scope="col">Impuesto</th>
                                <th scope="col">Tipo Factor(Impuesto)</th>
                                <th scope="col">Tasa O Cuota (Impuesto)</th>
                                <th scope="col">Importe Impuesto</th>
                                <th scope="col">Total Impuestos Trasladados</th>
                                <th scope="col">Impuesto Traslado</th>
                                <th scope="col">Impuesto Traslado Tipo Factor</th>
                                <th scope="col">Impuesto Traslado Tasa O Cuota</th>
                                <th scope="col">Impuesto Traslado (Importe)</th>
                                <th scope="col">Versión</th>
                                <th scope="col">UUID</th>
                                <th scope="col">Fecha Timbrado</th>
                                <th scope="col">Rfc Proveedor Certificado</th>
                                <th scope="col">No. Certificado</th>
                            </tr>
                        </thead>
                        <tbody class="buscar">
                            @foreach ($colI as $i)
                                <tr>
                                    <td>{{ $i['Fecha'] }}</td>
                                    <td>{{ $i['LugarExpedicion'] }}</td>
                                    <td>{{ $i['MetodoPago'] }}</td>
                                    <td>{{ $i['TipoDeComprobante'] }}</td>
                                    <td>{{ $i['Total'] }}</td>
                                    <td>{{ $i['Moneda'] }}</td>
                                    <td>{{ $i['SubTotal'] }}</td>
                                    <td>{{ $i['NoCertificado'] }}</td>
                                    <td>{{ $i['FormaPago'] }}</td>

                                    <td>{{ $i['Version'] }}</td>
                                    <td>{{ $i['Emisor.Rfc'] }}</td>
                                    <td>{{ $i['Emisor.Nombre'] }}</td>
                                    <td>{{ $i['Emisor.RegimenFiscal'] }}</td>
                                    <td>{{ $i['Receptor.Rfc'] }}</td>
                                    <td>{{ $i['Receptor.Nombre'] }}</td>
                                    <td>{{ $i['Receptor.UsoCFDI'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.ClaveProdServ'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Cantidad'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.ClaveUnidad'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Descripcion'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.ValorUnitario'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Importe'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Base'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Importe'] }}</td>
                                    <td>{{ $i['Impuestos.TotalImpuestosTrasladados'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.Importe'] }}</td>

                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.Version'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.UUID'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.FechaTimbrado'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.RfcProvCertif'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.NoCertificadoSAT'] }}</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <input type="button" name="btnExportar" id="btnExportar" class="btn btn-primary" value="Exportar a Excel"
                        onclick="ExportToExcel(jQuery('#tabla').prop('outerHTML'))" />
                    <div>

            @break
            @case('P')

                <div id="div1">
                    <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Estado SAT</th>
                                <th scope="col">Versión</th>
                                <th scope="col">Tipo De Comprobante</th>
                                {{-- <th scope="col"></th> --}}
                                <th scope="col">Fecha Emisión</th>
                                <th scope="col">Serie</th>
                                <th scope="col">Folio</th>
                                <th scope="col">UUID</th>
                                <th scope="col">Rfc Emisor</th>
                                <th scope="col">Nombre Emisor</th>
                                <th scope="col">Rfc Receptor</th>
                                <th scope="col">Nombre Receptor</th>
                                <th scope="col">Uso CFDI</th>
                                <th scope="col">Fecha Pago</th>
                                <th scope="col">Forma Pago</th>
                                <th scope="col">ID Doc Relacionado</th>
                                <th scope="col">Lugar Expedición</th>

                                <th scope="col">Total</th>
                                <th scope="col">Moneda</th>
                                {{-- <th scope="col">Certificado</th> --}}
                                <th scope="col">Subtotal</th>
                                <th scope="col">No. Certificado</th>

                                {{-- <th scope="col">Sello</th> --}}

                                <th scope="col">Regimen Fiscal Emisor</th>


                                <th scope="col">Clave Producto/Servicio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Clave Unidad</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Valor Unitario</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Versión</th>
                                <th scope="col">Fecha Timbrado</th>
                                <th scope="col">Rfc Proveedor Certificado</th>
                                <th scope="col">No. Certificado</th>
                                <th scope="col">Impuesto Base</th>
                                <th scope="col">Impuesto</th>
                                <th scope="col">Tipo Factor(Impuesto)</th>
                                <th scope="col">Tasa O Cuota (Impuesto)</th>
                                <th scope="col">Importe Impuesto</th>
                                <th scope="col">Total Impuestos Trasladados</th>
                                <th scope="col">Impuesto Traslado</th>
                                <th scope="col">Impuesto Traslado Tipo Factor</th>
                                <th scope="col">Impuesto Traslado Tasa O Cuota</th>
                                <th scope="col">Impuesto Traslado</th>

                            </tr>
                        </thead>
                        <tbody class="buscar">
                            @foreach ($colI as $i)
                                <tr>
                                    <td></td>
                                    <td>{{ $i['Version'] }}</td>
                                    <td>{{ $i['TipoDeComprobante'] }}</td>
                                    <td>{{ $i['Fecha'] }}</td>
                                    <td>{{ $i['Serie'] }}</td>
                                    <td>{{ $i['Folio'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.UUID'] }}</td>
                                    <td>{{ $i['Emisor.Rfc'] }}</td>
                                    <td>{{ $i['Emisor.Nombre'] }}</td>
                                    <td>{{ $i['Receptor.Rfc'] }}</td>
                                    <td>{{ $i['Receptor.Nombre'] }}</td>
                                    <td>{{ $i['Receptor.UsoCFDI'] }}</td>
                                    <td>{{ $i['Complemento.0.Pagos.Pago.0.FechaPago'] }}</td>
                                    <td>{{ $i['Complemento.0.Pagos.Pago.0.FormaDePagoP'] }}</td>
                                    @php
                                        $nCon = 0;
                                        $idrel = $i['Complemento.0.Pagos.Pago.0.DoctoRelacionado'];
                                    @endphp

                                    <td>
                                        @foreach ($idrel as $id)
                                            {{ ++$nCon }}. {{ $id['IdDocumento'] }}/
                                        @endforeach
                                    </td>

                                    <td>{{ $i['LugarExpedicion'] }}</td>
                                    <td>{{ $i['Total'] }}</td>
                                    <td>{{ $i['Moneda'] }}</td>
                                    <td>{{ $i['SubTotal'] }}</td>
                                    <td>{{ $i['NoCertificado'] }}</td>


                                    <td>{{ $i['Emisor.RegimenFiscal'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.Version'] }}</td>

                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.FechaTimbrado'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.RfcProvCertif'] }}</td>
                                    <td>{{ $i['Complemento.0.TimbreFiscalDigital.NoCertificadoSAT'] }}</td>

                                    @php
                                        $nCon = 0;
                                        $nCon1 = 0;
                                        $nCon2 = 0;
                                        $nCon3 = 0;
                                        $nCon4 = 0;
                                        $nCon5 = 0;
                                        $concep = $i['Conceptos.Concepto'];
                                    @endphp
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon }}. {{ $con['ClaveProdServ'] }}/
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon1 }}. {{ $con['Cantidad'] }}/
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon2 }}. {{ $con['ClaveUnidad'] }}/
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon3 }}. {{ $con['Descripcion'] }}/
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon4 }}. {{ $con['ValorUnitario'] }}/
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($concep as $con)
                                            {{ ++$nCon5 }}. {{ $con['Importe'] }}/
                                        @endforeach
                                    </td>

                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Base'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>
                                    <td>{{ $i['Conceptos.Concepto.0.Impuestos.Traslados.Traslado.0.Importe'] }}</td>
                                    <td>{{ $i['Impuestos.TotalImpuestosTrasladados'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.Impuesto'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.TipoFactor'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.TasaOCuota'] }}</td>
                                    <td>{{ $i['Impuestos.Traslados.Traslado.0.Importe'] }}</td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <input type="button" name="btnExportar" id="btnExportar" class="btn btn-primary" value="Exportar a Excel"
                    onclick="ExportToExcel(jQuery('#tabla').prop('outerHTML'))" />
            @break
            @case('N')
                <div id="div1">
                    <table border="1" id="tabla" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                {{-- <th scope="col">ID</th> --}}
                                <th scope="col">Estado SAT</th>
                                <th scope="col">Versión</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Forma Pago</th>
                                <th scope="col">No Certificado</th>
                                <th scope="col">Moneda</th>
                                <th scope="col">Tipo de Comprobante</th>
                                <th scope="col">Método Pago</th>
                                <th scope="col">Serie</th>
                                <th scope="col">Folio</th>
                                <th scope="col">Lugar Expedición</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Descuento</th>
                                <th scope="col">Total</th>
                                <th scope="col">Emisor Regimen Fiscal</th>
                                <th scope="col">Emisor RFC</th>
                                <th scope="col">Emisor Nombre</th>
                                <th scope="col">Receptor RFC</th>
                                <th scope="col">Receptor Nombre</th>
                                <th scope="col">Receptor Uso CFDI</th>
                                <th scope="col">Clave Producto/Servicio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Clave Unidad</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Valor Unitario</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Descuento</th>
                                <th scope="col">Versión Nómina</th>
                                <th scope="col">Tipo Nómina</th>
                                <th scope="col">Fecha Pago</th>
                                <th scope="col">Fecha Inicial Pago</th>
                                <th scope="col">Fecha Final Pago</th>
                                <th scope="col">Num Días Pagados</th>
                                <th scope="col">Total Percepciones</th>
                                <th scope="col">Total Deducciones</th>
                                <th scope="col">Total Otros Pagos</th>
                                <th scope="col">Registro Patronal</th>
                                <th scope="col">CURP Receptor</th>
                                <th scope="col">Numero Seguridad Social</th>
                                <th scope="col">Fecha Inicio Relación Laboral</th>
                                <th scope="col">Antigüedad</th>
                                <th scope="col">Tipo Contrato</th>
                                <th scope="col">Sindicalizado</th>
                                <th scope="col">Tipo Jornada</th>
                                <th scope="col">Tipo Regimen</th>
                                <th scope="col"># Empleado</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Puesto</th>
                                <th scope="col">Riesgo Puesto</th>
                                <th scope="col">Periocidad Pago</th>
                                <th scope="col">Salario Base Cot Aportación</th>
                                <th scope="col">Salario Diario Integrado</th>
                                <th scope="col">Clave Entidad Federativa</th>
                                <th scope="col">Total Sueldos</th>
                                <th scope="col">Total Gravado</th>
                                <th scope="col">Total Exento</th>

                                <!--Percepciones-->
                                <th scope="col">Tipo Percepcion</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Importe Gravado</th>
                                <th scope="col">Importe Exento</th>

                                <!--Deducciones-->
                                <th scope="col">Total Otras Deducciones</th>

                                <th scope="col">Tipo Deduccion</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Importe</th>

                                <th scope="col">Tipo Otro Pago</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Concepto</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Subsidio Causado</th>

                                <th scope="col">Versión</th>
                                <th scope="col">UUID</th>
                                <th scope="col">Fecha Timbrado</th>
                                <th scope="col">Rfc Proveedor Certificado</th>
                                <th scope="col"># Certificado SAT</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($colI as $nom)
                                <tr>
                                    <td></td>
                                    {{-- <td>{{$nom->_id}}</td> --}}
                                    <td>{{ $nom['Version'] }}</td>
                                    <td>{{ $nom['Fecha'] }}</td>
                                    <td>{{ $nom['FormaPago'] }}</td>
                                    <td>{{ $nom['NoCertificado'] }}</td>
                                    <td>{{ $nom['Moneda'] }}</td>
                                    <td>{{ $nom['TipoDeComprobante'] }}</td>
                                    <td>{{ $nom['MetodoPago'] }}</td>
                                    <td>{{ $nom['Serie'] }}</td>
                                    <td>{{ $nom['Folio'] }}</td>
                                    <td>{{ $nom['LugarExpedicion'] }}</td>
                                    <td>{{ $nom['SubTotal'] }}</td>
                                    <td>{{ $nom['Descuento'] }}</td>
                                    <td>{{ $nom['Total'] }}</td>
                                    <td>{{ $nom['Emisor.RegimenFiscal'] }}</td>
                                    <td>{{ $nom['Emisor.Rfc'] }}</td>
                                    <td>{{ $nom['Emisor.Nombre'] }}</td>
                                    <td>{{ $nom['Receptor.Rfc'] }}</td>
                                    <td>{{ $nom['Receptor.Nombre'] }}</td>
                                    <td>{{ $nom['Receptor.UsoCFDI'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.ClaveProdServ'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.Cantidad'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.ClaveUnidad'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.Descripcion'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.ValorUnitario'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.Importe'] }}</td>
                                    <td>{{ $nom['Conceptos.Concepto.0.Descuento'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Version'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.TipoNomina'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.FechaPago'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.FechaInicialPago'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.FechaFinalPago'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.NumDiasPagados'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.TotalPercepciones'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.TotalDeducciones'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.TotalOtrosPagos'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Emisor.RegistroPatronal'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.Curp'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.NumSeguridadSocial'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.FechaInicioRelLaboral'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.Antigüedad'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.TipoContrato'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.Sindicalizado'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.TipoJornada'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.TipoRegimen'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.NumEmpleado'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.Departamento'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.Puesto'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.RiesgoPuesto'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.PeriodicidadPago'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.SalarioBaseCotApor'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.SalarioDiarioIntegrado'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Receptor.ClaveEntFed'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Percepciones.TotalSueldos'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Percepciones.TotalGravado'] }}</td>
                                    <td>{{ $nom['Complemento.0.Nomina.Percepciones.TotalExento'] }}</td>

                                    @php
                                        $no = count($nom['Complemento.0.Nomina.Percepciones.Percepcion']);
                                    @endphp

                                    @for ($n = 0; $n < $no; $n++)

                                        <td>{{ $nom['Complemento.0.Nomina.Percepciones.Percepcion.$n.TipoPercepcion'] }}
                                        </td>
                                        <td>{{ $nom['Complemento.0.Nomina.Percepciones.Percepcion.$n.Clave'] }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <input type="button" name="btnExportar" id="btnExportar" class="btn btn-primary" value="Exportar a Excel"
                        onclick="ExportToExcel(jQuery('#tabla').prop('outerHTML'))" />
                </div>

            @break

            @default

        @endswitch
        <br>

    </div>


@endsection
