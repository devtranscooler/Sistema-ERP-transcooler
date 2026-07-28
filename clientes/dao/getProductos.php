<?php
require '../../system/connection.php';
$db = new MySQL();

$id_sucursal = intval($_POST['id_sucursal'] ?? 0);
$productos = [];

if ($id_sucursal > 0) {
    $q = "SELECT DISTINCT id_producto, nombre_producto 
          FROM servicios 
          WHERE id_sucursal = $id_sucursal 
            AND (Status IS NULL OR Status != 'eliminado')";
    $result = $db->consulta($q);
    while ($row = $db->fetch_array($result)) {
        $productos[] = $row;
    }
}

echo json_encode($productos);
?>