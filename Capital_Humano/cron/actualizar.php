<?php

require_once __DIR__ . '/../../system/connection.php';

date_default_timezone_set('America/Mexico_City');

$db = new MySQL();

echo "=====================================\n";
echo " ACTUALIZACIÓN AUTOMÁTICA VACACIONES \n";
echo "=====================================\n\n";

/* =========================================
   OBTENER OPERADORES ACTIVOS
========================================= */

$sqlUsuarios = "
    SELECT
        ID,
        FECHA_INGRESO
    FROM usuarios
    WHERE FECHA_INGRESO IS NOT NULL
";

$usuarios = $db->consulta($sqlUsuarios);

/* =========================================
   RECORRER OPERADORES
========================================= */

while($usuario = $usuarios->fetch_assoc()){

    $idUsuario = $usuario['ID'];
    $fechaIngreso = $usuario['FECHA_INGRESO'];

    $fechaIngresoDate = new DateTime($fechaIngreso);
    $hoy = new DateTime();

    /* =========================================
       CALCULAR AÑOS CUMPLIDOS
    ========================================= */

    $anios = $fechaIngresoDate->diff($hoy)->y;

    // Ignorar si todavía no cumple 1 año
    if($anios <= 0){
        continue;
    }

    /* =========================================
       OBTENER DÍAS CORRESPONDIENTES
    ========================================= */

    $diasCorrespondientes = obtenerDiasVacaciones($anios);

    /* =========================================
       VALIDAR SI YA EXISTE REGISTRO
    ========================================= */

    $sqlExiste = "
        SELECT *
        FROM vacaciones_operador
        WHERE id_usuario = '".$db->escape_string($idUsuario)."'
    ";

    $existe = $db->consulta($sqlExiste);

    /* =========================================
       SI YA EXISTE → UPDATE
    ========================================= */

    if($existe->num_rows > 0){

        $registro = $existe->fetch_assoc();

        $diasTomados = $registro['dias_tomados'];

        $diasDisponibles = $diasCorrespondientes - $diasTomados;

        $sqlUpdate = "
            UPDATE vacaciones_operador
            SET
                anios_cumplidos = '".$db->escape_string($anios)."',
                dias_correspondientes = '".$db->escape_string($diasCorrespondientes)."',
                dias_disponibles = '".$db->escape_string($diasDisponibles)."',
                fecha_actualizacion = NOW()

            WHERE id_usuario = '".$db->escape_string($idUsuario)."'
        ";

        $db->consulta($sqlUpdate);

        echo "UPDATE -> Usuario {$idUsuario} actualizado correctamente\n";

    }

    /* =========================================
       SI NO EXISTE → INSERT
    ========================================= */

    else {

        $sqlInsert = "
            INSERT INTO vacaciones_operador (
                id_usuario,
                fecha_ingreso,
                anios_cumplidos,
                dias_correspondientes,
                dias_tomados,
                dias_disponibles,
                fecha_actualizacion
            ) VALUES (
                '".$db->escape_string($idUsuario)."',
                '".$db->escape_string($fechaIngreso)."',
                '".$db->escape_string($anios)."',
                '".$db->escape_string($diasCorrespondientes)."',
                '0',
                '".$db->escape_string($diasCorrespondientes)."',
                NOW()
            )
        ";

        $db->consulta($sqlInsert);

        echo "INSERT -> Usuario {$idUsuario} agregado correctamente\n";
    }
}

echo "\n=====================================\n";
echo " PROCESO FINALIZADO \n";
echo "=====================================\n";


/* =========================================
   FUNCIÓN DÍAS VACACIONES MÉXICO
========================================= */

function obtenerDiasVacaciones($anios){

    switch($anios){

        case 1:
            return 12;

        case 2:
            return 14;

        case 3:
            return 16;

        case 4:
            return 18;

        case 5:
            return 20;

        default:

            // A partir del sexto año:
            // aumenta 2 días cada 5 años

            if($anios >= 6 && $anios <= 10){
                return 22;
            }

            if($anios >= 11 && $anios <= 15){
                return 24;
            }

            if($anios >= 16 && $anios <= 20){
                return 26;
            }

            if($anios >= 21 && $anios <= 25){
                return 28;
            }

            if($anios >= 26 && $anios <= 30){
                return 30;
            }

            return 32;
    }
}