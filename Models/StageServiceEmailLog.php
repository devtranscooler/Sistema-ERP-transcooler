<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class StageServiceEmailLog
{
    private MySQL $db;
    private string $table = "etapas_servicios_email_logs";

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }
    public function create(array $data): array
    {
        try {

            $SQL = "INSERT INTO etapas_servicios_email_logs (
                servicio_id,
                etapa_servicio_id,
                etapa_servicio_estatus_id,
                id_usuario_emisor,
                id_usuario_receptor,
                comentarios,
                created_at
            ) VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                NOW()
            )";

            $params = [
                $data['servicio_id'],
                $data['etapa_servicio_id'],
                $data['etapa_servicio_estatus_id'],
                $data['id_usuario_emisor'],
                $data['id_usuario_receptor'],
                $data['comentarios']
            ];

            $this->db->execute($SQL, $params);

            return [
                "status" => true,
                "message" => "Estatus email log guradado correctamente"
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}