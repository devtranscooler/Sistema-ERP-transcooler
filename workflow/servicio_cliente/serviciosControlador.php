<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

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
        //Servicios para tab de Trafico
        if ($context === 'trafico'){
            $where = "WHERE (s.status = 'activo' AND s.tracking = 'Asignación de unidad')";
            }
        //Servicios para tab de Mesa de control
        else if ($context === 'mesaControl') {
            $where = "WHERE (s.status = 'activo' AND s.tracking = 'Asignación de productos')";
        }
        //Servicios para tab de Salidas
        else if ($context === 'salida') {
            $where = "WHERE (s.status = 'activo' AND s.tracking = 'En espera de salida')";
        }
        else {
            //Servicios para tab de Main y servicio a cliente
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
    public function show($id)
    {
        $conexion = $this->db->getConexion();
        $SQLServicio = "SELECT
            s.*,
            c.nombre_razon,
            cu.eco,

            CONCAT(uo.nombre, ' ', uo.apellidoP, ' ', uo.apellidoM) AS nombreOperador,

            CONCAT(ua.nombre, ' ', ua.apellidoP) AS nombreUsuarioAlta

        FROM servicios s

        LEFT JOIN clientes c      ON c.id = s.id_cliente
        LEFT JOIN cat_unidades cu ON cu.id = s.id_unidad

        LEFT JOIN usuarios uo ON uo.id = s.id_operador
        LEFT JOIN usuarios ua ON ua.id = s.id_usuario_alta

        WHERE s.id = ?";

        $stmt = $conexion->prepare($SQLServicio);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $servicio = $result->fetch_assoc();
        $stmt->close();

        if ($servicio) {
            $servicio = [
                'id' => $servicio['id'],
                'shipment' => $servicio['shipment'],
                'fecha_carga' => $servicio['fecha_carga'],
                'fecha_descarga' => $servicio['fecha_descarga'],
                'tipo_servicio' => $servicio['tipo_servicio'],
                'fec_alta' => $servicio['fec_alta'],
                'tipo_viaje' => $servicio['tipo_viaje'],
                'num_repartos' => $servicio['num_repartos'],
                'status' => $servicio['status'],

                'nombre_razon' => $servicio['nombre_razon'],
                'eco' => $servicio['eco'],
                'nombreOperador' => $servicio['nombreOperador'],
                'nombreUsuarioAlta' => $servicio['nombreUsuarioAlta'],
            ];
        }

        $SQLRepartos = "SELECT
                r.id,
                r.numero_reparto,
                r.origen_inicio,
                r.destino_final,

                co.nombre AS origen,
                cd.nombre AS destino,

                pr.cantidad,
                pr.peso,

                p.id AS producto_id,
                p.nombre AS producto_nombre

            FROM repartos r

            LEFT JOIN cat_destinos co ON co.id = r.id_origen
            LEFT JOIN cat_destinos cd ON cd.id = r.id_destino

            LEFT JOIN producto_reparto pr ON pr.reparto_id = r.id
            LEFT JOIN productos p         ON p.id = pr.producto_id

            WHERE r.id_servicio = ?";

            $stmt = $conexion->prepare($SQLRepartos);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $repartos = [];

        while ($row = $result->fetch_assoc()) {
            $repartoId = $row['id'];
            // Crear reparto
            if (!isset($repartos[$repartoId])) {
                $repartos[$repartoId] = [
                    'id' => $row['id'],
                    'numero_reparto' => $row['numero_reparto'],
                    'destino' => $row['destino'],
                    'origen' => $row['origen'],
                    'origen_inicio' => $row['origen_inicio'],
                    'destino_final' => $row['destino_final'],
                    'productos' => []
                ];
            }
            // Agregar productos
            if ($row['producto_id']) {
                $repartos[$repartoId]['productos'][] = [
                    'producto_id' => $row['producto_id'],
                    'producto_nombre' => $row['producto_nombre'],
                    'cantidad' => $row['cantidad'],
                    'peso' => $row['peso'],
                ];
            }
        }
        $stmt->close();
        return [
            'servicio' => $servicio,
            'repartos' => array_values($repartos)
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
                    id_dolly = ?,
                    config_vehicular = ?
                WHERE id = ?";
        
        $params = [
            $data['id_operador'],
            $data['id_unidad'],
            $data['id_remolque']  ?? null,
            $data['id_remolque2'] ?? null,
            $data['id_dolly'] ?? null,
            $data['config_vehicular'] ?? null,
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
    public function agregarProductosPermisos ($id, $data){
        $SQL = "UPDATE servicios 
                SET km_origen_destino = ?, 
                    monto_total = ?,
                    tipo_permiso = ?,
                    folio_permiso = ?
                WHERE id = ?";
        
        $params = [
            $data['km_origen_destino'],
            $data['monto_total'],
            $data['tipo_permiso']  ?? null,
            $data['folio_permiso'] ?? null,
            $id
        ];

        return $this->db->execute($SQL, $params);
    }
}
