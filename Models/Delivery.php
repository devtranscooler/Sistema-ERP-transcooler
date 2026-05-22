<?php

declare(strict_types=1);

class Delivery
{
    private MySQL $db;
    private string $table = "repartos";

    private array $fillable = [
        'id_servicio',
        'numero_reparto',
        'id_destino',
        'status',
        'origen_inicio',
        'id_origen',
        'destino_final'
    ];

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }
    /**
     * This PHP function retrieves deliveries associated with a specific operator, filtering by status
     * and ordering the results by service ID.
     * 
     * @param int operatorId The `getDeliveriesByOperator` function retrieves delivery information for
     * a specific operator based on the provided `operatorId`. The function constructs a SQL query to
     * fetch data from the database, including details such as service ID, delivery ID, origin,
     * destination, and status for deliveries associated with the operator
     * 
     * @return ?array An array of delivery information is being returned for a specific operator based
     * on the provided operator ID. The array includes details such as service ID, delivery ID, origin,
     * destination, and status of the deliveries that are either 'Pendiente' or 'Completado'. If no
     * data is found, it will return null.
     */
    public function getDeliveriesByOperator(int $operatorId): ?array
    {
        $connection = $this->db->getConexion();

        $params = [];
        $types = "";

        $sql = "SELECT 
                svcs.id as id_servicio,
                svcs.id_operador,
                rprts.id as id_reparto,
                rprts.numero_reparto,
                rprts.id_origen,
                rprts.origen_inicio,
                rprts.id_destino,
                CONCAT(cdts.calle, ', No. Int: ', cdts.numero_interior, ', No. Ext: ', cdts.numero_exterior, ', ', cdts.ciudad, ', CP: ' , cdts.codigo_postal, ', ', cdts.municipio) as direccion_destino,
                rprts.destino_final,
                rprts.status,
                DATE_FORMAT(rprts.created_at, '%Y-%m-%d') as fecha_creacion
            FROM servicios as svcs
            LEFT JOIN repartos as rprts
                ON svcs.id = rprts.id_servicio
            LEFT JOIN cat_destinos as cdts
                ON rprts.id_destino = cdts.id
            WHERE 
                svcs.id_operador = ?
                AND svcs.id = (SELECT svcs2.id FROM servicios as svcs2 WHERE svcs2.id_operador = ? ORDER BY svcs2.id DESC LIMIT 1)
                AND rprts.status IN ('Pendiente', 'Completado')
            ORDER BY svcs.id DESC";

        $params[] = $operatorId;
        $params[] = $operatorId;
        $types .= "ii";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }

    /**
     * This PHP function retrieves products associated with a specific delivery ID from a database and
     * returns them as an array.
     * 
     * @param int deliveryId The `getProductsByDelivery` function retrieves product information based
     * on a delivery ID. The function executes a SQL query to select specific fields from the
     * `producto_reparto` and `productos` tables where the `reparto_id` matches the provided
     * `deliveryId`. The function then fetches
     * 
     * @return ?array An array of products associated with a specific delivery ID is being returned.
     * Each product in the array includes details such as ID, delivery ID, product ID, quantity,
     * weight, name, description, and key. If no data is found for the given delivery ID, null is
     * returned.
     */
    public function getProductsByDelivery(int $deliveryId): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT 
                    pdtrpt.id as id_producto_reparto,
                    pdtrpt.reparto_id,
                    pdtrpt.producto_id,
                    pdtrpt.cantidad,
                    pdtrpt.peso,
                    pdts.nombre,
                    pdts.descripcion,
                    pdts.clave,
                    cnts.id as id_cliente,
                    cnts.nombre_razon as nombre_cliente
                FROM producto_reparto as pdtrpt
                LEFT JOIN productos as pdts
                    ON pdtrpt.producto_id = pdts.id
                LEFT JOIN clientes as cnts
                    ON pdts.cliente_id = cnts.id
                WHERE pdtrpt.reparto_id = ?
                ORDER BY pdtrpt.id ASC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $deliveryId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }

    /**
     * This PHP function retrieves deliveries based on a specified service ID from a database table and
     * returns the results as an array.
     * 
     * @param int deliveryId The `getDeliveriesByService` function retrieves delivery information based
     * on the provided `deliveryId`. It executes a SQL query to select specific columns from the
     * `repartos` table where the `id_servicio` matches the given `deliveryId`. The function then
     * fetches the results and
     * 
     * @return ?array An array of deliveries with the specified service ID is being returned. Each
     * delivery includes the ID, service ID, delivery number, and status. If no deliveries are found
     * for the specified service ID, null is returned.
     */
    public function getDeliveriesByService(int $deliveryId): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT
            rprts.id as id_reparto,
            rprts.id_servicio,
            rprts.numero_reparto,
            rprts.status
        FROM repartos as rprts
        WHERE rprts.id_servicio = ?
        ORDER BY rprts.id ASC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $deliveryId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }

    /**
    * This PHP function retrieves delivery information by ID from a database table named "repartos".
    * 
    * @param int deliveryId The `getDeliveryById` function is a method that retrieves delivery
    * information from a database based on the provided `deliveryId`. The function executes a SQL query
    * to select all columns from the `repartos` table where the `id` matches the provided `deliveryId`.
    * It limits the result to
    * 
    * @return ?array The `getDeliveryById` function returns an array containing the delivery
    * information for the specified delivery ID. If no delivery is found for the given ID, it returns
    * `null`.
    */
    public function getDeliveryById(int $deliveryId): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT *
        FROM repartos as rprts
        WHERE rprts.id = ?
        ORDER BY rprts.id ASC
        LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $deliveryId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }

    /**
     * The function `update` takes an ID and an array of data, filters and validates the data,
     * generates a dynamic set clause for an SQL update query, binds the values dynamically, and
     * executes the query to update a record in the database table.
     * 
     * @param int id The `id` parameter in the `update` function represents the identifier of the
     * record you want to update in the database. It is an integer value that uniquely identifies the
     * record.
     * @param array data The `update` function you provided is a PHP method that updates a record in a
     * database table based on the given ID and data. The function performs several steps to construct
     * and execute the SQL update query.
     * 
     * @return bool The `update` function returns a boolean value (`true` or `false`) based on whether
     * the SQL update query was successfully executed.
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
}