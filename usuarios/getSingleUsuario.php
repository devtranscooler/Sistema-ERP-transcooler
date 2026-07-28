<div class="modal-header">
    <h5 class="modal-title" id="addModalLabel">Alta de Usuario</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="addForm" method="POST" enctype="multipart/form-data" action="./dao/insertUsuario.php">
        <input type="hidden" id="id" name="id">
        <input type="hidden" id="mode" name="mode" value="insert">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="nombre" class="form-label">Nombre <font color="red">*</font></label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="apellidoP" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="apellidoP" name="apellidoP">
            </div>
            <div class="col-md-4 mb-3">
                <label for="apellidoM" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="apellidoM" name="apellidoM">
            </div>
            <div class="col-md-4 mb-3">
                <label for="idRol" class="form-label">Rol</label>
                <input type="text" class="form-control" id="idRol" name="idRol">
            </div>
            <div class="col-md-4 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="col-md-4 mb-3">
                <label for="fecNac" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fecNac" name="fecNac">
            </div>
            <div class="col-md-4 mb-3">
                <label for="noEmpleado" class="form-label">Número empleado</label>
                <input type="text" class="form-control" id="noEmpleado" name="noEmpleado">
            </div>
            <div class="col-md-4 mb-3">
                <label for="movil" class="form-label">Movil</label>
                <input type="text" class="form-control" id="movil" name="movil">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Foto colaborador</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="foto" name="foto">
                    <span class="input-group-text">
                        <i class="bi bi-upload"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="fecContratacion" class="form-label">Fecha de contratacion</label>
                <input type="date" class="form-control" id="fecContratacion" name="fecContratacion">
            </div>
            <div class="col-md-4 mb-3">
                <label for="diasVacaciones" class="form-label">Dias de Vacaciones</label>
                <input type="text" class="form-control" id="diasVacaciones" name="diasVacaciones">
            </div>
            <div class="col-md-4 mb-3">
                <label for="diasVacDisfrutados" class="form-label">Dias de Vacaciones Disfrutados</label>
                <input type="text" class="form-control" id="diasVacDisfrutados" name="diasVacDisfrutados">
            </div>
            <div class="col-md-4 mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <input type="text" class="form-control" id="estatus" name="estatus">
            </div>
            <div class="col-md-4 mb-3">
                <label for="puesto" class="form-label">Puesto</label>
                <input type="text" class="form-control" id="puesto" name="puesto">
            </div>
            <div class="col-md-4 mb-3">
                <label for="area" class="form-label">Area</label>
                <input type="text" class="form-control" id="area" name="area">
            </div>
            <div class="col-md-4 mb-3">
                <label for="cedis" class="form-label">Centro de costos</label>
                <input type="text" class="form-control" id="cedis" name="cedis">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Contrato</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="contrato" name="contrato">
                    <span class="input-group-text">
                        <i class="bi bi-upload"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
            </div>
            <div class="col-md-4 mb-3">
                <label for="jefeInmediato" class="form-label">Jefe Inmediato</label>
                <input type="text" class="form-control" id="jefeInmediato" name="jefeInmediato">
            </div>
        </div>
        <button type="submit" id="saveButton" class="btn btn-success">Guardar</button>
        <button type="submit" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
    </form>
</div>