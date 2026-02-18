<?php
$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$apellidoP = $_POST['apellidoP'] ?? '';
$apellidoM = $_POST['apellidoM'] ?? '';
$email = $_POST['email'] ?? '';
$fecNac = $_POST['fecNac'] ?? '';
$movil = $_POST['movil'] ?? '';
$telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : 'No registrado';
$noEmpleado = $_POST['noEmpleado'] ?? '';
$puesto = $_POST['puesto'] ?? '';
$area = $_POST['area'] ?? '';
$cedis = $_POST['cedis'] ?? '';
$jefeInmediato = $_POST['jefeInmediato'] ?? 'Sin asignar';
$idRol = $_POST['idRol'] ?? '';
$fecContratacion = $_POST['fecContratacion'] ?? '';
$diasVacaciones = $_POST['diasVacaciones'] ?? 0;
$diasVacDisfrutados = $_POST['diasVacDisfrutados'] ?? 0;
$estatus = $_POST['estatus'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$curp = $_POST['curp'] ?? '';
$estado_civil = $_POST['estado_civil'] ?? '';
$escolaridad = $_POST['escolaridad'] ?? '';

$estatusBadge = [
    'activo' => 'success',
    'inactivo' => 'secondary',
    'suspendido' => 'danger'
];
$estatusColor = $estatusBadge[$estatus] ?? 'secondary';

$roles = [
    '1' => 'Administrador',
    '2' => 'Usuario',
    '3' => 'Supervisor'
];

$nombreRol = $roles[$idRol] ?? 'No asignado';

$fecNacFormateada = $fecNac ? date('d/m/Y', strtotime($fecNac)) : 'No registrada';
$fecContratacionFormateada = $fecContratacion ? date('d/m/Y', strtotime($fecContratacion)) : 'No registrada';

$diasDisponibles = $diasVacaciones - $diasVacDisfrutados;
$nombreCompleto = trim("$nombre $apellidoP $apellidoM");
?>

<div class="modal-header">
    <h5 class="modal-title">Información del Usuario</h5>
</div>

<div class="modal-body">

    <!-- CABECERA CON INFORMACIÓN PRINCIPAL -->
    <div class="row mb-3 pb-3 border-bottom">
        <div class="col-auto">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 600;">
                <?= strtoupper(substr($nombre, 0, 1)) ?>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-1"><?= $nombreCompleto ?></h5>
            <div>
                <span class="badge bg-<?= $estatusColor ?> me-2">
                    <?= ucfirst($estatus) ?>
                </span>
                <span class="badge bg-secondary">
                    <?= $nombreRol ?>
                </span>
            </div>
        </div>
    </div>

    <!-- INFORMACIÓN DE CONTACTO -->
    <h6 class="bg-light p-2 mb-2">
        <i class="bi bi-person-lines-fill me-2"></i>Contacto
    </h6>
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <p class="text-muted d-block">Móvil</p>
            <span><?= $movil ?></span>
        </div>
        <div class="col-md-3">
            <p class="text-muted d-block">Teléfono</p>
            <span><?= $telefono ?></span>
        </div>
        <div class="col-md-3">
            <p class="text-muted d-block">Correo</p>
            <span><?= $email ?></span>
        </div>
        <div class="col-md-3">
            <p class="text-muted d-block">Fecha de Nacimiento</p>
            <span><?= $fecNacFormateada ?></span>
        </div>
    </div>

    <!-- INFORMACIÓN LABORAL -->
    <h6 class="bg-light p-2 mb-2">
        <i class="bi bi-briefcase-fill me-2"></i>Información Laboral
    </h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <p class="text-muted d-block">No. Empleado</p>
            <span class="fw-semibold"><?= $noEmpleado ?></span>
        </div>
        <div class="col-md-4">
            <p class="text-muted d-block">Puesto</p>
            <span><?= $puesto ?></span>
        </div>
        <div class="col-md-4">
            <p class="text-muted d-block">Área</p>
            <span><?= $area ?></span>
        </div>
        <div class="col-md-4">
            <p class="text-muted d-block">CEDIS</p>
            <span><?= $cedis ?></span>
        </div>
        <div class="col-md-4">
            <p class="text-muted d-block">Jefe Inmediato</p>
            <span><?= $jefeInmediato ?></span>
        </div>
        <div class="col-md-4">
            <p class="text-muted d-block">Fecha de Contratación</p>
            <span><?= $fecContratacionFormateada ?></span>
        </div>
    </div>

    <!-- VACACIONES -->
    <h6 class="bg-light p-2 mb-2">
        <i class="bi bi-umbrella-fill me-2"></i>Vacaciones
    </h6>
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-8">
            <?php
            $porcentajeUsado = $diasVacaciones > 0 ? ($diasVacDisfrutados / $diasVacaciones) * 100 : 0;
            $colorBarra = $porcentajeUsado < 50 ? 'success' : ($porcentajeUsado < 80 ? 'warning' : 'danger');
            ?>
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-<?= $colorBarra ?>"
                    role="progressbar"
                    style="width: <?= $porcentajeUsado ?>%">
                    <small><?= round($porcentajeUsado) ?>%</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-around text-center p">
                <div>
                    <div class="text-muted">Total</div>
                    <strong class="text-primary"><?= $diasVacaciones ?></strong>
                </div>
                <div>
                    <div class="text-muted">Usados</div>
                    <strong class="text-danger"><?= $diasVacDisfrutados ?></strong>
                </div>
                <div>
                    <div class="text-muted">Disponibles</div>
                    <strong class="text-success"><?= $diasDisponibles ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- INFORMACIÓN ADICIONAL -->
    <?php if ($rfc || $curp || $estado_civil || $escolaridad): ?>
        <h6 class="bg-light p-2 mb-2">
            <i class="bi bi-info-circle-fill me-2"></i>Información Adicional
        </h6>
        <div class="row g-2 mb-3">
            <?php if ($rfc): ?>
                <div class="col-md-6">
                    <p class="text-muted d-block">RFC</p>
                    <span><?= $rfc ?></span>
                </div>
            <?php endif; ?>

            <?php if ($curp): ?>
                <div class="col-md-6">
                    <p class="text-muted d-block">CURP</p>
                    <span><?= $curp ?></span>
                </div>
            <?php endif; ?>

            <?php if ($estado_civil): ?>
                <div class="col-md-6">
                    <p class="text-muted d-block">Estado Civil</p>
                    <span><?= $estado_civil ?></span>
                </div>
            <?php endif; ?>

            <?php if ($escolaridad): ?>
                <div class="col-md-6">
                    <p class="text-muted d-block">Escolaridad</p>
                    <span><?= $escolaridad ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i>Cerrar
    </button>
</div>
