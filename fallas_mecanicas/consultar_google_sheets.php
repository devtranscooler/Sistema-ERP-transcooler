<?php
$eco = isset($_GET['eco']) ? trim($_GET['eco']) : '';

if (empty($eco)) {header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'mensaje' => 'ECO no proporcionado']); exit;
}

$token = '317247';
$script_url = 'https://script.google.com/macros/s/AKfycbwjWDZn4Ob4iZJecDCSbMGjoUFif_BI6vP1GGkM19NuNRZ9AtrtYMT3QhPAoapuqf-7_A/exec';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $script_url . '?token=' . $token . '&eco=' . urlencode($eco));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
header('Content-Type: application/json');

if ($http_code != 200 || $response === false) {echo json_encode(['ok' => false,'mensaje' => 'Error al consultar']);exit;}
$data = json_decode($response, true);
if ($data && isset($data['ok']) && $data['ok']) {echo json_encode([
        'ok' => true,
        'origen' => $data['origen'] ?? '',
        'destino' => $data['destino'] ?? '',
        'cliente' => $data['cliente'] ?? '']);
} else {echo json_encode(['ok' => false,'mensaje' => $data['mensaje'] ?? 'No se encontraron datos']);
}
?>