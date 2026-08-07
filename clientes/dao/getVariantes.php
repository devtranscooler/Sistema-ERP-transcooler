<?php
require '../../system/connection.php';
$db = new MySQL();

$id_sucursal = intval($_POST['id_sucursal'] ?? 0);
$id_producto = intval($_POST['id_producto'] ?? 0);

$variantes = [];

if ($id_producto > 0 && $id_sucursal > 0) {
    $q = "SELECT 
              s.id_variante, 
              s.nombre_variante, 
              s.precio, 
              s.tipo_precio,
              v.recurrencia, 
              cr.descripcion
          FROM servicios s
          LEFT JOIN variantes v ON s.id_variante = v.id_variantes
          LEFT JOIN catRecurrencia cr ON v.recurrencia = cr.idCatReferencia
          WHERE s.id_producto = $id_producto 
            AND s.id_sucursal = $id_sucursal
            AND (s.Status IS NULL OR s.Status != 'eliminado')";
    
    $result = $db->consulta($q);
    while ($row = $db->fetch_array($result)) {
        $variantes[] = $row;
    }
}

echo json_encode($variantes);