<?php
$id              = $_POST['id']              ?? null;
$nombre          = $_POST['nombre']          ?? null;
$calle           = $_POST['calle']           ?? null;
$numero_interior = $_POST['numero_interior'] ?? null;
$numero_exterior = $_POST['numero_exterior'] ?? null;
$ciudad          = $_POST['ciudad']          ?? null;
$pais            = $_POST['pais']            ?? null;
$codigo_postal   = $_POST['codigo_postal']   ?? null;
$municipio       = $_POST['municipio']       ?? null;
?>
<div class="modal-header">
    <h5 class="modal-title">
        <?= $id ? "Editar Destino" : "Nuevo Destino" ?>
    </h5>
</div>

<form id="formDestinos">
    <div class="modal-body">
        <input type="hidden" name="action" value="<?= $id ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id"     value="<?= $id ?>">

        <h5 class="border-bottom pb-2 mb-3" style="color:#007AA3">
            <i class="bi bi-geo-alt me-1"></i> Información del Destino
        </h5>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">País <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="pais" value="<?= htmlspecialchars($pais) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ciudad <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ciudad" value="<?= htmlspecialchars($ciudad) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Municipio <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="municipio" value="<?= htmlspecialchars($municipio) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Calle <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="calle" value="<?= htmlspecialchars($calle) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Número Exterior <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="numero_exterior" value="<?= htmlspecialchars($numero_exterior) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Número Interior</label>
                <input type="text" class="form-control" name="numero_interior" value="<?= htmlspecialchars($numero_interior) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Código Postal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="codigo_postal" value="<?= htmlspecialchars($codigo_postal) ?>" required>
            </div>
        </div>
    </div>
</form>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle"></i> Cancelar
    </button>
    <button type="button" class="btn btn-success" onclick="guardar()">
        <i class="bi bi-save"></i> Guardar
    </button>
</div>