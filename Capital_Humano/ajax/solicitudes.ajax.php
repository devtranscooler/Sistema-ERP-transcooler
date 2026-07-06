<?php

session_start();

//echo '<pre>';
//print_r($_SESSION);
//exit;

header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/SolicitudesControlador.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($_POST['accion'] == 'crear'){

        $_POST['id_usuario'] = $_SESSION['ID_USUARIO'];

        $respuesta = SolicitudesControlador::crearSolicitud($_POST);

        echo json_encode($respuesta);
        exit;
    }


    if($_POST['accion'] == 'autorizar'){

        $respuesta = SolicitudesControlador::autorizarSolicitud($_POST);

        echo json_encode($respuesta);
        exit;
    }


    if($_POST['accion'] == 'rechazar'){

        $respuesta = SolicitudesControlador::rechazarSolicitud($_POST);

        echo json_encode($respuesta);
        exit;
    }
}