<?php require '../system/connection.php';
require '../system/constants.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

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

</head>

<body onclick="closeMenu(event)">
    <?php require_once '../utilities/sidebar.php';
    Sidebar::render("Clientes"); ?>

    <div class="container-fluid">

        <!-- Breadcrumb mejorado con íconos -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <i class="bi bi-house-door me-1"></i>Inicio
                </li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-people me-1"></i>Clientes
                </li>
            </ol>
        </nav>

        <!-- Encabezado con mejor espaciado y diseño -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-person-video2 text-primary"></i>
                    Gestión de Clientes
                </h2>
            </div>

            <div class="col-md-6 text-md-end">
                <!-- Botones con mejor diseño y spacing -->
                <button class="btn btn-primary  shadow-sm" onclick="abrirModal('formCliente.php')">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Cliente
                </button>
                <button class="btn btn-success shadow-sm ms-2">
                    <i class="bi bi-file-earmark-excel me-2"></i>Exportar
                </button>
            </div>
        </div>

        <!-- Filtros dentro de un card con mejor organización -->
        <div class="filter-card">
            <div class="row">
                <!-- Campo de búsqueda por nombre -->
                <div class="col-md-12">
                    <label for="filtroRazon" class="form-label small text-muted">
                        <i class="bi bi-person-square"></i> Buscar por razon social
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            name="nombre"
                            id="filtroRazon"
                            class="form-control border-start-0 ps-0"
                            placeholder="Escribe la razon social...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-0">
            <table class="table table-hover aligmiddle mb-0" id="tablaClientes">n-
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Razón social</th>
                        <th>RFC</th>
                        <th>Correo</th>
                        <th>Movil</th>
                        <th>Operación</th>
                        <th class="text-center" style="width: 200px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargan -->
                </tbody>
            </table>
        </div>

        <div class="row mt-2 align-items-center">
            <div class="col-md-6">
                <div id="info-paginacion" class="text-muted"></div>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-md-end justify-content-center mb-0" id="paginacion"></ul>
                </nav>
            </div>
        </div>

        <!-- MODAL GLOBAL -->
        <div class="modal fade" id="globalModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" id="globalModalContent" style="overflow-y: auto;">
                </div>
            </div>
        </div>

    </div>
</body>

<script src="clientes.js"></script>

</html>