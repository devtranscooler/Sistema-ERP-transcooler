<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        <i class="bi bi-person-fill me-2"></i>
        Gestión de Rol
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="formRol" onsubmit="event.preventDefault(); guardar()">
        <input type="hidden" name="action" value="<?= isset($data['id']) ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Rol</label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                value="<?= $data['nombre'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"
                rows="3"><?= $data['descripcion'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select class="form-select" id="status" name="status" required>
                <option value="activo" <?= (isset($data['status']) && $data['status'] == 'activo') ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= (isset($data['status']) && $data['status'] == 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar Rol</button>
        </div>
    </form>
</div>
