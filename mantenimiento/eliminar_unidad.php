<?php
require '../system/connection.php';

if (!isset($_GET['id'])) {
    die("ID no especificado");
}

$id = intval($_GET['id']);

$query = "DELETE FROM unidades_detenidas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: base_unidades_detenidas.php");
} else {
    echo "Error al eliminar";
}
