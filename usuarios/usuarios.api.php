<?php
require_once 'UsuarioControlador.php';

header('Content-Type: application/json');

$controlador = new UsuarioControlador();
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'listar':

        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'nombre' => $_POST['nombre'] ?? null,
            'rol' => $_POST['rol'] ?? null,
            'fecContratacion' => $_POST['fecContratacion'] ?? null 
        ];


        echo json_encode([
            "data" => $controlador->listar($page, $limit, $filtros),
            "total" => $controlador->totalRegistros($filtros)
        ]);
        break;

    case 'find':
        $id = $_POST['id'];
        $usuario = $controlador->show($id);

        // Validamos si se encontró el usuario
        if ($usuario) {
            echo json_encode([
                'data' => $usuario,
                'success' => true
            ]);
        } else {
            echo json_encode([
                'data' => null,
                'success' => false,
                'message' => 'controlador no encontrado'
            ]);
        }
        break;

    case 'crear':

        echo json_encode([
            "success" => $controlador->crear($_POST)
        ]);
        break;

    case 'actualizar':

        $id = $_POST['id'];
        echo json_encode([
            "success" => $controlador->actualizar($id, $_POST)
        ]);
        break;

    case 'eliminar':

        $id = $_POST['id'];
        echo json_encode([
            "success" => $controlador->eliminar($id)
        ]);
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "post" => $_POST
        ]);
        break;
}
