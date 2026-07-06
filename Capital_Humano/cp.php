<?php

$input = "CPdescarga.txt";   // archivo SEPOMEX
$output = "sepomex3.sql";

$handle = fopen($input, "r");
$sql = fopen($output, "w");

if (!$handle) {
    die("No se pudo abrir el archivo");
}

// BOM UTF-8
fwrite($sql, "\xEF\xBB\xBF");

// =========================
// FUNCION NORMALIZADORA
// =========================
function normalizar($texto) {

    // Detectar encoding
    $encoding = mb_detect_encoding($texto, ["UTF-8", "ISO-8859-1", "WINDOWS-1252"], true);

    if ($encoding !== "UTF-8") {
        $texto = mb_convert_encoding($texto, "UTF-8", $encoding);
    }

    // Corregir doble encoding
    $texto = utf8_decode($texto);
    $texto = mb_convert_encoding($texto, "UTF-8", "ISO-8859-1");

    // Reemplazos comunes
    $reemplazos = [
        'Ã¡' => 'á','Ã©' => 'é','Ã­' => 'í','Ã³' => 'ó','Ãº' => 'ú',
        'Ã�' => 'Á','Ã‰' => 'É','Ã�' => 'Í','Ã“' => 'Ó','Ãš' => 'Ú',
        'Ã±' => 'ñ','Ã‘' => 'Ñ',
        '�'  => ''
    ];

    $texto = strtr($texto, $reemplazos);

    // Mayúsculas
    return mb_strtoupper(trim($texto), "UTF-8");
}

// =========================
// SALTAR ENCABEZADO
// =========================
fgets($handle);

$estados = [];
$municipios = [];
$cp_data = [];

// =========================
// LECTURA
// =========================
while (($line = fgets($handle)) !== false) {

    $cols = explode("|", $line);

    if (count($cols) < 5) continue;

    // Saltar encabezados repetidos
    if (
        strtoupper(trim($cols[0])) == 'D_CODIGO' ||
        strtoupper(trim($cols[1])) == 'D_ASENTA'
    ) {
        continue;
    }

    $cp = trim($cols[0]);
    $colonia = normalizar($cols[1]);
    $municipio = normalizar($cols[3]);
    $estado = normalizar($cols[4]);

    if (!$cp || !$colonia || !$municipio || !$estado) continue;

    $estados[$estado] = true;
    $municipios[$estado][$municipio] = true;

    $cp_data[] = [
        "cp" => $cp,
        "colonia" => $colonia,
        "municipio" => $municipio,
        "estado" => $estado
    ];
}

// =========================
// INSERT ESTADOS
// =========================
fwrite($sql, "-- ESTADOS\n");
fwrite($sql, "INSERT INTO cat_estados (nombre) VALUES\n");

$i = 0;
$total = count($estados);

foreach ($estados as $estado => $_) {
    $i++;
    $coma = ($i == $total) ? ";" : ",";
    fwrite($sql, "('" . addslashes($estado) . "')$coma\n");
}

// =========================
// INSERT MUNICIPIOS
// =========================
fwrite($sql, "\n-- MUNICIPIOS\n");

foreach ($municipios as $estado => $munis) {

    foreach ($munis as $municipio => $_) {

        fwrite($sql, "
INSERT INTO cat_municipios (nombre, id_estado)
SELECT '" . addslashes($municipio) . "', id_estado
FROM cat_estados
WHERE nombre = '" . addslashes($estado) . "';
");
    }
}

// =========================
// INSERT CODIGOS POSTALES (CON ID_MUNICIPIO)
// =========================
fwrite($sql, "\n-- CODIGOS POSTALES\n");

$batchSize = 300; // menor tamaño por uso de UNION
$chunks = array_chunk($cp_data, $batchSize);

foreach ($chunks as $chunk) {

    $selects = [];

    foreach ($chunk as $row) {

        $selects[] = "SELECT 
'" . $row['cp'] . "' AS codigo_postal,
'" . addslashes($row['colonia']) . "' AS colonia,
m.id_municipio
FROM cat_municipios m
JOIN cat_estados e ON e.id_estado = m.id_estado
WHERE m.nombre = '" . addslashes($row['municipio']) . "'
AND e.nombre = '" . addslashes($row['estado']) . "'";
    }

    fwrite($sql, "
INSERT INTO cat_codigos_postales (codigo_postal, colonia, id_municipio)
" . implode("\nUNION ALL\n", $selects) . ";
");
}

// =========================
// CIERRE
// =========================
fclose($handle);
fclose($sql);

echo "Archivo SQL generado correctamente";