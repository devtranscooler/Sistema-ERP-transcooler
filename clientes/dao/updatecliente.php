<?php
require '../../system/connection.php';

$id_cliente = $_POST['id_cliente'] ?? '';
$nombre_razon = $_POST['nombre_razon'] ?? '';
$tipo_cliente = $_POST['tipo_cliente'] ?? '';
$regimen = $_POST['regimen'] ?? '';
$calle = $_POST['calle'] ?? '';
$municipio = $_POST['municipio'] ?? '';
$estado = $_POST['estado'] ?? '';
$num_ext = $_POST['num_ext'] ?? '';
$num_int = $_POST['num_int'] ?? '';
$credito = $_POST['credito'] ?? '';
$stp = $_POST['stp'] ?? '';

if (!$id_cliente) {
    echo "Error: ID de Cliente no proporcionado.";
    exit;
}


$db = new MySQL();
$conn = $db->getConexion();

        $q = "";
        $q .= "UPDATE clientes SET ";
        $q .= "nombre_razon = '$nombre_razon', ";
        $q .= "tipo_cliente = '$tipo_cliente', ";
        $q .= "regimen = '$regimen', ";
        $q .= "calle = '$calle', ";
        $q .= "municipio = '$municipio', ";
        $q .= "estado = '$estado', ";
        $q .= "num_ext = '$num_ext', ";
        $q .= "num_int = '$num_int', ";
        $q .= "credito = '$credito', ";
        $q .= "stp = '$stp' ";
        $q .= "WHERE id_cliente = '$id_cliente'";

if ($conn->query($q) === TRUE) {
    echo "Cliente actualizado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>