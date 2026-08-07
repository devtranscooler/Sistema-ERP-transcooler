<?php
require '../../system/connection.php';

$id_contacto = $_POST['id'] ?? '';


if (!$id_contacto) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE contactos SET ";
$q .= "status = 'eliminado' ";
$q .= "WHERE id_contacto = '$id_contacto'";

if ($conn->query($q) === TRUE) {
    echo "El Contacto ha sido eliminada.";
} else {
    echo "Error al actualizar estado: " . $conn->error;
}

$conn->close();
?>