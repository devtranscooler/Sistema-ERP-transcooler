<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/services/gcs/GCSAuth.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/gcs/GCSUploader.php');


require_once "StorageStrategy.php";


abstract class BaseStorageStrategy implements StorageStrategy
{
    protected string $bucketName;
    protected string $credentialsPath;

    public function __construct(string $bucketName, string $credentialsPath)
    {
        $this->bucketName = $bucketName;
        $this->credentialsPath = $credentialsPath;
    }

    protected function uploadFile(string $tmpPath, string $destinyPath): array
    {
        try {

            // 1. Obtener token
            $auth = new GCSAuth($this->credentialsPath);
            $token = $auth->getAccessToken();

            // 2. Subir archivo
            $uploader = new GCSUploader($this->bucketName, $token);

            $resultado = $uploader->upload($tmpPath, $destinyPath);

            if (!$resultado['status']) {
                return [
                    "status" => false,
                    "message" => $resultado['error'] ?? 'Error al subir archivo'
                ];
            }

            return [
                "status" => true,
                "data" => $resultado['response']
            ];

        } catch (\Throwable $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    protected function generateName(): string
    {
        return uniqid();
    }

    protected function generatePath(string $basePath, string $extension): string
    {
        $fecha = new DateTime();

        $path = sprintf(
            '%s/%s/%s/%s/%s.%s',
            trim($basePath, '/'),        // llegada, arribo
            $fecha->format('Y'),         // 2026
            $fecha->format('m'),         // 04
            $fecha->format('d'),         // 16
            $this->generateName(),      // uniqid
            strtolower($extension)       // png
        );

        return $path;
    }
}