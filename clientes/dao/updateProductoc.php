<?php
require '../../system/connection.php';

// Get POST data
$id_cliente = $_POST['id_cliente'] ?? '';
$id_producto_cliente = $_POST['id_producto_cliente'] ?? '';
$precio = $_POST['precio'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$descuento = $_POST['descuento'] ?? '';
$precio_final = $_POST['precio_final'] ?? '';
$total_pagar = $_POST['total_pagar'] ?? '';
$recurrencia = $_POST['recurrencia'] ?? '';

// Validate required inputs
if (!$id_producto_cliente) {
    echo "Error: Datos incompletos para actualizar el producto.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

// Construct SQL update query
$q  = "UPDATE productos_clientes SET ";
$q .= "precio = '$precio', ";
$q .= "cantidad = '$cantidad', ";
$q .= "descuento = '$descuento', ";
$q .= "precio_final = '$precio_final', ";
$q .= "total_pagar = '$total_pagar', ";
$q .= "recurrencia = '$recurrencia' ";
$q .= "WHERE id_producto_cliente = '$id_producto_cliente'";

// Execute the query
if ($conn->query($q) === TRUE) {
    echo "Producto actualizado con éxito.";
} else {
    echo "Error al actualizar: " . $conn->error;
}

$conn->close();
?>