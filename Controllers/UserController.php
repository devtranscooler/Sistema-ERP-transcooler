<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/User.php';

class UserController
{
    public function index()
    {
        $filters = [
            'rol' => $_GET['rol'] ?? null,
        ];

        $pagination = [
            'page' => (int)($_GET['page'] ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 15),
        ];

        $userModel = new User();
        $users = $userModel->all($filters, $pagination);

        if (empty($users) || !$users) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "No results found",
            ];
        }

        http_response_code(200);
        return [
            'data' => $users,
            "pagination" => [
                "page" => $pagination['page'],
                "per_page" => $pagination['per_page']
            ]
        ];
    }
}