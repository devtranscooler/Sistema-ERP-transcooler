<?php
require '../../system/connection.php';

// Get form data
$id_cliente     = $_POST['id_cliente'] ?? '';
$id_sucursal    = $_POST['id_sucursal'] ?? '';
$id_producto    = $_POST['id_producto'] ?? '';
$id_variante    = $_POST['id_variante'] ?? '';
$cantidad       = $_POST['cantidad'] ?? '0';
$precio         = $_POST['precio'] ?? '0.00';
$descuento      = $_POST['descuento'] ?? '0.00';
$precio_final   = $_POST['precio_final'] ?? '0.00';
$total_pagar    = $_POST['total_pagar'] ?? '0.00';
$recurrencia    = $_POST['recurrencia'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "INSERT INTO productos_clientes (";
$q .= "    id_cliente, ";
$q .= "    sucursal_id, ";
$q .= "    producto_id, ";
$q .= "    variante_id, ";
$q .= "    cantidad, ";
$q .= "    precio, ";
$q .= "    descuento, ";
$q .= "    precio_final, ";
$q .= "    total_pagar, ";
$q .= "    recurrencia";
$q .= ") VALUES (";
$q .= "    '$id_cliente', ";
$q .= "    '$id_sucursal', ";
$q .= "    '$id_producto', ";
$q .= "    '$id_variante', ";
$q .= "    '$cantidad', ";
$q .= "    '$precio', ";
$q .= "    '$descuento', ";
$q .= "    '$precio_final', ";
$q .= "    '$total_pagar', ";
$q .= "    '$recurrencia'";
$q .= ")";

if ($conn->query($q) === TRUE) {
    echo "Producto cliente agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>