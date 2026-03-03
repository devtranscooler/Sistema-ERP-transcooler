<?php
/**
 * procesar_fiscal.php
 * Recibe un PDF fiscal via AJAX → Document AI → BD → responde JSON
 */
header('Content-Type: application/json; charset=utf-8');

// ─── Configuración ────────────────────────────────────────────────────────────
define('GCP_CREDENTIALS', __DIR__ . '/../system/agentes/transcooler-480721-69e587eae341.json');
define('GCP_PROJECT_ID',  'transcooler-480721');        
define('GCP_LOCATION',    'us');
define('GCP_PROCESSOR',   '4643122a2495a98a');         


// ─── Validar petición ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['error' => 'Método no permitido']);
}
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    responder(400, ['error' => 'No se recibió ningún archivo válido']);
}

$tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png', 'image/tiff'];
$mimeType        = mime_content_type($_FILES['archivo']['tmp_name']);
if (!in_array($mimeType, $tiposPermitidos)) {
    responder(415, ['error' => "Tipo de archivo no permitido: $mimeType"]);
}

// ─── Codificar archivo ────────────────────────────────────────────────────────
$contenido = file_get_contents($_FILES['archivo']['tmp_name']);
if ($contenido === false) responder(500, ['error' => 'No se pudo leer el archivo']);
$encoded = base64_encode($contenido);

// ─── Token de Google ──────────────────────────────────────────────────────────
$accessToken = obtenerAccessToken(GCP_CREDENTIALS);
if (!$accessToken) responder(500, ['error' => 'No se pudo autenticar con Google']);

// ─── Llamar a Document AI ─────────────────────────────────────────────────────
$processorPath = sprintf('projects/%s/locations/%s/processors/%s',
    GCP_PROJECT_ID, GCP_LOCATION, GCP_PROCESSOR);
$endpoint = sprintf('https://%s-documentai.googleapis.com/v1/%s:process',
    GCP_LOCATION, $processorPath);

$respuestaAI = llamarDocumentAI($endpoint, $accessToken, $encoded, $mimeType);

if (!$respuestaAI || !isset($respuestaAI['document']['text'])) {
    responder(500, ['error' => 'Document AI no devolvió texto', 'detalle' => $respuestaAI]);
}

// ─── Extraer, guardar y responder ─────────────────────────────────────────────
$datos       = extraerDatos($respuestaAI['document']['text']);

responder(200, ['success' => true, 'datos' => $datos]);


// =============================================================================
// FUNCIONES
// =============================================================================

function obtenerAccessToken(string $ruta): ?string
{
    if (!file_exists($ruta)) return null;
    $creds = json_decode(file_get_contents($ruta), true);
    $now   = time();

    $header  = b64u(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $payload = b64u(json_encode([
        'iss'   => $creds['client_email'],
        'scope' => 'https://www.googleapis.com/auth/cloud-platform',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600,
    ]));

    $firma = '';
    openssl_sign("$header.$payload", $firma, $creds['private_key'], 'SHA256');
    $jwt = "$header.$payload." . b64u($firma);

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]),
    ]);
    $res = json_decode(curl_exec($ch), true);
    return $res['access_token'] ?? null;
}

function llamarDocumentAI(string $url, string $token, string $encoded, string $mime): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode(['rawDocument' => ['content' => $encoded, 'mimeType' => $mime]]),
        CURLOPT_HTTPHEADER     => ["Authorization: Bearer $token", 'Content-Type: application/json'],
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    if ($err) { error_log("cURL Document AI: $err"); return null; }
    return json_decode($res, true);
}

function extraerDatos(string $texto): array
{    
    preg_match('/[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}/',                           $texto, $rfc);
    preg_match('/Denominaci[oó]n\/Raz[oó]n Social\s*\n(.+)/u',             $texto, $razon);
    if (empty($razon)) preg_match('/Registro Federal de Contribuyentes\s*\n(.+)/u', $texto, $razon);
    preg_match('/A\s+(\d{1,2}\s+DE\s+[A-ZÁÉÍÓÚÜ]+\s+DE\s+\d{4})/ui',    $texto, $fecha);
    //regimen fizcal
    preg_match('/R[eé]gimen\s+(R[eé]gimen\s+.+?)\s+Fecha\s+Inicio/iu', $texto, $regimen);
    
    preg_match('/C[oó]digo Postal\s*[:\-]?\s*(\d{5})|C\.?P\.?\s*(\d{5})/u', $texto, $cp);
    // Captura todo hasta el siguiente campo conocido o fin de línea
    preg_match('/N[uú]mero\s+Exterior\s*:\s*(.*?)(?=\s{2,}[A-Z]|\n|$)/ui', $texto, $num_ext);
    preg_match('/N[uú]mero\s+Interior\s*:\s*(.*?)/ui', $texto, $num_int);
    preg_match('/Nombre\s+de\s+Vialidad\s*[:\-]?\s*(.+)/iu', $texto, $calle);
    

    return [
        'rfc'          => $rfc[0]           ?? null,
        'razon_social' => isset($razon[1])  ? trim($razon[1])    : null,
        'fecha'        => $fecha[1]         ?? null,
        'regimen'      => $regimen[1] ?? null,
        'codigo_postal' => $cp[1] ?? null,
        'num_ext'      => $num_ext[1]         ?? null, 
        'num_int'      => $num_int[1]         ?? null,  
        'calle'      => $calle[1]         ?? null,  
    ];
}

function b64u(string $d): string { return rtrim(strtr(base64_encode($d), '+/', '-_'), '='); }
function responder(int $c, array $b): void { http_response_code($c); echo json_encode($b, JSON_UNESCAPED_UNICODE); exit; }