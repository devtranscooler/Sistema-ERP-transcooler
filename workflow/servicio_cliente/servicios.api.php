<?php
require_once "serviciosControlador.php";
require_once __DIR__ . '/../repartos/repartosControlador.php';
require_once __DIR__ . '/../producto_reparto/repartoProductoControlador.php';
require_once __DIR__ . '/../../destinos/DestinosControlador.php';

header('Content-Type: application/json');

$controlador = new serviciosControlador();
$repartosControlador = new repartosControlador();
$repartoProductoControlador = new repartoProductoControlador();
$destinosControlador = new DestinosControlador();
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
            $origen   = $_POST['origen'] ?? "";
            $destinos = $_POST['id_destino'] ?? [];
            $destinoAnterior = null;

            // Obtener último destino
            $ultimoDestinoId = end($destinos);

            // Consultar destino completo
            $destinoFinalTexto = null;
            if ($ultimoDestinoId) {
                // Ajusta esto según tu controlador/modelo
                $destinoInfo = $destinosControlador->show($ultimoDestinoId);
                
                if ($destinoInfo) {
                    $direccion = [
                        $destinoInfo['calle'] ?? '',
                        $destinoInfo['numero_exterior'] ?? '',
                        $destinoInfo['numero_interior'] ?? '',
                        $destinoInfo['municipio'] ?? '',
                        $destinoInfo['ciudad'] ?? '',
                        $destinoInfo['pais'] ?? '',
                        $destinoInfo['codigo_postal'] ?? '',
                    ];
                    // Eliminar vacíos
                    $direccion = array_filter($direccion);
                    // Convertir a texto
                    $destinoFinalTexto = implode(', ', $direccion);                    
                }
            }

            foreach ($destinos as $numero => $id_destino) {
                if (!$id_destino) continue;
                $data = [
                    'id_servicio'    => $id_servicio,
                    'numero_reparto' => $numero + 1,
                    'id_destino'     => $id_destino,
                    'id_origen'      => null,
                    'origen_inicio'  => null,
                    'destino_final'  => null,
                ];
                if ($numero == 0) {
                    // Primer reparto
                    $data['origen_inicio'] = $origen;
                } else {
                    // Siguientes repartos
                    $data['id_origen'] = $destinoAnterior;
                }
                // Si es el último reparto
                if ($id_destino == $ultimoDestinoId) {
                    $data['destino_final'] = $destinoFinalTexto;
                }
                $repartosControlador->crear($data);
                // Guardar destino actual
                $destinoAnterior = $id_destino;
            }
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear el servicio'
            ]);
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
        $servicioId = $_POST['id'];
        $tracking = 'Asignación de productos';
        $controlador->agregarOperadorUnidad($servicioId, $_POST);
        echo json_encode([
            'success' => $controlador->actualizarTracking($servicioId, $tracking),
        ]);
        break;

    case 'actualizarTracking':
        $id = $_POST['id'];
        $tracking = $_POST['tracking'];
        echo json_encode([
            'success' => $controlador->actualizarTracking($id, $tracking),
        ]);
        break;

    case 'agregarProductosPermisos':
        try {
            //Agregar detalles de trayecto
            $servicioId = $_POST['servicio_id'];
            $tracking = 'En espera de salida';
            $data = [
                'km_origen_destino'  => $_POST['kilometros'],
                'monto_total' => $_POST['montoFlete'],
                'tipo_permiso'    => $_POST['tipoPermiso'],
                'folio_permiso'        => $_POST['folioPermiso'],
            ];
            $controlador->agregarProductosPermisos($servicioId, $data);

            //Crear registros de repartos y sus roductos
            $productos = $_POST['productos'] ?? [];
            foreach ($productos as $repartoId => $items) {
                foreach ($items as $producto) {
                    $repartoProductoControlador->crear([
                        'reparto_id'  => $producto['reparto_id'],
                        'producto_id' => $producto['producto_id'],
                        'cantidad'    => $producto['cantidad'],
                        'peso'        => $producto['peso'],
                    ]);
                }
            }
            echo json_encode([
                'success' => $controlador->actualizarTracking($servicioId, $tracking)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    break;

    case 'buscar_shipments':
        $term = $_POST['term'] ?? '';
        
        echo json_encode([
            'data' => $controlador->buscarShipment($term)
        ]);
        break;

    case 'buscar_servicio_operador':
        $idOperador = $_POST['id_operador'] ?? '';
        
        echo json_encode([
            'data' => $controlador->buscarServicioOperador($idOperador)
        ]);
        break;
        
    default:
        echo json_encode([
            "error" => "Acción no válida",
            "post" => $_POST
        ]);
        break;
}
