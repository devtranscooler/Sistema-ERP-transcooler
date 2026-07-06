<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/constants.php';

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

<title>Administración de Solicitudes</title>

<style>

.card-kpi{
    transition: .2s;
    border-radius: 12px;
}

.card-kpi:hover{
    transform: translateY(-2px);
}

.kpi-pendiente{
    border-left: 5px solid #ffc107;
}

.kpi-autorizado{
    border-left: 5px solid #198754;
}

.kpi-rechazado{
    border-left: 5px solid #dc3545;
}

.table thead th{
    background-color:#f8f9fa;
    font-weight:600;
}

.modal textarea{
    resize:none;
}

</style>

</head>

<body>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';
Sidebar::render("Administración Solicitudes");
?>

<div class="container-fluid">

```
<!-- BREADCRUMB -->

<nav aria-label="breadcrumb">

    <ol class="breadcrumb mb-1">

        <li class="breadcrumb-item">
            Inicio
        </li>

        <li class="breadcrumb-item">
            Capital Humano
        </li>

        <li class="breadcrumb-item active">
            Solicitudes
        </li>

    </ol>

</nav>

<!-- HEADER -->

<div class="row align-items-center">

    <div class="col-md-6">

        <h2 class="fw-bold mb-0">
            Administración de Solicitudes
        </h2>

    </div>

</div>

<!-- INDICADORES -->

<div class="row mt-4">

    <div class="col-md-4 mb-3">

        <div class="card shadow-sm card-kpi kpi-pendiente">

            <div class="card-body text-center">

                <small class="text-muted">
                    Pendientes
                </small>

                <h2 id="totalPendientes">
                    0
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 mb-3">

        <div class="card shadow-sm card-kpi kpi-autorizado">

            <div class="card-body text-center">

                <small class="text-muted">
                    Autorizadas
                </small>

                <h2 id="totalAutorizadas">
                    0
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 mb-3">

        <div class="card shadow-sm card-kpi kpi-rechazado">

            <div class="card-body text-center">

                <small class="text-muted">
                    Rechazadas
                </small>

                <h2 id="totalRechazadas">
                    0
                </h2>

            </div>

        </div>

    </div>

</div>

<!-- FILTROS -->

<div class="card shadow-sm mt-3">

    <div class="card-header bg-primary text-white">

        Filtros

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Estatus
                </label>

                <select
                    id="filtroEstatus"
                    class="form-select">

                    <option value="">
                        Todos
                    </option>

                    <option value="PENDIENTE">
                        Pendiente
                    </option>

                    <option value="AUTORIZADO">
                        Autorizado
                    </option>

                    <option value="RECHAZADO">
                        Rechazado
                    </option>

                    <option value="CANCELADO">
                        Cancelado
                    </option>

                </select>

            </div>

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Tipo
                </label>

                <select
                    id="filtroTipo"
                    class="form-select">

                    <option value="">
                        Todos
                    </option>

                    <option value="DESCANSO">
                        Descanso
                    </option>

                    <option value="VACACIONES">
                        Vacaciones
                    </option>

                </select>

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Buscar Operador
                </label>

                <input
                    type="text"
                    id="buscarOperador"
                    class="form-control"
                    placeholder="Nombre del operador">

            </div>

            <div class="col-md-2 mb-3 d-flex align-items-end">

                <button
                    class="btn btn-primary w-100"
                    onclick="cargarSolicitudes()">

                    Buscar

                </button>

            </div>

        </div>

    </div>

</div>

<!-- TABLA -->

<div class="card shadow-sm mt-4">

    <div class="card-header bg-primary text-white">

        Solicitudes Registradas

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-hover align-middle"
                id="tablaSolicitudes">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Operador</th>

                        <th>Tipo</th>

                        <th>Inicio</th>

                        <th>Fin</th>

                        <th>Días</th>

                        <th>Estatus</th>

                        <th>Registrada</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody id="tbodySolicitudes">

                    <tr>

                        <td colspan="9" class="text-center text-muted">

                            No hay solicitudes registradas

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
```

</div>

<!-- MODAL GESTIÓN -->

<div
    class="modal fade"
    id="modalSolicitud"
    tabindex="-1">

```
<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <div class="modal-header">

            <h5 class="modal-title">
                Gestionar Solicitud
            </h5>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal">
            </button>

        </div>

        <div class="modal-body">

            <input
                type="hidden"
                id="idSolicitud">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Operador
                    </label>

                    <input
                        type="text"
                        id="modalOperador"
                        class="form-control"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Tipo
                    </label>

                    <input
                        type="text"
                        id="modalTipo"
                        class="form-control"
                        readonly>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fecha Inicio
                    </label>

                    <input
                        type="text"
                        id="modalInicio"
                        class="form-control"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fecha Fin
                    </label>

                    <input
                        type="text"
                        id="modalFin"
                        class="form-control"
                        readonly>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Motivo
                </label>

                <textarea
                    id="modalMotivo"
                    class="form-control"
                    rows="2"
                    readonly></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Observaciones
                </label>

                <textarea
                    id="modalObservaciones"
                    class="form-control"
                    rows="3"
                    readonly></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Comentario Administrativo
                </label>

                <textarea
                    id="comentarioAdmin"
                    class="form-control"
                    rows="3"></textarea>

            </div>

        </div>

        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-danger"
                onclick="rechazarSolicitud()">

                Rechazar

            </button>

            <button
                type="button"
                class="btn btn-success"
                onclick="autorizarSolicitud()">

                Autorizar

            </button>

        </div>

    </div>

</div>
```

</div>

<!-- JQuery -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->

<script src="../../assets/js/solicitudes_admin.js"></script>

<script>

function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

function closeMenu(event) {
    const sidebar = document.getElementById('sidebar');

    if (
        sidebar.classList.contains('open') &&
        !sidebar.contains(event.target)
    ) {
        sidebar.classList.remove('open');
    }
}

</script>

</body>
</html>
