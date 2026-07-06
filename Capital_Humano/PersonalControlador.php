<?php

require_once __DIR__ . '/../system/connection.php';

class PersonalControlador {

    private $db;

    public function __construct(){
        $this->db = new MySQL();
    }

    /* =====================================================
       CREAR EMPLEADO
    ===================================================== */
    public function crear($data){

        try {

            $this->db->beginTransaction();

            $sql = "
            INSERT INTO usuarios
            (
                nombre, apellidoP, apellidoM,
                email, noEmpleado, fecContratacion,
                rfc, curp, nss, sexo, id_estado_nacimiento, movil, telefono_emergencia
            )
            VALUES
            (
                '".$this->db->escape_string($data['nombre'])."',
                '".$this->db->escape_string($data['apellido_paterno'])."',
                '".$this->db->escape_string($data['apellido_materno'] ?? '')."',
                '".$this->db->escape_string($data['correo'] ?? '')."',
                '".$this->db->escape_string($data['numero_empleado'] ?? '')."',
                '".$this->db->escape_string($data['fecha_ingreso'] ?? '')."',
                '".$this->db->escape_string($data['rfc'] ?? '')."',
                '".$this->db->escape_string($data['curp'] ?? '')."',
                '".$this->db->escape_string($data['nss'] ?? '')."',
                '".$this->db->escape_string($data['sexo'] ?? '')."',
                '".$this->db->escape_string($data['id_estado_nacimiento'] ?? '')."',
                '".$this->db->escape_string($data['telefono'] ?? '')."',
                '".$this->db->escape_string($data['telefono_emergencia'] ?? '')."'

            )
            ";

            $this->db->consulta($sql);
            $empleado_id = $this->db->getLastId();

            /* ESTATUS */
            if(isset($data['id_estatus'])){
                $this->db->consulta("
                    INSERT INTO empleado_estatus_historial
                    (id_empleado, id_estatus, fecha_inicio, activo)
                    VALUES
                    ($empleado_id, ".intval($data['id_estatus']).", CURDATE(), 1)
                ");
            }

            /* HISTORIAL PUESTO */
            if(isset($data['id_puesto']) && isset($data['id_departamento'])){
                $this->db->consulta("
                    INSERT INTO empleado_puesto_historial
                    (id_empleado, id_puesto, id_departamento, fecha_inicio)
                    VALUES
                    ($empleado_id, ".intval($data['id_puesto']).", ".intval($data['id_departamento']).", CURDATE())
                ");
            }

            $this->db->commit();

           return [
            "success" => true,
            "empleado_id" => $empleado_id
            ];

        } catch(Exception $e){

            $this->db->rollback();

            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }
    }

    /* =====================================================
       LISTAR EMPLEADOS + DOCUMENTOS
    ===================================================== */
    public function listar(
    $nombre='',
    $departamento='',
    $estatus='',
    $sort='u.noEmpleado',
    $order='DESC'
    ){

        $sql = "
        SELECT 
            u.id,
            u.noEmpleado,
            CONCAT(u.nombre,' ',u.apellidoP,' ',IFNULL(u.apellidoM,'')) AS nombreCompleto,
            d.nombre AS departamento,
            p.nombre AS puesto,
            es.nombre AS estatus,

            docs.licencia_estatus,
            docs.apto_estatus

        FROM usuarios u

        LEFT JOIN empleado_puesto_historial eph
            ON eph.id_empleado = u.id AND eph.fecha_fin IS NULL

        LEFT JOIN cat_departamentos d
            ON d.id_departamento = eph.id_departamento

        LEFT JOIN cat_puestos p
            ON p.id_puesto = eph.id_puesto

        LEFT JOIN empleado_estatus_historial eeh
            ON eeh.id_empleado = u.id AND eeh.activo = 1

        LEFT JOIN cat_estatus_empleado es
            ON es.id_estatus = eeh.id_estatus

        LEFT JOIN (
            SELECT 
                ed.id_empleado,

                MAX(CASE 
                    WHEN td.nombre = 'Licencia de conducir' THEN
                        CASE 
                            WHEN ed.fecha_vencimiento IS NULL THEN 'SIN_FECHA'
                            WHEN ed.fecha_vencimiento < CURDATE() THEN 'VENCIDO'
                            WHEN ed.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'POR_VENCER'
                            ELSE 'VIGENTE'
                        END
                END) AS licencia_estatus,

                MAX(CASE 
                    WHEN td.nombre = 'Apto médico' THEN
                        CASE 
                            WHEN ed.fecha_vencimiento IS NULL THEN 'SIN_FECHA'
                            WHEN ed.fecha_vencimiento < CURDATE() THEN 'VENCIDO'
                            WHEN ed.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'POR_VENCER'
                            ELSE 'VIGENTE'
                        END
                END) AS apto_estatus

            FROM empleado_documento ed
            LEFT JOIN cat_tipo_documento td 
                ON td.id_tipo_documento = ed.tipo_documento_id
            GROUP BY ed.id_empleado
        ) docs ON docs.id_empleado = u.id

        WHERE 1=1
        ";

        if($nombre!=''){
            $nombre = $this->db->escape_string($nombre);
            $sql .= " AND CONCAT(u.nombre,' ',u.apellidoP,' ',IFNULL(u.apellidoM,'')) LIKE '%$nombre%'";
        }

        if($departamento!=''){
            $sql .= " AND eph.id_departamento = ".intval($departamento);
        }

        if($estatus!=''){
            $sql .= " AND eeh.id_estatus = ".intval($estatus);
        }

        /* =========================
        COLUMNAS PERMITIDAS
        ========================= */

        $columnasPermitidas = [

            "empleado"      => "nombreCompleto",
            "departamento"  => "d.nombre",
            "puesto"        => "p.nombre",
            "estatus"       => "es.nombre",
            "numero"        => "u.noEmpleado"

        ];

        /* =========================
        VALIDAR SORT
        ========================= */

        if(isset($columnasPermitidas[$sort])){
            $sort = $columnasPermitidas[$sort];
        }else{
            $sort = "u.noEmpleado";
        }

        /* =========================
        VALIDAR ORDER
        ========================= */

        $order = strtoupper($order);

        if($order !== "ASC" && $order !== "DESC"){
            $order = "DESC";
        }

        $sql .= " ORDER BY $sort $order";

        $result = $this->db->consulta($sql);

        $data=[];
        while($row=$this->db->fetch_assoc($result)){
            $data[]=$row;
        }

        return $data;
    }

    /* =====================================================
       ACTUALIZAR EMPLEADO
    ===================================================== */
    public function actualizar($data){

        try{

            $this->db->beginTransaction();
            $id = intval($data['id']);

            $this->db->consulta("
                UPDATE usuarios SET
                    nombre = '".$this->db->escape_string($data['nombre'])."',
                    apellidoP = '".$this->db->escape_string($data['apellido_paterno'])."',
                    apellidoM = '".$this->db->escape_string($data['apellido_materno'] ?? '')."',
                    email = '".$this->db->escape_string($data['correo'] ?? '')."',
                    noEmpleado = '".$this->db->escape_string($data['numero_empleado'] ?? '')."',
                    fecContratacion = '".$this->db->escape_string($data['fecha_ingreso'] ?? '')."',
                    rfc = '".$this->db->escape_string($data['rfc'] ?? '')."',
                    curp = '".$this->db->escape_string($data['curp'] ?? '')."',
                    nss = '".$this->db->escape_string($data['nss'] ?? '')."',
                    sexo = '".$this->db->escape_string($data['sexo'] ?? '')."',
                    id_estado_nacimiento = '".$this->db->escape_string($data['id_estado_nacimiento'] ?? '')."',
                    movil = '".$this->db->escape_string($data['telefono'] ?? '')."',
                    telefono_emergencia = '".$this->db->escape_string($data['telefono_emergencia'] ?? '')."'


                WHERE id = $id
            ");

            /* HISTORIAL */
            if(isset($data['id_puesto']) && isset($data['id_departamento'])){

                $this->db->consulta("
                    UPDATE empleado_puesto_historial
                    SET fecha_fin = NOW()
                    WHERE id_empleado = $id AND fecha_fin IS NULL
                ");

                $this->db->consulta("
                    INSERT INTO empleado_puesto_historial
                    (id_empleado, id_puesto, id_departamento, fecha_inicio)
                    VALUES
                    ($id, ".intval($data['id_puesto']).", ".intval($data['id_departamento']).", NOW())
                ");
            }

            /* =========================
            DOMICILIO
            ========================= */

            if(!empty($data['id_cp'])){

                $domicilio = $this->guardarActualizarDomicilio([

                    "id_empleado" => $id,
                    "id_cp" => $data['id_cp'],

                    "calle" => $data['calle'] ?? '',
                    "numero_exterior" => $data['numero_exterior'] ?? '',
                    "numero_interior" => $data['numero_interior'] ?? '',
                    "referencia" => $data['referencia'] ?? ''

                ]);

                if($domicilio['status'] !== 'ok'){
                    throw new Exception($domicilio['message']);
}
            }
            
            $this->db->commit();

            return ["status"=>"ok"];

        }catch(Exception $e){

            $this->db->rollback();

            return ["status"=>"error","message"=>$e->getMessage()];
        }
    }

    /* =====================================================
       LISTAR DOCUMENTOS
    ===================================================== */
    public function listarDocumentos($id){

        $id = intval($id);

        $sql = "
        SELECT 
            ed.*,
            td.nombre AS tipo_documento,
            CASE 
                WHEN ed.fecha_vencimiento IS NULL THEN 'SIN_FECHA'
                WHEN ed.fecha_vencimiento < CURDATE() THEN 'VENCIDO'
                WHEN ed.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'POR_VENCER'
                ELSE 'VIGENTE'
            END AS estatus_vencimiento
        FROM empleado_documento ed
        LEFT JOIN cat_tipo_documento td
            ON td.id_tipo_documento = ed.tipo_documento_id
        WHERE ed.id_empleado = $id
        ";

        $result = $this->db->consulta($sql);

        $data = [];
        while($row = $this->db->fetch_assoc($result)){
            $data[] = $row;
        }

        return $data;
    }

    /* =====================================================
       OBTENER EMPLEADO
    ===================================================== */
    public function obtenerEmpleado($id){

        $id = intval($id);

        $sql = "
        SELECT
            u.*,

            dom.id_cp,
            dom.calle,
            dom.numero_exterior,
            dom.numero_interior,
            dom.referencia,

            cp.codigo_postal,
            cp.colonia AS colonia_nombre,

            m.nombre AS municipio_nombre,
            est.nombre AS estado_domicilio,

            ce.nombre AS estado_nombre,

            eph.id_departamento,
            eph.id_puesto,
            eeh.id_estatus

        FROM usuarios u

        LEFT JOIN cat_estados ce
            ON ce.id_estado = u.id_estado_nacimiento

        LEFT JOIN empleado_puesto_historial eph
            ON eph.id_empleado = u.id AND eph.fecha_fin IS NULL

        LEFT JOIN empleado_estatus_historial eeh
            ON eeh.id_empleado = u.id AND eeh.activo = 1

        LEFT JOIN empleado_domicilio dom
            ON dom.id_empleado = u.id

        LEFT JOIN cat_codigos_postales cp
            ON cp.id_cp = dom.id_cp

        LEFT JOIN cat_municipios m
            ON m.id_municipio = cp.id_municipio

        LEFT JOIN cat_estados est
            ON est.id_estado = m.id_estado

        WHERE u.id = $id
        LIMIT 1
        ";

        $result = $this->db->consulta($sql);

        return $this->db->fetch_assoc($result);
    }


    /* =====================================================
   GUARDAR DOCUMENTO (LICENCIA / APTO)
===================================================== */
    public function guardarDocumento($data){

        try{

            $id_empleado = intval($data['id_empleado']);
            $tipo_documento_id = intval($data['tipo_documento_id']);

            $folio = isset($data['folio']) 
                ? "'".$this->db->escape_string($data['folio'])."'" 
                : "NULL";

            $fecha_vencimiento = !empty($data['fecha_vencimiento']) 
                ? "'".$this->db->escape_string($data['fecha_vencimiento'])."'" 
                : "NULL";

            $tipo_licencia = isset($data['tipo_licencia']) 
                ? "'".$this->db->escape_string($data['tipo_licencia'])."'" 
                : "NULL";

            $sql = "
            INSERT INTO empleado_documento
            (
                id_empleado,
                tipo_documento_id,
                folio,
                fecha_vencimiento,
                tipo_licencia
            )
            VALUES
            (
                $id_empleado,
                $tipo_documento_id,
                $folio,
                $fecha_vencimiento,
                $tipo_licencia
            )
            ";

            $this->db->consulta($sql);

            return ["success"=>true];

        }catch(Exception $e){

            return [
                "success"=>false,
                "error"=>$e->getMessage()
            ];
        }
    }


    /* =====================================================
   ACTUALIZAR DOCUMENTO
===================================================== */
    public function actualizarDocumento($data){

        try{

            $id_documento = intval($data['id_documento']);
            $tipo_documento_id = intval($data['tipo_documento_id']);

            $folio = isset($data['folio']) 
                ? "'".$this->db->escape_string($data['folio'])."'" 
                : "NULL";

            $fecha_vencimiento = !empty($data['fecha_vencimiento']) 
                ? "'".$this->db->escape_string($data['fecha_vencimiento'])."'" 
                : "NULL";

            $tipo_licencia = isset($data['tipo_licencia']) 
                ? "'".$this->db->escape_string($data['tipo_licencia'])."'" 
                : "NULL";

            $sql = "
            UPDATE empleado_documento SET
                tipo_documento_id = $tipo_documento_id,
                folio = $folio,
                fecha_vencimiento = $fecha_vencimiento,
                tipo_licencia = $tipo_licencia
            WHERE id_documento = $id_documento
            ";

            $this->db->consulta($sql);

            return [
                "status"=>"ok",
                "message"=>"Documento actualizado correctamente"
            ];

        }catch(Exception $e){

            return [
                "status"=>"error",
                "message"=>$e->getMessage()
            ];
        }
    }

    /* =====================================================
   OBTENER DOCUMENTO
===================================================== */
    public function obtenerDocumento($id_documento)
        {

        $id_documento = intval($id_documento);

        $sql = "
        SELECT *
        FROM empleado_documento
        WHERE id_documento = $id_documento
        LIMIT 1
        ";

        $result = $this->db->consulta($sql);
        $data = $this->db->fetch_assoc($result);

        if(!$data){
            return [
                "status"=>"error",
                "message"=>"Documento no encontrado"
            ];
        }

        return [
            "status"=>"ok",
            "data"=>$data
        ];
    }

    public function buscarEstados($term){

        $term = $this->db->escape_string($term);

        $sql = "
        SELECT id_estado, nombre
        FROM cat_estados
        WHERE nombre LIKE '%$term%'
        ORDER BY nombre
        LIMIT 10
        ";

        $result = $this->db->consulta($sql);

        $data = [];
        while($row = $this->db->fetch_assoc($result)){
            $data[] = $row;
        }

        return $data;
    }

    /* =====================================================
   CAMBIAR ESTATUS (CON HISTORIAL)
===================================================== */
public function cambiarEstatus($idEmpleado, $idEstatus){

    try{

        $this->db->beginTransaction();

        $idEmpleado = intval($idEmpleado);
        $idEstatus = intval($idEstatus);

        /* 1. Cerrar estatus actual */
        $this->db->consulta("
            UPDATE empleado_estatus_historial
            SET fecha_fin = CURDATE(), activo = 0
            WHERE id_empleado = $idEmpleado
            AND activo = 1
        ");

        /* 2. Insertar nuevo estatus */
        $this->db->consulta("
            INSERT INTO empleado_estatus_historial
            (id_empleado, id_estatus, fecha_inicio, activo)
            VALUES
            ($idEmpleado, $idEstatus, CURDATE(), 1)
        ");

        /* 3. Actualizar estatus actual en usuarios */
        $this->db->consulta("
            UPDATE usuarios
            SET id_estatus = $idEstatus
            WHERE id = $idEmpleado
        ");

        $this->db->commit();

        return [
            "status" => "ok",
            "message" => "Estatus actualizado correctamente"
        ];

        

    }catch(Exception $e){

        $this->db->rollback();

        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
}


public function buscarCP($cp){

    $cp = $this->db->escape_string($cp);

    $sql = "
    SELECT
        cp.id_cp,
        cp.codigo_postal,
        cp.colonia,

        m.nombre AS municipio,
        e.nombre AS estado

    FROM cat_codigos_postales cp

    LEFT JOIN cat_municipios m
        ON m.id_municipio = cp.id_municipio

    LEFT JOIN cat_estados e
        ON e.id_estado = m.id_estado

    WHERE cp.codigo_postal = '$cp'
    ";

    $result = $this->db->consulta($sql);

    $colonias = [];

    $estado = "";
    $municipio = "";

    while($row = $this->db->fetch_assoc($result)){

        $estado = $row['estado'];
        $municipio = $row['municipio'];

        $colonias[] = [
            "id_cp" => $row['id_cp'],
            "colonia" => $row['colonia']
        ];
    }

    return [
        "status" => "ok",
        "estado" => $estado,
        "municipio" => $municipio,
        "colonias" => $colonias
    ];
}

public function guardarDomicilio($data){

    try{

        $id_empleado = intval($data['id_empleado']);
        $id_cp = intval($data['id_cp']);

        $calle = mb_strtoupper(
            $this->db->escape_string($data['calle'] ?? ''),
            'UTF-8'
        );

        $numero_exterior = mb_strtoupper(
            $this->db->escape_string($data['numero_exterior'] ?? ''), 
            'UTF-8'
        );


        $numero_interior = mb_strtoupper(
             $this->db->escape_string($data['numero_interior'] ?? ''),
            'UTF-8'
        );

        $referencia = mb_strtoupper(
            $this->db->escape_string($data['referencia'] ?? ''),
            'UTF-8'
        );

        $sql = "
        INSERT INTO empleado_domicilio
        (
            id_empleado,
            id_cp,
            calle,
            numero_exterior,
            numero_interior,
            referencia
        )
        VALUES
        (
            $id_empleado,
            $id_cp,
            '$calle',
            '$numero_exterior',
            '$numero_interior',
            '$referencia'
        )
        ";

        $this->db->consulta($sql);

        return [
            "status" => "ok"
        ];

    }catch(Exception $e){

        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
}

public function guardarActualizarDomicilio($data){

    try{

        $id_empleado = intval($data['id_empleado']);
        $id_cp = intval($data['id_cp']);

        $calle = mb_strtoupper(
            $this->db->escape_string($data['calle'] ?? ''),
            'UTF-8'
        );


        $numero_exterior = mb_strtoupper(
            $this->db->escape_string($data['numero_exterior'] ?? ''),
            'UTF-8'
        );


        $numero_interior = mb_strtoupper(
            $this->db->escape_string($data['numero_interior'] ?? ''),
            'UTF-8'
        );


        $referencia = mb_strtoupper(
            $this->db->escape_string($data['referencia'] ?? ''),
            'UTF-8'
        );

        /* =========================
           VALIDAR EXISTENCIA
        ========================= */

        $sqlValidar = "
            SELECT id_domicilio
            FROM empleado_domicilio
            WHERE id_empleado = $id_empleado
            LIMIT 1
        ";

        $resultValidar = $this->db->consulta($sqlValidar);

        if(!$resultValidar){
            throw new Exception("Error validando domicilio");
        }

        $existe = $this->db->fetch_assoc($resultValidar);

        /* =========================
           UPDATE
        ========================= */

        if($existe){

            $sql = "
                UPDATE empleado_domicilio SET

                    id_cp = $id_cp,
                    calle = '$calle',
                    numero_exterior = '$numero_exterior',
                    numero_interior = '$numero_interior',
                    referencia = '$referencia'

                WHERE id_empleado = $id_empleado
            ";

        }else{

            /* =========================
               INSERT
            ========================= */

            $sql = "
                INSERT INTO empleado_domicilio
                (
                    id_empleado,
                    id_cp,
                    calle,
                    numero_exterior,
                    numero_interior,
                    referencia
                )
                VALUES
                (
                    $id_empleado,
                    $id_cp,
                    '$calle',
                    '$numero_exterior',
                    '$numero_interior',
                    '$referencia'
                )
            ";

        }

        /* =========================
           EJECUTAR SQL
        ========================= */

        $result = $this->db->consulta($sql);

        if(!$result){

            throw new Exception(
                "Error SQL al guardar domicilio"
            );

        }

        return [
            "status" => "ok"
        ];

    }catch(Exception $e){

        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
}

}