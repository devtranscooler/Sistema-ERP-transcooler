<?php
require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

$id = intval($_GET['id']);

// Si se envía el formulario → ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $eco = $_POST['eco'];
    $razon_social = $_POST['razon_social'];
    $placas = $_POST['placas'];
    $folio_tc = $_POST['folio_tc'];
    $niv = $_POST['niv'];
    $no_motor = $_POST['no_motor'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $capacidad = $_POST['capacidad'];
    $tipo_unidad = $_POST['tipo_unidad'];
    $anio = $_POST['anio'];
    $color = $_POST['color'];
    $aseguradora = $_POST['aseguradora'];
    $cobertura = $_POST['cobertura'];

    $sql = "UPDATE cat_unidades SET
        eco = '$eco',
        razon_social = '$razon_social',
        placas = '$placas',
        folio_tc = '$folio_tc',
        niv = '$niv',
        no_motor = '$no_motor',
        marca = '$marca',
        modelo = '$modelo',
        capacidad = '$capacidad',
        tipo_unidad = '$tipo_unidad',
        anio = '$anio',
        color = '$color',
        aseguradora = '$aseguradora',
        cobertura = '$cobertura'
        WHERE id = $id";

    $db->consulta($sql);

    header("Location: cat_unidades.php");
    exit;
}

// Obtener datos actuales
$sql = "SELECT * FROM cat_unidades WHERE id = $id";
$resultado = $db->consulta($sql);
$unidad = $db->fetch_array($resultado);

if (!$unidad) {
    die("Unidad no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
    <title>Editar Unidad</title>
</head>

<body>

<div class="container mt-5">
    <h3 class="text-warning mb-4">✏ Editar Unidad</h3>

    <form method="POST">

        <div class="row">

            <div class="col-md-3 mb-3">
                <label>ECO</label>
                <input type="text" name="eco" class="form-control"
                       value="<?= htmlspecialchars($unidad['eco']) ?>" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Razón Social</label>
                <input type="text" name="razon_social" class="form-control"
                       value="<?= htmlspecialchars($unidad['razon_social']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Placas</label>
                <input type="text" name="placas" class="form-control"
                       value="<?= htmlspecialchars($unidad['placas']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Folio TC</label>
                <input type="text" name="folio_tc" class="form-control"
                       value="<?= htmlspecialchars($unidad['folio_tc']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>NIV</label>
                <input type="text" name="niv" class="form-control"
                       value="<?= htmlspecialchars($unidad['niv']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>No. Motor</label>
                <input type="text" name="no_motor" class="form-control"
                       value="<?= htmlspecialchars($unidad['no_motor']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control"
                       value="<?= htmlspecialchars($unidad['marca']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>Modelo</label>
                <input type="text" name="modelo" class="form-control"
                       value="<?= htmlspecialchars($unidad['modelo']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>Capacidad</label>
                <input type="text" name="capacidad" class="form-control"
                       value="<?= htmlspecialchars($unidad['capacidad']) ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label>Tipo Unidad</label>
                <input type="text" name="tipo_unidad" class="form-control"
                       value="<?= htmlspecialchars($unidad['tipo_unidad']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Año</label>
                <input type="number" name="anio" class="form-control"
                       value="<?= htmlspecialchars($unidad['anio']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Color</label>
                <input type="text" name="color" class="form-control"
                       value="<?= htmlspecialchars($unidad['color']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Aseguradora</label>
                <input type="text" name="aseguradora" class="form-control"
                       value="<?= htmlspecialchars($unidad['aseguradora']) ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Cobertura</label>
                <select name="cobertura" class="form-control">
                    <option <?= $unidad['cobertura'] == 'AMPLIA' ? 'selected' : '' ?>>
                        AMPLIA
                    </option>
                    <option <?= $unidad['cobertura'] == 'LIMITADA' ? 'selected' : '' ?>>
                        LIMITADA
                    </option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-warning">
            💾 Guardar Cambios
        </button>

        <a href="parque_vehicular.php" class="btn btn-secondary">
            Cancelar
        </a>

    </form>
</div>

</body>
</html>