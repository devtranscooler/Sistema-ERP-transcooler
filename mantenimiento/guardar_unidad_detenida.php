<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

// ================= RECIBIR DATOS =================
$taller_id = $_POST['taller_id'];
$prioridad = $_POST['prioridad'];
$urgencia = $_POST['urgencia'];
$fecha_reporte = $_POST['fecha_reporte'];
$fecha_ingreso = $_POST['fecha_ingreso'];
$fecha_compromiso = $_POST['fecha_compromiso'];
$falla_grupo_motor = $_POST['falla_grupo_motor'];
$descripcion_falla = $_POST['descripcion_falla'];
$tipo_reparacion = $_POST['tipo_reparacion'];
$status = $_POST['status'];
$observaciones = $_POST['observaciones'];
$fecha_termino = $_POST['fecha_termino'];
$fecha_salida = $_POST['fecha_salida'];

// ================= VALORES DEFAULT =================
$porcentaje_avance = 0;
$status_refacciones = '';
$actividades = $observaciones;
$ultima_actualizacion = date('Y-m-d H:i:s');
$activo = 1;

// ================= INSERT =================
$sql = "INSERT INTO reparaciones (
    taller_id,
    prioridad,
    urgencia,
    fecha_reporte,
    fecha_ingreso_taller,
    fecha_compromiso_entrega,
    porcentaje_avance,
    falla_grupo_motor,
    descripcion_falla,
    tipo_reparacion,
    status_refacciones,
    actividades,
    fecha_termino,
    fecha_salida,
    ultima_actualizacion
) VALUES (
    '$taller_id',
    '$prioridad',
    '$urgencia',
    '$fecha_reporte',
    '$fecha_ingreso',
    '$fecha_compromiso',
    '$porcentaje_avance',
    '$falla_grupo_motor',
    '$descripcion_falla',
    '$tipo_reparacion',
    '$status_refacciones',
    '$actividades',
    '$fecha_termino',
    '$fecha_salida',
    '$ultima_actualizacion'
)";

$db->consulta($sql);

// ================= REDIRECCION =================
header("Location: index.php?guardado=1");
exit;
?>