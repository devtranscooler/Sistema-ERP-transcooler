<?php
require '../system/connection.php';
require '../system/constants.php';

// Datos simulados (sin base de datos)
$unidades = [
    [
        "id" => 1,
        "eco" => "3002",
        "prioridad" => "Alta",
        "urgencia" => "URGE",
        "taller" => "SEMADISA",
        "fecha_ingreso" => "22/12/2025",
        "falla" => "Balatas / Escape",
        "status" => "EN PROCESO",
        "tipo_unidad" => "5a Rueda",
        "dias" => 46
    ]
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

    <!-- 📍 Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="bi bi-house-door me-1"></i>Inicio
            </li>
            <li class="breadcrumb-item active">
                <i class="bi bi-truck me-1"></i>Unidades Detenidas
            </li>
        </ol>
    </nav>

    <!-- 🎯 Encabezado -->
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">
                Base de Unidades Detenidas
            </h2>
        </div>
        
        <div class="col-md-6 text-md-end">
            <a href="form_unidades_detenidas.php?id=1" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>
                Registrar / Actualizar Unidad
            </a>
        </div>
    </div>

    <!-- 📋 Tabla -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-center" style="background-color:#063a61; color:white;">
                <tr>
                    <th>ECO</th>
                    <th>Prioridad</th>
                    <th>Urgencia</th>
                    <th>Taller</th>
                    <th>Fecha Ingreso</th>
                    <th>Falla</th>
                    <th>Status</th>
                    <th>Tipo Unidad</th>
                    <th>Días</th>
                    <th>+2 Semanas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($unidades as $unidad): ?>

                <tr>
                    <td class="text-center fw-bold"><?= $unidad['eco']; ?></td>
                    <td><?= $unidad['prioridad']; ?></td>

                    <td class="text-center">
                        <span class="badge bg-danger">
                            <?= $unidad['urgencia']; ?>
                        </span>
                    </td>

                    <td><?= $unidad['taller']; ?></td>
                    <td><?= $unidad['fecha_ingreso']; ?></td>
                    <td><?= $unidad['falla']; ?></td>

                    <td class="text-warning fw-bold text-center">
                        <?= $unidad['status']; ?>
                    </td>

                    <td><?= $unidad['tipo_unidad']; ?></td>

                    <td class="text-danger text-center fw-bold">
                        <?= $unidad['dias']; ?>
                    </td>

                    <td class="text-center fw-bold <?= $unidad['dias'] > 14 ? 'bg-danger text-white' : ''; ?>">
                        <?= $unidad['dias'] > 14 ? "Sí" : "No"; ?>
                    </td>

                    <!-- 🔥 BOTONES -->
                    <td class="text-center">
                        <div class="btn-group">

                            <!-- VER -->
                            <button class="btn btn-info btn-sm shadow-sm verUnidad"
                                data-id="<?= $unidad['id']; ?>"
                                title="Ver">
                                <i class="bi bi-eye"></i>
                            </button>
                            <!-- EDITAR -->
                            <a href="editar_unidad.php?id=<?= $unidad['id']; ?>"
                               class="btn btn-warning btn-sm shadow-sm"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- ELIMINAR SIMULADO -->
                            <button class="btn btn-danger btn-sm shadow-sm"
                                    onclick="eliminarSimulado()"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="row mt-3 align-items-center">
        <div class="col-md-6">
            <div class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Mostrando registros actuales
            </div>
        </div>


</div>
<div class="modal fade" id="modalUnidad" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalle de Unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenidoModal">
                <div class="text-center">
                    <div class="spinner-border text-info"></div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>

<script>
function eliminarSimulado() {
    if (confirm("¿Seguro que deseas eliminar esta unidad?")) {
        alert("Eliminación simulada correctamente.");
    }
}

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

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".verUnidad").forEach(button => {

        button.addEventListener("click", function () {

            let id = this.getAttribute("data-id");

            let modal = new bootstrap.Modal(
                document.getElementById('modalUnidad')
            );

            modal.show();

            // Simulación sin base de datos
            document.getElementById("contenidoModal").innerHTML = `
                <div class="row">
                    <div class="col-md-6"><strong>ECO:</strong> 3002</div>
                    <div class="col-md-6"><strong>Taller:</strong> SEMADISA</div>
                    <div class="col-md-6"><strong>Falla:</strong> Balatas / Escape</div>
                    <div class="col-md-6"><strong>Status:</strong> EN PROCESO</div>
                    <div class="col-md-6"><strong>Días:</strong> 46</div>
                    <div class="col-md-6"><strong>Prioridad:</strong> Alta</div>
                </div>
            `;

        });

    });

});
</script>
