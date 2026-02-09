<?php
require '../../system/connection.php';

$id_sucursal = $_POST['id_sucursal'] ?? '';
$nombre_unidad = $_POST['nombre_unidad'] ?? '';
$estado = $_POST['estado'] ?? '';
$municipio = $_POST['municipio'] ?? '';
$modelo_negocio = $_POST['modelo_negocio'] ?? '';
$socio = $_POST['socio'] ?? '';
$empresa = $_POST['empresa'] ?? '';
$fondo = $_POST['fondo'] ?? '';
$renta = $_POST['renta'] ?? '';
if (!$id_sucursal) {
    echo "Error: ID de Sucursal no proporcionado.";
    exit;
}


$db = new MySQL();
$conn = $db->getConexion();

        $q = "";
        $q .= "UPDATE sucursales SET ";
        $q .= "nombre_unidad = '$nombre_unidad', ";
        $q .= "estado = '$estado', ";
        $q .= "municipio = '$municipio', ";
        $q .= "modelo_negocio = '$modelo_negocio', ";
        $q .= "socio = '$socio', ";
        $q .= "empresa = '$empresa', ";
        $q .= "fondo = '$fondo', ";
        $q .= "renta = '$renta' ";
        $q .= "WHERE id_sucursal = '$id_sucursal'";

if ($conn->query($q) === TRUE) {
    echo "Sucursal actualizada con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>