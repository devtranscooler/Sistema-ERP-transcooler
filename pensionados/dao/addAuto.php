<?php
require '../../system/connection.php';

// Get form data
$id_pensionados     = $_POST['id_pensionados'] ?? '';
$marca    = $_POST['marca'] ?? '';
$modelo    = $_POST['modelo'] ?? '';
$year    = $_POST['year'] ?? '';
$placas       = $_POST['placas'] ?? '0';

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "INSERT INTO autos (";
$q .= "    id_pensionado, ";
$q .= "    marca, ";
$q .= "    modelo, ";
$q .= "    year, ";
$q .= "    placas";
$q .= ") VALUES (";
$q .= "    '$id_pensionados', ";
$q .= "    '$marca', ";
$q .= "    '$modelo', ";
$q .= "    '$year', ";
$q .= "    '$placas'";
$q .= ")";

if ($conn->query($q) === TRUE) {
    echo "Auto agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>