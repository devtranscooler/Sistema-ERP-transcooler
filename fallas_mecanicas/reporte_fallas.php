<?php
require '../system/connection.php';
require '../system/constants.php';
$db = new MySQL();
/*TIPO DE FILTRO*/
$tipo = $_GET['tipo'] ?? 'todos';
$tipo_db = '';
if ($tipo === 'auxilios') {$tipo_db = "AUXILIO";}
if ($tipo === 'mecanicas') {$tipo_db = "MECANICA";}
if ($tipo === 'talachas') {$tipo_db = "TALACHA";}
/*FILTRO DE ESTADO (KPI)*/
$filtro_status = isset($_GET['filtro_status']) ? trim($_GET['filtro_status']) : '';
/*PAGINACIÓN*/
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$limite = 10;
/*WHERE BASE*/
$where_base = "WHERE activo=1";
$where_tipo = "";
if ($tipo !== 'todos') {$where_tipo = " AND tipo_reporte = '$tipo_db' ";} $where_status = "";
if ($filtro_status != '') {$where_status = " AND estatus = '$filtro_status' ";}
/*DASHBOARD - TOTALES*/
$sql_total = " SELECT COUNT(*) total FROM fallas $where_base $where_tipo";
$total = $db->fetch_array($db->consulta($sql_total))['total'];
$sql_abiertas = "SELECT COUNT(*) total FROM fallas $where_base $where_tipo AND estatus='PENDIENTE'";
$abiertas = $db->fetch_array($db->consulta($sql_abiertas))['total'];
$sql_proceso = "SELECT COUNT(*) total FROM fallas $where_base $where_tipo AND estatus='EN PROCESO'";
$proceso = $db->fetch_array($db->consulta($sql_proceso))['total'];
$sql_cerradas = "SELECT COUNT(*) total FROM fallas $where_base $where_tipo AND estatus='CERRADA'";
$cerradas = $db->fetch_array($db->consulta($sql_cerradas))['total'];
/*TABLA - CON PAGINACIÓN*/
/*TABLA - CON PAGINACIÓN*/
$sql = "SELECT f.*, 
               CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as nombre_operador
        FROM fallas f
        LEFT JOIN usuarios u ON f.operador = u.id
        $where_base $where_tipo $where_status 
        ORDER BY f.id DESC ";
$rs = $db->consulta($sql);
$datos = [];
while ($fila = $db->fetch_array($rs)) {$datos[] = $fila;}
//Paginar los datos
$total_registros = count($datos);
$total_paginas = ceil($total_registros / $limite);
if ($total_paginas == 0) $total_paginas = 1;
if ($pagina > $total_paginas) $pagina = $total_paginas;
$inicio = ($pagina - 1) * $limite;
$datos_paginados = array_slice($datos, $inicio, $limite);

