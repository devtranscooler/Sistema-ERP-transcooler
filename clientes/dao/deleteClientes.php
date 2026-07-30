<?php
require '../../system/connection.php';

$id_cliente = $_POST['id'] ?? '';


if (!$id_cliente) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE clientes SET ";
$q .= "Status = 'eliminado' ";
$q .= "WHERE id_cliente = '$id_cliente'";

if ($conn->query($q) === TRUE) {
    echo "EL cliente ha sido eliminado.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>