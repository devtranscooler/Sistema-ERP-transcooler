<?php
require_once "ClientesControlador.php";

header('Content-Type: application/json');

$controlador = new ClientesControlador();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'razon_social' => $_POST['razon_social'] ?? null,
        ];

        echo json_encode([
            'data' => $controlador->listar($page, $limit, $filtros),
            'total' => $controlador->totalRegistros($filtros),
        ]);

        break;

    case 'find':
        $id = $_POST['id'];
        $cliente = $controlador->show($id);
        if ($cliente) {
            echo json_encode([
                'data' => $cliente,
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'data' => $null,
                'success' => false,
                'messague' => 'Cliente no encontrado'
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
