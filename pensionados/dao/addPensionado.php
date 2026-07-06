<?php
require '../../system/connection.php';

// Get form data
$id_cliente     = $_POST['id_cliente'] ?? '';
$nombre_pensionado    = $_POST['nombre_pensionado'] ?? '';
$email    = $_POST['email'] ?? '';
$celular    = $_POST['celular'] ?? '';
$idsucursal       = $_POST['idsucursal'] ?? '0';

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "INSERT INTO pensionados (";
$q .= "    nombre_pensionado, ";
$q .= "    email, ";
$q .= "    id_sucursal, ";
$q .= "    celular, ";
$q .= "    id_cliente";
$q .= ") VALUES (";
$q .= "    '$nombre_pensionado', ";
$q .= "    '$email', ";
$q .= "    '$idsucursal', ";
$q .= "    '$celular', ";
$q .= "    '$id_cliente'";
$q .= ")";

if ($conn->query($q) === TRUE) {
    echo "Pensionado agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>