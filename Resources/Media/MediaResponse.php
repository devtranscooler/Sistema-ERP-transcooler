<?php

declare(strict_types=1);

class MediaResponse
{
    public static function transform(array $item): array
    {
        return [
            "status" => $item['status'] ?? false,
            "file"   => $item['file'] ?? null,
            "path"   => $item['path'] ?? null,
            "gcs"    => self::transformGCS($item['gcs'] ?? null)
        ];
    }

    private static function transformGCS(?array $gcs): ?array
    {
        if (!$gcs) return null;

        return [
            "id" => $gcs['id'] ?? null,
            "selfLink" => $gcs['selfLink'] ?? null,
            "mediaLink" => $gcs['mediaLink'] ?? null,
            "name" => $gcs['name'] ?? null,
            "contentType" => $gcs['contentType'] ?? null,
            "timeCreated" => $gcs['timeCreated'] ?? null,
            "updated" => $gcs['updated'] ?? null,
            "timeStorageClassUpdated" => $gcs['timeStorageClassUpdated'] ?? null,
            "timeFinalized" => $gcs['timeFinalized'] ?? null,
        ];
    }
}