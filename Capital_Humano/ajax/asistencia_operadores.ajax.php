<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

$db = new MySQL();

header('Content-Type: application/json');

// 🔹 MÉTODO GET → LISTAR OPERADORES
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $hoy = date('Y-m-d');

    $sql = "
        SELECT 
            u.id,
            u.noEmpleado,
            CONCAT(u.nombre, ' ', u.apellidoP) AS nombre,
            a.estatus,
            a.id_viaje
        FROM usuarios u

        INNER JOIN empleado_puesto_historial eph 
            ON eph.id_empleado = u.id
            AND eph.fecha_fin IS NULL

        LEFT JOIN asistencia_operadores a 
            ON a.id_usuario = u.id 
            AND a.fecha = '$hoy'

        WHERE eph.id_departamento = 1

        ORDER BY nombre
    ";

    $rs = $db->consulta($sql);

    $data = [];

    while ($row = $db->fetch_array($rs)) {
        $data[] = [
            "id" => $row['id'],
            "noEmpleado" => $row['noEmpleado'],
            "nombre" => $row['nombre'],
            "estatus" => $row['estatus'],
            "id_viaje" => $row['id_viaje']
        ];
    }

    echo json_encode($data);
    exit;
}


// 🔹 MÉTODO POST → ACTUALIZAR ESTATUS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_usuario = intval($_POST['id_usuario'] ?? 0);
    $estatus = $_POST['estatus'] ?? '';

    if ($id_usuario <= 0 || $estatus == '') {
        echo json_encode([
            "status" => "error",
            "msg" => "Datos inválidos"
        ]);
        exit;
    }

    $hoy = date('Y-m-d');

    $estatus = $db->escape_string($estatus);

    $sql = "
        INSERT INTO asistencia_operadores
        (id_usuario, fecha, estatus, origen_registro)
        VALUES
        ($id_usuario, '$hoy', '$estatus', 'MANUAL')
        ON DUPLICATE KEY UPDATE
            estatus = '$estatus',
            origen_registro = 'MANUAL',
            updated_at = NOW()
    ";

    $ok = $db->consulta($sql);

    if ($ok) {
        echo json_encode([
            "status" => "ok"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "Error al guardar"
        ]);
    }

    exit;
}