<?php
require '../system/connection.php';
session_start();

$db = new MySQL();

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID no válido");
}

$id = intval($_GET['id']);

// ELIMINACIÓN LÓGICA (recomendado)
$sql = "UPDATE preventivo_unidades 
        SET activo = 0 
        WHERE id = $id";

$db->consulta($sql);

// Redirigir
header("Location: preventivo_index.php");
exit;