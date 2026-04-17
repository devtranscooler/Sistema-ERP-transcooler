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

    public function findByResource(string $resourceType, int $resourceId): array
    {
        try {

            $connection = $this->db->getConexion();

            $sql = "SELECT 
                mda.id as id_media,
                mda.nombre_origen,
                mda.ruta,
                mda.tipo_recurso,
                mda.tipo_recurso_id,
                mda.estatus,
                usrs.id as id_usuario_creador,
                usrs.nombre,
                usrs.apellidoP,
                usrs.apellidoM,
                mda.created_at as fecha_carga_archivo 
            FROM media as mda
            LEFT JOIN usuarios as usrs
                ON mda.id_usuario_creador = usrs.id
            WHERE mda.estatus = 1 
                AND mda.deleted_at IS NULL
                AND mda.tipo_recurso = ?
                AND mda.tipo_recurso_id = ?
            ORDER BY mda.id ASC
            LIMIT 10";

            $stmt = $connection->prepare($sql);

            $stmt->bind_param("si", $resourceType, $resourceId);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $stmt->close();

            return [
                "status" => true,
                "data" => $data
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
    * The function `create` inserts media data into a database media table and returns a success message or
    * an error message.
    * 
    * @param array data - nombre_origen (string): The original name of the media file
    * 
    * @return array An array is being returned with a "status" key indicating whether the operation was
    * successful or not, and a "message" key providing a message related to the operation status. If
    * the operation is successful, the "status" key will be true and the "message" key will contain the
    * message "Media guardada correctamente". If an exception occurs during the operation, the "status"
    * key will be
    */
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
