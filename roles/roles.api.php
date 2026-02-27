<?php
require_once "RolesControlador.php";

header('Content-Type: application/json');

$controlador = new RolesControlador(); 
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'nombre_rol' => $_POST['rol_descripcion'] ?? null,
        ];

        echo json_encode([
            'data' => $controlador->listar($page, $limit, $filtros),
            'total' => $controlador->totalRegistros($filtros),
        ]);

        break;

    case 'find':
        $id = $_POST['id'];
        $rol = $controlador->show($id);
        if ($rol) {
            echo json_encode([
                'data' => $rol,
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'data' => null,
                'success' => false,
                'messague' => 'Rol no encontrado'
            ]);
        }
        break;

    case 'crear':
        echo json_encode([
            'success' => $controlador->crear($_POST),
        ]);
        break;


    case 'actualizar':
        $id = $_POST['id'];
        echo json_encode([
            'success' => $controlador->actualizar($id, $_POST),
        ]);
        break;

    case 'eliminar':
        $id = $_POST['id'];
        echo json_encode([
            'success' => $controlador->eliminar($id),
        ]);
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "post" => $_POST
        ]);
        break;
}
