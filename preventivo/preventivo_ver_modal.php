<?php

require '../system/connection.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID no válido</div>";
    exit;
}

$id = (int) $_GET['id'];

$db = new MySQL();

$sql = "SELECT 
    p.*, 
    c.tipo_unidad,
    c.marca,
    c.modelo,
    c.anio
FROM preventivo_unidades p
LEFT JOIN cat_unidades c 
    ON p.eco = c.eco
WHERE p.id = $id
LIMIT 1";

$rs = $db->consulta($sql);

if ($row = $db->fetch_array($rs)) {
?>

<!-- ================= GENERAL ================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-dark text-white fw-bold">
        🚛 Información General
    </div>
    <div class="card-body row">

        <div class="col-md-4">
            <strong>ECO:</strong><br>
            <?= $row['eco'] ?>
        </div>

        <div class="col-md-4">
            <strong>Tipo Unidad:</strong><br>
            <?= $row['tipo_unidad'] ?? '—' ?>
        </div>

        <div class="col-md-4">
            <strong>Marca:</strong><br>
            <?= $row['marca'] ?? '—' ?>
        </div>

        <div class="col-md-4 mt-3">
            <strong>Modelo:</strong><br>
            <?= $row['modelo'] ?? '—' ?>
        </div>

        <div class="col-md-4 mt-3">
            <strong>Año:</strong><br>
            <?= $row['anio'] ?? '—' ?>
        </div>

    </div>
</div>

<!-- ================= KM ================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-primary text-white fw-bold">
        🛣️ Información Kilometraje
    </div>
    <div class="card-body row">

        <div class="col-md-4">
            <strong>KM Actual:</strong><br>
            <?= number_format($row['km_ultimo_servicio'] ?? 0) ?>
        </div>

        <div class="col-md-4">
            <strong>Último Servicio:</strong><br>
            <?= !empty($row['fecha_ultimo_servicio_kilometros']) 
                ? date('d/m/Y', strtotime($row['fecha_ultimo_servicio_kilometros'])) 
                : '—' ?>
        </div>

        <div class="col-md-4">
            <strong>KM Próximo Servicio:</strong><br>
            <?= number_format($row['km_proximo_servicio'] ?? 0) ?>
        </div>

        <div class="col-md-4 mt-3">
            <strong>Próximo Preventivo:</strong><br>
            <?= !empty($row['proximo_preventivo_kilometros']) 
                ? date('d/m/Y', strtotime($row['proximo_preventivo_kilometros'])) 
                : '—' ?>
        </div>

    </div>
</div>

<!-- ================= HRS ================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-warning text-dark fw-bold">
        ⏱️ Información Horas
    </div>
    <div class="card-body row">

        <div class="col-md-4">
            <strong>Horas Actual:</strong><br>
            <?= number_format($row['hrs_actual'] ?? 0) ?>
        </div>

        <div class="col-md-4">
            <strong>Último Servicio:</strong><br>
            <?= !empty($row['fecha_ultimo_servicio_hrs']) 
                ? date('d/m/Y', strtotime($row['fecha_ultimo_servicio_hrs'])) 
                : '—' ?>
        </div>

        <div class="col-md-4">
            <strong>Horas Próximo Servicio:</strong><br>
            <?= number_format($row['hrs_proximo_servicio'] ?? 0) ?>
        </div>

        <div class="col-md-4 mt-3">
            <strong>Próximo Preventivo:</strong><br>
            <?= !empty($row['proximo_preventivo_hrs']) 
                ? date('d/m/Y', strtotime($row['proximo_preventivo_hrs'])) 
                : '—' ?>
        </div>

    </div>
</div>

<?php
} else {
    echo "<div class='alert alert-warning'>No se encontró información</div>";
}
?>