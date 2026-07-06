<?php
$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$apellidoP = $_POST['apellidoP'] ?? '';
$apellidoM = $_POST['apellidoM'] ?? '';
$email = $_POST['email'] ?? '';
$fecNac = $_POST['fecNac'] ?? '';
$movil = $_POST['movil'] ?? '';
$telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : '';
$noEmpleado = $_POST['noEmpleado'] ?? '';
$puesto = $_POST['puesto'] ?? '';
$area = $_POST['area'] ?? '';
$cedis = $_POST['cedis'] ?? '';
$jefeInmediato = $_POST['jefeInmediato'] ?? '';
$idRol = $_POST['idRol'] ?? '';
$fecContratacion = $_POST['fecContratacion'] ?? '';
$diasVacaciones = $_POST['diasVacaciones'] ?? 0;
$diasVacDisfrutados = $_POST['diasVacDisfrutados'] ?? 0;
$estatus = $_POST['estatus'] ?? '';
$edad = $_POST['edad'] ?? '';
$antiguedad = $_POST['antiguedad'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$curp = $_POST['curp'] ?? '';
$estado_civil = $_POST['estado_civil'] ?? '';
$escolaridad = $_POST['escolaridad'] ?? '';
$hijos = $_POST['hijos'] ?? '';
?>

<div class="modal-header">
    <h5 class="modal-title" id="modalUsuarioLabel">
        <?php if ($id) {
            echo "Editar Usuario $nombre $apellidoM $apellidoP";
        } else {
            echo "Nuevo Usuario</";
        } ?>
    </h5>
</div>
<form id="formUsuario">
    <div class="modal-body">
        <input type="hidden" name="action" value="<?= $id ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <!-- Información Personal -->
        <h5 class="border-bottom pb-2 mb-3">
            <i class="bi bi-person"></i> Información Personal
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nombre <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="nombre" id="usuario-nombre" value="<?= $nombre ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="apellidoP" id="usuario-apellidoP" value="<?= $apellidoP ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" name="apellidoM" id="usuario-apellidoM" value="<?= $apellidoM ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="usuario-email" value="<?= $email ?>" required>
                <small id="email-validacion" class="form-text"></small>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" name="fecNac" id="usuario-fecNac" value="<?= $fecNac ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Foto</label>
                <p><em>aqui deberia estar en campo para subir la foto</em></p>
                <small class="text-muted">Máximo 2MB. Formatos: JPG, PNG, GIF</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Móvil <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="movil" id="usuario-movil" value="<?= $movil ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="telefono" id="usuario-telefono" value="<?= $telefono ?>">
            </div>
        </div>

        <!-- Información Laboral -->
        <h5 class="border-bottom pb-2 mb-3 mt-2">
            <i class="bi bi-briefcase"></i> Información Laboral
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">No. Empleado <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="noEmpleado" id="usuario-noEmpleado" value="<?= $noEmpleado ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Área <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="area" id="usuario-area" value="<?= $area ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">CEDIS <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cedis" id="usuario-cedis" value="<?= $cedis ?>" required>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Jefe Inmediato</label>
                <input type="text" class="form-control" name="jefeInmediato" id="usuario-jefeInmediato" value="<?= $jefeInmediato ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Fecha de Contratación <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="fecContratacion" id="usuario-fecContratacion" value="<?= $fecContratacion ?>" required>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">Días de Vacaciones <?= $diasVacaciones ?> </label>
                <input type="number" class="form-control" name="diasVacaciones" id="usuario-diasVacaciones" value="<?= $diasVacaciones ?>">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Días Disfrutados</label>
                <input type="number" class="form-control" name="diasVacDisfrutados" id="usuario-diasVacDisfrutados" value="<?= $diasVacDisfrutados ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Puesto <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="puesto" id="usuario-puesto" value="<?= $puesto ?>" required>
            </div>
        </div>

        <!-- Seguridad -->
        <h5 class="border-bottom pb-2 mb-3 mt-2">
            <i class="bi bi-shield-lock"></i> Seguridad
        </h5>

        <div class="row">
            <div class="col-md-4 mb-3" id="password-group">
                <label class="form-label"> Contraseña <?= isset($id) ? '(dejar vacío para no cambiar)' : '<span class="text-danger">*</span>' ?></label>
                <input type="password" class="form-control" name="password" id="usuario-password" 
                <?= !isset($id) ? 'required' : '' ?> minlength="6" >
                <small class="form-text text-muted">Mínimo 6 caracteres</small>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Rol <span class="text-danger">*</span></label>
                <select class="form-select" name="idRol" id="usuario-idRol" value="<?= $idRol ?>" required>
                    <option value="">Seleccione un rol</option>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario</option>
                    <option value="3">Supervisor</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Estatus <span class="text-danger">*</span></label>
                <select class="form-select" name="estatus" id="usuario-estatus" value="<?= $estatus ?>" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="suspendido">Suspendido</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
        </button>
        <button type="button" class="btn btn-success" onclick="guardarUsuario()">
            <i class="bi bi-save"></i> Guardar Usuario
        </button>
    </div>
</form>