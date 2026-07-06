<?php



require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/constants.php';

$idUsuario = $_SESSION['ID_USUARIO'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/Capital_Humano/controllers/SolicitudesControlador.php';

$solicitudes =
    SolicitudesControlador::listarSolicitudesOperador($idUsuario);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

<title>Solicitudes de Descanso</title>

</head>

<body>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';
Sidebar::render("Solicitudes de Descanso");
?>

<div class="container-fluid">

    <!-- BREADCRUMB -->

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb mb-1">

            <li class="breadcrumb-item">
                Inicio
            </li>

            <li class="breadcrumb-item active">
                Descansos
            </li>

        </ol>

    </nav>

    <!-- HEADER -->

    <div class="row align-items-center">

        <div class="col-md-6">

            <h2 class="fw-bold mb-0">
                Solicitudes de Descanso
            </h2>

        </div>

    </div>

    <!-- FORMULARIO -->

    <div class="card shadow-sm mt-3">

        <div class="card-header bg-primary text-white">

            Nueva Solicitud

        </div>

        <div class="card-body">

            <div class="row">

                <!-- TIPO -->

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Tipo
                    </label>

                    <select id="tipo" class="form-select">

                        <option value="DESCANSO">
                            Descanso
                        </option>

                        <option value="VACACIONES">
                            Vacaciones
                        </option>

                    </select>

                </div>

                <!-- FECHA INICIO -->

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Fecha inicio
                    </label>

                    <input
                        type="date"
                        id="fecha_inicio"
                        class="form-control">

                </div>

                <!-- FECHA FIN -->

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Fecha fin
                    </label>

                    <input
                        type="date"
                        id="fecha_fin"
                        class="form-control">

                </div>

                <!-- COMENTARIO -->

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Comentario
                    </label>

                    <input
                        type="text"
                        id="comentario"
                        class="form-control"
                        placeholder="Opcional">

                </div>

            </div>

            <!-- BOTÓN -->

            <div class="mt-2">

                <button
                    class="btn btn-primary"
                    onclick="guardarSolicitud()">

                    Enviar Solicitud

                </button>

            </div>

        </div>

    </div>

    <!-- TABLA -->

    <div class="table-responsive mt-4">

        <table class="table table-hover" id="tablaSolicitudes">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Días</th>
                    <th>Estatus</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = $solicitudes->fetch_assoc()){ ?>

                <tr>

                    <td>
                        <?= $row['id_solicitud'] ?>
                    </td>

                    <td>

                        <?php if($row['tipo'] == 'DESCANSO'){ ?>

                            <span class="badge bg-info">
                                Descanso
                            </span>

                        <?php } else { ?>

                            <span class="badge bg-primary">
                                Vacaciones
                            </span>

                        <?php } ?>

                    </td>

                    <td>
                        <?= $row['fecha_inicio'] ?>
                    </td>

                    <td>
                        <?= $row['fecha_fin'] ?>
                    </td>

                    <td>
                        <?= $row['dias_solicitados'] ?>
                    </td>

                    <td>

                        <?php

                        switch($row['estatus']){

                            case 'PENDIENTE':
                                echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                            break;

                            case 'AUTORIZADO':
                                echo '<span class="badge bg-success">Autorizado</span>';
                            break;

                            case 'RECHAZADO':
                                echo '<span class="badge bg-danger">Rechazado</span>';
                            break;

                            case 'CANCELADO':
                                echo '<span class="badge bg-secondary">Cancelado</span>';
                            break;
                        }

                        ?>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script src="../../assets/js/operador.js"></script>

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


</body>
</html>