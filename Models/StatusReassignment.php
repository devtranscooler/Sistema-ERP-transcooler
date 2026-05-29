<?php

declare(strict_types=1);

class StatusReassignment
{
    private MySQL $db;

    private string  $table = 'estatus_reasignacion';

    private array $fillable = [
        'nombre',
        'descripcion'
    ];

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function all(): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT * FROM {$this->table}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }
}