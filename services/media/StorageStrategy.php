<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php';

interface StorageStrategy
{
    public function save(array $file, UploadMediaDTO $mediaDTO): array;
}