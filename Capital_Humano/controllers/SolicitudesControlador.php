<?php

require_once __DIR__ . '/../models/SolicitudesModelo.php';

class SolicitudesControlador {

    public static function crearSolicitud($data){

        if(empty($data['tipo'])){
            return [
                'status' => false,
                'mensaje' => 'Tipo requerido'
            ];
        }

        if(empty($data['fecha_inicio'])){

            return [
                'status' => false,
                'mensaje' => 'Fecha inicio requerida'
            ];
        }

        if(empty($data['fecha_fin'])){

            return [
                'status' => false,
                'mensaje' => 'Fecha fin requerida'
            ];
        }

        $fechaInicio = new DateTime($data['fecha_inicio']);
        $fechaFin = new DateTime($data['fecha_fin']);

        $dias = $fechaInicio->diff($fechaFin)->days + 1;

        $data['dias_solicitados'] = $dias;

        return SolicitudesModelo::crearSolicitud($data);
    }


    public static function listarSolicitudesOperador($idUsuario){

        return SolicitudesModelo::obtenerSolicitudesOperador($idUsuario);
    }


    public static function listarSolicitudesAdmin(){

        return SolicitudesModelo::obtenerSolicitudesAdmin();
    }


    public static function autorizarSolicitud($data){

        $data['estatus'] = 'AUTORIZADO';

        return SolicitudesModelo::actualizarEstatus($data);
    }


    public static function rechazarSolicitud($data){

        $data['estatus'] = 'RECHAZADO';

        return SolicitudesModelo::actualizarEstatus($data);
    }
}