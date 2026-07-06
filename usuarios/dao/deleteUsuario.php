<?php
require '../../system/connection.php';

$id_usuario = $_POST['id'] ?? '';


if (!$id_usuario) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE usuarios SET ";
$q .= "estatus = 'eliminado' ";
$q .= "WHERE id = '$id_usuario'";

if ($conn->query($q) === TRUE) {
    echo "El usuario ha sido eliminado.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>