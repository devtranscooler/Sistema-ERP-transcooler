<?php
require '../../../system/connection.php';

$id_variante = $_POST['id'] ?? '';


if (!$id_variante) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE variantes SET ";
$q .= "Status = 'eliminado' ";
$q .= "WHERE id_variantes = '$id_variante'";

if ($conn->query($q) === TRUE) {
    echo "La variante ha sido eliminada.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>