// FUNCIÓN PARA GENERAR LINKS
function generar_link_fallas($tipo, $filtro_status, $pagina) {$params = [];
    if ($tipo && $tipo !== 'todos') {$params[] = "tipo=" . urlencode($tipo);}
    if ($filtro_status) {$params[] = "filtro_status=" . urlencode($filtro_status);}
    if ($pagina > 1) {$params[] = "pagina=" . $pagina;}
    return "?" . implode("&", $params);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
    <title>Reporte de Fallas</title>
</head>
<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Reporte de Fallas");
?>

<div class="container-fluid">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="bi bi-house-door me-1"></i>
                Inicio
            </li>
            <li class="breadcrumb-item active">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Reporte de Fallas
            </li>
        </ol>
    </nav>
    <!-- HEADER -->
    <div class="row align-items-center mb-4">

        <div class="col-md-6">
            <h2 class="fw-bold mb-0">
                Reporte de Fallas
            </h2>

            <div class="text-muted">
                Registro y seguimiento de incidencias
            </div>
        </div>
        <div class="col-md-6 text-md-end">

            <a href="registrar_falla.php"
               class="btn btn-danger shadow-sm">

                <i class="bi bi-plus-circle me-2"></i>
                Registrar Falla
            </a>
        </div>
    </div>
    <!-- DASHBOARD - TARJETAS KPI -->
    <div class="row g-3 mb-4">
        <!-- TOTAL -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_fallas($tipo, '', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-primary text-white <?= ($filtro_status == '') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Total Reportes</div>
                            <div class="fs-3 fw-bold"><?= $total ?></div>
                        </div>
                        <i class="bi bi-clipboard-data fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ABIERTAS (PENDIENTE) -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_fallas($tipo, 'PENDIENTE', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-danger text-white <?= ($filtro_status == 'PENDIENTE') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Abiertas</div>
                            <div class="fs-3 fw-bold"><?= $abiertas ?></div>
                        </div>
                        <i class="bi bi-exclamation-octagon fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- EN PROCESO -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_fallas($tipo, 'EN PROCESO', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-warning text-dark <?= ($filtro_status == 'EN PROCESO') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">En Proceso</div>
                            <div class="fs-3 fw-bold"><?= $proceso ?></div>
                        </div>
                        <i class="bi bi-tools fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- CERRADAS -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_fallas($tipo, 'CERRADA', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-success text-white <?= ($filtro_status == 'CERRADA') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Cerradas</div>
                            <div class="fs-3 fw-bold"><?= $cerradas ?></div>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- TABS - ESTILO PREVENTIVO                   -->
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tipo == 'todos') ? 'active' : '' ?>" 
               href="<?= generar_link_fallas('todos', $filtro_status, 1) ?>"
               role="tab">
                Todas
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tipo == 'auxilios') ? 'active' : '' ?>" 
               href="<?= generar_link_fallas('auxilios', $filtro_status, 1) ?>"
               role="tab">
                Auxilios
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tipo == 'mecanicas') ? 'active' : '' ?>" 
               href="<?= generar_link_fallas('mecanicas', $filtro_status, 1) ?>"
               role="tab">
                Mecánicas
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tipo == 'talachas') ? 'active' : '' ?>" 
               href="<?= generar_link_fallas('talachas', $filtro_status, 1) ?>"
               role="tab">
                Talachas
            </a>
        </li>
    </ul>
    <!-- TABLA                                      -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <?php if ($filtro_status): ?>
                        Reportes con estado <span class="badge bg-<?= ($filtro_status=='PENDIENTE')?'danger':(($filtro_status=='EN PROCESO')?'warning':'success') ?>"><?= $filtro_status ?></span>
                    <?php else: ?>
                        Todos los Reportes
                    <?php endif; ?>
                </h5>
                <span class="text-muted small">
                    Mostrando <?= count($datos_paginados) ?> de <?= $total_registros ?> reportes
                    <?php if ($total_paginas > 1): ?>
                        (Página <?= $pagina ?> de <?= $total_paginas ?>)
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#063a61; color:white;">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>ECO</th>
                        <th>Operador</th>
                        <th>Falla</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th>Status</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($datos_paginados as $row): ?>
                        <tr data-status="<?= $row['estatus'] ?>">
                            <td class="text-center">
                                <?= $row['id'] ?>
                            </td>
                            <td class="text-center fw-bold">
                                <?= $row['eco'] ?? '-' ?>
                            </td>
                            <td>
                                <?= $row['nombre_operador'] ?? '-' ?>
                            </td>
                            <td>
                                <?= $row['tipo_falla'] ?? '-' ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    NORMAL
                                </span>
                            </td>

                            <td class="text-center">
                                <?= !empty($row['fecha_registro']) ? date('d/m/Y', strtotime($row['fecha_registro'])): '-'?>
                            </td>
                            <td class="text-center">
                                <?php
                                $color = 'danger';
                                if (($row['estatus'] ?? '') == 'EN PROCESO') {$color = 'warning';}
                                if (($row['estatus'] ?? '') == 'CERRADA') {$color = 'success';}
                                ?>
                                <span class="badge bg-<?= $color ?>">
                                    <?= $row['estatus'] ?? 'PENDIENTE' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_falla.php?id=<?= $row['id'] ?>"
                                       class="btn btn-info btn-sm"
                                       title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="editar_falla.php?id=<?= $row['id'] ?>"
                                       class="btn btn-warning btn-sm"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm"
                                            title="Ocultar"
                                            onclick="eliminar(<?= $row['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($datos_paginados)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-2"></i>
                            No hay reportes que coincidan con los filtros aplicados
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- PAGINACIÓN                                 -->
        <?php if ($total_registros > $limite): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= generar_link_fallas($tipo, $filtro_status, $pagina-1) ?>">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-left"></i> Anterior</span>
                    </li>
                    <?php endif; ?>
                    <?php 
                    $rango = 2;
                    $inicio_paginacion = max(1, $pagina - $rango);
                    $fin_paginacion = min($total_paginas, $pagina + $rango);
                    if ($inicio_paginacion > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= generar_link_fallas($tipo, $filtro_status, 1) ?>">1</a>
                        </li>
                        <?php if ($inicio_paginacion > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= generar_link_fallas($tipo, $filtro_status, $i) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <?php if ($fin_paginacion < $total_paginas): ?>
                        <?php if ($fin_paginacion < $total_paginas - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= generar_link_fallas($tipo, $filtro_status, $total_paginas) ?>">
                                <?= $total_paginas ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= generar_link_fallas($tipo, $filtro_status, $pagina+1) ?>">
                            Siguiente <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">Siguiente <i class="bi bi-chevron-right"></i></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<style>
.dashboard-card { border-radius: 14px; transition: .2s ease;}
.dashboard-card:hover { transform: translateY(-3px);}
.table thead th { position: sticky; top: 0; z-index: 2;}
.table-responsive {max-height: 500px;overflow-y: auto;}
.table-responsive::-webkit-scrollbar {width: 8px;}
.table-responsive::-webkit-scrollbar-thumb { background: #888; border-radius: 10px;}
.pagination .page-item.active .page-link { background-color: #063a61; border-color: #063a61; color: white;}
.pagination .page-link {color: #063a61;}
.pagination .page-link:hover {background-color: #e9ecef;}
/* KPI activo*/
.kpi-activo { border: 3px solid white !important; box-shadow: 0 0 20px rgba(255,255,255,0.5) !important; transform: scale(1.02);}
/* ESTILO DE TABS*/
.nav-tabs .nav-link {color: #063a61; font-weight: 500; border: none;padding: 10px 20px; border-radius: 8px 8px 0 0; transition: all 0.2s ease;}
.nav-tabs .nav-link:hover {background-color: #e9ecef;border-color: transparent;}
.nav-tabs .nav-link.active {color: white; background-color: #063a61; border-color: #063a61; font-weight: 600;}
.nav-tabs .nav-link:not(.active) { background-color: transparent; color: #063a61;}
</style>
<script>

function toggleMenu() {const sidebar = document.getElementById('sidebar');
sidebar.classList.toggle('open');
}

function closeMenu(event) {const sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('open') &&!sidebar.contains(event.target)) {sidebar.classList.remove('open');
    }
}
</script>
<script>
function eliminar(id) {if (confirm('¿Eliminar reporte?')) { window.location = 'eliminar_falla.php?id=' + id; }}
</script>