<?php
// CONFIGURACIÓN INICIAL
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../system/connection.php';
require '../system/constants.php';

// RECIBIR FILTROS DE LA URL
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'km';
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$tipo_unidad = isset($_GET['tipo_unidad']) ? trim($_GET['tipo_unidad']) : '';

// Filtrar por estado (URGENTE, PRÓXIMO, A TIEMPO)
$filtro_estado = isset($_GET['filtro_estado']) ? trim($_GET['filtro_estado']) : '';

// PAGINACIÓN - RECIBIR PÁGINA ACTUAL
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$limite = 10;

// CONFIGURACIONES DE TOLERANCIA
$tolerancia_urgente = 500;
$tolerancia_proximo = 2100;
//CONSULTAR BASE DE DATOS
$db = new MySQL();
$sql = "SELECT p.*,  c.km AS km_actual, c.tipo_unidad, c.control_km, c.control_horas FROM preventivo_unidades p LEFT JOIN cat_unidades c ON p.eco = c.eco ORDER BY p.eco ASC";
$rs = $db->consulta($sql);

// PROCESAR RESULTADOS DE LA BD
$unidades = [];
while ($row = $db->fetch_array($rs)) {
    $row['km_actual'] = $row['km_actual'] ?? 0;
    $row['km_servicio'] = $row['km_servicio'] ?? 0;
    $row['limite_km'] = $row['limite_km'] ?? 0;
    $row['hrs_actual'] = $row['hrs_actual'] ?? 0;
    $row['hrs_ultimo_servicio'] = $row['hrs_ultimo_servicio'] ?? 0;
    $row['limite_hrs'] = $row['limite_hrs'] ?? 0;
    $row['hrs_proximo_servicio'] = $row['hrs_proximo_servicio'] ?? 0;
    $row['fecha_ultimo_servicio_hrs'] = $row['fecha_ultimo_servicio_hrs'] ?? null;
    $row['proximo_preventivo_hrs'] = $row['proximo_preventivo_hrs'] ?? null;
    $row['fecha_servicio'] = $row['fecha_servicio'] ?? null;
    $row['limite_dias'] = $row['limite_dias'] ?? 0;
    $unidades[] = $row;
}

// FUNCIÓN PARA CALCULAR ESTADO DE UNA UNIDAD
function calcular_estado_unidad($u, $tab, $tolerancia_urgente, $tolerancia_proximo, $hoy) {
    if ($tab === 'km') {
        $restante = $u['km_proximo_servicio'] - $u['km_actual'];
        if ($restante <= $tolerancia_urgente) return 'URGENTE';
        if ($restante <= $tolerancia_proximo) return 'PRÓXIMO';
        return 'A TIEMPO';
    }
    
    if ($tab === 'hrs') {
        $restante = $u['hrs_proximo_servicio'] - $u['hrs_actual'];
        if ($restante <= 40) return 'URGENTE';
        if ($restante <= 120) return 'PRÓXIMO';
        return 'A TIEMPO';
    }
    
    $fecha_proximo = $u['proximo_preventivo_hrs'] ?? null;
    if (empty($fecha_proximo) || $fecha_proximo === '0000-00-00') {
        return 'SIN FECHA';
    }
    $dias = (strtotime($fecha_proximo) - strtotime($hoy)) / 86400;
    $restante = floor($dias);
    if ($restante < 0) return 'URGENTE';
    if ($restante <= 15) return 'PRÓXIMO';
    return 'A TIEMPO';
}

// APLICAR FILTROS - CORREGIDO
$unidades_filtradas = [];

foreach ($unidades as $u) {
    if ($buscar != '') {
        $eco = $u['eco'] ?? '';
        $tipo = $u['tipo_unidad'] ?? '';
        if (stripos($eco, $buscar) === false && stripos($tipo, $buscar) === false) {continue;
        }
    }
    if ($tipo_unidad != '' && $u['tipo_unidad'] != $tipo_unidad) {continue;
    }
    $unidades_filtradas[] = $u;
}

