<?php

declare(strict_types=1);

final class UploadMediaDTO
{
    public function __construct(
        public readonly string $tipo_recurso,
        public readonly int $tipo_recurso_id,
        public readonly int $id_usuario,
        public readonly string $modulo_servicio
    ) {}
}