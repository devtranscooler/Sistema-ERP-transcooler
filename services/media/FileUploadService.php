<?php

declare(strict_types=1);

require_once 'StorageContext.php';
require_once 'ImageStorageStrategy.php';
require_once 'DocumentStorageStrategy.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php';

define('BASE_PATH', dirname(__DIR__, 2));

class FileUploadService 
{
    private string $bucket = 'transcooler';
    //private string $credentials = __DIR__ . '/../../config/storage/transcooler-480721-85cfc67ca604.json';
    private string $credentials = BASE_PATH . '/config/storage/transcooler-480721-85cfc67ca604.json';

    public function process(array $files, UploadMediaDTO $mediaDTO): array
    {
        $resultados = [];

        foreach ($files as $file) {

            $strategy = match (true) {

                str_starts_with($file['type'], 'image/') =>
                    new ImageStorageStrategy($this->bucket, $this->credentials),

                $file['type'] === 'application/pdf' =>
                    new DocumentStorageStrategy($this->bucket, $this->credentials),

                default => null
            };

            if (!$strategy) {
                $resultados[] = [
                    "status" => false,
                    "file" => $file['name'],
                    "message" => "Tipo no soportado"
                ];
                continue;
            }

            $context = new StorageContext();
            $context->setStrategy($strategy);

            $resultados[] = $context->execute($file, $mediaDTO);
        }

        return $resultados;
    }
}