<?php
require '../system/connection.php';

$db = new MySQL();

$id = intval($_GET['id']);

$sql = "DELETE FROM catalogo_unidades WHERE id = $id";
$db->consulta($sql);

header("Location: parque_vehicular.php");
exit;