<?php
require '../system/connection.php';
require '../system/constants.php';

$id = $_GET['id'] ?? 1;

$unidad = [
    "eco" => "3002",
    "prioridad" => "Alta",
    "urgencia" => "URGE",
    "taller" => "SEMADISA",
    "fecha_ingreso" => "2025-12-22",
    "falla" => "Balatas / Escape",
    "status" => "EN PROCESO",
    "tipo_unidad" => "5a Rueda"
];

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = "Cambios simulados correctamente (modo demostración).";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
</head>

<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Unidades Detenidas");
?>

<div class="container-fluid">

    <h2 class="fw-bold mb-4">Editar Unidad</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= $mensaje; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">

        <form method="POST">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">ECO</label>
                    <input type="text" class="form-control" value="<?= $unidad['eco']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Prioridad</label>
                    <input type="text" class="form-control" value="<?= $unidad['prioridad']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Urgencia</label>
                    <input type="text" class="form-control" value="<?= $unidad['urgencia']; ?>">
                </div>
            </div>

            <div class="row m