<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app/Env.php';
Env::load(__DIR__.'/../.env');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../system/connection.php';

try {

    $db = new MySQL();

    // 🔐 TOKEN SAMSARA
    $token = $_ENV['SAMSARA_API'];

    echo "<h2>Actualización de Kilometraje</h2>";
    echo "<hr>";

    /* =========================================
       1️⃣ TRAER TODAS LAS UNIDADES
    ========================================= */

    $urlVehicles = "https://api.samsara.com/fleet/vehicles";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $urlVehicles,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CAINFO => __DIR__ . "/../certs/cacert.pem",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ]
    ]);

    $responseVehicles = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Error CURL (vehículos): " . curl_error($ch));
    }

    $vehiclesData = json_decode($responseVehicles, true);

    if (!isset($vehiclesData['data'])) {
        throw new Exception("No se recibieron vehículos");
    }

    $vehicles = $vehiclesData['data'];

    /* =========================================
       2️⃣ TRAER LOS ODOMETROS
    ========================================= */

    $urlStats = "https://api.samsara.com/fleet/vehicles/stats?types=obdOdometerMeters,gpsOdometerMeters";

    curl_setopt($ch, CURLOPT_URL, $urlStats);

    $responseStats = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Error CURL (odómetros): " . curl_error($ch));
    }

    curl_close($ch);

    $statsData = json_decode($responseStats, true);

    if (!isset($statsData['data'])) {
        throw new Exception("No se recibieron odómetros");
    }

    $stats = $statsData['data'];

    /* =========================================
       3️⃣ CREAR MAPA vehicleId → KM
    ========================================= */

    $kmMap = [];

    foreach ($stats as $s) {

        if (isset($s['obdOdometerMeters']['value'])) {
            $km = $s['obdOdometerMeters']['value'] / 1000;

        } elseif (isset($s['gpsOdometerMeters']['value'])) {
            $km = $s['gpsOdometerMeters']['value'] / 1000;

        } else {
            $km = 0;
        }

        $kmMap[$s['id']] = round($km);
    }

    /* =========================================
       4️⃣ ACTUALIZAR BASE DE DATOS
    ========================================= */

    $actualizadas = 0;

    foreach ($vehicles as $v) {

        $eco = $v['name'];
        $vehicleId = $v['id'];

        $km = isset($kmMap[$vehicleId]) ? $kmMap[$vehicleId] : 0;

        $sql = "UPDATE cat_unidades
                SET km = '$km'
                WHERE eco = '$eco'
                AND km < '$km'";

        if (!$db->consulta($sql)) {
            throw new Exception("Error al actualizar unidad $eco");
        }

        echo "✅ Unidad <b>$eco</b> actualizada a <b>$km km</b><br>";

        $actualizadas++;
    }

    echo "<hr>";
    echo "<b>Total de unidades procesadas:</b> $actualizadas<br>";

} catch (Exception $e) {

    echo "<div style='color:red; font-weight:bold;'>";
    echo "❌ ERROR AL ESTILO CHARLY: " . $e->getMessage();
    echo "</div>";

}

echo "<br><br><a href='index.php' class='btn btn-primary'>Regresar</a>";