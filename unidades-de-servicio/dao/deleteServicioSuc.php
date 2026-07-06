<?php
require '../../system/connection.php';

$id_servicio = $_POST['id'] ?? '';


if (!$id_servicio) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE servicios SET ";
$q .= "status = 'eliminado' ";
$q .= "WHERE id_servicio = '$id_servicio'";

if ($conn->query($q) === TRUE) {
    echo "El Servicio ha sido eliminado de esta sucursal.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>