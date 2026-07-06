<?php

require_once __DIR__ . '/../system/connection.php';

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
       2. VALIDAR SI ES OPERADOR
    ========================================= */
    private function esOperador($id_usuario){

        $sql = "
            SELECT 1
            FROM empleado_puesto_historial
            WHERE id_usuario = $id_usuario
            AND id_departamento = 1
            AND fecha_fin IS NULL
            LIMIT 1
        ";

        $result = $this->db->query($sql);
        return $result->num_rows > 0;
    }

    /* =========================================
       3. VALIDAR SI TIENE VIAJE ACTIVO
    ========================================= */
    private function tieneViajeActivo($id_usuario){

        $sql = "
            SELECT id
            FROM servicios
            WHERE id_operador = $id_usuario
              AND status = 'activo'
              AND fecha_carga <= NOW()
              AND (
                    fecha_descarga IS NULL 
                    OR fecha_descarga >= NOW()
                  )
            LIMIT 1
        ";

        $result = $this->db->query($sql);

        if($result->num_rows > 0){
            return $result->fetch_assoc()['id'];
        }

        return false;
    }

    /* =========================================
       4. REGISTRAR ASISTENCIA OPERADOR (AUTO)
    ========================================= */
    private function registrarAsistenciaOperador($id_usuario, $id_viaje){

        $hoy = date('Y-m-d');

        $sql = "
            INSERT INTO asistencia_operadores
            (id_usuario, fecha, estatus, id_viaje)
            VALUES
            ($id_usuario, '$hoy', 'EN_VIAJE', $id_viaje)
            ON DUPLICATE KEY UPDATE
                estatus = 'EN_VIAJE',
                id_viaje = $id_viaje
        ";

        $this->db->query($sql);
    }

    /* =========================================
       5. OBTENER ASISTENCIA DEL DÍA (ADMIN)
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
       6. REGISTRAR ENTRADA
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
       7. REGISTRAR SALIDA
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
       8. PROCESAR CHECADA (CONTROL TOTAL)
    ========================================= */
    public function procesarChecada($noEmpleado){

        //1. Buscar usuario
        $usuario = $this->obtenerUsuarioPorNoEmpleado($noEmpleado);

        

        if(!$usuario){
            return [
                "status" => "error",
                "msg" => "Empleado no encontrado"
            ];
        }

        $id_usuario = $usuario['id'];

        //2. VALIDAR SI ES OPERADOR
        if($this->esOperador($id_usuario)){

            $id_viaje = $this->tieneViajeActivo($id_usuario);

            if($id_viaje){
                // 🔥 REGISTRO AUTOMÁTICO
                $this->registrarAsistenciaOperador($id_usuario, $id_viaje);

                return [
                    "status" => "ok",
                    "msg" => "Asistencia automática por viaje",
                    "nombre" => $usuario['nombre']
                ];
            }

            // 👉 Si es operador pero NO tiene viaje
            return [
                "status" => "error",
                "msg" => "Operador sin viaje activo",
                "nombre" => $usuario['nombre']
            ];
        }

        //3. FLUJO NORMAL (ADMINISTRATIVO)
        $asistencia = $this->asistenciaHoy($id_usuario);

        if(!$asistencia){
            $this->registrarEntrada($id_usuario);

            return [
                "status" => "ok",
                "msg" => "Entrada registrada",
                "nombre" => $usuario['nombre']
            ];
        }

        if($asistencia && !$asistencia['hora_salida']){
            $this->registrarSalida($id_usuario);

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