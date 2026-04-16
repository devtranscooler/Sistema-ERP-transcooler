<?php

declare(strict_types=1);

require_once "BaseStorageStrategy.php";

require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php';

class DocumentStorageStrategy extends BaseStorageStrategy
{
    public function save(array $file, UploadMediaDTO $mediaDTO): array
    {
        return [
            "status" => false,
            "file" => $file['name'],
            "message" => "Estrategia de documentos no implementada aún"
        ];
    }
}