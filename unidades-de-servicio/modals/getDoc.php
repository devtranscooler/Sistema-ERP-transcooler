 <?php $id_sucursal = $_POST['id_sucursal'] ?? null; ?>
 <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Subir Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form id="alta" action="dao/inserDoc.php?id_sucursal=<?= $id_sucursal ?>" method="POST" enctype="multipart/form-data" target="_self">
        <div class="modal-body">
          <div class="mb-3">
            <label for="documento" class="form-label">Seleccionar archivo</label>
            <input class="form-control" type="file" id="documento" name="documento" required>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Subir</button>
        </div>
      </form>