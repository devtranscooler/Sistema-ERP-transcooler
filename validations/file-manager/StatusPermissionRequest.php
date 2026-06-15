<?php

declare(strict_types=1);

class StatusPermissionRequest
{
    public static function validation(string $status): array
    {
        $fields = self::validationFields($status);

        if(!$fields['status']) {
            return [
                'status' => false,
                'message' => $fields['message']
            ];
        }

        return [
            'status' => true,
            'data' => [
                'status' => $status
            ]
        ];
    }

    private static function validationFields(string $status): array
    {
        if(!in_array($status, ['rechazado', 'aprobado'])) {
            return [
                'status' => false,
                'message' => 'Estatus no valido'
            ];
        }

        return [
            'status' => true
        ];
    }
}