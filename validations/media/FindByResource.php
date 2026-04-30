<?php

declare(strict_types=1);

class FindByResource
{
    private const RESOURCES_TYPE = ["SALIDA"];
    public static function validationMediaResource(array $post): array
    {

        $fieldsValidation = self::validateFields($post);

        if (!$fieldsValidation['status']) {
            return $fieldsValidation;
        }

        return [
            "status" => true,
            "data" => [
                "tipo_recurso" => $post["tipo_recurso"],
                "tipo_recurso_id" => $post["tipo_recurso_id"]
            ]
        ];
    }

    private static function validateFields(array $post): array
    {
        // tipo_recurso
        if (empty($post['tipo_recurso']) || !is_string($post['tipo_recurso'])) {
            return self::error("El campo tipo_recurso es requerido y debe ser texto");
        }

        if(!in_array($post['tipo_recurso'], self::RESOURCES_TYPE)) {
            return self::error("Tipo de recurso invalido, solo se permite " . implode(", ", self::RESOURCES_TYPE));
        }

        // tipo_recurso_id
        if (!isset($post['tipo_recurso_id']) || !is_numeric($post['tipo_recurso_id'])) {
            return self::error("El campo tipo_recurso_id es requerido y debe ser numérico");
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