<?php

declare(strict_types=1);

class ControlConsole
{
    private MySQL $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function getServices(array $filters = [], array $pagination = []): array
    {
        $connection = $this->db->getConexion();

        $where = [];
        $whereSql = "";
        $params = [];
        $types = "";

        if (!empty($filters['tipo_unidad'])) {
            $where[] = "udds.tipo_unidad = ?";
            $params[] = $filters['tipo_unidad'];
            $types .= "s";
        }

        if (!empty($filters['tipo_cliente'])) {
            $where[] = "cnts.tipo_cliente = ?";
            $params[] = $filters['tipo_cliente'];
            $types .= "s";
        }

        if (!empty($where)) {
            $whereSql = "WHERE " . implode(" AND ", $where);
        }

        $countSql = "SELECT 
            COUNT(*) as total
        FROM cat_unidades udds
        INNER JOIN servicios as svcs
            ON svcs.id_unidad = udds.id
        INNER JOIN usuarios as usrs
            ON usrs.id = svcs.id_operador  
        INNER JOIN clientes as cnts
            ON cnts.id = svcs.id_cliente 
        $whereSql";

        $countStmt = $connection->prepare($countSql);

        if (!empty($params)) {
            $countStmt->bind_param($types, ...$params);
        }

        $countStmt->execute();

        $total = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
        

        $page = max(1, $pagination['page'] ?? 1);
        $perPage = max(1, $pagination['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT 
            udds.id as id_unidad,
            udds.eco,
            udds.placas as placas_unidad,
            udds.tipo_unidad,
            svcs.id as id_servicio,
            etps.nombre as nombre_etapa_servicio,
	        etpse.nombre as nombre_estatus_etapa_servicio,
	        etpse.color as color_estatus_etapa_servicio,
            etpsl.id as etapa_servicio_log_id,
            etpsl.etapa_servicio_id,
            etpsl.etapa_servicio_estatus_id,
            svcs.shipment,
            svcs.tipo_viaje,
            svcs.num_repartos,
            svcs.tipo_servicio,
            svcs.status as estatus_servicio,
            svcs.id_operador,
            svcs.tracking,
            CONCAT(usrs.nombre, ' ', usrs.apellidoP, ' ', usrs.apellidoM) as nombre_operador,
            cnts.nombre_razon,
            cnts.tipo_cliente,
            svcs.fec_alta as fecha_alta,
            svcs.fecha_carga,
            svcs.fecha_descarga,
            DATEDIFF(CURDATE(), svcs.fecha_descarga) AS dias_transcurridos_desde_descarga
        FROM cat_unidades udds
        INNER JOIN servicios as svcs
            ON svcs.id_unidad = udds.id
        INNER JOIN usuarios as usrs
            ON usrs.id = svcs.id_operador  
        INNER JOIN clientes as cnts
            ON cnts.id = svcs.id_cliente 
        LEFT JOIN (
            SELECT *
            FROM (
                SELECT esl.*,
                ROW_NUMBER() OVER(PARTITION BY servicio_id ORDER BY id DESC) AS rn
                FROM etapas_servicios_logs esl )
            t
            WHERE rn = 1
        ) etpsl ON svcs.id = etpsl.servicio_id 
        LEFT JOIN etapas_servicios etps
            ON etpsl.etapa_servicio_id = etps.id
        LEFT JOIN etapas_servicios_estatus etpse
            ON etpsl.etapa_servicio_estatus_id = etpse.id 
        $whereSql
        ORDER BY svcs.id DESC
        LIMIT ? OFFSET ?";

        $stmt = $connection->prepare($sql);

        $selectParams = $params;
        $selectTypes = $types;

        $selectParams[] = $perPage;
        $selectParams[] = $offset;

        $selectTypes .= "ii";

        $stmt->bind_param(
            $selectTypes,
            ...$selectParams
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    public function getById(int $serviceId): ?array
    {
        try {
            
            $connection = $this->db->getConexion();

            $sql = "SELECT 
                esl.id as etapa_servicio_log_id,
                esl.servicio_id as servicio_id,
                ets.nombre as nombre_etapa_servicio,
                etse.nombre as nombre_estatus_etapa_servicio,
                etse.descripcion as descripcion_estatus_etapa_servicio,
                etse.color as color_estatus_etapa_servicio,
                etse.orden as orden_estatus_etapa_servicio,
                etse.es_final as es_final_estatus_etapa_servicio,
                esl.created_at as fecha_creacion_log,
                udds.id as id_unidad,
                udds.eco,
                udds.placas as placas_unidad,
                udds.tipo_unidad,
                svs.shipment,
                svs.tipo_viaje,
                svs.num_repartos,
                svs.tipo_servicio,
                svs.status as estatus_servicio,
                svs.id_operador,
                svs.tracking,
                CONCAT(usrs.nombre, ' ', usrs.apellidoP, ' ', usrs.apellidoM) as nombre_usuario_registro_log,
                svs.fec_alta as fecha_alta,
                svs.fecha_carga,
                svs.fecha_descarga
            FROM etapas_servicios_logs esl 
            INNER JOIN servicios as svs
                ON esl.servicio_id = svs.id 
            INNER JOIN cat_unidades udds 
                ON svs.id_unidad = udds.id
            INNER JOIN etapas_servicios ets 
                ON esl.etapa_servicio_id = ets.id  
            INNER JOIN etapas_servicios_estatus etse
                ON esl.etapa_servicio_estatus_id = etse.id 
            LEFT JOIN usuarios as usrs
	            ON esl.creado_por = usrs.id
            WHERE esl.servicio_id = ?
            ORDER BY esl.id DESC";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $serviceId);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return [
                'data' => $data,
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public function getLastServiceStageByServiceId(int $serviceId): ?array
    {
        try {
            
            $connection = $this->db->getConexion();

            $sql = "SELECT 
                esl.id as etapa_servicio_log_id,
                esl.servicio_id as servicio_id,
                ets.nombre as nombre_etapa_servicio,
                esl.etapa_servicio_id,
                esl.etapa_servicio_estatus_id,
                etse.nombre as nombre_estatus_etapa_servicio,
                etse.descripcion as descripcion_estatus_etapa_servicio,
                etse.color as color_estatus_etapa_servicio,
                etse.orden as orden_estatus_etapa_servicio,
                etse.es_final as es_final_estatus_etapa_servicio,
                esl.created_at,
                udds.id as id_unidad,
                udds.eco,
                udds.placas as placas_unidad,
                udds.tipo_unidad,
                svs.shipment,
                svs.tipo_viaje,
                svs.num_repartos,
                svs.tipo_servicio,
                svs.status as estatus_servicio,
                svs.id_operador,
                svs.tracking,
                CONCAT(usrs.nombre, ' ', usrs.apellidoP, ' ', usrs.apellidoM) as nombre_usuario_registro_log,
                CONCAT(usrsprd.nombre, ' ', usrsprd.apellidoP, ' ', usrsprd.apellidoM) as nombre_operador,
                svs.fec_alta as fecha_alta,
                svs.fecha_carga,
                svs.fecha_descarga,
                DATEDIFF(CURDATE(), svs.fecha_descarga) AS dias_transcurridos_desde_descarga
            FROM etapas_servicios_logs esl 
            INNER JOIN servicios as svs
                ON esl.servicio_id = svs.id 
            INNER JOIN cat_unidades udds 
                ON svs.id_unidad = udds.id
            INNER JOIN usuarios as usrsprd
	            ON usrsprd.id = svs.id_operador  
            INNER JOIN etapas_servicios ets 
                ON esl.etapa_servicio_id = ets.id  
            INNER JOIN etapas_servicios_estatus etse
                ON esl.etapa_servicio_estatus_id = etse.id 
            LEFT JOIN usuarios as usrs
                ON esl.creado_por = usrs.id
            WHERE esl.servicio_id = ?
            ORDER BY esl.id DESC
            LIMIT 1";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $serviceId);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;

        } catch (\Throwable $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}