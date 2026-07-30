<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/ProductController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/MediaController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/DeliveryController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/CartaPorteController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/DeliveryReassignmentController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/DeliveryOperatorController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/OperatorController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/FileManagerController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/UserController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/ControlConsoleController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/StageServiceController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/StatusStageServiceController.php';

/** Product Routes */
$router->get('/api/products', function () {
    return (new ProductController())->index($_GET);
});
/** Product Routes */

/** Media Routes */
$router->get('/api/media', function() {
    return (new MediaController())->index($_GET);
});

$router->post('/api/media', function() {
    return (new MediaController())->upload($_POST, $_FILES);
});

$router->delete('/api/media/{id}', function($id) {
    return (new MediaController())->delete((int) $id);
});
/** End Media Routes */

/** Servicios Routes */
$router->get('/api/deliveries/{serviceId}', function($id) {
    return (new DeliveryController())->getDeliveriesByService((int) $id);
});

$router->put('/api/deliveries/{deliveryId}', function($id) use($router) {
    return (new DeliveryController())->updateDelivery((int) $id, $router->getJsonBody());
});
/** Termina Servicios Routes */

/** Carta porte Routes */
$router->get('/api/carta-porte/{service_id}', function($id) {
    return (new CartaPorteController())->generate((int) $id);
});
/** Carta Porte Routes */

/** Repartos */
    $router->get('/api/reasignacion-repartos', function() {
        return (new DeliveryReassignmentController())->index();
    });

    /** Repartos operador  */
    $router->get('/api/operator-deliveries/{opetaorId}', function($id) {
        return (new DeliveryOperatorController())->index((int) $id);
    });

    /** Productos por reparto */
    $router->get('/api/products-delivery/{deliveryId}', function($id) {
        return (new DeliveryOperatorController())->productByDelivery((int) $id);
    });
/** End Repartos  */

/** Operadores */
    $router->get('/api/operators', function() {
        return (new OperatorController())->index($_GET);
    });
/** Termina Operadores */

/** Gestor de archivos */
    $router->get('/api/file-manager', function() {
        return (new FileManagerController())->index($_GET);
    });

    $router->get('/api/file-manager/{mediaId}', function($id) {
        return (new FileManagerController())->show((int) $id);
    });

    $router->post('/api/file-manager', function() {
        return (new FileManagerController())->sendRequestPermission($_POST);
    });

    $router->get('/api/file-manager/solicitudes/{token}/{status}', function($token, $status) {
        return (new FileManagerController())->statusRequestPermission($token, $status);
    });
/** Termina Gestor de archivos */

/** Usuarios */
    $router->get('/api/users', function() {
        return (new UserController())->index($_GET);
    });
/** Termina Usuarios */

/** Mesa de control */
    $router->get('/api/control-console', function() {
        return (new ControlConsoleController())->index($_GET);
    });

    $router->get('/api/control-console/units', function() {
        return (new ControlConsoleController())->getAllUnits();
    });

    $router->get('/api/control-console/stages', function() {
        return (new ControlConsoleController())->getAllStages();
    });


    $router->get('/api/control-console/{serviceId}', function($serviceId) {
        return (new ControlConsoleController())->show((int) $serviceId);
    });

    $router->post('/api/control-console/email', function() {
        return (new ControlConsoleController())->sendEmailAlert($_POST);
    });

    
    $router->post('/api/control-console/status', function() {
        return (new ControlConsoleController())->updateStatus($_POST);
    });

    $router->get('/api/control-console/status/{serviceId}', function($serviceId) {
        return (new ControlConsoleController())->getStageStatusByService((int) $serviceId);
    });

/** Termina Mesa de control */

/** Estatus y Etapas servicios */
    $router->get('/api/stage-services', function() {
        return (new StageServiceController())->index($_GET);
    });

    $router->get('/api/status-stage-services/{stageId}', function($stageId) {
        return (new StatusStageServiceController())->getStatusStageById((int) $stageId);
    });
/** Termina Estatus y Etapas servicios */

