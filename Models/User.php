<?php

declare(strict_types=1);

class User
{
    private MySQL $db;

    private string $table = 'usuarios';

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }


    public function getOperatorTypeUsers(array $filters = [], array $pagination = []): ?array
    {
        $connection = $this->db->getConexion();

        $where = ["usrs.puesto_id IN ( SELECT id_puesto FROM cat_puestos)"];
        $params = [];
        $types = "";

        if (!empty($filters['nombre'])) {
            $where[] = " usrs.nombre LIKE ?";
            $params[] = "%" . $filters['nombre'] . "%";
            $types .= "s";
        }

        $page = max(1, $pagination['page'] ?? 1);
        $perPage = max(1, $pagination['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT 
                usrs.id,
                CONCAT(usrs.nombre, ' ', usrs.apellidoP, ' ', usrs.apellidoM) as nombre_completo,
                usrs.puesto_id,
                usrs.area
            FROM {$this->table} as usrs
            WHERE " . implode(" AND ", $where) . "
            ORDER BY usrs.id ASC
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

        return $data ?? null;
    }
}