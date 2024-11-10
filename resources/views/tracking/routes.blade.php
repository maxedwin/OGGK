@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-map-marker position-left"></i> <span class="text-semibold">RUTAS</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li> Rutas </li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

@stop

<!-- CONTENIDO DE LA PAGINA -->

@section('contenido')

	<style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>

    <body>
	    <h3>Tracking</h3>
	    <!--The div element for the map -->
	    <div id="map"></div>
  	</body>

    <script>
		// Initialize and add the map
		function initMap() {
		  // The location of Uluru
		  var uluru = {lat: -16.3930442, lng: -71.5367744};
		  // The map, centered at Uluru
		  var map = new google.maps.Map(
		      document.getElementById('map'), {zoom: 4, center: uluru});
		  // The marker, positioned at Uluru
		  var marker = new google.maps.Marker({position: uluru, map: map});
		}
    </script>
    <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAy1hxZ_RZlAoM0WhpizWLu9w4DIylX5jQ&callback=initMap">
    </script>

@stop