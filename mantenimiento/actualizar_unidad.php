<?php
require '../system/connection.php';

$id = intval($_POST['id']);
$eco = $_POST['eco'];
$prioridad = $_POST['prioridad'];
$status = $_POST['status'];

$query = "UPDATE unidades_detenidas 
          SET eco = ?, prioridad = ?, status = ?
          WHERE id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $eco, $prioridad, $status, $id);

if ($stmt->execute()) {
    header("Location: base_unidades_detenidas.php");
} else {
    echo "Error al actualizar";
}
