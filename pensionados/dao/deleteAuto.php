<?php
require '../../system/connection.php';

$id_pensionado = $_POST['id'] ?? '';


if (!$id_pensionado) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE autos SET ";
$q .= "status = 'eliminado' ";
$q .= "WHERE id_pensionado = '$id_pensionado'";

if ($conn->query($q) === TRUE) {
    echo "El Auto ha sido eliminada.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>