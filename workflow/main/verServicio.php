<?php
    $data = $_POST;

    $servicio = json_decode($data['servicio'], true);
    $repartos = json_decode($data['repartos'], true);

    // Datos del servicio
    $id               = $servicio['id'] ?? null;
    $shipment         = $servicio['shipment'] ?? 'N/A';
    $fecha_carga      = $servicio['fecha_carga'] ?? 'N/A';
    $fecha_descarga   = $servicio['fecha_descarga'] ?? 'N/A';
    $tipo_servicio    = $servicio['tipo_servicio'] ?? 'N/A';
    $fec_alta         = $servicio['fec_alta'] ?? 'N/A';
    $tipo_viaje       = $servicio['tipo_viaje'] ?? 'N/A';
    $origen           = $servicio['origen'] ?? 'N/A';
    $status           = $servicio['status'] ?? 'N/A';
    $nombreUsuarioAlta= $servicio['nombreUsuarioAlta'] ?? 'N/A';
    $num_repartos = $servicio['num_repartos'] ?? 'N/A';

    //Datos de operador y unidad
    $nombre_razon     = $servicio['nombre_razon'] ?? 'N/A';
    $eco              = $servicio['eco'] ?? 'N/A';
    $nombreOperador   = $servicio['nombreOperador'] ?? 'N/A';

    $badge = match($status) {
        'activo'    => 'success',
        'eliminado' => 'danger',
        default     => 'secondary'
    }
?>

<style>
    .servicio-modal {
        --color-text-primary: #1a1a1a;
        --color-text-secondary: #666;
        --color-border: #e5e5e5;
    }
    .servicio-modal .modal-header { border-bottom: 1px solid var(--color-border); padding: 1rem; }
    .servicio-modal .modal-title  { font-size: 1.25rem; font-weight: 600; letter-spacing: -0.3px; }
    .servicio-modal .modal-body   { padding: 1rem; background: #fff; }
    .servicio-modal .modal-footer { border-top: 1px solid var(--color-border); padding: 0.75rem 1rem; gap: 0.5rem; }

    .info-section  { margin-bottom: 1.5rem; }
    .info-section:last-child { margin-bottom: 0; }

    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #007AA3;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-content {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .info-field { display: flex; flex-direction: column; }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #000;
        margin-bottom: 0.35rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--color-text-primary);
        font-weight: 500;
        word-break: break-word;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    @media (max-width: 992px) { .section-content { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .section-content { grid-template-columns: 1fr; } }
</style>

<div class="modal-header servicio-modal">
    <h5 class="modal-title">
        <i class="bi bi-journal-text me-2"></i> Detalle del servicio
        <span class="badge bg-<?= $badge ?> ms-2" style="font-size: 0.75rem"><?= ucfirst($status) ?></span>
    </h5>
</div>

<div class="modal-body servicio-modal">

    <!-- IDENTIFICACIÓN -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-hash" style="font-size: 0.85rem"></i> Identificación
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">ID Servicio</div>
                <div class="info-value">#<?= $id ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Shipment</div>
                <div class="info-value"><?= $shipment ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Cliente</div>
                <div class="info-value"><?= $nombre_razon ?></div>
            </div>
        </div>
    </div>

    <!-- OPERACIÓN -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-truck" style="font-size: 0.85rem"></i> Operación
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Eco unidad</div>
                <div class="info-value"><?= $eco ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Operador</div>
                <div class="info-value"><?= $nombreOperador ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Tipo de servicio</div>
                <div class="info-value"><?= ucfirst($tipo_servicio) ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Tipo de viaje</div>
                <div class="info-value"><?= ucfirst($tipo_viaje) ?></div>
            </div>
        </div>
    </div>

    <!-- Repartos -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-truck" style="font-size: 0.85rem"></i> Repartos
        </div>
        <div class="section-content">
            <div class="info-field full-width">
                <div class="info-label">Inicio de ruta:</div>
                <div class="info-value"><?= $repartos[0]['origen_inicio'] ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">No. Repartos: <?= $num_repartos ?></div>
            </div>
        </div>
        <div class="section-content">
            <?php foreach ($repartos as $index => $reparto): ?>
                <div class="info-field">
                    <div class="info-label">Reparto <?= $index + 1 ?></div>
                    <div class="info-value">
                        <?php if ($index === 1): ?>
                            <strong>Origen:</strong> <?= $reparto['origen'] ?> <br>
                        <?php endif; ?>
                        <strong>Destino:</strong> <?= $reparto['destino'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FECHAS -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-calendar3" style="font-size: 0.85rem"></i> Fechas
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Fecha de carga</div>
                <div class="info-value"><?= $fecha_carga ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Fecha de descarga</div>
                <div class="info-value"><?= $fecha_descarga ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Fecha de alta</div>
                <div class="info-value"><?= $fec_alta ?></div>
            </div>
        </div>
    </div>

    <!-- REGISTRO -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-person-check" style="font-size: 0.85rem"></i> Registro
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Usuario alta</div>
                <div class="info-value"><?= $nombreUsuarioAlta ?></div>
            </div>
        </div>
    </div>

</div>

<div class="modal-footer servicio-modal">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Cerrar
    </button>
</div>
