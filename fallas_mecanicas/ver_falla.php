<?php

require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

$id = $_GET['id'] ?? 0;

/*=========================
OBTENER REPORTE
=========================*/
$sql = "
SELECT f.*, 
       CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as nombre_operador
FROM fallas f
LEFT JOIN usuarios u ON f.operador = u.id  -- ← f.operador ahora contiene el ID
WHERE f.id='$id'
LIMIT 1
";

$rs = $db->consulta($sql);
$falla = $db->fetch_array($rs);

if (!$falla) {
    die('Reporte no encontrado');
}

/*=========================
COLOR POR TIPO
=========================*/
$tipo = strtolower($falla['tipo_reporte'] ?? '');

$colorTipo = 'secondary';
$textoTipo = 'SIN TIPO';

if ($tipo === 'auxilio') {
    $colorTipo = 'danger';
    $textoTipo = 'Auxilio';
}

if ($tipo === 'mecanica') {
    $colorTipo = 'warning';
    $textoTipo = 'Falla Mecánica';
}

if ($tipo === 'talacha') {
    $colorTipo = 'dark';
    $textoTipo = 'Talachas';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/head.php'); ?>

<title>Ver Falla </title>
</head>

<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Reporte de Fallas");
?>

<div class="container-fluid">

<!-- BREADCRUMB -->
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item">Inicio</li>
<li class="breadcrumb-item">Reporte de Fallas</li>
<li class="breadcrumb-item active">Detalle</li>
</ol>
</nav>

<!-- HEADER -->
<div class="d-flex justify-content-between mb-4">

<div>
<h2 class="fw-bold">Reporte #<?= $falla['id'] ?></h2>
<div class="text-muted">Detalle del reporte</div>
</div>

<a href="reporte_fallas.php" class="btn btn-secondary">
Volver
</a>

</div>

<!-- BANNER TIPO REPORTE -->
<div class="alert alert-<?= $colorTipo ?> fw-bold text-center">
<?= $textoTipo ?>
</div>

<!-- CARD PRINCIPAL -->
<div class="card border-0 shadow">
<div class="card-body">

<div class="row">

<?php
function campo($titulo, $valor){

echo '
<div class="col-md-6 mb-4">
<label class="fw-bold text-primary mb-1">'.$titulo.'</label>
<div class="border rounded p-3 bg-light">
'.htmlspecialchars($valor ?: '-').'
</div>
</div>';
}

/*=========================
CAMPOS
=========================*/

campo('Número Económico', $falla['eco']);
campo('Remolque', $falla['remolque']);
campo('Operador', $falla['nombre_operador'] ?? 'Sin asignar');
campo('Teléfono', $falla['telefono']);
campo('Cliente', $falla['cliente']);
campo('Tipo Carga', $falla['tipo_carga']);
campo('Tipo de Falla', $falla['tipo_falla']);

campo('Grupo Motor', $falla['grupo_motor']);
campo('Detalle Falla', $falla['detalle_falla']);

campo('Ubicación Actual', $falla['ubicacion_actual']);
campo('Link Ubicación', $falla['link_ubicacion']);

campo('Estatus', $falla['estatus']);

/* NUEVO CAMPO */
campo('Estatus Operativo', $falla['estatus_operativo'] ?? 'SIN REGISTRO');

campo('Fecha Registro', $falla['fecha_registro']);
campo('Descripción', $falla['descripcion']);

?>

</div>

</div>
</div>

</div>

</body>
</html>

<style>
.card{
    border-radius:18px;
}
label{
    display:block;
    font-size:14px;
}
</style>

<script>
function closeMenu(event){
const sidebar = document.getElementById('sidebar');

if(
    sidebar.classList.contains('open') &&
    !sidebar.contains(event.target)
){
    sidebar.classList.remove('open');
}
}
</script>