<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

$ecos = [];

$res = $db->consulta("SELECT eco FROM cat_unidades ORDER BY eco ASC");

while($row = $db->fetch_array($res)){
    $ecos[] = $row['eco'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 🔹 Captura de datos
    $eco = $_POST['eco'] ?? '';
    $km_actual = (int)($_POST['km_actual'] ?? 0);
    $km_servicio = (int)($_POST['km_servicio'] ?? 0);
    $limite_km = (int)($_POST['limite_km'] ?? 0);

    $hrs_actual = (int)($_POST['hrs_actual'] ?? 0);
    $hrs_servicio = (int)($_POST['hrs_servicio'] ?? 0);
    $limite_hrs = (int)($_POST['limite_hrs'] ?? 0);

    $fecha_servicio = $_POST['fecha_servicio'] ?? '';
    $limite_dias = (int)($_POST['limite_dias'] ?? 0);

    // 🔹 Validación básica
    if (empty($eco) || empty($fecha_servicio)) {
        die("Faltan datos obligatorios");
    }

    // 🔹 Validar ECO
    $check = $db->consulta("SELECT eco FROM cat_unidades WHERE eco = '$eco'");

    if ($db->num_rows($check) == 0) {
        die("El ECO no existe en el catálogo");
    }

    // 🔹 Insert
    $sql = "INSERT INTO preventivo_unidades 
            (eco, km_ultimo_servicio, km_proximo_servicio, limite_km,
             hrs_actual, hrs_ultimo_servicio, limite_hrs,
             fecha_ultimo_servicio_kilometros)
            VALUES
            ('$eco','$km_actual','$km_servicio','$limite_km',
             '$hrs_actual','$hrs_servicio','$limite_hrs',
             '$fecha_servicio')";

    if (!$db->consulta($sql)) {
        die("Error al guardar");
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
</head>

<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Preventivo");
?>

<div class="container-fluid">
<div class="row">
<div class="col-md-12">

<div class="card shadow-sm mt-4">
<div class="card-body">

<h3 class="mb-4 fw-bold">Captura Individual - Preventivo</h3>

<form method="POST">

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">ECO</label>
            <select name="eco" class="form-control" required>
    <option value="">Selecciona una unidad</option>
    <?php foreach($ecos as $eco_item): ?>
        <option value="<?= $eco_item ?>">
            <?= $eco_item ?>
        </option>
    <?php endforeach; ?>
</select>
        </div>
    </div>

    <hr>

    <h5 class="fw-bold mt-3">Kilometraje</h5>
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">KM Actual</label>
            <input type="number" name="km_actual" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">KM Último Servicio</label>
            <input type="number" name="km_servicio" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Límite KM</label>
            <input type="number" name="limite_km" class="form-control" required>
        </div>
    </div>

    <hr>

    <h5 class="fw-bold">Horas</h5>
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">HRS Actual</label>
            <input type="number" name="hrs_actual" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">HRS Último Servicio</label>
            <input type="number" name="hrs_servicio" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Límite HRS</label>
            <input type="number" name="limite_hrs" class="form-control" required>
        </div>
    </div>

    <hr>

    <h5 class="fw-bold">Tiempo</h5>
    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">Fecha Último Servicio</label>
            <input type="date" name="fecha_servicio" class="form-control" required>
        </div>
        

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
            Guardar Preventivo
        </button>

        <a href="preventivo.php" class="btn btn-secondary">
            Cancelar
        </a>
    </div>

</form>

</div>
</div>

</div>
</div>
</div>

</body>
</html>