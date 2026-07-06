<?php
require '../../system/connection.php';
function sqlValue($value) {
    return $value !== '' ? "'" . addslashes($value) . "'" : "NULL";
}

// Get form data
$nombre_unidad = $_POST['nombre_unidad'] ?? '';
$estado = $_POST['estado'] ?? '';
$municipio = $_POST['municipio'] ?? '';
$modelo_negocio = $_POST['modelo_negocio'] ?? '';
$socio = $_POST['socio'] ?? '';
$empresa = $_POST['empresa'] ?? '';
$fondo = $_POST['fondo'] ?? '';
$renta = $_POST['renta'] ?? '';
$operadora = $_POST['operadora'] ?? '';
$codigo_unidad = $_POST['codigo_unidad'] ?? '';
$fee = $_POST['fee'] ?? '';
$sistema_gestion = $_POST['sistema_gestion'] ?? '';
$link_reporte = $_POST['link_reporte'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$email_gerente = $_POST['email_gerente'] ?? '';
$email_coordinador = $_POST['email_coordinador'] ?? '';
$email_encargado = $_POST['email_encargado'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();
// Insert into database
        $q = "";
        $q=$q." INSERT INTO sucursales"; 
        $q=$q."     (nombre_unidad,";
        $q=$q."     estado,";
        $q=$q."     municipio,";
        $q=$q."     modelo_negocio,";
        $q=$q."     socio,";
        $q=$q."     empresa,";
        $q=$q."     fondo,";
        $q=$q."     operadora,";
        $q=$q."     codigo_unidad,";
        $q=$q."     fee,";
        $q=$q."     sistema_gestion,";
        $q=$q."     link_reporte,";
        $q=$q."     direccion,";
        $q=$q."     email_gerente,";
        $q=$q."     email_coordinador,";
        $q=$q."     email_encargado,";
        $q=$q."     renta)";
        
        $q = $q." VALUES ( ";
        $q = $q." " . sqlValue($nombre_unidad) . ", ";
        $q = $q." " . sqlValue($estado) . ", ";
        $q = $q." " . sqlValue($municipio) . ",";
        $q = $q." " . sqlValue($modelo_negocio) . ",";
        $q = $q." " . sqlValue($socio) . ", ";
        $q = $q." " . sqlValue($empresa) . ", ";
        $q = $q." " . sqlValue($fondo) . ", ";
        $q = $q." " . sqlValue($operadora) . ", ";
        $q = $q." " . sqlValue($codigo_unidad) . ", ";
        $q = $q." " . sqlValue($fee) . ", ";
        $q = $q." " . sqlValue($sistema_gestion) . ", ";
        $q = $q." " . sqlValue($link_reporte) . ", ";
        $q = $q." " . sqlValue($direccion) . ", ";
        $q = $q." " . sqlValue($email_gerente) . ", ";
        $q = $q." " . sqlValue($email_coordinador) . ", ";
        $q = $q." " . sqlValue($email_encargado) . ", ";
        $q = $q." " . sqlValue($renta) . " )";
 

if ($conn->query($q) === TRUE) {
    echo "Sucursal agregada con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>