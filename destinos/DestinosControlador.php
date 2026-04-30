<?php
require_once __DIR__ . '/../system/connection.php';

class DestinosControlador
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

        $where = "WHERE (d.nombre IS NOT NULL) AND (d.status = 'activo')";

        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['nombre'])) {
            $where .= " AND d.nombre LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
            $types .= "s";
        }

        $SQL = " SELECT d.id, 
            d.nombre,
            d.calle,
            d.numero_interior,
            d.numero_exterior,
            d.ciudad,
            d.pais,
            d.codigo_postal,
            d.municipio
            FROM cat_destinos d
            $where
            ORDER BY d.id DESC
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
                'nombre' => $row['nombre'] ?? 'N/A',
                'calle' => $row['calle'] ?? 'N/A',
                'numero_interior' => $row['numero_interior'] ?? 'N/A',
                'numero_exterior' => $row['numero_exterior'] ?? 'N/A',
                'ciudad' => $row['ciudad'] ?? 'N/A',
                'pais' => $row['pais'] ?? 'N/A',
                'codigo_postal' => $row['codigo_postal'] ?? 'N/A',
                'municipio' => $row['municipio'] ?? 'N/A',
            ];
        }
        return $data;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (d.nombre IS NOT NULL) AND (d.status = 'activo')";
        
        $params = [];
        $types = "";

        // Filtro por nombre
        if (!empty($filtros['nombre'])) {
            $where .= " AND d.nombre LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total FROM cat_destinos d $where";

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

        $SQL = "SELECT * FROM cat_destinos WHERE id = ? LIMIT 1";

        $stmt = $conexion->prepare($SQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $destino = $result->fetch_assoc();
        $stmt->close();

        return $destino;
    }
    public function crear($data){
        $SQL = "INSERT INTO cat_destinos (nombre,calle,numero_interior,numero_exterior,ciudad,pais,codigo_postal,municipio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['nombre'],
            $data['calle'],
            $data['numero_interior'],
            $data['numero_exterior'],
            $data['ciudad'],
            $data['pais'],
            $data['codigo_postal'],
            $data['municipio'],
            
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'nombre = ?',
            'calle = ?',
            'numero_interior = ?',
            'numero_exterior = ?',
            'ciudad = ?',
            'pais = ?',
            'codigo_postal = ?',
            'municipio = ?',            
        ];

        $params = [
            $data['nombre'],
            $data['calle'],
            $data['numero_interior'],
            $data['numero_exterior'],
            $data['ciudad'],
            $data['pais'],
            $data['codigo_postal'],
            $data['municipio'],
            $id
        ];

        $sql = "UPDATE cat_destinos SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }
    public function eliminar($id){
        $sql = "UPDATE cat_destinos SET status='eliminado' WHERE id=?";
        return $this->db->execute($sql, [$id]);
    }
    public function buscarDestinos($term)
    {
        $term = $this->db->escape_string($term);

        $query = "SELECT id, nombre
                FROM cat_destinos cu  
                WHERE nombre LIKE '%$term%' 
                LIMIT 10";

        $result = $this->db->consulta($query);

        $destinos = [];

        while ($row = $result->fetch_assoc()) {
            $destinos[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre']
            ];
        }
        return $destinos;
    }
}
