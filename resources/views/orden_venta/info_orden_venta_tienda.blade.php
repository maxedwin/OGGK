<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORDEN Nº {{ $order->numeracion }}</title>

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
            border-bottom: 1px solid #fe980f
        }

        .invoice header .orden_venta{
            text-align: center;
            padding: 5px;
            border: groove;
            margin-top: 0;
            margin-bottom: 0;
        }

        .invoice main .contacts .invoice-to .tab {
            display: inline-block;
            margin-left: 40px;
        }    
        

        .invoice header .logo{
            text-align: center;
            margin-top: 0;
            margin-bottom: 5px
        }

        .invoice .company-details {
            text-align: center;
            font-size:  15px;
        }

        .invoice .contacts {
            margin-bottom: 20px;
            padding: 10px;
            border: groove;
        }

        .invoice .invoice-to {
            text-align: left;
            /*margin-left: 10px;*/
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
            font-size: 1.2em;
            margin-left: 10px;
            margin-top: 2px;
            margin-bottom: 2px
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
            background: #fe980f;
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
<?PHP   
        $costoenv = 0;
        $costoenv = $send_method->precio;
        $totalSinEnvio=$order->total-$costoenv;
        $subtotal=$totalSinEnvio*100/118;
                        
?>

<div id="invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            
            <header>
                <div class="row">
                    <div class="col logo">
                        <img src="/images/logo.jpg" alt="" width="200" height="120"/>
                    </div>
                    <div class="col company-details">
                        <div><b>SOLUCIONES OGGK SAC</b></div>
                        <div>Pasaje La Ronda 107, Cayma, Arequipa</div>
                        <div><a href="https://api.whatsapp.com/send?phone=51999295220"><i class="fa fa-whatsapp"></i>999 295 220</a></div>
                        <div>o.gutierrez@solucionesoggk.com</div>
                    </div>
                    <div class="col">
                        <h4 class="orden_venta">
                            <div>ORDEN Nº {{ $order->numeracion }}</div>
                        </h4>                        
                    </div>
                </div>
            </header>
        
            <main>
                <div class="contacts">
                    <div class="row">
                        <div class="col invoice-to">
                            <div><b>Cliente: </b>{{$user->name}}</div>
                            <div><b>DIRECCION: </b>{{ $user->direccion  }}, {{ $user->distrito  }}</div>
                            <div><b>CELULAR: </b>{{ $user->telefono  }}</div>
                        </div>
                        <div class="col invoice-details">
                            <div><b>FECHA: </b>{{ date('d/m/Y', strtotime($order->created_at)) }}</div>
                        </div>
                    </div>
                </div>

                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>DESCRIPCIÓN</th>
                            <th>UNIDAD MEDIDA</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($orderD); $i++)
                            <tr>
                                <td class="text-center"> {{ $i+1 }}                    </td>
                                <td class="text-center"> {{ $orderD[$i]->nombre }}   </td>
                                <td class="text-center"> UND                     </td>
                                <td class="text-center"> {{ $orderD[$i]->cantidad }}                       </td>
                                <td class="text-center"> {{ sprintf("%.2f",round($orderD[$i]->precio_unit*$orderD[$i]->cantidad,2)) }} </td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">SUBTOTAL</td>
                            <td>S/ {{ sprintf("%.2f", ($subtotal)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">IGV</td>
                            <td>S/ {{ sprintf("%.2f", ($totalSinEnvio-($subtotal))) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">ENVÍO</td>
                            <td>S/ {{ sprintf("%.2f", ($costoenv)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">TOTAL</td>
                            <td>S/ {{ sprintf("%.2f",round($order->total,2)) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="contacts">
                    <div class="row">  
                        <div class=" col invoice-to">
                            <b>Condiciones generales de la venta:</b><br>
                            <div>Precios:                
                                <span class="tab">Precios unitarios incluyen IGV.
                                </span>
                            </div>
                            <div>Plazo de Entrega:       
                                <span class="tab">2 días posteriores a su orden, salvo variación de stock y cantidad requerida.</span>
                            </div>
                            <!--div>Lugar de Entrega:       
                                <span class="tab">En Arequipa Metropolitana</span>
                            </div-->
                            <!--div>Método de pago:    
                                <span class="tab">{{($order->payment_method=="cash")?' Efectivo':'Tarjeta'}}</span>
                            </div-->
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
</div>

