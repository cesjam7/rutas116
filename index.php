<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RUTAS 116</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<style>
	.center { text-align: center;}
	h1{ font-size: 50px; }
	ul.list-group { margin-top: 20px; }
	</style>
</head>
<body>
	<div class='container-fluid'>
		<h1 class='page-header center'>RUTAS 116</h1>
		<h3 class='center'>Te indicamos como llegar a tu emergencia</h3>
		<p class='center'>Permite al navegador obtener tu ubicación para poder guiarte como llegar a una emergencia reportada por el Cuerpo de Bomberos del Perú. Página no oficial.</p>
		<p><select class="form-control input-lg" id="emergencias"></select></p>
		<p><a class='btn btn-primary btn-lg btn-block' id='start_travel'>VER RUTA</a></p>
		<div id="map" class='col-xs-10 col-xs-offset-1 img-thumbnail' style='height:500px;'></div>
		<div class="clearfix"></div>
		<ul class="list-group" id="instructions"></ul>
		<div id='lat' data=""></div>
		<div id='lng' data=""></div>
		<p>Desarrollado por <a href="http://twitter.com/cesjam7">@cesjam7</a>. Puedes ver mas datos en <a href="http://databomberos.com">#DataBomberos</a>.</p>
	</div>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript" src="https://hpneo.github.io/gmaps/gmaps.js"></script>
	<script type="text/javascript" src="https://hpneo.github.io/gmaps/prettify/prettify.js"></script>
	<script type="text/javascript">
	var map;
	$(document).ready(function(){
		prettyPrint();
		map = new GMaps({
			div: '#map',
			lat: -12.043333,
			lng: -77.028333
		});

		GMaps.geolocate({
			success: function(position){
				$('#lat').attr('data', position.coords.latitude);
				$('#lng').attr('data', position.coords.longitude);
				map.setCenter(position.coords.latitude, position.coords.longitude);
				map.addMarker({
					lat: position.coords.latitude,
					lng: position.coords.longitude,
					title: 'Origen',
					click: function(e) {
						alert('Tu ubicación actual');
					}
				});
			},
			error: function(error){
				alert('Geolocation failed: '+error.message);
			},
			not_supported: function(){
				alert("Your browser does not support geolocation");
			},
			always: function(){
				//alert("Done!");
			}
		});

		$('#start_travel').click(function(e){

			var origin_lat = $('#lat').attr('data');
			var origin_lng = $('#lng').attr('data');

			var emergencia = $("#emergencias").val();

			if(emergencia == 0){
				alert('Debes seleccionar una emergencia de destino');
			}
			var latlng = emergencia.split("&");

			if(latlng[0] == 'Sin definir'){
				alert('La emergencia no muestra ubicaci0n geografica');
			}

			var destination_lat = latlng[0];
			var destination_lng = latlng[1];


			map.addMarker({
				lat: destination_lat,
				lng: destination_lng,
				title: 'Destino',
				click: function(e) {
					alert(latlng[2]);
				}
			});

			e.preventDefault();
			map.travelRoute({
				origin: [origin_lat, origin_lng],
				destination: [destination_lat, destination_lng],
				travelMode: 'driving',
				step: function(e){
					$('#instructions').append('<li class="list-group-item">'+e.instructions+'</li>');
					$('#instructions li:eq('+e.step_number+')').delay(450*e.step_number).fadeIn(200, function(){
						map.setCenter(e.end_location.lat(), e.end_location.lng());
						map.drawPolyline({
							path: e.path,
							strokeColor: '#131540',
							strokeOpacity: 0.6,
							strokeWeight: 6
						});
					});
				}
			});
		});

		$.getJSON( "http://databomberos.com/json/", function( data ) {
			var items = ['<option value="0"> - Seleccionar emergencia - </option>'];
			$.each( data, function( key, val ) {
				items.push('<option value="'+val.lat+'&'+val.lng+'&'+val.tipo+': '+val.direccion+'">'+val.tipo+': '+val.direccion+'</option>');
			});
			$('#emergencias').html(items.join(""));

		});

	});
	</script>
</body>
</html>
