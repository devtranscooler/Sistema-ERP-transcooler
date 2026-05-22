<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class Service
{
    private MySQL $db;

    private string $table = "servicios";

    private array $fillable = [
        'id',
        'id_cliente',
        'id_usuario_alta',
        'fec_alta',
        'shipment',
        'tipo_viaje',
        'num_repartos',
        'fecha_carga',
        'fecha_descarga',
        'tipo_servicio',
        'status',
        'id_unidad',
        'id_operador',
        'tracking',
        'id_remolque',
        'id_remolque2',
        'id_dolly',
        'config_vehicular',
        'km_origen_destino',
        'monto_total',
        'tipo_permiso',
        'folio_permiso',
        'status_operativo'
    ];

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }

    /**
     * This PHP function retrieves data related to a carta porte (freight document) based on the
     * provided ID.
     * 
     * @param int id The `getCartaPorteData` function retrieves data related to a carta porte (freight
     * document) based on the provided `id`. The SQL query fetches various details such as service ID,
     * dates, shipment information, client details, operator details, and unit details associated with
     * the carta porte
     * 
     * @return ?array The function `getCartaPorteData` is returning an associative array of data
     * related to a specific carta porte (freight document) based on the provided ID. The data includes
     * information such as service details, client information, operator details, and unit details. If
     * the data is found, it will be returned as an array. If no data is found for the provided ID, it
     * will return
     */
    public function getCartaPorteData(int $id): ?array
    {
        $connection = $this->db->getConexion();

            $sql = "SELECT 
                svcs.id as servicio_id,
                svcs.id_usuario_alta,
                svcs.fec_alta as fecha_alta,
                svcs.shipment,
                svcs.tipo_viaje,
                svcs.num_repartos,
                svcs.fecha_carga,
                svcs.fecha_descarga,
                svcs.tipo_servicio,
                svcs.status,
                svcs.id_unidad,
                svcs.id_operador,
                svcs.tracking,
                cnts.nombre_razon as nombre_cliente,
                CONCAT(cnts.calle, ', ', cnts.num_ext, ', ', cnts.codigo_postal) as direccion_cliente,
                cnts.rfc as rfc_cliente,
                CONCAT(urs.nombre, ' ', urs.apellidoP, ' ', urs.apellidoM) as nombre_operador,
                catuds.placas as placas_unidad,
                catuds.eco,
                catuds.anio,
                catuds.capacidad,
                catuds.cobertura
            FROM servicios as svcs
            LEFT JOIN usuarios as urs
                ON svcs.id_operador = urs.id
            LEFT JOIN cat_unidades as catuds
                ON svcs.id_unidad = catuds.id
            LEFT JOIN clientes as cnts
                ON svcs.id_cliente = cnts.id 
            WHERE svcs.id = ?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_assoc();

            return $data ?? null;
    }


    /**
     * This PHP function retrieves a route address based on a service ID and route type (origin or
     * destination) from a database table.
     * 
     * @param int serviceId The `serviceId` parameter is an integer that represents the ID of a service
     * for which you want to retrieve the route address.
     * @param string routeType The `routeType` parameter in the `getRouteAddress` function determines
     * whether to fetch the route address for the origin or destination of a service based on the
     * provided service ID.
     * 
     * @return The function `getRouteAddress` returns an associative array containing the data of a
     * route from the `repartos` table based on the provided `` and ``. The array
     * includes the route's `id`, `id_servicio`, `origen_inicio`, and `destino_final` fields. If no
     * data is found, it returns `null`.
     */
    public function getRouteAddress(int $serviceId, string $routeType)
    {
        $connection = $this->db->getConexion();
        $where = ["rpts.id_servicio = ?"];
        
        if($routeType === 'origen') {
            $where[] = "rpts.origen_inicio IS NOT NULL";
            $where[] = "rpts.destino_final IS NULL";
        }

        if($routeType === 'destino') {
            $where[] = "rpts.origen_inicio IS NULL";
            $where[] = "rpts.destino_final IS NOT NULL";
        }

        $sql = "SELECT 
            rpts.id,
            rpts.id_servicio,
            rpts.origen_inicio,
            rpts.destino_final
        FROM repartos as rpts
        WHERE " . implode(" AND ", $where) . "
        ORDER BY rpts.id ASC
        LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        return $data ?? null;
    }

    /**
     * This PHP function retrieves a service by its ID from a database and returns the data as an array
     * or null if no data is found.
     * 
     * @param int serviceId The `getServiceById` function is a method that retrieves a service from the
     * database based on the provided `serviceId`. It connects to the database, prepares and executes a
     * SQL query to select the service with the specified ID, and then fetches the result into an
     * array.
     * 
     * @return ?array An array containing the service data for the service with the specified ID is
     * being returned. If no data is found for the given service ID, then `null` is returned.
     */
    public function getServiceById(int $serviceId): ?array
    {
        $connection = $this->db->getConexion();

        $sql = "SELECT *
        FROM servicios as srvcs
        WHERE srvcs.id = ?
        ORDER BY srvcs.id ASC
        LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?? null;
    }

    /**
     * The function `update` updates a record in a database table with the provided data based on the
     * specified ID.
     * 
     * @param int id The `id` parameter in the `update` function represents the identifier of the
     * record you want to update in the database. It is an integer value that uniquely identifies the
     * record within the table.
     * @param array data The `update` function you provided is a PHP method that updates a record in a
     * database table based on the given ID and data. The function performs the following steps:
     * 
     * @return bool The `update` function returns a boolean value indicating whether the update
     * operation was successful or not. It returns `true` if the update was executed successfully, and
     * `false` if there were no fields to update or if an error occurred during the execution of the
     * update query.
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