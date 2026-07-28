<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require '../system/connection.php';
require '../system/constants.php';
$db = new MySQL();

/*FUNCION LIMPIAR*/
function limpiar($valor){return trim(addslashes($valor ?? ''));}

/*DATOS GENERALES*/
$eco = limpiar($_POST['eco']);
$remolque = limpiar($_POST['remolque']);
$operador = limpiar($_POST['operador']);
$telefono = limpiar($_POST['telefono']);
$cliente = limpiar($_POST['cliente']);
$tipo_carga = limpiar($_POST['tipo_carga']);
$link_ubicacion = limpiar($_POST['link_ubicacion']);
$ubicacion_actual = limpiar($_POST['ubicacion_actual']);
$tipo_falla = limpiar($_POST['tipo_falla']);
$descripcion = limpiar($_POST['descripcion']);
$estatus = limpiar($_POST['estatus'] ?? '');
$estatus_operativo = limpiar($_POST['estatus_operativo'] ?? ''); // 
$origen = limpiar($_POST['origen'] ?? '');
$destino = limpiar($_POST['destino'] ?? '');
$temp_solicitada = limpiar($_POST['temp_solicitada'] ?? '');
$temp_actual = limpiar($_POST['temp_actual'] ?? '');
$tipo_reporte = limpiar($_POST['tipo_reporte'] ?? '');
$files = $_FILES;
//var_dump($files);
//die();

/*CONDICIONALES*/
$grupo_motor = 'NULL';
$detalle_falla = 'NULL';
$tipo_llanta = 'NULL';
$posicion_llanta = 'NULL';
$tipo_danio = 'NULL';

/*MOTRIZ*/
if($tipo_falla === 'MOTRIZ'){
$grupo_motor =(isset($_POST['grupo_motor']) && $_POST['grupo_motor'] !== '')? "'".limpiar($_POST['grupo_motor'])."'": 'NULL';
$detalle_falla =(isset($_POST['detalle_falla']) && $_POST['detalle_falla'] !== '')? "'".limpiar($_POST['detalle_falla'])."'": 'NULL';
}

/*REFRIGERACION*/
if($tipo_falla === 'REFRIGERACION'){$detalle_falla =
(isset($_POST['detalle_falla']) && $_POST['detalle_falla'] !== '')? "'".limpiar($_POST['detalle_falla'])."'": 'NULL';
}

/*LLANTAS*/
if($tipo_falla === 'LLANTAS'){$tipo_llanta =(isset($_POST['tipo_llanta']) && $_POST['tipo_llanta'] !== '')
? "'".limpiar($_POST['tipo_llanta'])."'": 'NULL';

$posicion_llanta =(isset($_POST['posicion_llanta']) && $_POST['posicion_llanta'] !== '')
? "'".limpiar($_POST['posicion_llanta'])."'": 'NULL';

$tipo_danio =(isset($_POST['tipo_danio']) && $_POST['tipo_danio'] !== '')
? "'".limpiar($_POST['tipo_danio'])."'": 'NULL';
}

/*VALIDACION BASICA*/
// Validar que el operador exista en BD (el valor ahora es un ID)
if(!empty($operador)) {
    $sql_verificar = "SELECT id FROM usuarios WHERE id = '$operador' AND puesto_id IS NOT NULL";
    $rs_verificar = $db->consulta($sql_verificar);
    if($db->num_rows($rs_verificar) == 0) {
        die('Operador no válido');
    }
}

if(
    empty($eco) ||
    empty($operador) ||  // ← IGUAL, pero ahora valida que sea un ID válido
    empty($tipo_falla) ||
    empty($estatus) ||
    empty($tipo_reporte)
){
    die('Faltan campos obligatorios');
}

/*INSERT*/
$sql = " INSERT INTO fallas(
eco,
remolque,
operador,
telefono,
cliente,
tipo_carga,
link_ubicacion,
ubicacion_actual,
tipo_falla,
estatus,
estatus_operativo,   -- 
tipo_reporte,
grupo_motor,
detalle_falla,
tipo_llanta,
posicion_llanta,
tipo_danio,
descripcion,
origen,
destino,
temp_solicitada,
temp_actual
)

VALUES(

'$eco',
'$remolque',
'$operador',
'$telefono',
'$cliente',
'$tipo_carga',
'$link_ubicacion',
'$ubicacion_actual',
'$tipo_falla',
'$estatus',
'$estatus_operativo',   -- 
'$tipo_reporte',

$grupo_motor,
$detalle_falla,
$tipo_llanta,
$posicion_llanta,
$tipo_danio,

'$descripcion',
'$origen',
'$destino',
'$temp_solicitada',
'$temp_actual'
)";

$resultado = $db->consulta($sql);
/*RESPUESTA*/
if($resultado){header('Location: reporte_fallas.php?guardado=1');
exit;
}else{
echo "<h3>Error al guardar</h3>";
echo "<pre>$sql</pre>";
}
?>