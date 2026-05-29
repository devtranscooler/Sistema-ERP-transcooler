<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/User.php';

class OperatorController
{
    /**
     * The index function retrieves operator type users from the User model and returns them in a
     * response.
     * 
     * @return An array is being returned with the key 'data' containing the list of operators fetched
     * from the database. If no operators are found, a message 'Results not found' is returned. The
     * HTTP response code is set to 200 if operators are found.
     */
    public function index()
    {
        $filters = [
            'nombre' => $_GET['nombre'] ?? null
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 15),
        ];

        $userModel = new User();
        $operators = $userModel->getOperatorTypeUsers($filters, $pagination);

        if(!$operators || empty($operators)) {
            http_response_code();
            return ['message' => 'Results not found'];
        }

        http_response_code(200);
        return [
            'data' => $operators,
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page']
            ]
        ];
    }
}