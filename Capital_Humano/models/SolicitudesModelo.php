<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

class SolicitudesModelo {

    /* =========================================
       INSERTAR SOLICITUD
    ========================================= */

    public static function crearSolicitud($datos){

        try {

            $db = new MySQL();

            $inicio = new DateTime($datos['fecha_inicio']);
            $fin = new DateTime($datos['fecha_fin']);

            $dias = $inicio->diff($fin)->days + 1;

            $sql = "
                INSERT INTO solicitudes_descanso (
                    id_usuario,
                    tipo,
                    fecha_inicio,
                    fecha_fin,
                    dias_solicitados,
                    motivo
                ) VALUES (
                    '".$db->escape_string($datos['id_usuario'])."',
                    '".$db->escape_string($datos['tipo'])."',
                    '".$db->escape_string($datos['fecha_inicio'])."',
                    '".$db->escape_string($datos['fecha_fin'])."',
                    '".$db->escape_string($dias)."',
                    '".$db->escape_string($datos['comentario'])."'
                )
            ";

            $respuesta = $db->consulta($sql);

            if($respuesta){

                return [
                    "status" => true,
                    "mensaje" => "Solicitud registrada correctamente"
                ];

            } else {

                return [
                    "status" => false,
                    "mensaje" => "Error al registrar solicitud"
                ];
            }

        } catch(Exception $e){

            return [
                "status" => false,
                "mensaje" => $e->getMessage()
            ];
        }

    }

    /* =========================================
       OBTENER SOLICITUDES OPERADOR
    ========================================= */

    public static function obtenerSolicitudesOperador($idUsuario){

        $db = new MySQL();

        $sql = "
            SELECT *
            FROM solicitudes_descanso
            WHERE id_usuario = '".$db->escape_string($idUsuario)."'
            ORDER BY fecha_registro DESC
        ";

        return $db->consulta($sql);
    }


    /* =========================================
    OBTENER SOLICITUDES ADMIN
    ========================================= */

    public static function obtenerSolicitudesAdmin(
        $estatus = '',
        $tipo = '',
        $operador = ''
    ){

        $db = new MySQL();

        $where = " WHERE 1=1 ";

        if(!empty($estatus)){

            $where .= "
                AND sd.estatus = '".$db->escape_string($estatus)."'
            ";
        }

        if(!empty($tipo)){

            $where .= "
                AND sd.tipo = '".$db->escape_string($tipo)."'
            ";
        }

        if(!empty($operador)){

            $where .= "
                AND CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidoP,
                    ' ',
                    u.apellidoM
                ) LIKE '%".$db->escape_string($operador)."%'
            ";
        }

        $sql = "
            SELECT
                sd.*,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidoP,
                    ' ',
                    u.apellidoM
                ) AS operador

            FROM solicitudes_descanso sd

            INNER JOIN usuarios u
                ON u.id = sd.id_usuario

            $where

            ORDER BY sd.fecha_registro DESC
        ";

        return $db->consulta($sql);
    }


    /* =========================================
       ACTUALIZAR ESTATUS
    ========================================= */

    public static function actualizarEstatus($data){

        try {

            $db = new MySQL();

            $sql = "
                UPDATE solicitudes_descanso
                SET
                    estatus = '".$db->escape_string($data['estatus'])."',
                    comentario_admin = '".$db->escape_string($data['comentario_admin'])."',
                    autorizado_por = '".$db->escape_string($data['autorizado_por'])."',
                    fecha_autorizacion = NOW()

                WHERE id_solicitud = '".$db->escape_string($data['id_solicitud'])."'
            ";

            $respuesta = $db->consulta($sql);

            if($respuesta){

                return [
                    'status' => true,
                    'mensaje' => 'Solicitud actualizada correctamente'
                ];

            } else {

                return [
                    'status' => false,
                    'mensaje' => 'Error al actualizar solicitud'
                ];
            }

        } catch(Exception $e){

            return [
                'status' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    /* =========================================
    OBTENER SOLICITUD POR ID
    ========================================= */


    /* =========================================
    OBTENER SOLICITUD POR ID
    ========================================= */

    public static function obtenerSolicitudPorId($idSolicitud){

        $db = new MySQL();

        $sql = "
            SELECT

                sd.*,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellidoP,
                    ' ',
                    u.apellidoM
                ) AS operador

            FROM solicitudes_descanso sd

            INNER JOIN usuarios u
                ON u.id = sd.id_usuario

            WHERE sd.id_solicitud =
                '".$db->escape_string($idSolicitud)."'
        ";

        return $db->consulta($sql);
    }



    /* =========================================
    INDICADORES
    ========================================= */

    public static function obtenerIndicadores(){

        $db = new MySQL();

        $sql = "
            SELECT

                SUM(
                    CASE
                        WHEN estatus = 'PENDIENTE'
                        THEN 1
                        ELSE 0
                    END
                ) AS pendientes,

                SUM(
                    CASE
                        WHEN estatus = 'AUTORIZADO'
                        THEN 1
                        ELSE 0
                    END
                ) AS autorizadas,

                SUM(
                    CASE
                        WHEN estatus = 'RECHAZADO'
                        THEN 1
                        ELSE 0
                    END
                ) AS rechazadas,

                COUNT(*) AS total

            FROM solicitudes_descanso
        ";

        return $db->consulta($sql);
    }



}