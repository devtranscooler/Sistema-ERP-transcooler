<div class="modal-header">
  <h5 class="modal-title" id="uploadModalLabel">Carga de usuarios.</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>

<form id="alta" name="alta" enctype="multipart/form-data" method="post">
  <div class="modal-body">
    
    <div class="mb-3">
      <label for="plantilla" class="form-label">Descargar el archivo CSV</label>
      <br>
      <a href="../system/plantillas/usuarios.csv" download class="btn btn-primary">
        Descargar plantilla 
        <i class="bi bi-cloud-arrow-down"></i>
      </a>
      <br>
      <br>
    </div>

    <div class="mb-3">
    <p><strong>Agrega o edita los campos de los Usuarios en la Plantilla CSV</strong></p>
      <label for="plantilla" class="form-label">
        Los campos obligatorios son el Rol, nombre, el apellido paterno, la dirección de correo electrónico, la contraseña y la fecha de contratacion
      </label>
      <br>
    </div>

    <div class="mb-3">
      <table class="table table-hover">
        <thead class="thead-light">
          <tr>
            <th scope="col">Rol</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido Paterno</th>
            <th scope="col">Correo</th>
            <th scope="col">Contraseña</th>
            <th scope="col">Fecha de Contratacion</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">Admin</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>mark@gmail.com</td>
            <td>********</td>
            <td>08/06/2022</td>
          </tr>
        </tbody>
      </table>
      <br>
    </div>

    <div class="mb-3">
      <label for="documento" class="form-label">Subir un Archivo CSV</label>
      <div class="input-group">
        <input type="file" class="form-control" id="documento" name="documento" accept=".csv" required>
        <span class="input-group-text"><i class="bi bi-upload"></i></span>
      </div>
    </div>
    
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="button" onclick="cargarUsuarios();" class="btn btn-success">Subir</button>
  </div>
</form>
