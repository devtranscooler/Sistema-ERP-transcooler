<?php
require '../system/connection.php';
require '../system/constants.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

    <style>
        .form-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
        }

        .form-title {
            color: #063a61;
            font-weight: 700;
        }

        .section-title {
            font-weight: 600;
            color: #063a61;
            margin-top: 15px;
            margin-bottom: 10px;
            border-bottom: 2px solid #063a61;
            padding-bottom: 5px;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Unidades Detenidas");
?>

<div class="container-fluid">

    <!-- 📍 Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="bi bi-house-door me-1"></i>Inicio
            </li>
            <li class="breadcrumb-item">
                <i class="bi bi-truck me-1"></i>Unidades Detenidas
            </li>
            <li class="breadcrumb-item active">
                Registro de Unidad
            </li>
        </ol>
    </nav>

    <!-- ENCABEZADO -->
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="form-title mb-0">
                Registro / Seguimiento de Unidad Detenida
            </h2>
        </div>

        <div class="col-md-6 text-md-end">
            <a href="index.php" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>
                Regresar a Base
            </a>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="form-card shadow-sm">

        <form method="POST" action="guardar_unidad_detenida.php">

            <div class="row g-3">

                <div class="section-title">Información General</div>

                <div class="col-md-2">
                    <label class="form-label">ECO</label>
                    <input type="text" name="eco" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Prioridad</label>
                    <select name="prioridad" class="form-select">
                        <option value="">Seleccione</option>
                        <option>Alta</option>
                        <option>Media</option>
                        <option>Baja</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Urgencia</label>
                    <select name="urgencia" class="form-select">
                        <option value="">Seleccione</option>
                        <option>URGE</option>
                        <option>Normal</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Taller de Atención</label>
                    <input type="text" name="taller" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo de Unidad</label>
                    <input type="text" name="tipo_unidad" class="form-control">
                </div>

                <div class="section-title">Fechas</div>

                <div class="col-md-3">
                    <label class="form-label">Fecha Ingreso</label>
                    <input type="date" name="fecha_ingreso" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha Reporte</label>
                    <input type="date" name="fecha_reporte" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha Compromiso</label>
                    <input type="date" name="fecha_compromiso" class="form-control">
                </div>

                <div class="section-title">Falla y Reparación</div>

                <div class="col-md-4">
                    <label class="form-label">Falla Grupo Motor</label>
                    <input type="text" name="falla_grupo_motor" class="form-control">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Descripción de Falla</label>
                    <textarea name="descripcion_falla" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo de Reparación</label>
                    <select name="tipo_reparacion" class="form-select">
                        <option value="">Seleccione</option>
                        <option>Preventiva</option>
                        <option>Correctiva</option>
                        <option>Mayor</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Observaciones / Refacciones Pendientes</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>

            </div>

            <!-- BOTONES -->
            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success shadow-sm">
                    <i class="bi bi-save me-2"></i>
                    Guardar Registro
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>

<script>
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

function closeMenu(event) {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('open') && !sidebar.contains(event.target)) {
        sidebar.classList.remove('open');
    }
}
</script>
