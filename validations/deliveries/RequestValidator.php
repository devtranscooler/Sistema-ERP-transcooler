<?php

declare(strict_types=1);

class RequestValidator
{
    public static function validation(array $post): array
    {
        $fieldsValidation = self::validateFields($post);
        if (!$fieldsValidation['status']) {
            return $fieldsValidation;
        }

        return [
            "status" => true,
            "data" => [
                "status" => $post['status'],
            ]
        ];
    }

    private static function validateFields(array $post): array
    {
        $allowedstatus = ["Pendiente", "Completado", "Rechazado"];

        // campo status, vacio y string
        if (empty($post['status']) || !is_string($post['status'])) {
            return self::error("El campo status es requerido y debe ser de tipo string");
        }

        if(!in_array($post['status'], $allowedstatus)) {
            return self::error("El campo status solo permite los valores Pendiente, Rechazado y Completado");
        }

        return ["status" => true];
    }

    private static function error(string $message): array
    {
        return [
            "status" => false,
            "message" => $message,
            "code" => 422
        ];
    }
}