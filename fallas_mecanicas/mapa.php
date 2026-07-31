<?php

// 1. Tus datos de entrada
$coordenadas = "19.653478,-99.186443"; // El par que te da Samsara
$apiKey = "TU_API_KEY_DE_GOOGLE"; // Reemplaza con tu llave de Google

// 2. Construir la URL de la API de Google
// Añadimos 'language=es' para asegurar que los nombres de estados/países vengan en español
$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . urlencode($coordenadas) . "&key=" . $apiKey . "&language=es";

// 3. Realizar la petición HTTP con cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// 4. Decodificar la respuesta JSON
$data = json_decode($response, true);

// Variables para guardar los campos que necesitas
$calle = "";
$numero = "";
$colonia = "";
$alcaldia_municipio = "";
$estado = "";
$pais = "";
$cp = "";
$direccion_completa = "";

if ($data['status'] == 'OK') {
    
    // Google devuelve una lista de direcciones ordenadas de la más específica a la más general.
    // Usamos el primer resultado [0], que es el más preciso (nivel de calle/número).
    $primer_resultado = $data['results'][0];
    $direccion_completa = $primer_resultado['formatted_address'];
    
    // Recorremos los componentes de la dirección para separarlos limpiamente
    foreach ($primer_resultado['address_components'] as $componente) {
        $tipos = $componente['types'];
        
        if (in_array('route', $tipos)) {
            $calle = $componente['long_name'];
        }
        if (in_array('street_number', $tipos)) {
            $numero = $componente['long_name'];
        }
        if (in_array('sublocality_level_1', $tipos) || in_array('political', $tipos) && in_array('sublocality', $tipos)) {
            $colonia = $componente['long_name'];
        }
        if (in_array('locality', $tipos) || in_array('administrative_area_level_2', $tipos)) {
            $alcaldia_municipio = $componente['long_name'];
        }
        if (in_array('administrative_area_level_1', $tipos)) {
            $estado = $componente['long_name'];
        }
        if (in_array('country', $tipos)) {
            $pais = $componente['long_name'];
        }
        if (in_array('postal_code', $tipos)) {
            $cp = $componente['long_name'];
        }
    }
    
    // 5. Armar tu cuadro de texto personalizado o usar las variables por separado
    echo "<h3>Dirección Desglosada:</h3>";
    echo "Calle: " . $calle . " #" . $numero . "<br>";
    echo "CP: " . $cp . "<br>";
    echo "Colonia: " . $colonia . "<br>";
    echo "Alcaldía/Municipio: " . $alcaldia_municipio . "<br>";
    echo "Estado: " . $estado . "<br>";
    echo "País: " . $pais . "<br><br>";
    
    echo "<h3>Dirección Completa en un solo texto (Formato de Google):</h3>";
    echo $direccion_completa;

} else {
    echo "Error al consultar la API de Google: " . $data['status'];
}

?>