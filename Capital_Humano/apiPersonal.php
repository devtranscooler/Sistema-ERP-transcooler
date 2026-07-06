<?php

/* ==========================================
   CONFIGURACIÓN DE ERRORES
========================================== */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ==========================================
   HEADERS
========================================== */
header('Content-Type: application/json; charset=utf-8');

/* ==========================================
   DEPENDENCIAS
========================================== */
require_once __DIR__ . '/PersonalControlador.php';

$controller = new PersonalControlador();

/* ==========================================
   OBTENER ACCIÓN
========================================== */
$action = $_POST['action'] ?? $_GET['action'] ?? '';

/* ==========================================
   RESPUESTA BASE
========================================== */
$response = [
    "status" => "error",
    "message" => "Acción no válida"
];

try {

    switch ($action) {

        /* ==========================================
           CREAR EMPLEADO
        ========================================== */
        case "guardar":

            $resultado = $controller->crear($_POST);

            if ($resultado['success']) {

                $empleado_id = $resultado['empleado_id'];

                /* =========================
                GUARDAR DOMICILIO
                ========================= */

                if(!empty($_POST['id_cp'])){

                    $domicilio = [

                        "id_empleado" => $empleado_id,
                        "id_cp" => $_POST['id_cp'],

                        "calle" => $_POST['calle'] ?? '',
                        "numero_exterior" => $_POST['numero_exterior'] ?? '',
                        "numero_interior" => $_POST['numero_interior'] ?? '',
                        "referencia" => $_POST['referencia'] ?? ''

                    ];

                    $controller->guardarDomicilio($domicilio);
                }

                $response = [
                    "status" => "ok",
                    "message" => "Empleado guardado correctamente",
                    "empleado_id" => $empleado_id
                ];

            } else {

                $response = [
                    "status" => "error",
                    "message" => $resultado['error']
                ];
            }

        break;


        /* ==========================================
           ACTUALIZAR EMPLEADO
        ========================================== */
        case "actualizar":

            file_put_contents(
                "debug_actualizar.txt",
                print_r($_POST, true)
            );
            
            if (empty($_POST['id'])) {
                throw new Exception("ID de empleado requerido");
            }

            

            $resultado = $controller->actualizar($_POST);

            $response = $resultado;

        break;


        /* ==========================================
           LISTAR EMPLEADOS
        ========================================== */
        case "listar":

            $nombre = $_GET['nombre'] ?? '';
            $departamento = $_GET['departamento'] ?? '';
            $estatus = $_GET['estatus'] ?? '';

            $sort = $_GET['sort'] ?? 'numero';
            $order = $_GET['order'] ?? 'DESC';

            $data = $controller->listar(
                $nombre,
                $departamento,
                $estatus,
                $sort,
                $order
            );

            $response = [
                "status" => "ok",
                "data" => $data
            ];

        break;


        /* ==========================================
           OBTENER EMPLEADO
        ========================================== */
        case "obtener":

            if (empty($_GET['id'])) {
                throw new Exception("ID requerido");
            }

            $id = intval($_GET['id']);

            $data = $controller->obtenerEmpleado($id);

            $response = [
                "status" => "ok",
                "data" => $data
            ];

        break;


        /* ==========================================
           GUARDAR DOCUMENTO (NUEVO)
        ========================================== */
        case "guardarDocumento":

            if (empty($_POST['id_empleado'])) {
                throw new Exception("ID de empleado requerido");
            }

            if (empty($_POST['tipo_documento_id'])) {
                throw new Exception("Tipo de documento requerido");
            }

            if (empty($_POST['folio'])) {
                throw new Exception("Folio requerido");
            }

            $data = [
                "id_empleado" => intval($_POST['id_empleado']),
                "tipo_documento_id" => intval($_POST['tipo_documento_id']),
                "folio" => trim($_POST['folio']),
                "fecha_vencimiento" => $_POST['fecha_vencimiento'] ?? null,
                "tipo_licencia" => $_POST['tipo_licencia'] ?? null
            ];

            /* Validación específica */
            if ($data["tipo_documento_id"] == 1 && empty($data["tipo_licencia"])) {
                throw new Exception("El tipo de licencia es obligatorio");
            }

            $resultado = $controller->guardarDocumento($data);

            if ($resultado['success']) {
                $response = [
                    "status" => "ok",
                    "message" => "Documento guardado correctamente"
                ];
            } else {
                $response = [
                    "status" => "error",
                    "message" => $resultado['error']
                ];
            }

        break;


        /* ==========================================
           CATÁLOGOS
        ========================================== */
        case "catalogos":

            $response = [
                "status" => "ok",
                "departamentos" => $controller->catalogoDepartamentos(),
                "estatus" => $controller->catalogoEstatus()
            ];

        break;


        /* ==========================================
           ELIMINAR EMPLEADO
        ========================================== */
        case "eliminar":

            if (empty($_POST['id'])) {
                throw new Exception("ID requerido para eliminar");
            }

            $id = intval($_POST['id']);

            $resultado = $controller->eliminar($id);

            $response = $resultado;

        break;

        /* ==========================================
           LISTAR DOCUMENTOS EMPLEADO
        ========================================== */    

        case "listarDocumentos":
            if (!isset($_GET['id_empleado'])) {
                throw new Exception("ID requerido");
            }

            $data = $controller->listarDocumentos($_GET['id_empleado']);

            $response = [
                "status" => "ok",
                "data" => $data
            ];
        break;

        /* ==========================================
           ACTUALIZAR DOCUMENTOS EMPLEADO
        ========================================== */    

        case "actualizarDocumento":

            if (!isset($_POST['id_documento'])) {
                throw new Exception("ID documento requerido");
            }

            $response = $controller->actualizarDocumento($_POST);

        break;

        case "buscarEstados":

            $term = $_GET['term'] ?? '';

            $data = $controller->buscarEstados($term);

            $response = [
                "status"=>"ok",
                "data"=>$data
            ];

        break;

        case "cambiarEstatus":

            $idEmpleado = $_POST['id_empleado'] ?? null;
            $idEstatus = $_POST['id_estatus'] ?? null;

            if(!$idEmpleado || !$idEstatus){
                throw new Exception("Datos incompletos");
            }

            $response = $controller->cambiarEstatus($idEmpleado, $idEstatus);

        break;

        case "buscarCP":

            $cp = $_GET['cp'] ?? '';

            $response = $controller->buscarCP($cp);

        break;

        
        case 'obtenerDocumento':

            if (empty($_GET['id_documento'])) {
                throw new Exception("ID documento requerido");
            }

            $id = intval($_GET['id_documento']);

            $data = $controller->obtenerDocumento($id);

            $response = $data;

        break;


    }

} catch (Throwable $e) {

    $response = [
        "status" => "error",
        "message" => $e->getMessage()
    ];

}

/* ==========================================
   RESPUESTA FINAL
========================================== */
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;