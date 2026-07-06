<?php
require '../system/connection.php';
require '../system/constants.php';

$id = $_GET['id'] ?? 1;

// Datos duros simulados
$unidad = [
    "eco" => "3002",
    "prioridad" => "Alta",
    "urgencia" => "URGE",
    "taller" => "SEMADISA",
    "fecha_ingreso" => "22/12/2025",
    "falla" => "Balatas / Escape",
    "status" => "EN PROCESO",
    "tipo_unidad" => "5a Rueda",
    "dias" => 46
];
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

    <h2 class="fw-bold mb-4">Detalle de Unidad</h2>

    <div class="card shadow-sm p-4">

        <div class="row mb-2">
            <div class="col-md-4"><strong>ECO:</strong> <?= $unidad['eco']; ?></div>
            <div class="col-md-4"><strong>Prioridad:</strong> <?= $unidad['prioridad']; ?></div>
            <div class="col-md-4">
                <strong>Urgencia:</strong>
                <span class="badge bg-danger"><?= $unidad['urgencia']; ?></span>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4"><strong>Taller:</strong> <?= $unidad['taller']; ?></div>
            <div class="col-md-4"><strong>Fecha Ingreso:</strong> <?= $unidad['fecha_ingreso']; ?></div>
            <div class="col-md-4"><strong>Días en Taller:</strong> <?= $unidad['dias']; ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6"><strong>Falla:</strong> <?= $unidad['falla']; ?></div>
            <div class="col-md-3"><strong>Status:</strong> <?= $unidad['status']; ?></div>
            <div class="col-md-3"><strong>Tipo Unidad:</strong> <?= $unidad['tipo_unidad']; ?></div>
        </div>

    </div>

    <div class="mt-4">
        <a href="index.php" clas
