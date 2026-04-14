<?php
$eco = $_POST['eco'] ?? 'N/A';
$razon_social = $_POST['razon_social'] ?? 'N/A';
$placas = $_POST['placas'] ?? 'N/A';
$folio_tc = $_POST['folio_tc'] ?? 'N/A';
$niv = $_POST['niv'] ?? 'N/A';
$no_motor = $_POST['no_motor'] ?? 'N/A';
$marca = $_POST['marca'] ?? 'N/A';
$modelo = $_POST['modelo'] ?? 'N/A';
$capacidad = $_POST['capacidad'] ?? 'N/A';
$tipo_unidad = $_POST['tipo_unidad'] ?? 'N/A';
$anio = $_POST['anio'] ?? 'N/A';
$color = $_POST['color'] ?? 'N/A';
$aseguradora = $_POST['aseguradora'] ?? 'N/A';
$cobertura = $_POST['cobertura'] ?? 'N/A';
$vigencia_poliza = $_POST['vigencia_poliza'] ?? 'N/A';
$km = $_POST['km'] ?? 'N/A';

?>
<style>
    .vehicle-modal {
        --color-text-primary: #1a1a1a;
        --color-text-secondary: #666;
        --color-border: #e5e5e5;
        --color-bg-section: #fafafa;
    }

    .vehicle-modal .modal-header {
        border-bottom: 1px solid var(--color-border);
        padding: 1rem;
        background: transparent;
    }

    .vehicle-modal .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-text-primary);
        letter-spacing: -0.3px;
    }

    .vehicle-modal .modal-body {
        padding: 1rem;
        background: #fff;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

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

    .info-field {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #000;
        margin-bottom: 0.35rem;
        font-weight: bold;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--color-text-primary);
        word-break: break-word;
        font-weight: 500;
    }

    .info-value.empty {
        color: var(--color-text-secondary);
        font-style: italic;
    }

    .vehicle-modal .modal-footer {
        border-top: 1px solid var(--color-border);
        padding: 0.75rem 1rem;
        background: transparent;
        gap: 0.5rem;
    }

    .vehicle-modal .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }

    .vehicle-modal .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .section-content {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .section-content {
            grid-template-columns: 1fr;
        }

        .vehicle-modal .modal-body {
            padding: 0.75rem;
        }

        .vehicle-modal .modal-header {
            padding: 0.75rem;
        }
    }
</style>

<div class="modal-header vehicle-modal">
    <h5 class="modal-title">
        <i class="bi bi-truck"></i> Información de la unidad
    </h5>
</div>

<div class="modal-body vehicle-modal">

    <!-- DATOS GENERALES -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-info-circle" style="font-size: 0.85rem"></i>
            Identificación
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Económico</div>
                <div class="info-value"><?= $eco ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Placas</div>
                <div class="info-value"><?= $placas ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Razón social</div>
                <div class="info-value"><?= $razon_social ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Folio TC</div>
                <div class="info-value"><?= $folio_tc ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">NIV</div>
                <div class="info-value"><?= $niv ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">No. Motor</div>
                <div class="info-value"><?= $no_motor ?></div>
            </div>
        </div>
    </div>

    <!-- CARACTERÍSTICAS DEL VEHÍCULO -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-gear" style="font-size: 0.85rem;"></i>
            Características técnicas
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Marca</div>
                <div class="info-value"><?= $marca ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Modelo</div>
                <div class="info-value"><?= $modelo ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Año</div>
                <div class="info-value"><?= $anio ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Tipo de unidad</div>
                <div class="info-value"><?= $tipo_unidad ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Capacidad</div>
                <div class="info-value"><?= $capacidad ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Color</div>
                <div class="info-value"><?= $color ?></div>
            </div>
        </div>
    </div>

    <!-- ESTADO Y OPERACIÓN -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-speedometer2" style="font-size: 0.85rem;"></i>
            Estado operativo
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Kilómetros</div>
                <div class="info-value"><?= $km ?></div>
            </div>
        </div>
    </div>

    <!-- COBERTURA Y SEGUROS -->
    <div class="info-section">
        <div class="section-title">
            <i class="bi bi-shield-check" style="font-size: 0.85rem;"></i>
            Cobertura de seguros
        </div>
        <div class="section-content">
            <div class="info-field">
                <div class="info-label">Aseguradora</div>
                <div class="info-value"><?= $aseguradora ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Tipo de cobertura</div>
                <div class="info-value"><?= $cobertura ?></div>
            </div>
            <div class="info-field">
                <div class="info-label">Vigencia de póliza</div>
                <div class="info-value"><?= $vigencia_poliza ?></div>
            </div>
        </div>
    </div>

</div>

<div class="modal-footer vehicle-modal">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i>Cerrar
    </button>
</div>