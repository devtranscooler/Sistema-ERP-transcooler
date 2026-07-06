<?php
require '../../../system/connection.php';

$id_variante = $_POST['id_variante'] ?? '';
$nombrev = $_POST['nombrev'] ?? '';
$minutos = $_POST['minutos'] ?? '';
$tolerancia = $_POST['tolerancia'] ?? '';
$recurrencia = $_POST['recurrencia'] ?? '';

if (!$id_variante || !$nombrev) {
    echo "Faltan datos obligatorios.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE variantes SET ";
$q .= "nombre = '$nombrev', ";
$q .= "minutos = '$minutos', ";
$q .= "tolerancia = '$tolerancia', ";
$q .= "recurrencia = '$recurrencia' ";
$q .= "WHERE id_variantes = '$id_variante'";

if ($conn->query($q) === TRUE) {
    echo "Variante actualizada con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>