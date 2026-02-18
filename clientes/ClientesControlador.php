<?php
require_once "../system/connection.php";

class ClientesControlador
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function listar($page = 1, $limit = 10, $filtros = [])
    {
        $conxion = $this->db->getConexion();

        $offset = ($page - 1)  * $limit;

        $where = "WHERE (c.fecha_baja IS NULL OR c.status != 'eliminado')";

        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['razon_social'])) {
            $where .= " AND c.nombre_razon LIKE ?";
            $params[] = "%" . $filtros['razon_social'] . "%";
            $types .= "s";
        }

        $SQL = " SELECT c.id, 
            c.nombre_razon,
            c.RFC,
            c.correo,
            c.telefono,
            c.tipo_operacion
            FROM clientes c
            $where
            ORDER BY c.id DESC
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
                'razon_social' => $row['nombre_razon'] ?? 'N/A',
                'RFC' => $row['RFC'] ?? 'N/A',
                'correo' => $row['correo'] ?? 'N/A',
                'telefono' => $row['telefono'] ?? 'N/A',
                'tipo_operacion' => $row['tipo_operacion'] ?? 'N/A',
            ];
        }
        return $data;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (c.fecha_baja IS NULL OR c.status != 'eliminado')";
        $params = [];
        $types = "";

        // 🔎 Filtro nombre
        if (!empty($filtros['razon_social'])) {
            $where .= " AND c.nombre_razon LIKE ?";
            $params[] = "%" . $filtros['razon_social'] . "%";
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total FROM clientes c $where";

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

        $SQL = "SELECT * FROM clientes WHERE id = ? LIMIT 1";

        $stmt = $conexion->prepare($SQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();

        return $client;
    }
    public function crear($data){
        $SQL = "INSERT INTO clientes (nombre_razon, telefono, correo, tipo_operacion, tipo_cliente, status, fecha_alta) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['nombre_razon'],
            $data['telefono'],
            $data['correo'],
            $data['tipo_operacion'],
            $data['tipo_cliente'],
            $data['status'],
            date('Y-m-d H:i:s'),
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'nombre_razon = ?',
            'telefono = ?',
            'correo = ?',
            'tipo_operacion = ?',
            'tipo_cliente = ?',
            'status = ?',
        ];

        $params = [
            $data['nombre_razon'],
            $data['telefono'],
            $data['correo'],
            $data['tipo_operacion'],
            $data['tipo_cliente'],
            $data['status'],
            $id
        ];

        $sql = "UPDATE clientes SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    public function eliminar($id){

        $fechaHora = date('Y-m-d H:i:s');

        $sql = "UPDATE clientes SET status='eliminado', fecha_baja=? WHERE id=?";
        return $this->db->execute($sql, [$fechaHora, $id]);
    }

}
