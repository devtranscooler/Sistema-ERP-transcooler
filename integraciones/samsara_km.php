<?php

require '../system/connection.php';

function actualizarKilometrosSamsara($conexion){

    $token = $_ENV['SAMSARA_API'];

    $url = "https://api.samsara.com/fleet/vehicles/stats?types=gpsOdometerMeters";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    if(curl_errno($ch)){
        echo "Error CURL: " . curl_error($ch);
        return;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if(!isset($data['data'])){
        echo "No se recibieron datos";
        return;
    }

    foreach($data['data'] as $vehiculo){

        $eco = $vehiculo['name'];

        if(isset($vehiculo['gpsOdometerMeters']['value'])){

            $metros = $vehiculo['gpsOdometerMeters']['value'];

            $km = round($metros / 1000);

            $sql = "UPDATE cat_unidades 
                    SET km = '$km'
                    WHERE eco = '$eco'";

            mysqli_query($conexion,$sql);

        }

    }

    echo "Kilómetros actualizados correctamente";

}

actualizarKilometrosSamsara($conexion);