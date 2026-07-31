<?php
require '../system/connection.php';

$db = new MySQL();

$id = intval($_POST['id']);
$km_actual = intval($_POST['km_actual']);
$hrs_actual = intval($_POST['hrs_actual']);
$fecha_servicio = $_POST['fecha_servicio'];

$sql = "UPDATE preventivo_unidades 
        SET km_actual = $km_actual,
            hrs_actual = $hrs_actual,
            fecha_servicio = '$fecha_servicio'
        WHERE id = $id";

$db->consulta($sql);

header("Location: preventivo_index.php");
exit;