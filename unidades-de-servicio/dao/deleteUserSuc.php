<?php
require '../../system/connection.php';

$id_plantilla = $_POST['id'] ?? '';


if (!$id_plantilla) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE plantilla SET ";
$q .= "status = 'eliminado' ";
$q .= "WHERE id_plantilla = '$id_plantilla'";

if ($conn->query($q) === TRUE) {
    echo "El Usuario ha sido eliminado de esta sucursal.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>