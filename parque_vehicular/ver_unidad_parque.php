<?php
require '../system/connection.php';

$db = new MySQL();

$id = intval($_GET['id']);
$sql = "SELECT * FROM cat_unidades WHERE id = $id";
$resultado = $db->consulta($sql);
$unidad = mysqli_fetch_assoc($resultado);

if (!$unidad) {
    die("Unidad no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
    <title>Detalle de Unidad</title>
</head>

<body>

<div class="container mt-5">

    <h3 class="mb-4 text-primary">👁 Detalle de Unidad</h3>

    <table class="table table-bordered">
        <?php foreach ($unidad as $campo => $valor) : ?>
            <tr>
                <th><?= ucfirst($campo) ?></th>
                <td><?= htmlspecialchars($valor) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="parque_vehicular.php" class="btn btn-secondary">
        Volver
    </a>

</div>

</body>
</html>