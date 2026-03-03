<?php
$id = $_POST['id'] ?? null;
$nombre_razon = $_POST['nombre_razon'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$correo = $_POST['correo'] ?? null;
$tipo_operacion = $_POST['tipo_operacion'] ?? null;
$tipo_cliente = $_POST['tipo_cliente'] ?? null;
$status = $_POST['status'] ?? null;
?>

<div class="modal-header">
    <h5 class="modal-title">
        <?php if ($id) {
            echo "Editar Cliente";
        } else {
            echo "Nuevo Cliente";
        } ?>
    </h5>
</div>

<form id="formCliente">
    <div class="modal-body">
        <input type="hidden" name="action" value="<?= $id ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <h5 class="border-bottom pb-2 mb-3">
            <i class="bi bi-person"></i> Información General
        </h5>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Razón social <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="nombre_razon" id="nombre_razon" value="<?= $nombre_razon ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Telefono <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="telefono" id="telefono" value="<?= $telefono ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Correo <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="email" class="form-control" name="correo" id="correo" value="<?= $correo ?>" required>
            </div>
        </div>

        <h5 class="border-bottom pb-2 my-3">
            <i class="bi bi-briefcase"></i> Información Operativa
        </h5>

        <div class="row">

            <div class="col-md-4">
                <label class="form-label">Tipo de operacion <span class="text-danger"><span class="text-danger">*</span></span></label>
                <select class="form-select" name="tipo_operacion" id="tipo_operacion" required>
                    <option value="enajenacion" <?= $tipo_operacion === 'enajenacion' ? 'selected' : '' ?>>
                        Enajenación
                    </option>
                    <option value="Bienes" <?= $tipo_operacion === 'Bienes' ? 'selected' : '' ?>>
                        Bienes
                    </option>
                    <option value="prestacion de servicios" <?= $tipo_operacion === 'prestacion de servicios' ? 'selected' : '' ?>>
                        Prestación de servicios
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo de cliente <span class="text-danger"><span class="text-danger">*</span></span></label>
                <select class="form-select" name="tipo_cliente" id="tipo_cliente" value="<?= $tipo_cliente ?>" required>
                    <option value="nacional" <?= $tipo_cliente === 'nacional' ? 'selected' : '' ?>>Nacional </option>
                    <option value="extranjero" <?= $tipo_cliente === 'extranjero' ? 'selected' : '' ?>>Extranjero</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Estatus <span class="text-danger">*</span></label>
                <select class="form-select" name="status" id="status" value="<?= $status ?>" required>
                    <option value="activo" <?= $status === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="suspendido" <?= $status === 'suspendido' ? 'selected' : '' ?>>Suspendido</option>
                </select>
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