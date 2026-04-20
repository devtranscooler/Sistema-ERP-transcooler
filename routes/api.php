<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/ProductController.php';

$router->get('/api/products', function () {
    return (new ProductController())->index($_GET);
});