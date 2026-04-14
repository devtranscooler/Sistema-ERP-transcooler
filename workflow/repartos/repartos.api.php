<?php
require_once "repartosControlador.php";

header('Content-Type: application/json');

$controlador = new repartosControlador();
$action = $_POST['action'] ?? '';

switch ($action) {   
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
