<?php 
session_start();
require '../../system/connection.php';

// Get form data
$nombreProducto = $_POST['nombreProducto'] ?? '';
$idProducto = $_POST['id_producto'] ?? null;
$userId = $_SESSION['ID_USUARIO'] ?? null;
$date = date('Y-m-d');

if (!$idProducto) {
    echo "Error: ID de producto no proporcionado.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

// Update the product
$q = "UPDATE productos SET 
        descProducto = '$nombreProducto',
        idusuarios = '$userId',
        fecha = '$date'
      WHERE id = '$idProducto'";

if ($conn->query($q) === TRUE) {
    echo "Tu Producto ha sido actualizado correctamente.";
} else {
    echo "Error al actualizar: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>