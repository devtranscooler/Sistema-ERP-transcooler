<?php

declare(strict_types=1);

class MediaRequest
{
    private MySQL $db;
    private string $table = "media_solicitudes";

    private array $fillable = [
        'media_id',
        'usuario_solicitante_id',
        'usuario_aprobador_id',
        'estatus',
        'fecha_aprobacion',
        'comentario',
        'token'
    ];

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    /**
     * The function `create` inserts data into a database table and returns a success message or an
     * error message.
     * 
     * @param array data The `create` function you provided seems to be inserting data into a database
     * table. The function takes an array `` as input, which should contain the following keys:
     * 
     * @return ?array The `create` function is returning an array with a status and message. If the
     * insertion into the database is successful, it returns:
     * ```php
     * [
     *     "status" => true,
     *     "message" => "Solicitud guardada correctamente"
     * ]
     * ```
     * If an exception is caught during the process, it returns:
     * ```php
     * [
     *     "status" => false,
     *     "message"
     */
    public function create(array $data): ?array
    {
        try {

            $SQL = "INSERT INTO {$this->table} (
                media_id,
                usuario_solicitante_id,
                usuario_aprobador_id,
                estatus,
                fecha_aprobacion,
                comentario,
                token,
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
                $data['media_id'],
                $data['usuario_solicitante_id'],
                $data['usuario_aprobador_id'],
                $data['estatus'],
                $data['fecha_aprobacion'],
                $data['comentario'],
                $data['token']
            ];

            $this->db->execute($SQL, $params);

            return [
                "status" => true,
                "message" => "Solicitud guardada correctamente"
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * The function `update` updates a record in a database table based on the provided data and ID
     * using dynamic SQL generation and parameter binding in PHP.
     * 
     * @param int id The `id` parameter in the `update` function represents the identifier of the
     * record you want to update in the database. It is an integer value that uniquely identifies the
     * record.
     * @param array data The `update` function you provided seems to be updating a record in a database
     * table based on the given `` and ``. The `` parameter is an array containing the
     * fields and values that need to be updated in the database.
     * 
     * @return bool The `update` function returns a boolean value (`true` or `false`) based on whether
     * the SQL update query was executed successfully.
     */
    public function update(int $id, array $data): bool
    {

        /*
        |--------------------------------------------------------------------------
        | FILTRAR SOLO CAMPOS PERMITIDOS
        |--------------------------------------------------------------------------
        */

        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        /*
        |--------------------------------------------------------------------------
        | VALIDAR QUE HAYA CAMPOS
        |--------------------------------------------------------------------------
        */

        if (empty($filteredData)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR SET DINÁMICO
        |--------------------------------------------------------------------------
        */

        $fields = [];

        foreach ($filteredData as $column => $value) {
            $fields[] = "{$column} = ?";
        }

        $setClause = implode(', ', $fields);

        /*
        |--------------------------------------------------------------------------
        | QUERY FINAL
        |--------------------------------------------------------------------------
        */

        $sql = "
            UPDATE {$this->table}
            SET {$setClause}
            WHERE id = ?";

        $connection = $this->db->getConexion();
        $stmt = $connection->prepare($sql);

        /*
        |--------------------------------------------------------------------------
        | VALUES
        |--------------------------------------------------------------------------
        */

        $values = array_values($filteredData);

        /*
        |--------------------------------------------------------------------------
        | AGREGAR ID AL FINAL
        |--------------------------------------------------------------------------
        */

        $values[] = $id;

        /*
        |--------------------------------------------------------------------------
        | TYPES
        |--------------------------------------------------------------------------
        */

        $types = '';

        foreach ($values as $value) {

            if (is_int($value)) {

                $types .= 'i';

            } elseif (is_float($value)) {

                $types .= 'd';

            } else {

                $types .= 's';

            }

        }

        /*
        |--------------------------------------------------------------------------
        | BIND DINÁMICO
        |--------------------------------------------------------------------------
        */

        $stmt->bind_param($types, ...$values);

        /*
        |--------------------------------------------------------------------------
        | PREPARE
        |--------------------------------------------------------------------------
        */

        return $stmt->execute();
    }

    /**
     * This PHP function retrieves data based on a media file ID from a database table.
     * 
     * @param int mediaFileId The `findByMediaId` function is a method that queries a database table to
     * find a record based on the `media_id` field. It takes an integer parameter `` which
     * represents the `media_id` value to search for in the database.
     * 
     * @return ?array The `findByMediaId` function is returning an array with the keys "id" and
     * "estatus" if a record is found in the database matching the provided media file ID. If no record
     * is found, it returns `null`. In case of an exception being caught during the database operation,
     * it returns an array with keys "status" set to `false` and "message" containing the error
     */
    public function findByMediaId(int $mediaFileId): ?array
    {
        try {
            
            $connection = $this->db->getConexion();

            $sql = "SELECT 
                mdr.id,
                mdr.estatus
            FROM {$this->table} as mdr
            WHERE mdr.media_id = ?
            LIMIT 1";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $mediaFileId);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            return $data ?? null;

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * The function findByToken retrieves a record from a database table based on a provided token,
     * returning the data if found or an error message if an exception occurs.
     * 
     * @param string token The `findByToken` function you provided is a method that searches for a
     * record in a database table based on a given token. It uses a prepared SQL query to retrieve the
     * data corresponding to the token provided.
     * 
     * @return ?array The `findByToken` function returns an array containing the data fetched from the
     * database based on the provided token, or it returns `null` if no data is found. If an exception
     * occurs during the database query, it returns an array with a status of `false` and an error
     * message.
     */
    public function findByToken(string $token): ?array
    {
        try {
            
            $connection = $this->db->getConexion();

            $sql = "SELECT 
                mdr.*,
                md.nombre_origen
            FROM {$this->table} as mdr
            LEFT JOIN media as md
                ON mdr.media_id = md.id
            WHERE mdr.token = ?
            LIMIT 1";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            return $data ?? null;

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}