<?php

declare(strict_types=1);

class ControlConsoleEmailRequestValidation
{
    public static function validate(array $post): array
    {
        $valiateFields = self::validateFields($post);

        if(!$valiateFields['status']) {
            return $valiateFields;
        }
        
        return [
            'status' => true,
            'data' => $post
        ];
    }

    private static function validateFields(array $post): array
    {
        // id servicio
        if (empty($post['service_id']) || !is_numeric($post['service_id'])) {
            return self::error("El campo service_id es requerido y debe ser numerico");
        }

        // usuario emisor
        if (!isset($post['send_user_id']) || !is_numeric($post['send_user_id'])) {
            return self::error("El campo send_user_id es requerido y debe ser numérico");
        }

        // usuario receptor
        if (!isset($post['recipient_user_id']) || !is_numeric($post['recipient_user_id'])) {
            return self::error("El campo recipient_user_id es requerido y debe ser numérico");
        }

        // comentarios de recordatorio
        if (empty($post['comments']) || !is_string($post['comments'])) {
            return self::error("El campo comments es requerido y debe ser de tipo string");
        }

        if(strlen($post['comments']) <= 4) {
            return self::error("El campo comments debe tener una longitud mayor a 4 caracteres");
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