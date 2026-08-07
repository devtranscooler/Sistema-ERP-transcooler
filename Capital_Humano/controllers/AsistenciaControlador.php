<?php

require '../system/connection.php';

class AsistenciaControlador {

    private $db;

    public function __construct(){
        $this->db = new MySQL();
    }

    /* =========================================
       1. OBTENER USUARIO POR NO EMPLEADO
    ========================================= */
    private function obtenerUsuarioPorNoEmpleado($noEmpleado){

        $sql = "
            SELECT id, nombre, apellidoP, apellidoM
            FROM usuarios
            WHERE noEmpleado = '".$this->db->escape_string($noEmpleado)."'
            LIMIT 1
        ";

        $result = $this->db->query($sql);

        if($result->num_rows == 0){
            return null;
        }

        return $result->fetch_assoc();
    }

    /* =========================================
       2. OBTENER ASISTENCIA DEL DÍA
    ========================================= */
    private function asistenciaHoy($id_usuario){

        $hoy = date('Y-m-d');

        $sql = "
            SELECT *
            FROM asistencias
            WHERE id_usuario = $id_usuario
            AND fecha = '$hoy'
            LIMIT 1
        ";

        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    /* =========================================
       3. REGISTRAR ENTRADA
    ========================================= */
    private function registrarEntrada($id_usuario){

        $hoy = date('Y-m-d');
        $hora = date('Y-m-d H:i:s');

        $sql = "
            INSERT INTO asistencias
            (id_usuario, fecha, hora_entrada)
            VALUES ($id_usuario, '$hoy', '$hora')
        ";

        $this->db->query($sql);
    }

    /* =========================================
       4. REGISTRAR SALIDA
    ========================================= */
    private function registrarSalida($id_usuario){

        $hoy = date('Y-m-d');
        $hora = date('Y-m-d H:i:s');

        $sql = "
            UPDATE asistencias
            SET hora_salida = '$hora',
                estatus = 'CERRADO'
            WHERE id_usuario = $id_usuario
            AND fecha = '$hoy'
        ";

        $this->db->query($sql);
    }

    /* =========================================
       5. PROCESAR CHECADA (PUNTO DE ENTRADA)
    ========================================= */
    public function procesarChecada($noEmpleado){

        
        $usuario = $this->obtenerUsuarioPorNoEmpleado($noEmpleado);

        if(!$usuario){
            return [
                "status" => "error",
                "msg" => "Empleado no encontrado"
            ];
        }

        
        $asistencia = $this->asistenciaHoy($usuario['id']);

        //3. Decidir acción
        if(!$asistencia){
            $this->registrarEntrada($usuario['id']);

            return [
                "status" => "ok",
                "msg" => "Entrada registrada",
                "nombre" => $usuario['nombre']
            ];
        }

        if($asistencia && !$asistencia['hora_salida']){
            $this->registrarSalida($usuario['id']);

            return [
                "status" => "ok",
                "msg" => "Salida registrada",
                "nombre" => $usuario['nombre']
            ];
        }

        return [
            "status" => "error",
            "msg" => "Ya registró entrada y salida"
        ];
    }
}