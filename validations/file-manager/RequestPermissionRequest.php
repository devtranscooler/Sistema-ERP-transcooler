<?php

declare(strict_types=1);

class RequestPermissionRequest
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
        // usuario_solicitante_id
        if (empty($post['usuario_solicitante_id']) || !is_numeric($post['usuario_solicitante_id'])) {
            return self::error("El campo usuario_solicitante_id es requerido y debe ser numerico");
        }

        // usuario_aprobador_id
        if (!isset($post['usuario_aprobador_id']) || !is_numeric($post['usuario_aprobador_id'])) {
            return self::error("El campo usuario_aprobador_id es requerido y debe ser numérico");
        }

        // media_id
        if (empty($post['media_id']) || !is_numeric($post['media_id'])) {
            return self::error("El campo is_numeric es requerido y debe ser numérico");
        }

       // estatus
        if (!isset($post['estatus']) || !is_string($post['estatus']) || !in_array($post['estatus'], ['pendiente', 'aprobado', 'rechazado'])) {
            return self::error("El campo estatus es requerido y solo acepta status pendiente, aprobado, rechazado");
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