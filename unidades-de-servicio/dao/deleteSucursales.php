<?php
require '../../system/connection.php';

$id_sucursal = $_POST['id'] ?? '';


if (!$id_sucursal) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE sucursales SET ";
$q .= "Status = 'eliminado' ";
$q .= "WHERE id_sucursal = '$id_sucursal'";

if ($conn->query($q) === TRUE) {
    echo "La Sucursal ha sido eliminada.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>