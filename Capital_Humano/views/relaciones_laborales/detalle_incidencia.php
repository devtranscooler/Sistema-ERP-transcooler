<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/constants.php';

$db = new MySQL();

$id = intval($_GET['id'] ?? 0);

if($id <= 0){
    die("Incidencia no válida.");
}

$sql = "

SELECT

    i.*,

    c.nombre AS tipo_incidencia,

    CONCAT(
        u.nombre,' ',
        u.apellidoP,' ',
        IFNULL(u.apellidoM,'')
    ) AS colaborador,

    u.noEmpleado,
    u.area,
    u.puesto_id

FROM incidencias_rh i

INNER JOIN usuarios u
    ON u.id = i.id_usuario

INNER JOIN cat_incidencias_rh c
    ON c.id_tipo = i.id_tipo

WHERE i.id_incidencia = {$id}

LIMIT 1

";

$resultado = $db->consulta($sql);

if($db->num_rows($resultado) == 0){
    die("La incidencia no existe.");
}

$incidencia = $db->fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/head.php'); ?>

<title>Detalle de Incidencia</title>

</head>

<body onclick="closeMenu(event)">

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/utilities/sidebar.php';
Sidebar::render();
?>

<div class="container-fluid py-4">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h3 class="mb-0">
                        <?= $incidencia['folio']; ?>
                    </h3>

                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">

                    <span class="badge bg-light text-dark me-2">
                        <?= $incidencia['estatus']; ?>
                    </span>

                    <?php

                    $color = 'secondary';

                    switch($incidencia['severidad']){

                        case 'LEVE':
                            $color = 'success';
                            break;

                        case 'MEDIA':
                            $color = 'warning';
                            break;

                        case 'GRAVE':
                            $color = 'danger';
                            break;

                        case 'CRITICA':
                            $color = 'dark';
                            break;
                    }

                    ?>

                    <span class="badge bg-<?= $color; ?>">
                        <?= $incidencia['severidad']; ?>
                    </span>

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="card mb-4">

                <div class="card-header">
                    Datos del colaborador
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <strong>Colaborador</strong><br>
                            <?= $incidencia['colaborador']; ?>
                        </div>

                        <div class="col-md-3 mb-3">
                            <strong>No. Empleado</strong><br>
                            <?= $incidencia['noEmpleado']; ?>
                        </div>

                        <div class="col-md-3 mb-3">
                            <strong>Área</strong><br>
                            <?= $incidencia['area']; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Fecha</strong><br>
                            <?= $incidencia['fecha_incidencia']; ?>
                        </div>

                        <div class="col-md-8 mb-3">
                            <strong>Tipo de incidencia</strong><br>
                            <?= $incidencia['tipo_incidencia']; ?>
                        </div>

                    </div>

                </div>

            </div>

            <div class="card mb-4">

                <div class="card-header">
                    Descripción de los hechos
                </div>

                <div class="card-body">

                    <?= nl2br(
                        htmlspecialchars(
                            $incidencia['descripcion']
                        )
                    ); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-6 mb-4">

                    <div class="card h-100">

                        <div class="card-header">
                            Acciones generadas
                        </div>

                        <div class="card-body">

                            <ul class="list-group">

                                <li class="list-group-item">
                                    Disciplina:
                                    <?= $incidencia['genera_disciplina']
                                        ? 'Sí'
                                        : 'No'; ?>
                                </li>

                                <li class="list-group-item">
                                    Acta:
                                    <?= $incidencia['genera_acta']
                                        ? 'Sí'
                                        : 'No'; ?>
                                </li>

                                <li class="list-group-item">
                                    Adeudo:
                                    <?= $incidencia['genera_adeudo']
                                        ? 'Sí'
                                        : 'No'; ?>
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 mb-4">

                    <div class="card h-100">

                        <div class="card-header">
                            Seguimiento
                        </div>

                        <div class="card-body">

                            <table class="table">

                                <tr>
                                    <th>Registró</th>
                                    <td><?= $incidencia['registrado_por']; ?></td>
                                </tr>

                                <tr>
                                    <th>Evaluó</th>
                                    <td><?= $incidencia['evaluado_por']; ?></td>
                                </tr>

                                <tr>
                                    <th>Autorizó</th>
                                    <td><?= $incidencia['autorizado_por']; ?></td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <div class="text-end">

                <a
                    href="incidencias.php"
                    class="btn btn-secondary"
                >
                    Regresar
                </a>

                <button
                    class="btn btn-warning"
                >
                    Evaluar RH
                </button>

                <button
                    class="btn btn-primary"
                >
                    Autorizar
                </button>

            </div>

        </div>

    </div>

</div>

<script>

function toggleMenu() {

    const sidebar =
        document.getElementById('sidebar');

    if(sidebar){
        sidebar.classList.toggle('open');
    }
}

function closeMenu(event) {

    const sidebar =
        document.getElementById('sidebar');

    if(
        sidebar &&
        sidebar.classList.contains('open') &&
        !sidebar.contains(event.target)
    ){
        sidebar.classList.remove('open');
    }
}

</script>

</body>
</html>