<?php
require '../system/connection.php';
require '../system/constants.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario – Registro de Unidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid mt-4">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">🚛 Registro / Actualización de Unidad</h3>
    </div>

    <hr>

    <!-- FORMULARIO -->
    <form method="POST" action="guardar_unidad.php">

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">No. Económico</label>
                <input type="text" name="no_eco" class="form-control" required>
            </div>

            <div class="col-md-5">
                <label class="form-label">Razón Social</label>
                <input type="text" name="razon_social" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Placas</label>
                <input type="text" name="placas" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Folio TC</label>
                <input type="text" name="folio_tc" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">NIV</label>
                <input type="text" name="niv" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">No. de Motor</label>
                <input type="text" name="no_motor" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Año</label>
                <input type="number" name="anio" class="form-control" min="1990" max="2100">
            </div>

            <div class="col-md-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Capacidad</label>
                <input type="text" name="capacidad" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tipo de Unidad</label>
                <select name="tipo_unidad" class="form-control">
                    <option value="">Seleccione</option>
                    <option>Torton</option>
                    <option>Rabón</option>
                    <option>Trailer</option>
                    <option>Full</option>
                    <option>Camioneta 3.5</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Aseguradora</label>
                <input type="text" name="aseguradora" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Cobertura</label>
                <select name="cobertura" class="form-control">
                    <option value="">Seleccione</option>
                    <option>Amplia</option>
                    <option>Limitada</option>
                    <option>Responsabilidad Civil</option>
                </select>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="/parque_vehicular" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Guardar Unidad
            </button>
        </div>

    </form>

</div>

</body>
</html>
