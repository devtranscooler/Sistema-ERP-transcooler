<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class Product
{
    private MySQL $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function getAll(array $filters = [], array $pagination = []): array
    {
        $connection = $this->db->getConexion();

        $where = ["pdts.estatus = 1"];
        $params = [];
        $types = "";

        if (!empty($filters['nombre'])) {
            $where[] = "pdts.nombre LIKE ?";
            $params[] = "%" . $filters['nombre'] . "%";
            $types .= "s";
        }

        if (!empty($filters['clave'])) {
            $where[] = "pdts.clave LIKE ?";
            $params[] = "%" . $filters['clave'] . "%";
            $types .= "s";
        }

        if (!empty($filters['cliente_id'])) {
            $where[] = "pdts.cliente_id = ?";
            $params[] = $filters['cliente_id'];
            $types .= "i";
        }

        if (!empty($filters['fraccion_id'])) {
            $where[] = "pdts.fraccion_id = ?";
            $params[] = $filters['fraccion_id'];
            $types .= "i";
        }

        if (!empty($filters['tipo_embalaje_id'])) {
            $where[] = "pdts.tipo_embalaje_id = ?";
            $params[] = $filters['tipo_embalaje_id'];
            $types .= "i";
        }

        $page = max(1, $pagination['page'] ?? 1);
        $perPage = max(1, $pagination['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT 
            pdts.id,
            pdts.nombre,
            pdts.descripcion,
            pdts.peso,
            pdts.temperatura,
            pdts.clave,
            fcns.id as fraccion_id,
            fcns.nombre as fraccion_nombre, 
            ebjs.id as tipo_embalaje_id,
            ebjs.nombre as tipo_embalaje_nombre,
            cnts.id as cliente_id,
            cnts.nombre_razon as cliente_nombre,
            pdts.estatus,
            pdts.created_at,
            pdts.updated_at 
        FROM productos as pdts
        LEFT JOIN fracciones as fcns
            ON pdts.fraccion_id = fcns.id
        LEFT JOIN embalajes as ebjs
            ON pdts.tipo_embalaje_id = ebjs.id 
        LEFT JOIN clientes as cnts
            ON pdts.cliente_id  = cnts.id 
        WHERE " . implode(" AND ", $where) . "
        ORDER BY pdts.id DESC
        LIMIT ? OFFSET ?";

        $stmt = $connection->prepare($sql);

        $params[] = $perPage;
        $params[] = $offset;
        $types .= "ii";

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}