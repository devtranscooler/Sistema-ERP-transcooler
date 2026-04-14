<?php
require_once "../system/connection.php";

class UnidadesControlador
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

        $where = "WHERE (u.eco IS NOT NULL) AND (u.status = 'activo')";

        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['eco'])) {
            $where .= " AND u.eco LIKE ?";
            $params[] = "%" . $filtros['eco'] . "%";
            $types .= "s";
        }

        $SQL = " SELECT u.id, 
            u.eco,
            u.tipo_unidad,
            u.placas,
            u.no_motor,
            u.niv
            FROM cat_unidades u
            $where
            ORDER BY u.id DESC
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
                'eco' => $row['eco'] ?? 'N/A',
                'tipo_unidad' => $row['tipo_unidad'] ?? 'N/A',
                'placas' => $row['placas'] ?? 'N/A',
                'no_motor' => $row['no_motor'] ?? 'N/A',
                'niv' => $row['niv'] ?? 'N/A',
            ];
        }
        return $data;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (u.eco IS NOT NULL) AND (u.status = 'activo')";
        
        $params = [];
        $types = "";

        // Filtro por eco
        if (!empty($filtros['eco'])) {
            $where .= " AND u.eco LIKE ?";
            $params[] = "%" . $filtros['eco'] . "%";
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total FROM cat_unidades u $where";

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

        $SQL = "SELECT * FROM cat_unidades WHERE id = ? LIMIT 1";

        $stmt = $conexion->prepare($SQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $unidad = $result->fetch_assoc();
        $stmt->close();

        return $unidad;
    }
    public function crear($data){
        $SQL = "INSERT INTO cat_unidades (eco, razon_social, placas, folio_tc, niv, no_motor,marca,modelo,capacidad,tipo_unidad,anio,color,aseguradora,cobertura,vigencia_poliza) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['eco'],
            $data['razon_social'],
            $data['placas'],
            $data['folio_tc'],
            $data['niv'],
            $data['no_motor'],
            $data['marca'],
            $data['modelo'],
            $data['capacidad'],
            $data['tipo_unidad'],
            $data['anio'],
            $data['color'],
            $data['aseguradora'],
            $data['cobertura'],
            $data['vigencia_poliza'],
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'eco = ?',
            'razon_social = ?',
            'placas = ?',
            'folio_tc = ?',
            'niv = ?',
            'no_motor = ?',
            'marca = ?',
            'modelo = ?',
            'capacidad = ?',
            'tipo_unidad = ?',
            'anio = ?',
            'color = ?',
            'aseguradora = ?',
            'cobertura = ?',
            'vigencia_poliza = ?',
        ];

        $params = [
            $data['eco'],
            $data['razon_social'],
            $data['placas'],
            $data['folio_tc'],
            $data['niv'],
            $data['no_motor'],
            $data['marca'],
            $data['modelo'],
            $data['capacidad'],
            $data['tipo_unidad'],
            $data['anio'],
            $data['color'],
            $data['aseguradora'],
            $data['cobertura'],
            $data['vigencia_poliza'],
            $id
        ];

        $sql = "UPDATE cat_unidades SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }
    public function eliminar($id){
        $sql = "UPDATE cat_unidades SET status='eliminado' WHERE id=?";
        return $this->db->execute($sql, [$id]);
    }
    public function buscarUnidades($term)
    {
        $term = $this->db->escape_string($term);

        $query = "SELECT id, eco, tipo_unidad
                FROM cat_unidades cu  
                WHERE eco LIKE '%$term%' 
                LIMIT 10";

        $result = $this->db->consulta($query);

        $unidades = [];

        while ($row = $result->fetch_assoc()) {
            $unidades[] = [
                'id' => $row['id'],
                'eco' => $row['eco'],
                'tipo_unidad' => $row['tipo_unidad']
            ];
        }
        return $unidades;
    }
    public function buscarRemolques($term)
    {
        $term = $this->db->escape_string($term);

        $query = "SELECT id, eco, tipo_unidad
                FROM cat_unidades  
                WHERE eco LIKE '%$term%' 
                AND tipo_unidad LIKE 'Rem.%'
                LIMIT 10";

        $result = $this->db->consulta($query);

        $remolques = [];

        while ($row = $result->fetch_assoc()) {
            $remolques[] = [
                'id'          => $row['id'],
                'eco'         => $row['eco'],
                'tipo_unidad' => $row['tipo_unidad']
            ];
        }
        return $remolques;
    }
    public function buscarDollys($term)
    {
        $term = $this->db->escape_string($term);

        $query = "SELECT id, eco, tipo_unidad
                FROM cat_unidades  
                WHERE eco LIKE '%$term%' 
                AND tipo_unidad = 'Dolly'
                LIMIT 10";

        $result = $this->db->consulta($query);

        $dollys = [];

        while ($row = $result->fetch_assoc()) {
            $dollys[] = [
                'id'          => $row['id'],
                'eco'         => $row['eco'],
                'tipo_unidad' => $row['tipo_unidad']
            ];
        }
        return $dollys;
    }
}
