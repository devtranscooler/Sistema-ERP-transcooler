<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/constants.php';

$db = new MySQL();

$empleados = [];
$incidencias = [];

/*
|--------------------------------------------------------------------------
| Empleados
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT
        id,
        CONCAT(
            nombre,' ',
            apellidoP,' ',
            IFNULL(apellidoM,'')
        ) AS colaborador,
        noEmpleado,
        area
    FROM usuarios
    WHERE id_estatus = 1
    ORDER BY nombre ASC
";

$db = new MySQL();

$result = $db->consulta($sql);

while($row = $db->fetch_assoc($result)){
    $empleados[] = $row;
}

/*
|--------------------------------------------------------------------------
| Tipos de incidencia
|--------------------------------------------------------------------------
*/
$sqlIncidencias = "
    SELECT
        id_tipo,
        nombre
    FROM cat_incidencias_rh
    WHERE activo = 1
    ORDER BY nombre
";

$resultIncidencias = $db->consulta($sqlIncidencias);

while($row = $db->fetch_assoc($resultIncidencias)){
    $incidencias[] = $row;
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

<title>Nueva Incidencia</title>

</head>

<body onclick="closeMenu(event)">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';
Sidebar::render();
?>

<div class="container-fluid mt-4">


<div class="row">

    <div class="col-12">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Registrar Nueva Incidencia
                </h4>
            </div>

            <div class="card-body">

                <form
                    id="formIncidencia"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="action"
                        value="crear"
                    >

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Colaborador
                            </label>

                            <select
                                name="id_usuario"
                                id="id_usuario"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccione...
                                </option>

                                <?php foreach($empleados as $empleado): ?>

                                    <option value="<?= $empleado['id']; ?>">
                                        <?= htmlspecialchars(
                                            $empleado['colaborador']
                                        ); ?>
                                        -
                                        <?= htmlspecialchars(
                                            $empleado['noEmpleado']
                                        ); ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tipo de Incidencia
                            </label>

                            <select
                                name="id_tipo"
                                id="id_tipo"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Seleccione...
                                </option>

                                <?php foreach($incidencias as $incidencia): ?>

                                    <option
                                        value="<?= $incidencia['id_tipo']; ?>"
                                    >
                                        <?= htmlspecialchars(
                                            $incidencia['nombre']
                                        ); ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Fecha de Incidencia
                            </label>

                            <input
                                type="date"
                                name="fecha_incidencia"
                                id="fecha_incidencia"
                                class="form-control"
                                value="<?= date('Y-m-d'); ?>"
                                required
                            >

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Descripción de Hechos
                            </label>

                            <textarea
                                name="descripcion"
                                id="descripcion"
                                class="form-control"
                                rows="5"
                                required
                            ></textarea>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="observaciones"
                                class="form-control"
                                rows="3"
                            ></textarea>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Evidencia
                            </label>

                            <input
                                type="file"
                                name="evidencia"
                                id="evidencia"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf"
                            >

                        </div>

                    </div>

                    <hr>

                    <div class="text-end">

                        <a
                            href="incidencias.php"
                            class="btn btn-secondary"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Guardar Incidencia
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


</div>

<script>

    document
    .getElementById('formIncidencia')
    .addEventListener('submit', async function(e){

        e.preventDefault();

        const formData = new FormData(this);

        try {

            const response = await fetch(
                '/Capital_Humano/controllers/relaciones_laborales/incidencias_controller.php',
                {
                    method: 'POST',
                    body: formData
                }
            );

            const data = await response.json();

            if(data.success){

                alert(
                    'Incidencia registrada correctamente.\n\nFolio: '
                    + data.folio
                );

                window.location.href =
                    'incidencias.php';

            } else {

                alert(data.message);

            }

        } catch(error){

            console.error(error);

            alert(
                'Ocurrió un error al guardar la incidencia.'
            );

        }

    });

    function toggleMenu() 
    {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }

    function closeMenu(event) 
    {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('open') && !sidebar.contains(event.target)) 
            {
                sidebar.classList.remove('open');
            }
    }
    </script>


</body>

</html>
