<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/Models/Product.php');

class ProductController 
{
    /**
     * The function retrieves products based on specified filters and pagination parameters, returning
     * the results along with pagination details.
     * 
     * @return The function returning an array with the following structure:
     * ```php
     * [
     *     "status" => true, // Indicates the status of the operation
     *     "message" => "success", // A message indicating the success of the operation
     *     "data" =>  ?? null, // An array of products retrieved based on the filters and
     * pagination
     *     "pagination" => [
     */
    public function index()
    {
        $filters = [
            'nombre' => $_GET['nombre'] ?? null,
            'clave' => $_GET['clave'] ?? null,
            'cliente_id' => $_GET['cliente_id'] ?? null,
            'fraccion_id' => $_GET['fraccion_id'] ?? null,
            'tipo_embalaje_id' => $_GET['tipo_embalaje_id'] ?? null,
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 10),
        ];

        $productsModel = new Product();
        $products = $productsModel->getAll($filters, $pagination);

        if (!$products || empty($products)) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        http_response_code(200);
        return [
            "status" => true,
            "message" => "success",
            "data" => $products ?? null,
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page']
            ]
        ];
    }
}