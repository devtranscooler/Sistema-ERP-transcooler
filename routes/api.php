<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/ProductController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/MediaController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/CartaPorteController.php';

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

/** Service Routes */
$router->get('/api/carta-porte/{service_id}', function($id) {
    return (new CartaPorteController())->generate((int) $id);
});
/** Service Routes */
