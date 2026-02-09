<?php
require '../../system/connection.php';

// Get form data
$id_sucursal   = $_POST['id_sucursal'] ?? '';
$id_producto   = $_POST['id_producto'] ?? '';
$descproducto  = $_POST['nombre_producto'] ?? '';
$id_variante   = $_POST['id_variante'] ?? '';
$variante      = $_POST['nombre_variante'] ?? '';
$precio        = $_POST['precio'] ?? '';
$tipo_precio   = $_POST['tipo_precio'] ?? '';


$db = new MySQL();
$conn = $db->getConexion();

// Insert into database
$q = "";
$q .= "INSERT INTO servicios (";
$q .= "id_sucursal, ";
$q .= "id_producto, ";
$q .= "nombre_producto, ";
$q .= "id_variante, ";
$q .= "nombre_variante, ";
$q .= "tipo_precio, ";
$q .= "precio";

$q .= ") VALUES (";
$q .= "'$id_sucursal', ";
$q .= "'$id_producto', ";
$q .= "'$descproducto', ";
$q .= "'$id_variante', ";
$q .= "'$variante', ";
$q .= "'$tipo_precio', ";
$q .= "'$precio'";
$q .= ")";

if ($conn->query($q) === TRUE) {
    echo "Servicio agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>