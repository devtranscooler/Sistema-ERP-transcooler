<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class Media
{
    private MySQL $db;
    private string $table = "media";

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    /**
    * The function `findByResource` retrieves media data based on a specified resource type and ID,
    * handling exceptions and returning the data or an error message.
    * 
    * @param string resourceType resourceType is a string that represents the type of the resource you
    * are searching for in the database. It could be a specific type like 'image', 'video', 'document',
    * etc.
    * @param int resourceId The `resourceId` parameter is an integer value that represents the unique
    * identifier of the specific resource within the system. It is used to filter the query results to
    * retrieve media information related to this particular resource.
    * 
    * @return array An array is being returned with two keys: "status" and "data". If the query is
    * successful, "status" will be true and "data" will contain an array of rows fetched from the
    * database. If there is an error during the query execution, "status" will be false and "message"
    * will contain the error message.
    */
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

    /**
     * This PHP function retrieves a media file record from a database based on its ID and returns the
     * data along with a status indicator.
     * 
     * @param int mediaFileId The `findById` function is a method that retrieves a media file record
     * from the database based on the provided `mediaFileId`. The function executes a SQL query to
     * select specific columns from the database table where the `id` matches the provided
     * `mediaFileId` and the `estatus` is set
     * 
     * @return array An array is being returned with a "status" key indicating whether the operation
     * was successful or not, and a "data" key containing the fetched data if successful. If an error
     * occurs, the array will have a "status" key set to false and a "message" key with the error
     * message.
     */
    public function findById(int $mediaFileId): array
    {
        try {
            
            $connection = $this->db->getConexion();

            $sql = "SELECT 
                md.id,
                md.nombre_origen,
                md.ruta,
                md.estatus, 
                md.deleted_at
            FROM {$this->table} as md
            WHERE md.id = ?
            AND md.estatus = 1
            LIMIT 1";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $mediaFileId);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_assoc();

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
     * The function `delete` in PHP updates a record in a database table to mark it as deleted,
     * returning a status and message based on the outcome.
     * 
     * @param int id The `delete` function you provided is a method that deletes a record from a
     * database table based on the given `id`. Here's a breakdown of the function:
     * 
     * @return array An array is being returned with a "status" key indicating whether the deletion was
     * successful or not, and a "message" key providing a corresponding message. If the deletion was
     * successful, the status is true and the message is "Archivo eliminado correctamente" (File
     * deleted successfully). If the deletion was not successful or the resource was already deleted,
     * the status is false and the message is "No se
     */
    public function delete(int $id): array
    {
        try {

            $connection = $this->db->getConexion();

            $sql = "UPDATE {$this->table}
                    SET estatus = 0,
                        deleted_at = NOW()
                    WHERE id = ?
                    AND estatus = 1
                    LIMIT 1";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $id);

            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                return [
                    "status" => false,
                    "message" => "No se pudo eliminar el recurso o ya estaba eliminado"
                ];
            }

            return [
                "status" => true,
                "message" => "Archivo eliminado correctamente"
            ];

        } catch (\Throwable $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
