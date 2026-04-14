<?php
    $id = $_POST['id'] ?? null;
    $eco = $_POST['eco'] ?? null;
    $razon_social = $_POST['razon_social'] ?? null;
    $placas = $_POST['placas'] ?? null;
    $folio_tc = $_POST['folio_tc'] ?? null;
    $niv = $_POST['niv'] ?? null;
    $no_motor = $_POST['no_motor'] ?? null;
    $marca = $_POST['marca'] ?? null;
    $modelo = $_POST['modelo'] ?? null;
    $capacidad = $_POST['capacidad'] ?? null;
    $tipo_unidad = $_POST['tipo_unidad'] ?? null;
    $anio = $_POST['anio'] ?? null;
    $color = $_POST['color'] ?? null;
    $aseguradora = $_POST['aseguradora'] ?? null;
    $cobertura = $_POST['cobertura'] ?? null;
    $vigencia_poliza = $_POST['vigencia_poliza'] ?? null;
?>

<div class="modal-header">
    <h5 class="modal-title">
        <?php if ($id) {
            echo "Editar Unidad";
        } else {
            echo "Nueva Unidad";
        } ?>
    </h5>
</div>

<form id="formUnidades">
    <div class="modal-body">
        <input type="hidden" name="action" value="<?= $id ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <h5 class="border-bottom pb-2 mb-3" style="color: #007AA3">
            <i class="bi bi-truck me-1"></i> Identificación
        </h5>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Número economico <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="eco" id="eco" value="<?= $eco ?>" required>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Placas <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="email" class="form-control" name="placas" id="placas" value="<?= $placas ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Razón Social <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="razon_social" id="razon_social" value="<?= $razon_social ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Folio TC <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="folio_tc" id="folio_tc" value="<?= $folio_tc ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">NIV <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="niv" id="niv" value="<?= $niv ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">No. Motor <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="no_motor" id="no_motor" value="<?= $no_motor ?>" required>
            </div>

        </div>

        <h5 class="border-bottom pb-2 my-3" style="color: #007AA3">
            <i class="bi bi-gear"></i> Características técnicas
        </h5>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Marca <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="marca" id="marca" value="<?= $marca ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="modelo" id="modelo" value="<?= $modelo ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Año <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="anio" id="anio" value="<?= $anio ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo de unidad <span class="text-danger">*</span></label>
                <select name="tipo_unidad" id="tipo_unidad" class="form-control" required>
                    <option value="">Null</option>
                    <option value="5a Rueda" <?= $tipo_unidad == '5a Rueda' ? 'selected' : '' ?>>5a Rueda</option>
                    <option value="Torton" <?= $tipo_unidad == 'Torton' ? 'selected' : '' ?>>Torton</option>
                    <option value="Camioneta 3.5" <?= $tipo_unidad == 'Camioneta 3.5' ? 'selected' : '' ?>>Camioneta 3.5</option>
                    <option value="Rabón" <?= $tipo_unidad == 'Rabón' ? 'selected' : '' ?>>Rabón</option>
                    <option value="Full" <?= $tipo_unidad == 'Full' ? 'selected' : '' ?>>Full</option>
                    <option value="Rem. REF 40 FT" <?= $tipo_unidad == 'Rem. REF 40 FT' ? 'selected' : '' ?>>Rem. REF 40 FT</option>
                    <option value="Rem. REF 48 FT" <?= $tipo_unidad == 'Rem. REF 48 FT' ? 'selected' : '' ?>>Rem. REF 48 FT</option>
                    <option value="Rem. REF 53 FT" <?= $tipo_unidad == 'Rem. REF 53 FT' ? 'selected' : '' ?>>Rem. REF 53 FT</option>
                    <option value="Rem. SECO 53 FT" <?= $tipo_unidad == 'Rem. SECO 53 FT' ? 'selected' : '' ?>>Rem. SECO 53 FT</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Capacidad <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="capacidad" id="capacidad" value="<?= $capacidad ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Color <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="color" id="color" value="<?= $color ?>" required>
            </div>
            
        </div>

        <h5 class="border-bottom pb-2 my-3" style="color: #007AA3">
            <i class="bi bi-shield-check"></i> Cobertura de seguros
        </h5>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Aseguradora <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="aseguradora" id="aseguradora" value="<?= $aseguradora ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cobertura <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="cobertura" id="cobertura" value="<?= $cobertura ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Vigencia de póliza <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="date" class="form-control" name="vigencia_poliza" id="vigencia_poliza" value="<?= $vigencia_poliza ?>" required>
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