<?php
require '../../system/connection.php';

// Get POST data
$idUsuario = $_POST['id_usuario'] ?? null;
$idModulo = $_POST['id_modulo'] ?? null;
$permiso = $_POST['permiso'] ?? '';
$estado = isset($_POST['valor']) && $_POST['valor'] == 1 ? 1 : 0;

if (!$idUsuario || !$idModulo || !$permiso) {
    echo "Error: Datos incompletos.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

// First check if there's an existing permission row
$checkQuery = "SELECT id_accesos FROM roles WHERE id_usuario = '$idUsuario' AND id_modulo = '$idModulo'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    // Exists → Update
    $updateQuery = "UPDATE roles SET $permiso = '$estado' WHERE id_usuario = '$idUsuario' AND id_modulo = '$idModulo'";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Permiso actualizado correctamente.";
    } else {
        echo "Error al actualizar permiso: " . $conn->error;
    }
} else {
    // Doesn't exist → Insert new row
    $insertQuery = "INSERT INTO roles (id_usuario, id_modulo, $permiso) VALUES ('$idUsuario', '$idModulo', '$estado')";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Permiso asignado correctamente.";
    } else {
        echo "Error al asignar permiso: " . $conn->error;
    }
}

$conn->close();
?>