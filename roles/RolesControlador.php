<?php
require_once "../system/connection.php";

class RolesControlador
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

        $where = "WHERE (r.fecha_baja IS NULL OR r.status != 'eliminado')";

        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['nombre_rol'])) {
            $where .= " AND r.rol_descripcion LIKE ?";
            $params[] = "%" . $filtros['nombre_rol'] . "%";
            $types .= "s";
        }

        $SQL = " SELECT r.id, 
            r.rol_descripcion,
            r.status
            FROM cat_rol r
            $where
            ORDER BY r.id DESC
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
                'nombre' => $row['rol_descripcion'] ?? 'N/A',
                'status' => $row['status'] ?? 'N/A',
            ];
        }
        return $data;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (r.fecha_baja IS NULL OR r.status != 'eliminado')";
        $params = [];
        $types = "";

        // 🔎 Filtro nombre
        if (!empty($filtros['nombre_rol'])) {
            $where .= " AND r.rol_descripcion LIKE ?";
            $params[] = "%" . $filtros['nombre_rol'] . "%";
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total FROM cat_rol r $where";

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

        $SQL = "SELECT id, rol_descripcion, status FROM cat_rol WHERE id = ? LIMIT 1";

        $stmt = $conexion->prepare($SQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rol = $result->fetch_assoc();
        $stmt->close();

        return $rol;
    }
    public function crear($data){
        $SQL = "INSERT INTO cat_rol (rol_descripcion, status, fecha_alta) 
                VALUES (?, ?, ?)";

        $params = [
            $data['nombre'],
            $data['status'],
            date('Y-m-d H:i:s'),
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'rol_descripcion = ?',
            'status = ?',
        ];

        $params = [
            $data['nombre'],
            $data['status'],
            $id
        ];

        $sql = "UPDATE cat_rol SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    public function eliminar($id){

        $fechaHora = date('Y-m-d H:i:s');

        $sql = "UPDATE cat_rol SET status='eliminado', fecha_baja=? WHERE id=?";
        return $this->db->execute($sql, [$fechaHora, $id]);
    }

}