<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        <i class="bi bi-person-fill me-2"></i>
        Detalles del Rol
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <?php if (!empty($data)): ?>
        <p><strong>ID:</strong> <?= $data['id'] ?? 'N/A' ?></p>
        <p><strong>Nombre:</strong> <?= $data['nombre'] ?? 'N/A' ?></p>
        <p><strong>Descripción:</strong> <?= $data['descripcion'] ?? 'N/A' ?></p>
        <p><strong>Estado:</strong> <?= $data['status'] ?? 'N/A' ?></p>
        <!-- Add other role-specific details here -->
    <?php else: ?>
        <p>No se encontraron detalles para este rol.</p>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>