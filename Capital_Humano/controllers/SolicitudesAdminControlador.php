<?php

session_start();

require_once __DIR__ . '/../models/SolicitudesModelo.php';

header('Content-Type: application/json');

$body = json_decode(
    file_get_contents('php://input'),
    true
);

$accion = '';

if(isset($_GET['accion'])){

    $accion = $_GET['accion'];

}elseif(isset($_POST['accion'])){

    $accion = $_POST['accion'];

}elseif(isset($body['accion'])){

    $accion = $body['accion'];

}



switch($accion){

    case 'listar':
        listarSolicitudes();
    break;

    case 'detalle':
        detalleSolicitud();
    break;

    case 'procesar':
        procesarSolicitud();
    break;

    default:

        echo json_encode([
            'success' => false,
            'message' => 'Acción no válida'
        ]);

    break;
}


/* ==========================================
   LISTAR
========================================== */

function listarSolicitudes(){

    $estatus  = $_GET['estatus'] ?? '';
    $tipo     = $_GET['tipo'] ?? '';
    $operador = $_GET['operador'] ?? '';

    $resultado =
        SolicitudesModelo::obtenerSolicitudesAdmin(
            $estatus,
            $tipo,
            $operador
        );

    $solicitudes = [];

    while($row = $resultado->fetch_assoc()){

        $solicitudes[] = $row;
    }

    $indicadoresResult =
        SolicitudesModelo::obtenerIndicadores();

    $indicadores =
        $indicadoresResult->fetch_assoc();

    echo json_encode([
        'solicitudes' => $solicitudes,
        'indicadores' => $indicadores
    ]);
}


/* ==========================================
   DETALLE
========================================== */

function detalleSolicitud(){

    $id =
        $_GET['id'] ?? 0;

    $resultado =
        SolicitudesModelo::obtenerSolicitudPorId($id);

    $solicitud =
        $resultado->fetch_assoc();

    echo json_encode(
        $solicitud
    );
}


/* ==========================================
   PROCESAR
========================================== */

function procesarSolicitud(){

    $body =
        json_decode(
            file_get_contents('php://input'),
            true
        );

    $datos = [

        'id_solicitud' =>
            $body['id'],

        'estatus' =>
            $body['estatus'],

        'comentario_admin' =>
            $body['comentario'],

        'autorizado_por' =>
            $_SESSION['ID_USUARIO']

    ];

    $respuesta =
        SolicitudesModelo::actualizarEstatus(
            $datos
        );

    echo json_encode([

        'success' =>
            $respuesta['status'],

        'message' =>
            $respuesta['mensaje']

    ]);
}