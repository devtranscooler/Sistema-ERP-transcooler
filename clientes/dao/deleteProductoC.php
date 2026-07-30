<?php
require '../../system/connection.php';

$id_producto_cliente = $_POST['id'] ?? '';


if (!$id_producto_cliente) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE productos_clientes SET ";
$q .= "status = 'eliminado' ";
$q .= "WHERE id_producto_cliente = '$id_producto_cliente'";

if ($conn->query($q) === TRUE) {
    echo "El Producto ha sido eliminada.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>