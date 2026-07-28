<?php
require '../system/connection.php';
$db = new MySQL();

if (!isset($_GET['id'])) {
    die("ID no válido");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM preventivo_unidades WHERE id = $id";
$resultado = $db->consulta($sql);
$unidad = $db->fetch_array($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
</head>

<body onclick="closeMenu(event)">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';
Sidebar::render("Preventivo");
?>

<div class="container-fluid mt-4">

<div class="row">
<div class="col-md-10 offset-md-1">

<h3 class="mb-4">Editar Unidad ECO <?= $unidad['eco'] ?></h3>

<div class="card shadow">
<div class="card-body">

<form action="preventivo_actualizar.php" method="POST">

<input type="hidden" name="id" value="<?= $unidad['id'] ?>">

<div class="row">

<div class="col-md-4">
<label class="form-label">KM Actual</label>
<input type="number" name="km_actual" class="form-control"
       value="<?= $unidad['km_actual'] ?>">
</div>

<div class="col-md-4">
<label class="form-label">Horas Actual</label>
<input type="number" name="hrs_actual" class="form-control"
       value="<?= $unidad['hrs_actual'] ?>">
</div>

<div class="col-md-4">
<label class="form-label">Fecha Servicio</label>
<input type="date" name="fecha_servicio" class="form-control"
       value="<?= $unidad['fecha_servicio'] ?>">
</div>

</div>

<div class="mt-4">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</div>

</form>

</div>
</div>

</div>
</div>
</div>

</body>
</html>