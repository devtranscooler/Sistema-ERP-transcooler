<?php

declare(strict_types=1);

final class UploadMediaDTO
{
    public function __construct(
        public readonly string $tipo_recurso,
        public readonly int $tipo_recurso_id,
        public readonly int $user_id,
        public readonly string $modulo_servicio
    ) {}
}