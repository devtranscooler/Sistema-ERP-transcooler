<?php
require '../../system/connection.php';

$id_usuario = $_POST['id_usuario'] ?? '';
$id_modulo = $_POST['id_modulo'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();

// First, check if the record already exists
$checkQuery = "SELECT 1 FROM roles WHERE id_usuario = '$id_usuario' AND id_modulo = '$id_modulo'";
$exists = $conn->query($checkQuery);

if ($exists && $exists->num_rows > 0) {
    echo "Este módulo ya está asignado al usuario.";
} else {
    // Insert into database
    $q = "";
    $q .= "INSERT INTO roles (id_usuario, id_modulo) ";
    $q .= "VALUES ('$id_usuario', '$id_modulo')";

    if ($conn->query($q) === TRUE) {
        echo "Módulo agregado con éxito.";
    } else {
        echo "Error: " . $q . "<br>" . $conn->error;
    }
}

$conn->close();
?>