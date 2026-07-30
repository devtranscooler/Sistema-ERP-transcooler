<?php
require '../system/connection.php';

$db = new MySQL();

$id = $_POST['id'] ?? 0;

if(!$id){
    die("ID inválido");
}

/*=========================
DATOS
=========================*/

$tipo_reporte = $_POST['tipo_reporte'];
$eco = $_POST['eco'];
$remolque = $_POST['remolque'];
$operador = $_POST['operador'];
$telefono = $_POST['telefono'];
$cliente = $_POST['cliente'];
$tipo_carga = $_POST['tipo_carga'];

$link_ubicacion = $_POST['link_ubicacion'];
$ubicacion_actual = $_POST['ubicacion_actual'];

$estatus = $_POST['estatus'];
$estatus_operativo = $_POST['estatus_operativo'];
$origen = $_POST['origen'];
$destino = $_POST['destino'];

$tipo_falla = $_POST['tipo_falla'];
$descripcion = $_POST['descripcion'];

/*=========================
SUBTIPOS (opcionales)
=========================*/
$grupo_motor = $_POST['grupo_motor'] ?? null;
$falla_refrigeracion = $_POST['detalle_falla'] ?? null;
$tipo_llanta = $_POST['tipo_llanta'] ?? null;
$posicion_llanta = $_POST['posicion_llanta'] ?? null;
$tipo_danio = $_POST['tipo_danio'] ?? null;

/*=========================
UPDATE
=========================*/

$sql = "

UPDATE fallas SET
tipo_reporte = '$tipo_reporte',
eco = '$eco',
remolque = '$remolque',
operador = '$operador',
telefono = '$telefono',
cliente = '$cliente',
tipo_carga = '$tipo_carga',

link_ubicacion = '$link_ubicacion',
ubicacion_actual = '$ubicacion_actual',

estatus = '$estatus',
estatus_operativo = '$estatus_operativo',
origen = '$origen',
destino = '$destino',

tipo_falla = '$tipo_falla',
grupo_motor = '$grupo_motor',
falla_refrigeracion = '$falla_refrigeracion',
tipo_llanta = '$tipo_llanta',
posicion_llanta = '$posicion_llanta',
tipo_danio = '$tipo_danio',

descripcion = '$descripcion'

WHERE id = $id

";

$db->consulta($sql);

/*=========================
REDIRECCIÓN
=========================*/
header("Location: editar_falla.php?id=".$id."&updated=1");
exit;
?>