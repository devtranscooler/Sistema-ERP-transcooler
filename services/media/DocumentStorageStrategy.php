<?php

declare(strict_types=1);

require_once "BaseStorageStrategy.php";

require_once($_SERVER['DOCUMENT_ROOT'] . '/Models/Media.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php';

class DocumentStorageStrategy extends BaseStorageStrategy
{
    public function save(array $file, UploadMediaDTO $mediaDTO): array
    {
        try {

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            $path = $this->generatePath($mediaDTO->modulo_servicio, $extension);

            $upload = $this->uploadFile($file['tmp_name'], $path);

            if (!$upload['status']) {
                return [
                    "status" => false,
                    "file" => $file['name'],
                    "message" => $upload['message']
                ];
            }

            $mediaModel = new Media();

            $dbResult = $mediaModel->create([
                "nombre_origen" => $file['name'],
                "ruta" => $path,
                "extension" => $extension,
                "id_usuario_creador" => $mediaDTO->user_id,
                "tipo_recurso" => $mediaDTO->tipo_recurso,
                "tipo_recurso_id" => $mediaDTO->tipo_recurso_id
            ]);

            if (!$dbResult['status']) {
                return [
                    "status" => false,
                    "message" => "Error al guardar en BD",
                    "error" => $dbResult['message']
                ];
            }

            return [
                "status" => true,
                "original_file_name" => $file['name'],
                "path" => $path,
                "gcs" => $upload['data']
            ];

        } catch (\Throwable $e) {

            return [
                "status" => false,
                "file" => $file['name'],
                "message" => $e->getMessage()
            ];
        }
    }
}