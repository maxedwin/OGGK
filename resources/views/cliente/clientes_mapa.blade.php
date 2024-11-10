@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Mapa de Clientes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/list_clientes"></i>Mapa de Clientes</a></li>
@stop
<!-- MENU AUXLIAR -->


<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')
<div id="map" style="width: 100%; height: 90vh;"></div>


<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

<script>

    var map = L.map('map', {
        center: [-16.39889, -71.535],
        zoom: 13
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);


    $( document ).ready(function() {
        $.get( "{{route('ubicaciones_clientes')}}" ,{},function(data){
                    console.log(data);
                    data.map( (item) => {
                        L.marker([item.latitud, item.longitud]).addTo(map)
                        .bindPopup('<p>'+item.razon_social+'</p>'+ (item.sucursal_direccion || item.direccion))
                        .openPopup();
                    })
                });
    });

</script>

    
@stop