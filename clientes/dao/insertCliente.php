<?php
require '../../system/connection.php';

// Helper function to wrap non-empty values with quotes or return NULL
function sqlValue($value) {
    return $value !== '' ? "'" . addslashes($value) . "'" : "NULL";
}

$nombre_razon = $_POST['nombre_razon'] ?? '';
$tipo_cliente = $_POST['tipo_cliente'] ?? '';
$calle = $_POST['calle'] ?? '';
$codigo_postal = $_POST['codigo_postal'] ?? '';
$num_ext = $_POST['num_ext'] ?? '';
$num_int = $_POST['num_int'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();

// Build SQL query with proper NULL handling
$q="";
$q = $q."INSERT INTO clientes (" ;
$q = $q." nombre_razon, ";
$q = $q." tipo_cliente, ";
$q = $q." calle, ";
$q = $q." codigo_postal, ";
$q = $q." num_ext, ";
$q = $q." num_int)";
$q = $q." VALUES ( ";
$q = $q." " . sqlValue($nombre_razon) . ", ";
$q = $q." " . sqlValue($tipo_cliente) . ", ";
$q = $q." " . sqlValue($calle) . ",";
$q = $q." " . sqlValue($codigo_postal) . ", ";
$q = $q." " . sqlValue($num_ext) . ", ";
$q = $q." " . sqlValue($num_int) . " )";

if ($conn->query($q) === TRUE) {
    echo "Cliente agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>