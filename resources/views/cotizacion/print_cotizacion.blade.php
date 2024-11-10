<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CT <?PHP echo str_pad($sucursal->serie, 3, "0", STR_PAD_LEFT);?> - <?PHP echo str_pad($cotizacion->numeracion, 6, "0", STR_PAD_LEFT);?></title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <style>
        #invoice{
            padding: 30px;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            margin-bottom: 20px;
            border-bottom: 1px solid #3989c6
        }

        .invoice header .cotizacion{
            text-align: center;
            border: groove;
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice header .logo{
            text-align: center;
            margin-top: 0;
            margin-bottom: 5px
        }

        .invoice .company-details {
            text-align: center;
            font-size:  9px;
        }

        .invoice .contacts {
            margin-bottom: 20px;
            border: groove;
        }

        .invoice .invoice-to {
            text-align: left;
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: right
        }

        .invoice main {
            padding-bottom: 50px
        }

        .invoice main .thanks {
            margin-top: -100px;
            font-size: 2em;
            margin-bottom: 50px
        }

        .invoice main .notices {
            padding-left: 6px;
            border: groove;
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice main .notices .notice .tab {
            position:absolute;
            left:230px; 
        }    
        

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px
        }

        .invoice table thead th{
            background: #3989c6;
            color: #fff;
            text-align: center
        }

        .invoice table td,.invoice table th {
            padding: 15px;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #3989c6;
            font-size: 1.2em
        }

        .invoice .invoice table .total,.invoice table .unit {
        }

        .invoice .invoice table .no,.invoice table .qty {
        }

        .invoice table .no {
        }

        .invoice table .unit {
        }

        .invoice table .total {
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            border-top: 1px solid
        }

        .invoice table tfoot tr:first-child td {
            border-top: 1px solid
        }

        .invoice table tfoot tr:last-child td {
            border-top: 1px solid
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid;
            padding: 8px 0
        }

        @media print {
            .invoice {
                font-size: 11px!important;
                overflow: hidden!important
            }

            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }

            .invoice>div:last-child {
                page-break-before: always
            }
        }
    </style>
</head>

<div id="invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            
            <header>
                <div class="row">
                    <div class="col logo">
                        <img src="/images/logo_docs.png" data-holder-rendered="true" style="width:70%;height:auto;" />
                    </div>
                    <div class="col company-details">
                        <div>SOLUCIONES OGGK S.A.C.</div>
                        <div>RUC: {{ $sucursal->ruc  }}</div>
                        <div>OF: {{ $sucursal->direccion  }}</div>
                        <div>CEL: 999295220</div>
                        <div>TELF: {{ $sucursal->telefono  }}</div>
                        <div>O.GUTIERREZ@SOLUCIONESOGGK.COM</div>
                    </div>
                    <div class="col">
                        <h4 class="cotizacion">
                            <div>COTIZACIÓN</div>
                            <div>CT <?PHP echo str_pad($sucursal->serie, 3, "0", STR_PAD_LEFT);?> - <?PHP echo str_pad($cotizacion->numeracion, 6, "0", STR_PAD_LEFT);?></div>
                        </h4>                        
                    </div>
                </div>
            </header>
        
            <main>
                <div class="contacts">
                    <div class="row">
                        <div class="col invoice-to">
                            <div><b>CLIENTE: </b>{{$cliente->razon_social}}</div>
                            <div><b>ATENCION: </b>{{ $cliente->contacto_nombre  }}</div>
                            <div><b>RUC: </b>{{ $cliente->ruc_dni  }}</div>
                            <div><b>E-MAIL: </b>{{ $cliente->contacto_email  }}</div>
                        </div>
                        <div class="col invoice-details">
                            <div><b>TELEFONO: </b>{{ $cliente->contacto_telefono  }}</div>
                            <div><b>FECHA: </b>{{ date('d/m/Y', strtotime($cotizacion->created_at)) }}</div>
                        </div>
                    </div>

                    <div><b>DIRECCION: </b>{{ $cliente->direccion }}, {{ $cliente->distrito }}, {{ $cliente->provincia }}, {{ $cliente->departamento }}</div>
                </div>

                <div>Estimado nos es grato enviarle nuestra siguiente cotización:</div><br>

                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>CANT.</th>
                            <th>UNID.</th>
                            <th>DESCRIPCIÓN</th>
                            <th>V. UNITARIO (S/.)</th>
                            <th>V. TOTAL (S/.)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($cotizacionD as $product)
                        <tr>
                            <td class="text-center"> 01 </td>
                            <td class="text-center"> {{ $product->cantidad }}  </td>
                            <td class="text-center"> {{ $product->medida_venta }}  </td>
                            <td class="text-left">   {{ $product->nombre }}  </td>
                            <td class="text-right">  {{ sprintf("%.2f",round($product->precio_unit,2)) }} </td>
                            <td class="text-right">  {{ sprintf("%.2f",round($product->precio_unit,2) * $product->cantidad) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="3">SUBTOTAL</td>
                            <td>{{ sprintf("%.2f", round($cotizacion->subtotal,2)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="3">IGV 18%</td>
                            <td>{{ sprintf("%.2f",round($cotizacion->igv,2)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="3">TOTAL</td>
                            <td>{{ sprintf("%.2f",round($cotizacion->total,2)) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row notices">
                    <div class="col notice">  
                        <b>Condiciones generales de la venta:</b><br>
                        Precios:                <span class="tab">En Nuevos Soles, los valores unitarios no incluyen IGV.</span><br>
                        Plazo de Entrega:       <span class="tab">1 día posterior a su orden de compra, salvo variación de stock y cantidad requerida.</span><br>
                        Condiciones de pago:    <span class="tab">A tratar. Nro de Cta. Ahorros BCP: 215-32428316-0-70 . CCI: 002-21513242831607026 . A nombre de: Soluciones OGGK SAC. Ruc: 20600819667</span><br><br>
                        Lugar de Entrega:       <span class="tab">En Arequipa Metropolitana</span><br>
                        Validez de la Oferta:   <span class="tab">15 días</span><br>
                        Observaciones: <br>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

