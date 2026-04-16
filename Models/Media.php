<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class Media
{
    private MySQL $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    public function create(array $data): array
    {
        try {

            $SQL = "INSERT INTO media (
                        nombre_origen,
                        ruta,
                        extension,
                        id_usuario_creador,
                        tipo_recurso,
                        tipo_recurso_id,
                        estatus,
                        created_at,
                        updated_at
                    ) VALUES (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        NOW(),
                        NOW()
                    )";

            $params = [
                $data['nombre_origen'],
                $data['ruta'],
                $data['extension'],
                $data['id_usuario_creador'],
                $data['tipo_recurso'],
                $data['tipo_recurso_id'],
                $data['estatus'] ?? 1
            ];

            $this->db->execute($SQL, $params);

            return [
                "status" => true,
                "message" => "Media guardada correctamente"
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
