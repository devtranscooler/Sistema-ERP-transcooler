<?php 

declare(strict_types=1);

class FileValidator 
{
    private const ALLOWED_EXTENSIONS = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/pdf', 
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.documen',
        'application/msword'
    ];

    private const MAX_SIZE = 3 * 1024 * 1024;
    private const MAX_FILES = 2;


    public static function fileValidation(array $files, array $post, string $field = 'files'): array
    {
        $fieldsValidation = self::validateFields($post);
        if (!$fieldsValidation['status']) {
            return $fieldsValidation;
        }

        // Validar existencia de archivos
        if (!isset($files[$field])) {
            return self::error("No se envió el campo {$field}");
        }

        $file = $files[$field];
        $filesArray = self::normalizeFiles($file);

        if (empty($filesArray)) {
            return self::error("No se seleccionaron archivos");
        }

        // Validar máximo de archivos
        if (count($filesArray) > self::MAX_FILES) {
            return self::error("No se pueden subir más de " . self::MAX_FILES . " archivos");
        }

        foreach ($filesArray as $f) {

            // Validar error de archivo
            if ($f['error'] !== UPLOAD_ERR_OK) {
                return self::error("Error al subir el archivo: {$f['name']}");
            }

            // Validar tipo MIME
            if (!in_array($f['type'], self::ALLOWED_EXTENSIONS, true)) {
                return self::error("Tipo no permitido: {$f['name']}");
            }

            // Validar tamaño
            if ($f['size'] > self::MAX_SIZE) {
                return self::error("El archivo {$f['name']} excede 3MB");
            }
        }

        return [
            "status" => true,
            "files" => $filesArray,
            "data" => [
                "tipo_recurso" => $post['tipo_recurso'],
                "tipo_recurso_id" => (int) $post['tipo_recurso_id'],
                "modulo_servicio" => $post['modulo_servicio'],
                "user_id" => (int) $post["user_id"]
            ]
        ];
    }

    private static function validateFields(array $post): array
    {
        // tipo_recurso
        if (empty($post['tipo_recurso']) || !is_string($post['tipo_recurso'])) {
            return self::error("El campo tipo_recurso es requerido y debe ser texto");
        }

        // tipo_recurso_id
        if (!isset($post['tipo_recurso_id']) || !is_numeric($post['tipo_recurso_id'])) {
            return self::error("El campo tipo_recurso_id es requerido y debe ser numérico");
        }

        // modulo_servicio
        if (empty($post['modulo_servicio']) || !is_string($post['modulo_servicio'])) {
            return self::error("El campo modulo_servicio es requerido y debe ser texto");
        }

       // user_id
        if (!isset($post['user_id']) || !is_numeric($post['user_id']) || is_null($post['user_id'])) {
            return self::error("El campo user_id es requerido y debe ser numérico");
        }

        return ["status" => true];
    }

    private static function normalizeFiles(array $file): array
    {
        $result = [];

        if (is_array($file['name'])) {
            foreach ($file['name'] as $i => $name) {

                if ($file['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $result[] = [
                    "name" => $name,
                    "type" => $file['type'][$i],
                    "tmp_name" => $file['tmp_name'][$i],
                    "error" => $file['error'][$i],
                    "size" => $file['size'][$i],
                ];
            }
        } else {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                return [];
            }

            $result[] = $file;
        }

        return $result;
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