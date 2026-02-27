<?php
require_once '../system/connection.php';

class UsuarioControlador
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
    }

    public function listar($page = 1, $limit = 10, $filtros = [])
    {
        $conexion = $this->db->getConexion();

        $offset = ($page - 1) * $limit;

        $where = "WHERE (u.estatus IS NULL OR u.estatus != 'eliminado')";
        $params = [];
        $types = "";

        // Filtro nombre
        if (!empty($filtros['nombre'])) {
            $where .= " AND CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
            $types .= "s";
        }

        // Filtro rol
        if (!empty($filtros['rol'])) {
            $where .= " AND u.idRol = ?";
            $params[] = $filtros['rol'];
            $types .= "i";
        }

        // Filtro fecha
        if (!empty($filtros['fecContratacion'])) {
            $where .= " AND u.fecContratacion = ?";
            $params[] = $filtros['fecContratacion'];
            $types .= "s";
        }

        $sql = "SELECT u.id,
            CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) AS nombreCompleto,
            u.email,
            u.movil,
            u.cedis,
            u.puesto,
            cr.rol_descripcion
            FROM usuarios u
            LEFT JOIN cat_rol cr ON u.idRol = cr.id
            $where
            ORDER BY u.id DESC
            LIMIT ?, ?
        ";

        $params[] = $offset;
        $params[] = $limit;
        $types .= "ii";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'id' => $row['id'],
                'nombreCompleto' => $row['nombreCompleto'],
                'email' => $row['email'] ?? 'N/A',
                'movil' => $row['movil'] ?? 'N/A',
                'cedis' => $row['cedis'] ?? 'N/A',
                'puesto' => $row['puesto'] ?? 'N/A',
                'rol_descripcion' => $row['rol_descripcion'],
            ];
        }

        return $data;
    }
    public function show($id)
    {
        $conexion = $this->db->getConexion();

        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            return ["error" => $conexion->error];
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        return $usuario;
    }
    public function totalRegistros($filtros = [])
    {
        $conexion = $this->db->getConexion();

        $where = "WHERE (u.estatus IS NULL OR u.estatus != 'eliminado')";
        $params = [];
        $types = "";

        // 🔎 Filtro nombre
        if (!empty($filtros['nombre'])) {
            $where .= " AND CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
            $types .= "s";
        }

        // 🔎 Filtro rol
        if (!empty($filtros['rol'])) {
            $where .= " AND u.idRol = ?";
            $params[] = $filtros['rol'];
            $types .= "i";
        }

        // 🔎 Filtro fecha
        if (!empty($filtros['fecContratacion'])) {
            $where .= " AND u.fecContratacion = ?";
            $params[] = $filtros['fecContratacion'];
            $types .= "s";
        }

        $sql = "SELECT COUNT(*) as total
        FROM usuarios u
        LEFT JOIN cat_rol cr ON u.idRol = cr.id
        $where
    ";

        $stmt = $conexion->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function crear($data)
    {

        $sql = "INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, fecNac, movil, telefono, noEmpleado,
                puesto, area, cedis, jefeInmediato, fecContratacion, diasVacaciones, diasVacDisfrutados, password, idRol, estatus)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->execute($sql, [
            $data['nombre'],             //1
            $data['apellidoP'],          //2
            $data['apellidoM'],          //3
            $data['email'],              //4
            $data['fecNac'],             //5
            $data['movil'],              //6
            $data['telefono'],           //7
            $data['noEmpleado'],         //8 
            $data['puesto'],             //9
            $data['area'],               //10
            $data['cedis'],              //11
            $data['jefeInmediato'],      //12
            $data['fecContratacion'],    //14
            $data['diasVacaciones'],     //15
            $data['diasVacDisfrutados'], //16
            $data['diasVacDisfrutados'], //17
            password_hash($data['password'], PASSWORD_BCRYPT), //18
            $data['idRol'],              //18
            $data['estatus'],            //19
        ]);
    }
    public function actualizar($id, $data)
    {
        $campos = [
            "nombre = ?",
            "apellidoP = ?",
            "apellidoM = ?",
            "email = ?",
            "fecNac = ?",
            "movil = ?",
            "telefono = ?",
            "noEmpleado = ?",
            "puesto = ?",
            "area = ?",
            "cedis = ?",
            "jefeInmediato = ?",
            "fecContratacion = ?",
            "diasVacaciones = ?",
            "diasVacDisfrutados = ?"
        ];

        $params = [
            $data['nombre'],
            $data['apellidoP'],
            $data['apellidoM'],
            $data['email'],
            $data['fecNac'],
            $data['movil'],
            $data['telefono'],
            $data['noEmpleado'],
            $data['puesto'],
            $data['area'],
            $data['cedis'],
            $data['jefeInmediato'],
            $data['fecContratacion'],
            $data['diasVacaciones'],
            $data['diasVacDisfrutados']
        ];

        // Solo si viene password
        if (!empty($data['password'])) {
            $campos[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $campos[] = "estatus = ?";
        $params[] = $data['estatus'];

        // WHERE
        $params[] = $id;

        $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }
    public function eliminar($id)
    {
        $sql = "UPDATE usuarios SET estatus='eliminado' WHERE id=?";
        return $this->db->execute($sql, [$id]);
    }
}
