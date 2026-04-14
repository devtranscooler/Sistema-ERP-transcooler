<?php
$id              = $_POST['id']              ?? 'N/A';
$nombre          = $_POST['nombre']          ?? 'N/A';
$calle           = $_POST['calle']           ?? 'N/A';
$numero_interior = $_POST['numero_interior'] ?? 'N/A';
$numero_exterior = $_POST['numero_exterior'] ?? 'N/A';
$ciudad          = $_POST['ciudad']          ?? 'N/A';
$pais            = $_POST['pais']            ?? 'N/A';
$codigo_postal   = $_POST['codigo_postal']   ?? 'N/A';
$municipio       = $_POST['municipio']       ?? 'N/A';
?>
<style>
    .destino-modal { --color-text-primary:#1a1a1a; --color-border:#e5e5e5; }
    .destino-modal .modal-header { border-bottom:1px solid var(--color-border); padding:1rem; }
    .destino-modal .modal-title  { font-size:1.25rem; font-weight:600; }
    .destino-modal .modal-body   { padding:1rem; background:#fff; }
    .destino-modal .modal-footer { border-top:1px solid var(--color-border); padding:.75rem 1rem; }
    .info-section  { margin-bottom:1.5rem; }
    .section-title { font-size:.875rem; font-weight:700; text-transform:uppercase; color:#007AA3;
                     margin-bottom:.75rem; display:flex; align-items:center; gap:.5rem; }
    .section-content { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
    .info-field  { display:flex; flex-direction:column; }
    .info-label  { font-size:.75rem; font-weight:700; text-transform:uppercase; color:#000; margin-bottom:.35rem; }
    .info-value  { font-size:.95rem; font-weight:500; word-break:break-word; }
    @media(max-width:992px){ .section-content{ grid-template-columns:repeat(2,1fr); } }
    @media(max-width:576px){ .section-content{ grid-template-columns:1fr; } }
</style>

<div class="modal-header destino-modal">
    <h5 class="modal-title">
        <i class="bi bi-geo-alt-fill me-2"></i> Información del Destino #<?= $id ?>
    </h5>
</div>

<div class="modal-body destino-modal">
    <div class="info-section">
        <div class="section-title"><i class="bi bi-info-circle"></i> Identificación</div>
        <div class="section-content">
            <div class="info-field"><div class="info-label">Nombre</div><div class="info-value"><?= htmlspecialchars($nombre) ?></div></div>
            <div class="info-field"><div class="info-label">Ciudad</div><div class="info-value"><?= htmlspecialchars($ciudad) ?></div></div>
            <div class="info-field"><div class="info-label">Municipio</div><div class="info-value"><?= htmlspecialchars($municipio) ?></div></div>
        </div>
    </div>
    <div class="info-section">
        <div class="section-title"><i class="bi bi-map"></i> Dirección</div>
        <div class="section-content">
            <div class="info-field"><div class="info-label">Calle</div><div class="info-value"><?= htmlspecialchars($calle) ?></div></div>
            <div class="info-field"><div class="info-label">Número Exterior</div><div class="info-value"><?= htmlspecialchars($numero_exterior) ?></div></div>
            <div class="info-field"><div class="info-label">Número Interior</div><div class="info-value"><?= htmlspecialchars($numero_interior) ?></div></div>
            <div class="info-field"><div class="info-label">Código Postal</div><div class="info-value"><?= htmlspecialchars($codigo_postal) ?></div></div>
            <div class="info-field"><div class="info-label">País</div><div class="info-value"><?= htmlspecialchars($pais) ?></div></div>
        </div>
    </div>
</div>

<div class="modal-footer destino-modal">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Cerrar
    </button>
</div>