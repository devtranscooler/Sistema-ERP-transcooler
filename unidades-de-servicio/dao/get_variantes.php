<?php
require '../../system/connection.php';
$db = new MySQL();

$producto_id = intval($_POST['producto_id'] ?? 0);
$variantes = [];

if ($producto_id > 0) {
    $q = "SELECT id_variantes, nombre FROM variantes WHERE id_productos = $producto_id AND (Status IS NULL OR Status != 'eliminado')";
    $result = $db->consulta($q);
    while ($row = $db->fetch_array($result)) {
        $variantes[] = $row;
    }
}

echo json_encode($variantes);