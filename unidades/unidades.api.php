<?php
require_once "UnidadesControlador.php";

header('Content-Type: application/json');

$controlador = new UnidadesControlador();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'eco' => $_POST['eco'] ?? null,
        ];

        echo json_encode([
            'data' => $controlador->listar($page, $limit, $filtros),
            'total' => $controlador->totalRegistros($filtros),
        ]);

        break;

    case 'find':
        $id = $_POST['id'];
        $unidad = $controlador->show($id);
        if ($unidad) {
            echo json_encode([
                'data' => $unidad,
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'data' => $null,
                'success' => false,
                'messague' => 'Unidad no encontrada'
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

    case 'buscar_unidades':
        $term = $_POST['term'] ?? '';

        echo json_encode([
            'data' => $controlador->buscarUnidades($term)
        ]);
        break;
    
    case 'find_unidad':
        $id = $_POST['id'];
        $unidad = $controlador->show($id);
        echo json_encode([
            'data' => [
                'id'  => $unidad['id'],
                'eco' => $unidad['eco']
            ]
        ]);
        break;
    
    case 'buscar_remolques':
        $term = $_POST['term'] ?? '';
        $data = $controlador->buscarRemolques($term);
        echo json_encode(['data' => $data]);
        break;

    case 'find_remolque':
        $id   = $_POST['id'] ?? null;
        $unidad = $controlador->show($id);
        echo json_encode([
            'data' => [
                'id'  => $unidad['id'],
                'eco' => $unidad['eco']
            ]
        ]);
        break;

    case 'buscar_dollys':
        $term = $_POST['term'] ?? '';
        $data = $controlador->buscarDollys($term);
        echo json_encode(['data' => $data]);
        break;

    case 'find_dolly':
        $id   = $_POST['id'] ?? null;
        $unidad = $controlador->show($id);
        echo json_encode([
            'data' => [
                'id'  => $unidad['id'],
                'eco' => $unidad['eco']
            ]
        ]);
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "post" => $_POST
        ]);
        break;
}
