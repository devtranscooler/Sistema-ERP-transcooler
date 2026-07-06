<?php
$nombre_razon = $_POST['nombre_razon'] ?? 'N/A';
$calle = $_POST['calle'] ?? 'N/A';
$num_ext = $_POST['num_ext'] ?? 'N/A';
$num_int = $_POST['num_int'] ?? 'N/A';
$codigo_postal = $_POST['codigo_postal'] ?? 'N/A';
$tipo_cliente = $_POST['tipo_cliente'] ?? 'N/A';
$status = $_POST['status'] ?? 'N/A';
$tipo_operacion = $_POST['tipo_operacion'] ?? 'N/A';
$RFC = $_POST['RFC'] ?? 'N/A';
$tipo_persona = $_POST['tipo_persona'] ?? 'N/A';
$regimen_fiscal = $_POST['regimen_fiscal'] ?? 'N/A';
$CFDI = $_POST['CFDI'] ?? 'N/A';
$telefono = $_POST['telefono'] ?? 'N/A';
$correo = $_POST['correo'] ?? 'N/A';
$tipo_credito = $_POST['tipo_credito'] ?? 'N/A';
$cantidad_credito = $_POST['cantidad_credito'] ?? 'N/A';
$forma_pago = $_POST['forma_pago'] ?? 'N/A';
$metodo_pago = $_POST['metodo_pago'] ?? 'N/A';

?>
<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-person-badge"></i>
        Información del Cliente
    </h5>
</div>

<div class="modal-body">

    <div class="card mb-2">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Datos Generales
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Nombre o Razón Social -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nombre / Razón Social</label>
                    <p class="mb-0"><?= $nombre_razon ?></p>
                </div>

                <!-- RFC -->
                <div class="col-md-4">
                    <label class="form-label fw-bold ">RFC</label>
                    <p class="mb-0"><?= $RFC ?></p>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Estatus</label>
                    <p class="mb-0">
                        <?php if (!empty($status)): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Tipo de Persona -->
                <div class="col-md-4">
                    <label class="form-label fw-bold ">
                        Tipo de Persona
                    </label>
                    <p class="mb-0"><?= $tipo_persona ?></p>
                </div>

                <!-- Tipo de Cliente -->
                <div class="col-md-4">
                    <label class="form-label fw-bold ">
                        Tipo de Cliente
                    </label>
                    <p class="mb-0"><?= $tipo_cliente ?></p>
                </div>

                <!-- Tipo de Operación -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo de Operación</label>
                    <p class="mb-0"><?= $tipo_operacion ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>Domicilio Fiscal
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Calle -->
                <div class="col-md-5">
                    <label class="form-label fw-bold">Calle</label>
                    <p class="mb-0"><?= $calle ?></p>
                </div>

                <!-- Número Exterior -->
                <div class="col-md-2">
                    <label class="form-label fw-bold ">Núm. Exterior</label>
                    <p class="mb-0"><?= $num_ext ?></p>
                </div>

                <!-- Número Interior -->
                <div class="col-md-2">
                    <label class="form-label fw-bold ">Núm. Interior</label>
                    <p class="mb-0"><?= $num_int ?></p>
                </div>

                <!-- Código Postal -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Código Postal</label>
                    <p class="mb-0"><?= $codigo_postal ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>Información Fiscal
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Régimen Fiscal -->
                <div class="col-md-6  ">
                    <label class="form-label fw-bold ">Régimen Fiscal</label>
                    <p class="mb-0"><?= $regimen_fiscal ?></p>
                </div>

                <!-- Uso de CFDI -->
                <div class="col-md-6  ">
                    <label class="form-label fw-bold ">Uso de CFDI</label>
                    <p class="mb-0"><?= $CFDI ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-telephone me-2"></i>Datos de Contacto
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Teléfono -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Teléfono</label>
                    <p class="mb-0"><?= $telefono ?> </p>
                </div>

                <!-- Correo Electrónico -->
                <div class="col-md-6">
                    <label class="form-label fw-bold ">Correo Electrónico</label>
                    <p class="mb-0"><?= $correo ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-credit-card me-2"></i>Información de Pago y Crédito
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Tipo de Crédito -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tipo de Crédito</label>
                    <p class="mb-0"><?= $tipo_credito ?></p>
                </div>

                <!-- Cantidad de Crédito -->
                <div class="col-md-3">
                    <label class="form-label fw-bold ">Cantidad de Crédito</label>
                    <p class="mb-0"><?= $cantidad_credito ?></p>
                </div>

                <!-- Forma de Pago -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Forma de Pago</label>
                    <p class="mb-0"><?= $forma_pago ?></p>
                </div>

                <!-- Método de Pago -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Método de Pago
                    </label>
                    <p class="mb-0"><?= $metodo_pago ?></p>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i>Cerrar
    </button>
</div>