<?php
require '../../system/connection.php';

// Get form data
$id_usuario = $_POST['usuario'] ?? '';
$id_sucursal = $_POST['id_sucursal'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();

// Get full name using CONCAT
$nombre_usuario = '';
if ($id_usuario !== '') {
    $query = "SELECT CONCAT(nombre, ' ', apellidoP, ' ', apellidoM) as nombreCompleto 
              FROM usuarios 
              WHERE id = '$id_usuario' 
              AND (estatus IS NULL OR estatus != 'eliminado') 
              LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre_usuario = $row['nombreCompleto'];
    } else {
        echo "Error: Usuario no encontrado o eliminado.";
        exit;
    }
} else {
    echo "Error: Datos incompletos.";
    exit;
}
$checkQuery = "SELECT COUNT(*) AS total FROM plantilla WHERE id_sucursal = '$id_sucursal' AND id_usuario = '$id_usuario'";
$checkResult = $conn->query($checkQuery);
$checkRow = $checkResult->fetch_assoc();
if ($checkRow['total'] > 0) {
    echo "Este usuario ya está en la plantilla.";
    exit;
}

// Build insert query
$q = "";
$q .= "INSERT INTO plantilla ";
$q .= "    (id_sucursal, ";
$q .= "     id_usuario, ";
$q .= "     nombre_usuario) ";
$q .= "VALUES ";
$q .= "    ('$id_sucursal', ";
$q .= "     '$id_usuario', ";
$q .= "     '$nombre_usuario')";

// Execute query
if ($conn->query($q) === TRUE) {
    echo "Usuario agregado a plantilla con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>