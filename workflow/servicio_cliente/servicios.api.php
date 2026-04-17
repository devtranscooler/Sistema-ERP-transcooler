<?php
require_once "serviciosControlador.php";
require_once __DIR__ . '/../repartos/repartosControlador.php';

header('Content-Type: application/json');

$controlador = new serviciosControlador();
$repartosControlador = new repartosControlador();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $page  = $_POST['page'] ?? 1;
        $limit = $_POST['limit']  ?? 10;
        $filtros = [
            'filtroIdServicio' => $_POST['filtroIdServicio'] ?? null,
            'filtroIdServicioMain' => $_POST['filtroIdServicioMain'] ?? null,
            'filtroIdServicioTrafico' => $_POST['filtroIdServicioTrafico'] ?? null,
            'filtroIdServicioSalida' => $_POST['filtroIdServicioSalida'] ?? null,
        ];
        $context = $_POST['context'] ?? null;
        
        echo json_encode([
            'data' => $controlador->listar($page, $limit, $filtros, $context),
            'total' => $controlador->totalRegistros($filtros),
        ]);

        break;

    case 'find':
        $id = $_POST['id'];
        $servicio = $controlador->show($id);
        if ($servicio) {
            echo json_encode([
                'data' => $servicio,
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'data' => null,
                'success' => false,
                'messague' => 'Servicio no encontrado'
            ]);
        }
        break;

    case 'crear':
        $id_servicio = $controlador->crearRetornandoId($_POST);

        if ($id_servicio) {
            $origen = $_POST['origen'] ?? "";
            $destinos = $_POST['id_destino'] ?? [];

            $destinoAnterior = null;

            foreach ($destinos as $numero => $id_destino) {
                if (!$id_destino) continue;

                $data = [
                    'id_servicio'    => $id_servicio,
                    'numero_reparto' => $numero + 1,
                    'id_destino'     => $id_destino,
                    'id_origen'      => null,
                    'origen_inicio'  => null,
                ];

                if ($numero == 0) {
                    // Primer reparto
                    $data['origen_inicio'] = $origen;
                } else {
                    // Siguientes repartos
                    $data['id_origen'] = $destinoAnterior;
                }

                $repartosControlador->crear($data);

                // Guardar el destino actual como "anterior"
                $destinoAnterior = $id_destino;
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el servicio']);
        }
    break;

    case 'actualizar':
        $id = $_POST['id'];
        $ok = $controlador->actualizar($id, $_POST);

        if ($ok) {
            $repartosControlador->eliminarPorServicio($id);

            $destinos = $_POST['id_destino'] ?? [];
            foreach ($destinos as $numero => $id_destino) {
                if (!$id_destino) continue;
                $repartosControlador->crear([
                    'id_servicio'    => $id,
                    'numero_reparto' => $numero + 1,
                    'id_destino'     => $id_destino,
                ]);
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el servicio']);
        }
        break;

    case 'eliminar':
        $id = $_POST['id'];
        echo json_encode([
            'success' => $controlador->eliminar($id),
        ]);
        break;

    case 'agregarOperadorUnidad':
        $id = $_POST['id'];
        echo json_encode([
            'success' => $controlador->agregarOperadorUnidad($id, $_POST),
        ]);
        break;

    case 'actualizarTracking':
        $id = $_POST['id'];
        $tracking = $_POST['tracking'];
        echo json_encode([
            'success' => $controlador->actualizarTracking($id, $tracking),
        ]);
        break;

    case 'getMediaFilesByModule':
        echo json_encode([
            'success' => $controlador->mediaFilesByModuleId($_POST),
        ]);
        break;

    case 'cargarArchivos':
        echo json_encode($controlador->uploadFiles($_POST, $_FILES));
        break;
        
    default:
        echo json_encode([
            "error" => "Acción no válida",
            "post" => $_POST
        ]);
        break;
}
