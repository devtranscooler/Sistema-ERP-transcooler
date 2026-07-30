<?php
require '../system/connection.php';
require '../system/constants.php';
$db = new MySQL();
$limite = 25;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;
/* ===========================
   FILTROS
=========================== */
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$anio = isset($_GET['anio']) ? trim($_GET['anio']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$where = "WHERE 1=1";
if ($busqueda != '') {
    $where .= " AND (
        eco LIKE '%$busqueda%' OR
        marca LIKE '%$busqueda%' OR
        modelo LIKE '%$busqueda%' OR
        placas LIKE '%$busqueda%'
    )";
}
if ($anio != '') {
    $where .= " AND anio = '$anio'";
}
if ($tipo != '') {
    $where .= " AND tipo_unidad = '$tipo'";
}
/* ===========================
   TOTAL REGISTROS
=========================== */
$sql_total = "SELECT COUNT(*) as total FROM cat_unidades $where";
$result_total = $db->consulta($sql_total);
$total_registros = $db->fetch_array($result_total)['total'];
$total_paginas = ceil($total_registros / $limite);
/* ===========================
   CONSULTA FINAL
=========================== */
$sql = "SELECT * FROM cat_unidades 
        $where
        ORDER BY eco ASC
        LIMIT $inicio, $limite";
$resultado = $db->consulta($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
    <title>Catálogo de Unidades</title>
</head>

<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Catálogo de Unidades");
?>

<!-- 📍 Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="bi bi-house-door me-1"></i>Inicio
            </li>
            <li class="breadcrumb-item active">
                <i class="bi bi-truck me-1"></i>Parque Vehicular
            </li>
        </ol>
    </nav>

    <!-- 🎯 Encabezado -->
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">🚛 Catálogo de Unidades</h3>

        <a href="formulario_unidad.php" class="btn btn-success">
            ➕ Nueva Unidad
        </a>
    </div>
    <form method="GET" class="row mb-3">
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control"
               placeholder="🔍 Buscar por ECO, marca, modelo o placas"
               value="<?= htmlspecialchars($busqueda) ?>">
    </div>
    <div class="col-md-2">
        <select name="anio" class="form-select">
            <option value="">-- Año --</option>
            <?php
            $years = $db->consulta("SELECT DISTINCT anio FROM cat_unidades ORDER BY anio DESC");
            while ($y = $db->fetch_array($years)) {
                $selected = ($anio == $y['anio']) ? 'selected' : '';
                echo "<option value='{$y['anio']}' $selected>{$y['anio']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="tipo" class="form-select">
            <option value="">-- Tipo Unidad --</option>
            <?php
            $tipos = $db->consulta("SELECT DISTINCT tipo_unidad FROM cat_unidades");
            while ($t = $db->fetch_array($tipos)) {
                $selected = ($tipo == $t['tipo_unidad']) ? 'selected' : '';
                echo "<option value='{$t['tipo_unidad']}' $selected>{$t['tipo_unidad']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        <a href="?" class="btn btn-secondary w-100">Limpiar</a>
    </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ECO</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Capacidad</th>
                    <th>Año</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $db->fetch_array($resultado)) : ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['eco']) ?></strong></td>
                    <td><?= htmlspecialchars($row['marca']) ?></td>
                    <td><?= htmlspecialchars($row['modelo']) ?></td>
                    <td><?= htmlspecialchars($row['capacidad']) ?></td>
                    <td><?= htmlspecialchars($row['anio']) ?></td>

                    <td>
                        <button class="btn btn-info btn-sm verUnidad"
        data-id="<?= $row['id'] ?>">
    👁 Ver
</button>
                        <a href="editar_unidad_parque.php?id=<?= $row['id'] ?>" 
                           class="btn btn-warning btn-sm">
                           ✏ Editar
                        </a>
                        <a href="eliminar_unidad_parque.php?id=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Seguro que deseas eliminar esta unidad?')">
                           🗑 Eliminar
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <nav class="mt-3 d-flex justify-content-end">
        <ul class="pagination">
        <?php if ($pagina > 1): ?>
            <li class="page-item">
                <a class="page-link"
                   href="?pagina=<?= $pagina - 1 ?>&buscar=<?= $busqueda ?>&anio=<?= $anio ?>&tipo=<?= $tipo ?>">
                   Anterior
                </a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                <a class="page-link"
                   href="?pagina=<?= $i ?>&buscar=<?= $busqueda ?>&anio=<?= $anio ?>&tipo=<?= $tipo ?>">
                   <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
        <?php if ($pagina < $total_paginas): ?>
            <li class="page-item">
                <a class="page-link"
                   href="?pagina=<?= $pagina + 1 ?>&buscar=<?= $busqueda ?>&anio=<?= $anio ?>&tipo=<?= $tipo ?>">
                   Siguiente
                </a>
            </li>
        <?php endif; ?>
        </ul>
        </nav>
    </div>
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

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll(".verUnidad").forEach(button => {
        button.addEventListener("click", function() {

            let id = this.getAttribute("data-id");

            let modal = new bootstrap.Modal(document.getElementById('modalUnidad'));
            modal.show();

            document.getElementById("contenidoModal").innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary"></div>
                </div>
            `;

            fetch("ver_unidad_parque.php?id=" + id)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("contenidoModal").innerHTML = data;
                })
                .catch(error => {
                    document.getElementById("contenidoModal").innerHTML =
                        "<div class='alert alert-danger'>Error al cargar datos</div>";
                });

        });
    });

});
</script>
<!-- Modal -->
<div class="modal fade" id="modalUnidad" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalle de Unidad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="contenidoModal">
        <!-- Aquí se cargará la información -->
        <div class="text-center">
            <div class="spinner-border text-primary"></div>
        </div>
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
