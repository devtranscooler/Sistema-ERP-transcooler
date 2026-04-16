<?php

declare(strict_types=1);

require_once "StorageStrategy.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/DTO/media/UploadMediaDTO.php';


class StorageContext
{
    private StorageStrategy $strategy;

    public function setStrategy(StorageStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function execute(array $file, UploadMediaDTO $mediaDTO): array
    {
        return $this->strategy->save($file, $mediaDTO);
    }
}