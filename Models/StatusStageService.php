<?php

declare(strict_types=1);

class StatusStageService
{
    private MySQL $db;

    private string  $table = 'etapas_servicios_estatus';

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function getStatusById(int $stageId): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT * 
            FROM {$this->table}
            WHERE etapa_servicio_id = ?
            ORDER BY id DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $stageId);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }
}