// SEPARAR POR TIPO DE CONTROL
$unidades_km = [];
$unidades_hrs = [];

foreach ($unidades_filtradas as $u) {
    if ($u['control_km'] == 1) {$unidades_km[] = $u;
    }
    if ($u['control_horas'] == 1) {$unidades_hrs[] = $u;
    }
}

// CALCULAR KPIs
$total = count($unidades_filtradas);
$total_km = count($unidades_km);
$total_hrs = count($unidades_hrs);
$hoy = date("Y-m-d");

// KPI KM
$km_urgentes = $km_proximos = $km_tiempo = 0;
foreach ($unidades_km as $u) {
    $estado = calcular_estado_unidad($u, 'km', $tolerancia_urgente, $tolerancia_proximo, $hoy);
    if ($estado === 'URGENTE') $km_urgentes++;
    elseif ($estado === 'PRÓXIMO') $km_proximos++;
    else $km_tiempo++;
}

// KPI HRS
$hrs_urgentes = $hrs_proximos = $hrs_tiempo = 0;
foreach ($unidades_hrs as $u) {
    $estado = calcular_estado_unidad($u, 'hrs', $tolerancia_urgente, $tolerancia_proximo, $hoy);
    if ($estado === 'URGENTE') $hrs_urgentes++;
    elseif ($estado === 'PRÓXIMO') $hrs_proximos++;
    else $hrs_tiempo++;
}

// KPI TIEMPO
$time_urgentes = $time_proximos = $time_tiempo = $time_sin_fecha = 0;
foreach ($unidades_hrs as $u) {
    $estado = calcular_estado_unidad($u, 'tiempo', $tolerancia_urgente, $tolerancia_proximo, $hoy);
    if ($estado === 'URGENTE') $time_urgentes++;
    elseif ($estado === 'PRÓXIMO') $time_proximos++;
    elseif ($estado === 'SIN FECHA') $time_sin_fecha++;
    else $time_tiempo++;
}

// APLICAR FILTRO DE ESTADO (KPI)
if ($tab === 'km') {
    $datos_sin_filtrar = $unidades_km;
} elseif ($tab === 'hrs') {
    $datos_sin_filtrar = $unidades_hrs;
} else {
    $datos_sin_filtrar = $unidades_hrs;
}

// Aplicar filtro de estado si existe
$datos_filtrados_por_estado = [];
foreach ($datos_sin_filtrar as $u) {$estado = calcular_estado_unidad($u, $tab, $tolerancia_urgente, $tolerancia_proximo, $hoy);
    if ($filtro_estado != '') {
        if ($estado === $filtro_estado) {$datos_filtrados_por_estado[] = $u;
        }
    } else {$datos_filtrados_por_estado[] = $u;
    }
}

// PAGINACIÓN SOBRE DATOS FILTRADOS
$total_registros = count($datos_filtrados_por_estado);
$total_paginas = ceil($total_registros / $limite);
if ($total_paginas == 0) $total_paginas = 1;
if ($pagina > $total_paginas) $pagina = $total_paginas;
$inicio = ($pagina - 1) * $limite;
$datos_paginados = array_slice($datos_filtrados_por_estado, $inicio, $limite);

// KPIs ACTIVOS SEGÚN TAB
if ($tab === 'km') {
    $kpi_total = $total_km;
    $kpi_urgentes = $km_urgentes;
    $kpi_proximos = $km_proximos;
    $kpi_tiempo = $km_tiempo;
    $unidades_mostrar = $datos_paginados;
} elseif ($tab === 'hrs') {
    $kpi_total = $total_hrs;
    $kpi_urgentes = $hrs_urgentes;
    $kpi_proximos = $hrs_proximos;
    $kpi_tiempo = $hrs_tiempo;
    $unidades_mostrar = $datos_paginados;
} else {
    $kpi_total = $total_hrs;
    $kpi_urgentes = $time_urgentes;
    $kpi_proximos = $time_proximos;
    $kpi_tiempo = $time_tiempo;
    $unidades_mostrar = $datos_paginados;
}

