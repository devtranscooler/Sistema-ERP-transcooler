<?php
require_once "DestinosControlador.php";

header('Content-Type: application/json');

$controlador = new DestinosControlador();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'nombre' => $_POST['nombre'] ?? null,
        ];

        echo json_encode([
            'data' => $controlador->listar($page, $limit, $filtros),
            'total' => $controlador->totalRegistros($filtros),
        ]);

        break;

    case 'find':
        $id = $_POST['id'];
        $destino = $controlador->show($id);
        if ($destino) {
            echo json_encode([
                'data' => $destino,
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'data' => $null,
                'success' => false,
                'messague' => 'Destino no encontrado'
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

    case 'buscar_destinos':
        $term = $_POST['term'] ?? '';

        echo json_encode([
            'data' => $controlador->buscarDestinos($term)
        ]);
        break;
    
    case 'find_destino':
        $id = $_POST['id'];
        $destino = $controlador->show($id);
        echo json_encode([
            'data' => [
                'id'  => $destino['id'],
                'nombre' => $destino['nombre']
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
