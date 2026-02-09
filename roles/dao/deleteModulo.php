<?php
require '../../system/connection.php';

// Get form data
$id_usuario = $_POST['id_usuario'] ?? '';
$id_modulo = $_POST['id_modulo'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();

// Build DELETE query
$q = "";
$q .= "DELETE FROM roles ";
$q .= "WHERE id_usuario = '$id_usuario' ";
$q .= "AND id_modulo = '$id_modulo'";

if ($conn->query($q) === TRUE) {
    echo "Módulo eliminado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>