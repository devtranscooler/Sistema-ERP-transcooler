<?php
require '../../system/connection.php';

$nombre_pensionado = $_POST['nombre_pensionado'] ?? '';
$email = $_POST['email'] ?? '';
$id_sucursal = $_POST['idsucursal'] ?? '';
$celular = $_POST['celular'] ?? '';
$id_cliente = $_POST['id_cliente'] ?? '';
$id_pensionados = $_POST['id_pensionados'] ?? '';

if (!$id_pensionados) {
    echo "Error: ID de Cliente no proporcionado.";
    exit;
}


$db = new MySQL();
$conn = $db->getConexion();

        $q = "";
        $q .= "UPDATE pensionados SET ";
        $q .= "nombre_pensionado = '$nombre_pensionado', ";
        $q .= "email = '$email', ";
        $q .= "id_sucursal = '$id_sucursal', ";
        $q .= "celular = '$celular', ";
        $q .= "id_cliente = '$id_cliente' ";
        $q .= "WHERE id_pensionados = '$id_pensionados'";

if ($conn->query($q) === TRUE) {
    echo "Cliente actualizado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>