<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/constants.php';

$db = new MySQL();

$incidencias = [];

$sql = "
    SELECT
        i.id_incidencia,
        i.folio,
        i.fecha_incidencia,
        c.nombre AS tipo_incidencia,
        i.severidad,
        i.estatus,
        i.genera_disciplina,
        i.genera_acta,
        i.genera_adeudo,
        CONCAT(
            u.nombre,' ',
            u.apellidoP,' ',
            IFNULL(u.apellidoM,'')
        ) AS colaborador
    FROM incidencias_rh i
    INNER JOIN usuarios u
        ON u.id = i.id_usuario
    INNER JOIN cat_incidencias_rh c
        ON c.id_tipo = i.id_tipo
    ORDER BY i.id_incidencia DESC
";

$resultado = $db->consulta($sql);

while($row = $db->fetch_assoc($resultado)){
    $incidencias[] = $row;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

<title>Incidencias RH</title>

</head>

<body onclick="closeMenu(event)">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';
Sidebar::render();
?>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h3>Incidencias Laborales</h3>

        <a
            href="nueva_incidencia.php"
            class="btn btn-success"
        >
            Nueva Incidencia
        </a>

    </div>

<div class="card shadow">

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-striped table-hover"
                id="tablaIncidencias"
            >

                <thead>

                    <tr>

                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Colaborador</th>
                        <th>Incidencia</th>
                        <th>Severidad</th>
                        <th>Estatus</th>
                        <th>Disciplina</th>
                        <th>Acta</th>
                        <th>Adeudo</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($incidencias as $incidencia): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($incidencia['folio']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($incidencia['fecha_incidencia']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($incidencia['colaborador']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($incidencia['tipo_incidencia']); ?>
                        </td>

                        <td>

                            <?php
                            $badge = 'secondary';

                            switch($incidencia['severidad']){

                                case 'LEVE':
                                    $badge = 'success';
                                    break;

                                case 'MEDIA':
                                    $badge = 'warning';
                                    break;

                                case 'GRAVE':
                                    $badge = 'danger';
                                    break;

                                case 'CRITICA':
                                    $badge = 'dark';
                                    break;
                            }
                            ?>

                            <span class="badge bg-<?= $badge; ?>">
                                <?= $incidencia['severidad']; ?>
                            </span>

                        </td>

                        <td>
                            <?= $incidencia['estatus']; ?>
                        </td>

                        <td>

                            <?=
                            $incidencia['genera_disciplina']
                            ? 'Sí'
                            : 'No';
                            ?>

                        </td>

                        <td>

                            <?=
                            $incidencia['genera_acta']
                            ? 'Sí'
                            : 'No';
                            ?>

                        </td>

                        <td>

                            <?=
                            $incidencia['genera_adeudo']
                            ? 'Sí'
                            : 'No';
                            ?>

                        </td>

                        <td>

                            <a
                                href="detalle_incidencia.php?id=<?= $incidencia['id_incidencia']; ?>"
                                class="btn btn-primary btn-sm"
                            >
                                Ver
                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

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