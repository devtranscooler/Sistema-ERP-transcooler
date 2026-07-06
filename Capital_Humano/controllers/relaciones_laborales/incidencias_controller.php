<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

header('Content-Type: application/json; charset=utf-8');

$db = new MySQL();

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'crear':
        crearIncidencia($db);
        break;

    default:

        echo json_encode([
            'success' => false,
            'message' => 'Acción no válida.'
        ]);
        break;
}

/**
 * Crear incidencia
 */
function crearIncidencia($db)
{
    try {

        $id_usuario       = intval($_POST['id_usuario'] ?? 0);
        $id_tipo          = intval($_POST['id_tipo'] ?? 0);
        $fecha_incidencia = trim($_POST['fecha_incidencia'] ?? '');
        $descripcion      = trim($_POST['descripcion'] ?? '');
        $observaciones    = trim($_POST['observaciones'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | Validaciones
        |--------------------------------------------------------------------------
        */

        if ($id_usuario <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Debe seleccionar un colaborador.'
            ]);
            return;
        }

        if ($id_tipo <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Debe seleccionar un tipo de incidencia.'
            ]);
            return;
        }

        if (empty($fecha_incidencia)) {

            echo json_encode([
                'success' => false,
                'message' => 'Debe indicar la fecha de incidencia.'
            ]);
            return;
        }

        if (empty($descripcion)) {

            echo json_encode([
                'success' => false,
                'message' => 'Debe capturar la descripción.'
            ]);
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener configuración del catálogo
        |--------------------------------------------------------------------------
        */

        $sqlCatalogo = "
            SELECT
                nombre,
                nivel_gravedad,
                genera_disciplina,
                genera_acta,
                genera_adeudo
            FROM cat_incidencias_rh
            WHERE id_tipo = {$id_tipo}
            LIMIT 1
        ";

        $resultadoCatalogo = $db->consulta($sqlCatalogo);

        if ($db->num_rows($resultadoCatalogo) == 0) {

            echo json_encode([
                'success' => false,
                'message' => 'La incidencia seleccionada no existe.'
            ]);
            return;
        }

        $tipoIncidencia = $db->fetch_assoc($resultadoCatalogo);

        /*
        |--------------------------------------------------------------------------
        | Generar folio
        |--------------------------------------------------------------------------
        */

        $anio = date('Y');

        $sqlFolio = "
            SELECT COUNT(*) AS total
            FROM incidencias_rh
            WHERE YEAR(fecha_registro) = {$anio}
        ";

        $resultadoFolio = $db->consulta($sqlFolio);
        $datosFolio = $db->fetch_assoc($resultadoFolio);

        $consecutivo = intval($datosFolio['total']) + 1;

        $folio = 'INC-' .
                 $anio .
                 '-' .
                 str_pad($consecutivo, 5, '0', STR_PAD_LEFT);

        /*
        |--------------------------------------------------------------------------
        | Insert
        |--------------------------------------------------------------------------
        */

        $sqlInsert = "
            INSERT INTO incidencias_rh
            (
                folio,
                id_usuario,
                id_tipo,
                fecha_incidencia,
                severidad,
                descripcion,
                genera_disciplina,
                genera_acta,
                genera_adeudo,
                estatus
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                'ABIERTA'
            )
        ";

        $db->execute($sqlInsert, [
            $folio,
            $id_usuario,
            $id_tipo,
            $fecha_incidencia,
            $tipoIncidencia['nivel_gravedad'],
            $descripcion,
            intval($tipoIncidencia['genera_disciplina']),
            intval($tipoIncidencia['genera_acta']),
            intval($tipoIncidencia['genera_adeudo'])
        ]);

        /*echo json_encode([
            'success' => true,
            'message' => 'Incidencia registrada correctamente.',
            'folio' => $folio
        ]);*/

    } catch (Throwable $e) {

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}