<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FR<?PHP echo str_pad($sucursal->serie, 3, "0", STR_PAD_LEFT);?> - <?PHP echo str_pad($ficha_recepcion->numeracion, 6, "0", STR_PAD_LEFT);?></title>

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

        .invoice header .ficha_recepcion{
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
                        <img src="/images/logo.jpg" data-holder-rendered="true" style="width:70%;height:auto;" />
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
                        <h4 class="ficha_recepcion">
                            <div>FICHA DE RECEPCION</div>
                            <div>FR<?PHP echo str_pad($sucursal->serie, 3, "0", STR_PAD_LEFT);?> - <?PHP echo str_pad($ficha_recepcion->numeracion, 6, "0", STR_PAD_LEFT);?></div>
                        </h4>                        
                    </div>
                </div>
            </header>
        
            <main>
                <div class="contacts">
                    <div class="row">
                        <div class="col invoice-to">
                            <div><b>PROVEEDOR: </b>{{$proveedor->razon_social}}</div>
                            <div><b>ATENCION: </b>{{ $proveedor->contacto_nombre  }}</div>
                            <div><b>RUC: </b>{{ $proveedor->ruc_dni  }}</div>
                            <div><b>E-MAIL: </b>{{ $proveedor->contacto_email  }}</div>
                        </div>
                        <div class="col invoice-details">
                            <div><b>TELEFONO: </b>{{ $proveedor->contacto_telefono  }}</div>
                            <div><b>FECHA: </b>{{ date('d/m/Y', strtotime($ficha_recepcion->created_at)) }}</div>
                        </div>
                    </div>

                    <div><b>DIRECCION: </b>{{ $proveedor->direccion }}, {{ $proveedor->distrito }}, {{ $proveedor->provincia }}, {{ $proveedor->departamento }}</div>
                </div>

                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>DESCRIPCIÓN</th>
                            <th>CANT.</th>
                            <th>UNID.</th>
                            <th>LOTE</th>
                        </tr>
                    </thead>
                    <tbody>
                    @for ($i = 0; $i < count($ficha_recepcionD); $i++)
                        <tr>
                            <td class="text-center"> {{ $i+1 }}                               </td>
                            <td class="text-left">   {{ $ficha_recepcionD[$i]->nombre }}       </td>
                            <td class="text-center"> {{ $ficha_recepcionD[$i]->cantidad }}     </td>
                            <td class="text-center"> {{ $ficha_recepcionD[$i]->medida_venta }} </td>
                            <td class="text-left">   {{ $ficha_recepcionD[$i]->codigo }}       </td>
                        </tr>
                    @endfor
                    </tbody>
                    
                </table>

                <div class="row notices">
                    <div class="col notice">  
                        Orden de Compra: <span class="tab">OC-{{ $ficha_recepcion->oc }}</span><br>
                        <!--Guia de Compra: <span class="tab">{{ $ficha_recepcion->gs }}-{{ $ficha_recepcion->gn }} </span><br>-->
                        Observaciones: <span class="tab">{{ $ficha_recepcion->comentarios  }}</span><br><br>
                    </div>
                </div>


            </main>

        </div>
    </div>
</div>

