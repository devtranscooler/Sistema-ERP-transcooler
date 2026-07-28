<?php
require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

$id = $_GET['id'] ?? null;

if(!$id){
    die("ID no proporcionado");
}

/*=========================
OBTENER REGISTRO
=========================*/
$sql = "SELECT * FROM fallas WHERE id = $id";
$rs = $db->consulta($sql);
$datos = $db->fetch_array($rs);

if(!$datos){
    die("Registro no encontrado");
}

/*=========================
TRACTOS
=========================*/
$sql_tractos = "SELECT eco FROM cat_unidades WHERE control_km=1 ORDER BY eco";
$rs_tractos = $db->consulta($sql_tractos);

$tractos = [];
while($row = $db->fetch_array($rs_tractos)){
    $tractos[] = $row['eco'];
}

/*=========================
REMOLQUES
=========================*/
$sql_remolques = "SELECT eco FROM cat_unidades WHERE control_km=0 ORDER BY eco";
$rs_remolques = $db->consulta($sql_remolques);

$remolques = [];
while($row = $db->fetch_array($rs_remolques)){
    $remolques[] = $row['eco'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/head.php'); ?>

<title>Editar Falla</title>
</head>

<body>

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Editar Falla");
?>

<div class="container-fluid">

<nav class="breadcrumb mb-4">
Inicio / Reporte Fallas / Editar
</nav>

<div class="card form-card">

<form action="actualizar_falla.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $datos['id'] ?>">

<!-- =========================
TIPO DE REPORTE
========================= -->
<div class="col-md-4">
<label>Tipo de reporte *</label>
<select name="tipo_reporte" class="form-select" required>
    <option value="">Seleccionar</option>

    <option value="auxilio" <?= $datos['tipo_reporte']=='auxilio'?'selected':'' ?>>Auxilio</option>
    <option value="falla_mecanica" <?= $datos['tipo_reporte']=='falla_mecanica'?'selected':'' ?>>Falla Mecánica</option>
    <option value="talacha" <?= $datos['tipo_reporte']=='talacha'?'selected':'' ?>>Talacha</option>

</select>
</div>

<div class="section-title">Información General</div>

<div class="row">

<!-- ECO -->
<div class="col-md-3">
<label>Número Económico *</label>
<select name="eco" class="form-select" required>
<option value="">Seleccionar unidad</option>

<?php foreach($tractos as $eco): ?>
<option value="<?= $eco ?>" <?= $datos['eco']==$eco?'selected':'' ?>>
<?= $eco ?>
</option>
<?php endforeach; ?>

</select>
</div>

<!-- REMOLQUE -->
<div class="col-md-3">
<label>Remolque *</label>
<select name="remolque" class="form-select" required>
<option value="">Seleccionar remolque</option>

<?php foreach($remolques as $eco): ?>
<option value="<?= $eco ?>" <?= $datos['remolque']==$eco?'selected':'' ?>>
<?= $eco ?>
</option>
<?php endforeach; ?>

</select>
</div>

<!-- OPERADOR -->
<div class="col-md-3">
<label>Operador *</label>
<select name="operador" class="form-select" required>
<option value="">Seleccionar</option>

<option <?= $datos['operador']=='AGUILAR ALDANA ABRAHAM'?'selected':'' ?>>AGUILAR ALDANA ABRAHAM</option>
<option <?= $datos['operador']=='AGUILAR DE JESUS RAFAEL'?'selected':'' ?>>AGUILAR DE JESUS RAFAEL</option>
<option <?= $datos['operador']=='GARCIA GARCIA JONATHAN'?'selected':'' ?>>GARCIA GARCIA JONATHAN</option>

<!-- (puedes seguir pegando tu lista completa aquí) -->

</select>
</div>

</div>

<br>

<!-- =========================
CONTACTO
========================= -->
<div class="row">

<div class="col-md-4">
<label>Teléfono *</label>
<input name="telefono" class="form-control" value="<?= $datos['telefono'] ?>" required>
</div>

<div class="col-md-4">
<label>Cliente *</label>
<input name="cliente" class="form-control" value="<?= $datos['cliente'] ?>" required>
</div>

<div class="col-md-4">
<label>Tipo de Carga *</label>
<select name="tipo_carga" class="form-select" required>

<option <?= $datos['tipo_carga']=='Refrigerada'?'selected':'' ?>>Refrigerada</option>
<option <?= $datos['tipo_carga']=='Congelada'?'selected':'' ?>>Congelada</option>
<option <?= $datos['tipo_carga']=='Seca'?'selected':'' ?>>Seca</option>
<option <?= $datos['tipo_carga']=='Vacia'?'selected':'' ?>>Vacia</option>

</select>
</div>

</div>

<br>

<!-- =========================
UBICACIÓN (SIN CAMBIOS)
========================= -->
<div class="section-title">Ubicación</div>

<div class="row">

<div class="col-md-6">
<label>Link ubicación *</label>
<input type="text" name="link_ubicacion" class="form-control" readonly required
value="<?= $datos['link_ubicacion'] ?>">
</div>

<div class="col-md-6">
<label>Detenida en *</label>
<input type="text" name="ubicacion_actual" class="form-control" readonly required
value="<?= $datos['ubicacion_actual'] ?>">
</div>

</div>

<br>

<!-- =========================
ESTATUS
========================= -->
<div class="section-title">Estatus Operativo</div>

<div class="row g-3">

<div class="col-md-3">
<label>Status</label>
<select name="estatus" class="form-select" required>
<option value="">Seleccionar</option>

<option value="PENDIENTE" <?= $datos['estatus']=='PENDIENTE'?'selected':'' ?>>PENDIENTE</option>
<option value="EN PROCESO" <?= $datos['estatus']=='EN PROCESO'?'selected':'' ?>>EN PROCESO</option>
<option value="CERRADA" <?= $datos['estatus']=='CERRADA'?'selected':'' ?>>CERRADA</option>

</select>
</div>

<div class="col-md-3">
<label>Status operativo</label>
<input type="text" name="estatus_operativo" class="form-control"
value="<?= $datos['estatus_operativo'] ?>">
</div>

<div class="col-md-3">
<label>Origen</label>
<input type="text" name="origen" class="form-control"
value="<?= $datos['origen'] ?>">
</div>

<div class="col-md-3">
<label>Destino</label>
<input type="text" name="destino" class="form-control"
value="<?= $datos['destino'] ?>">
</div>

</div>

<br>

<!-- =========================
TIPO FALLA
========================= -->
<div class="section-title">Tipo de Falla </div>

<div class="row">

<div class="col-md-6">
<label>Tipo de falla *</label>
<select name="tipo_falla" class="form-select" id="tipo_falla" required>

<option value="">Seleccionar</option>

<option value="MOTRIZ" <?= $datos['tipo_falla']=='MOTRIZ'?'selected':'' ?>>Falla Motriz</option>
<option value="REFRIGERACION" <?= $datos['tipo_falla']=='REFRIGERACION'?'selected':'' ?>>Falla Refrigeración</option>
<option value="LLANTAS" <?= $datos['tipo_falla']=='LLANTAS'?'selected':'' ?>>Falla Llantas</option>

</select>
</div>

</div>

<!-- aquí puedes seguir reutilizando TU JS tal cual -->

<br>

<div class="section-title">Descripción</div>

<textarea name="descripcion" class="form-control" rows="6" required>
<?= $datos['descripcion'] ?>
</textarea>

<br>

<div class="text-end">
<button class="btn btn-secondary" type="button" onclick="history.back()">Cancelar</button>
<button class="btn btn-danger">Actualizar</button>
</div>

</form>

</div>
</div>

</body>
</html>