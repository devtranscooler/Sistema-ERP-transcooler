<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

class serviciosControlador
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function listar($page = 1, $limit = 10, $filtros = [], $context)
    {
        $conxion = $this->db->getConexion();

        $offset = ($page - 1)  * $limit;

        if ($context === 'trafico'){
            $where = "WHERE (s.status = 'activo' AND (s.id_unidad IS NULL OR s.id_operador IS NULL))";
            }
        else if ($context === 'salida') {
            $where = "WHERE (s.status = 'activo' AND s.tracking = 'En espera de salida')";
        }
        else {
            $where = "WHERE (s.status = 'activo')";
        }
        

        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['filtroIdServicio'])) {
            $where .= " AND CONCAT(s.id, s.shipment) LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicio'] . "%";
            $types .= "s";
        }
        // Filtro nombre
        if (!empty($filtros['filtroIdServicioTrafico'])) {
            $where .= " AND CONCAT(s.id, s.shipment) LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicioTrafico'] . "%";
            $types .= "s";
        }
        // Filtro nombre
        if (!empty($filtros['filtroIdServicioMain'])) {
            $where .= " AND CONCAT(s.id, s.shipment) LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicioMain'] . "%";
            $types .= "s";
        }

        $SQL = " SELECT s.id,
            s.id_cliente,
            s.id_usuario_alta,
            s.fec_alta,
            s.shipment,
            s.tipo_viaje,
            s.num_repartos,
            s.fecha_carga,
            s.fecha_descarga,
            s.tipo_servicio,
            s.status,
            s.id_operador,
            s.id_unidad,
            s.tracking

            FROM servicios s
            $where
            ORDER BY s.id DESC
            LIMIT ?, ?
        ";

        $params[] = $offset;
        $params[] = $limit;
        $types .= "ii";

        $stmt = $conxion->prepare($SQL);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'id' => $row['id'],
                'id_cliente' => $row['id_cliente'] ?? 'N/A',
                'id_usuario_alta' => $row['id_usuario_alta'] ?? 'N/A',
                'fec_alta' => $row['fec_alta'] ?? 'N/A',
                'shipment' => $row['shipment'] ?? 'N/A',
                'tipo_viaje' => $row['tipo_viaje'] ?? 'N/A',
                'num_repartos' => $row['num_repartos'] ?? 'N/A',
                'fecha_carga' => $row['fecha_carga'] ?? 'N/A',
                'fecha_descarga' => $row['fecha_descarga'] ?? 'N/A',
                'tipo_servicio' => $row['tipo_servicio'] ?? 'N/A',
                'status' => $row['status'] ?? 'N/A',
                'id_operador' => $row['id_operador'] ?? 'N/A',
                'id_unidad' => $row['id_unidad'] ?? 'N/A',
                'tracking' => $row['tracking'] ?? 'N/A',
            ];
        }
        return $data;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (s.status = 'activo')";
        
        $params = [];
        $types = "";

        // Filtro por eco
        if (!empty($filtros['filtroIdServicio'])) {
            $where .= " AND s.id LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicio'] . "%";
            $types .= "s";
        }

        // Filtro nombre
        if (!empty($filtros['filtroIdServicioTrafico'])) {
            $where .= " AND s.id LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicioTrafico'] . "%";
            $types .= "s";
        }
        // Filtro nombre
        if (!empty($filtros['filtroIdServicioMain'])) {
            $where .= " AND s.id LIKE ?";
            $params[] = "%" . $filtros['filtroIdServicioMain'] . "%";
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total FROM servicios s $where";

        $stmt = $conexion->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }
    public function show($id){
        $conexion = $this->db->getConexion();

        $SQL = "SELECT
            s.*,
            c.nombre_razon,
            cu.eco,
            CONCAT(uo.nombre, ' ', uo.apellidoP, ' ', uo.apellidoM) AS nombreOperador,
            CONCAT(ua.nombre, ' ', ua.apellidoP) AS nombreUsuarioAlta,
            r.id AS reparto_id,
            r.numero_reparto,
            r.origen_inicio,
            co.nombre AS origen,
            cd.nombre AS destino
        FROM servicios s
        LEFT JOIN clientes c        ON c.id  = s.id_cliente
        LEFT JOIN cat_unidades cu   ON cu.id = s.id_unidad
        LEFT JOIN usuarios uo       ON uo.id = s.id_operador
        LEFT JOIN usuarios ua       ON ua.id = s.id_usuario_alta
        INNER JOIN repartos r 		ON s.id = r.id_servicio
        LEFT JOIN cat_destinos co   ON co.id = r.id_origen
		LEFT JOIN cat_destinos cd   ON cd.id = r.id_destino
        WHERE s.id = ?";

        $stmt = $conexion->prepare($SQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicio = null;
        $repartos = [];

        while ($row = $result->fetch_assoc()) {
            
            if (!$servicio) {
                $servicio = [
                    'id' => $row['id'],
                    'shipment' => $row['shipment'],
                    'fecha_carga' => $row['fecha_carga'],
                    'fecha_descarga' => $row['fecha_descarga'],
                    'tipo_servicio' => $row['tipo_servicio'],
                    'fec_alta' => $row['fec_alta'],
                    'tipo_viaje' => $row['tipo_viaje'],
                    'num_repartos' => $row['num_repartos'],
                    'status' => $row['status'],

                    'nombre_razon' => $row['nombre_razon'],
                    'eco' => $row['eco'],
                    'nombreOperador' => $row['nombreOperador'],
                    'nombreUsuarioAlta' => $row['nombreUsuarioAlta'],
                ];
            }

            // Repartos
            $repartos[] = [
                'id' => $row['reparto_id'],
                'numero_reparto' => $row['numero_reparto'],
                'destino' => $row['destino'],
                'origen' => $row['origen'],
                'origen_inicio' => $row['origen_inicio'],
            ];
        }

        $stmt->close();
        return [
            'servicio' => $servicio,
            'repartos' => $repartos
        ];
    }
    public function crear($data){
        $SQL = "INSERT INTO servicios (id_cliente,id_usuario_alta,fec_alta,shipment,tipo_viaje,num_repartos,fecha_carga,fecha_descarga,tipo_servicio,origen,status) 
                VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?,'activo')";
        
        $params = [
            $data['id_cliente'],
            $_SESSION['ID_USUARIO'],
            $data['shipment'],
            $data['tipo_viaje'],
            $data['num_repartos'],
            $data['fecha_carga'],
            $data['fecha_descarga'],
            $data['tipo_servicio'],
            $data['origen'],
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'id_cliente = ?',
            'id_usuario_alta = ?',
            'fec_alta = ?',
            'shipment = ?',
            'tipo_viaje = ?',
            'num_repartos = ?',
            'fecha_carga = ?',
            'fecha_descarga = ?',
            'tipo_servicio = ?',
            'origen = ?',
            'status = ?',
            ];

        $params = [
            $data['id_cliente'],
            $data['id_usuario_alta'],
            $data['fec_alta'],
            $data['shipment'],
            $data['tipo_viaje'],
            $data['num_repartos'],
            $data['fecha_carga'],
            $data['fecha_descarga'],
            $data['tipo_servicio'],
            $data['origen'],
            $data['status'],
            $id
        ];

        $sql = "UPDATE servicios SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }
    public function agregarOperadorUnidad ($id, $data){
        $SQL = "UPDATE servicios 
                SET id_operador = ?, 
                    id_unidad = ?,
                    id_remolque = ?,
                    id_remolque2 = ?,
                    id_dolly = ?
                WHERE id = ?";
        
        $params = [
            $data['id_operador'],
            $data['id_unidad'],
            $data['id_remolque']  ?? null,
            $data['id_remolque2'] ?? null,
            $data['id_dolly'] ?? null,
            $id
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizarTracking ($id, $tracking){
        $SQL = "UPDATE servicios 
                SET tracking = ? 
                WHERE id = ?";
        
        $params = [
            $tracking,
            $id
        ];

        return $this->db->execute($SQL, $params);
    }
    public function eliminar($id){
        $sql = "UPDATE servicios SET status='eliminado' WHERE id=?";
        return $this->db->execute($sql, [$id]);
    }
    public function crearRetornandoId($data) {
        $SQL = "INSERT INTO servicios (id_cliente, id_usuario_alta, fec_alta, shipment, tipo_viaje, num_repartos, fecha_carga, fecha_descarga, tipo_servicio, status)
                VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, 'activo')";

        $params = [
            $data['id_cliente'],
            $_SESSION['ID_USUARIO'],
            $data['shipment'],
            $data['tipo_viaje'],
            $data['num_repartos'],
            $data['fecha_carga'],
            $data['fecha_descarga'],
            $data['tipo_servicio'],
        ];

        $this->db->execute($SQL, $params);

        return $this->db->getConexion()->insert_id;
    }
}