// FUNCIÓN PARA GENERAR LINKS
function generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, $pagina) {
    $params = [];
    $params[] = "tab=" . urlencode($tab);
    if ($buscar) $params[] = "buscar=" . urlencode($buscar);
    if ($tipo_unidad) $params[] = "tipo_unidad=" . urlencode($tipo_unidad);
    if ($filtro_estado) $params[] = "filtro_estado=" . urlencode($filtro_estado);
    if ($pagina > 1) $params[] = "pagina=" . $pagina;
    return "?" . implode("&", $params);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento Preventivo</title>
    
    <style>
        .dashboard-card {border-radius: 14px; transition: .2s ease; }
        .dashboard-card:hover {transform: translateY(-3px); }
        .table thead th {position: sticky; top: 0; z-index: 2; }
        .table-responsive {max-height: 500px; overflow-y: auto;}
        .table-responsive::-webkit-scrollbar {width: 8px;}
        .table-responsive::-webkit-scrollbar-thumb {background: #888; border-radius: 10px;}
        .pagination .page-item.active .page-link { background-color: #063a61; border-color: #063a61;color: white;}
        .pagination .page-link {color: #063a61;}
        .pagination .page-link:hover {background-color: #e9ecef;}
        .kpi-activo {border: 3px solid white !important; box-shadow: 0 0 20px rgba(255,255,255,0.5) !important; transform: scale(1.02);}
        .nav-tabs .nav-link {color: #063a61; font-weight: 500; border: none; padding: 10px 20px; border-radius: 8px 8px 0 0; transition: all 0.2s ease; background-color: transparent;}
        .nav-tabs .nav-link:hover {background-color: #e9ecef; border-color: transparent;}
        .nav-tabs .nav-link.active {color:#ffffff !important; background-color: #063a61 !important; border-color: #063a61; font-weight: 600;}
    </style>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
</head>
<body onclick="closeMenu(event)">

<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Preventivo");
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
                <i class="bi bi-truck me-1"></i>
                Mantenimiento Preventivo
            </li>
        </ol>
    </nav>
    <!-- HEADER -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">
                Mantenimiento Preventivo
            </h2>
            <div class="text-muted">
                Control de mantenimiento por kilómetros y horas
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="preventivo_captura_individual.php" class="btn btn-primary shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>
                Captura Individual
            </a>
        </div>
    </div>
    <!-- TARJETAS KPI - ENLACES-->
    <div class="row g-3 mb-4">
        <!-- Total Unidades -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, '', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-primary text-white <?= ($filtro_estado == '') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Total Unidades</div>
                            <div class="fs-3 fw-bold"><?= $kpi_total ?></div>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- Urgentes -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, 'URGENTE', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-danger text-white <?= ($filtro_estado == 'URGENTE') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Urgentes</div>
                            <div class="fs-3 fw-bold"><?= $kpi_urgentes ?></div>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- Próximos -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, 'PRÓXIMO', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-warning text-dark <?= ($filtro_estado == 'PRÓXIMO') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">Próximos</div>
                            <div class="fs-3 fw-bold"><?= $kpi_proximos ?></div>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- A Tiempo -->
        <div class="col-md-3 col-6">
            <a href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, 'A TIEMPO', 1) ?>" 
               class="text-decoration-none">
                <div class="card border-0 shadow-sm dashboard-card bg-success text-white <?= ($filtro_estado == 'A TIEMPO') ? 'kpi-activo' : '' ?>">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase fw-bold">A Tiempo</div>
                            <div class="fs-3 fw-bold"><?= $kpi_tiempo ?></div>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        
    </div>
    <!-- TABS - ESTILO REPORTE DE FALLAS            -->
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tab == 'km') ? 'active' : '' ?>" 
               href="<?= generar_link_preventivo('km', $buscar, $tipo_unidad, $filtro_estado, 1) ?>"
               role="tab">
                Por KM
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tab == 'hrs') ? 'active' : '' ?>" 
               href="<?= generar_link_preventivo('hrs', $buscar, $tipo_unidad, $filtro_estado, 1) ?>"
               role="tab">
                Por HRS
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($tab == 'tiempo') ? 'active' : '' ?>" 
               href="<?= generar_link_preventivo('tiempo', $buscar, $tipo_unidad, $filtro_estado, 1) ?>"
               role="tab">
                Por Tiempo
            </a>
        </li>
    </ul>

    <!-- FORMULARIO DE FILTROS                      -->
    <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="tab" value="<?= $tab ?>">
        <?php if ($filtro_estado): ?>
        <input type="hidden" name="filtro_estado" value="<?= htmlspecialchars($filtro_estado) ?>">
        <?php endif; ?>
        
        <div class="col-md-4">
            <input type="text" 
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar ECO o tipo unidad"
                   value="<?= htmlspecialchars($buscar) ?>">
        </div>
        
        <div class="col-md-3">
            <select name="tipo_unidad" class="form-select">
                <option value="">-- Tipo Unidad --</option>
                <?php
                $tipos = $db->consulta("SELECT DISTINCT tipo_unidad FROM cat_unidades");
                while ($t = $db->fetch_array($tipos)) {
                    $selected = ($tipo_unidad == $t['tipo_unidad']) ? 'selected' : '';
                    echo "<option value='{$t['tipo_unidad']}' $selected>{$t['tipo_unidad']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary w-100">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            <a href="<?= generar_link_preventivo($tab, '', '', '', 1) ?>" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
            </a>
        </div>
    </form>

    <!-- TABLA                                      -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <?php if ($filtro_estado): ?>
                        Unidades con estado <span class="badge bg-<?= ($filtro_estado=='URGENTE')?'danger':(($filtro_estado=='PRÓXIMO')?'warning':'success') ?>"><?= $filtro_estado ?></span>
                    <?php else: ?>
                        Todas las unidades
                    <?php endif; ?>
                </h5>
                <span class="text-muted small">
                    Mostrando <?= count($unidades_mostrar) ?> de <?= $total_registros ?> unidades
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
                        <?php if ($tab === 'km' || $tab === 'hrs'): ?>
                            <th>ECO</th>
                            <th><?= $tab === 'km' ? 'KM' : 'HRS' ?> Actual</th>
                            <th><?= $tab === 'km' ? 'Kilómetros' : 'Horas' ?> Límite</th>
                            <th>Último Servicio</th>
                            <th>Próximo Preventivo</th>
                            <th>Restante</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        <?php else: ?>
                            <th>ECO</th>
                            <th>Último Servicio</th>
                            <th>Próximo Preventivo</th>
                            <th>Restante (Días)</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unidades_mostrar as $u):
                        $estado = calcular_estado_unidad($u, $tab, $tolerancia_urgente, $tolerancia_proximo, $hoy);
                        
                        if ($tab === 'km') {
                            $restante = $u['km_proximo_servicio'] - $u['km_actual'];
                            $color = $estado === 'URGENTE' ? 'danger' : ($estado === 'PRÓXIMO' ? 'warning' : 'success');
                        } elseif ($tab === 'hrs') {
                            $restante = $u['hrs_proximo_servicio'] - $u['hrs_actual'];
                            $color = $estado === 'URGENTE' ? 'danger' : ($estado === 'PRÓXIMO' ? 'warning' : 'success');
                        } else {
                            $fecha_proximo = $u['proximo_preventivo_hrs'] ?? null;
                            if (empty($fecha_proximo) || $fecha_proximo === '0000-00-00') {
                                $restante = 'N/A';
                                $color = 'secondary';
                            } else {
                                $dias = (strtotime($fecha_proximo) - strtotime($hoy)) / 86400;
                                $restante = floor($dias);
                                $color = $estado === 'URGENTE' ? 'danger' : ($estado === 'PRÓXIMO' ? 'warning' : 'success');
                            }
                        }
                    ?>
                    <tr data-status="<?= $estado ?>">
                        <td class="text-center fw-bold"><?= $u['eco'] ?></td>
                        
                        <?php if ($tab === 'km'): ?>
                            <td class="text-center"><?= number_format((float)$u['km_actual']) ?></td>
                            <td class="text-center"><?= number_format($u['km_proximo_servicio']) ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($u['fecha_ultimo_servicio_kilometros'])) ?></td>
                            <td class="text-center">
                                <?= !empty($u['proximo_preventivo_kilometros']) ? date('d/m/Y', strtotime($u['proximo_preventivo_kilometros'])) : '—' ?>
                            </td>
                            <td class="text-center fw-bold"><?= number_format($restante) ?></td>
                            
                        <?php elseif ($tab === 'hrs'): ?>
                            <td class="text-center"><?= number_format($u['hrs_actual']) ?></td>
                            <td class="text-center"><?= number_format((float)$u['hrs_proximo_servicio']) ?></td>
                            <td class="text-center">
                                <?= !empty($u['fecha_ultimo_servicio_hrs']) && $u['fecha_ultimo_servicio_hrs'] !== '0000-00-00' ? date('d/m/Y', strtotime($u['fecha_ultimo_servicio_hrs'])) : '—' ?>
                            </td>
                            <td class="text-center">
                                <?= !empty($u['proximo_preventivo_hrs']) && $u['proximo_preventivo_hrs'] !== '0000-00-00' ? date('d/m/Y', strtotime($u['proximo_preventivo_hrs'])) : '—' ?>
                            </td>
                            <td class="text-center fw-bold"><?= number_format($restante) ?></td>
                            
                        <?php else: ?>
                            <td class="text-center">
                                <?= !empty($u['fecha_ultimo_servicio_kilometros']) && $u['fecha_ultimo_servicio_kilometros'] !== '0000-00-00' ? date('d/m/Y', strtotime($u['fecha_ultimo_servicio_kilometros'])) : '—' ?>
                            </td>
                            <td class="text-center">
                                <?= !empty($u['proximo_preventivo_hrs']) && $u['proximo_preventivo_hrs'] !== '0000-00-00' ? date('d/m/Y', strtotime($u['proximo_preventivo_hrs'])) : '—' ?>
                            </td>
                            <td class="text-center fw-bold"><?= $restante === 'N/A' ? '—' : $restante ?></td>
                        <?php endif; ?>
                        
                        <td class="text-center">
                            <span class="badge bg-<?= $color ?>"><?= $estado ?></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm verPreventivo" data-id="<?= $u['id'] ?>" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="preventivo_editar.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($unidades_mostrar)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-2"></i>
                            No hay unidades que coincidan con los filtros aplicados
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
                        <a class="page-link" href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, $pagina-1) ?>">
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
                            <a class="page-link" href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, 1) ?>">1</a>
                        </li>
                        <?php if ($inicio_paginacion > 2): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++): ?>
                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, $i) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($fin_paginacion < $total_paginas): ?>
                        <?php if ($fin_paginacion < $total_paginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, $total_paginas) ?>">
                                <?= $total_paginas ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= generar_link_preventivo($tab, $buscar, $tipo_unidad, $filtro_estado, $pagina+1) ?>">
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

<!-- MODAL                                      -->
<div class="modal fade" id="modalPreventivo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Detalle Preventivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoPreventivo">
                <div class="text-center"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>
<!-- JAVASCRIPT                                 -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".verPreventivo").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let modal = new bootstrap.Modal(document.getElementById('modalPreventivo'));
            modal.show();
            document.getElementById("contenidoPreventivo").innerHTML = `<div class="text-center"><div class="spinner-border text-primary"></div></div>`;
            fetch("preventivo_ver_modal.php?id=" + id).then(response => response.text()).then(data => {document.getElementById("contenidoPreventivo").innerHTML = data;
                })
                .catch(() => {document.getElementById("contenidoPreventivo").innerHTML ="<div class='alert alert-danger'>Error al cargar datos</div>";
                });
        });
    });
});

function toggleMenu() {const sidebar = document.getElementById('sidebar');sidebar.classList.toggle('open');}
function closeMenu(event) {const sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('open') && !sidebar.contains(event.target)) {sidebar.classList.remove('open');
    }
}
</script>
</body>
</html>