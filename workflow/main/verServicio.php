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

<head><link rel="stylesheet" href="/styles/style.css"></head>

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
        <div class="container full-width" style="margin: 10px;">
            <div class="info-field box">
                <div class="info-label">Inicio de ruta:</div>
                <div class="info-value"><?= $repartos[0]['origen_inicio'] ?></div>
            </div>
            <div class="info-field box">
                <div class="info-label">Destino de ruta:</div>
                <div class="info-value" style="text-transform: uppercase;"><?= end($repartos)['destino_final'] ?></div>
            </div>
        </div>        
        <?php if (!empty($repartos)): ?>
            <div class="section-content">
                <?php foreach ($repartos as $index => $reparto): ?>
                    <div class="reparto-card">
                        <div class="reparto-header">
                            Reparto <?= $index + 1 ?>
                        </div>
                        <div class="reparto-body">
                            <?php if (!empty($reparto['origen'])): ?>
                                <p>
                                    <strong>Origen:</strong>
                                    <?= $reparto['origen'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($reparto['origen_inicio'])): ?>
                                <p>
                                    <strong>Origen inicial:</strong>
                                    <?= $reparto['origen_inicio'] ?>
                                </p>
                            <?php endif; ?>
                            <p>
                                <strong>Destino:</strong>
                                <?= $reparto['destino'] ?>
                            </p>
                            <?php if (!empty($reparto['destino_final'])): ?>
                                <p>
                                    <strong>Destino final:</strong>
                                    <?= $reparto['destino_final'] ?>
                                </p>
                            <?php endif; ?>
                            <div class="productos-section">
                                <strong>Productos a entregar:</strong>
                                <?php if (!empty($reparto['productos'])): ?>
                                    <table class="productos-table">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Peso</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reparto['productos'] as $producto): ?>
                                                <tr>
                                                    <td><?= $producto['producto_nombre'] ?></td>
                                                    <td><?= $producto['cantidad'] ?></td>
                                                    <td><?= $producto['peso'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>No hay productos registrados.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
