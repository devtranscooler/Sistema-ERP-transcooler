<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app/Env.php';
Env::load(__DIR__.'/../.env');

header('Content-Type: application/json');

/*=========================
TOKEN SAMSARA
=========================*/

$token = $_ENV['SAMSARA_API'];

/*=========================
URL API
=========================*/

$url =
'https://api.samsara.com/fleet/vehicles/locations';

/*=========================
API GOOGLE
=========================*/

$google_api = $_ENV['GOOGLE_API'] ?? '';

/*=========================
OBTENER ECO
=========================*/

$eco =
trim(
$_GET['eco']
?? ''
);

/*=========================
VALIDAR
=========================*/

if(
empty($eco)
){

echo json_encode([

'ok' => false,

'mensaje' => 'No se recibió unidad'

]);

exit;

}

/*=========================
CURL
=========================*/

$curl =
curl_init();

curl_setopt_array($curl,[

CURLOPT_URL => $url,

CURLOPT_RETURNTRANSFER => true,

CURLOPT_TIMEOUT => 30,

CURLOPT_SSL_VERIFYPEER => false,

CURLOPT_SSL_VERIFYHOST => false,

CURLOPT_HTTPHEADER => [

'Authorization: Bearer '.$token,

'Accept: application/json'

]

]);

$response =
curl_exec($curl);

$error =
curl_error($curl);

curl_close($curl);

/*=========================
ERROR CURL
=========================*/

if(
$error
){

echo json_encode([

'ok' => false,

'mensaje' => 'Error CURL',

'error' => $error

]);

exit;

}

/*=========================
CONVERTIR JSON
=========================*/

$json =
json_decode(
$response,
true
);

/*=========================
VALIDAR RESPUESTA
=========================*/

if(
!isset($json['data'])
){

echo json_encode([

'ok' => false,

'mensaje' => 'Sin datos Samsara',

'respuesta' => $json

]);

exit;

}

/*=========================
BUSCAR UNIDAD
=========================*/

foreach(
$json['data']
as $vehiculo
){

$nombre =
trim(
$vehiculo['name']
?? ''
);

if(
$nombre == $eco
){

$lat =
$vehiculo['location']['latitude']
?? '';

$lon =
$vehiculo['location']['longitude']
?? '';

$time =
$vehiculo['location']['time']
?? '';

/*=========================
VALIDAR LAT/LON
=========================*/

if(
empty($lat)
||
empty($lon)
){

echo json_encode([

'ok' => false,

'mensaje' => 'Unidad sin coordenadas'

]);

exit;

}

/*=========================
LINK GOOGLE MAPS
=========================*/

$link_maps =
'https://www.google.com/maps?q=' .
$lat.
','.
$lon;

/*=========================
OBTENER DIRECCION GOOGLE
=========================*/

$direccion =
'Sin ubicación';

$google =
'https://maps.googleapis.com/maps/api/geocode/json?latlng='.
$lat.
','.
$lon.
'&key='.
$google_api;

$geo =
@file_get_contents($google);

if(
$geo
){

$geojson =
json_decode(
$geo,
true
);

if(
isset(
$geojson['results'][0]['formatted_address']
)
){

$direccion =
$geojson['results'][0]['formatted_address'];

}

}

/*=========================
RESPUESTA EXITOSA
=========================*/

echo json_encode([

'ok' => true,

'eco' => $eco,

'latitud' => $lat,

'longitud' => $lon,

'ultima_actualizacion' => $time,

'maps' => $link_maps,

'ubicacion' => $direccion

]);

exit;

}

}

/*=========================
NO ENCONTRADA
=========================*/

echo json_encode([

'ok' => false,

'mensaje' => 'Unidad no encontrada en Samsara'

]);