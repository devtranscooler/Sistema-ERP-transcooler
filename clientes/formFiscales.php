<?php
$id = $_POST['id'] ?? null;
$calle = $_POST['calle'] ?? null;
$num_ext = $_POST['num_ext'] ?? null;
$num_int = $_POST['num_int'] ?? null;
$codigo_postal = $_POST['codigo_postal'] ?? null;
$RFC = $_POST['RFC'] ?? null;
$tipo_persona = $_POST['tipo_persona'] ?? null;
$regimen_fiscal = $_POST['regimen_fiscal'] ?? null;
$CFDI = $_POST['CFDI'] ?? null;
$tipo_credito = $_POST['tipo_credito'] ?? null;
$cantidad_credito = $_POST['cantidad_credito'] ?? null;
$forma_pago = $_POST['forma_pago'] ?? null;
$metodo_pago = $_POST['metodo_pago'] ?? null;

?>

<div class="modal-header">
    <h5 class="modal-title">Datos fiscales del cliente</h5>
</div>

<form id="formClienteFiscales">
    <div class="modal-body">

        <input type="hidden" name="id" value="<?= $id ?>">

        <!-- ================= DOMICILIO FISCAL ================= -->
        <h5 class="border-bottom pb-2 mb-3">
            <i class="bi bi-geo-alt"></i> Domicilio fiscal
        </h5>

        <div class="row">

            <div class="col-md-6">
                <label class="form-label">Calle *</label>
                <input type="text" class="form-control" name="calle"
                    value="<?= $calle?>" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">No. Ext *</label>
                <input type="text" class="form-control" name="num_ext"
                    value="<?= $num_ext ?>" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">No. Int</label>
                <input type="text" class="form-control" name="num_int"
                    value="<?= $num_int ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label">C.P. *</label>
                <input type="text" class="form-control" name="codigo_postal"
                    value="<?= $codigo_postal ?>" required>
            </div>

        </div>

        <!-- ================= DATOS FISCALES ================= -->
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="bi bi-file-earmark-text"></i> Datos fiscales
        </h5>

        <div class="row">

            <div class="col-md-3">
                <label class="form-label">RFC *</label>
                <input type="text" class="form-control" name="RFC"
                    value="<?= $RFC ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Tipo de persona *</label>
                <select class="form-select" name="tipo_persona" required>
                    <option value="fisica" <?= $tipo_persona === 'fisica' ? 'selected' : '' ?>>Física</option>
                    <option value="moral" <?= $tipo_persona === 'moral' ? 'selected' : '' ?>>Moral</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Régimen fiscal *</label>
                <input type="text" class="form-control" name="regimen_fiscal"
                    value="<?= $regimen_fiscal ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Uso CFDI *</label>
                <input type="text" class="form-control" name="CFDI"
                    value="<?= $CFDI ?>" required>
            </div>

        </div>

        <!-- ================= CRÉDITO Y PAGO ================= -->
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="bi bi-cash-stack"></i> Crédito y pago
        </h5>

        <div class="row">

            <div class="col-md-3">
                <label class="form-label">Tipo de crédito</label>
                <select class="form-select" name="tipo_credito">
                    <option value="dia" <?= $tipo_credito === 'dia' ? 'selected' : '' ?>>Día</option>
                    <option value="mes" <?= $tipo_credito === 'mes' ? 'selected' : '' ?>>Mes</option>
                    <option value="año" <?= $tipo_credito === 'año' ? 'selected' : '' ?>>Año</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Cantidad de crédito</label>
                <input type="number" class="form-control" name="cantidad_credito"
                    value="<?= $cantidad_credito ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Forma de pago</label>
                <input type="text" class="form-control" name="forma_pago"
                    value="<?= $forma_pago ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Método de pago</label>
                <input type="text" class="form-control" name="metodo_pago"
                    value="<?= $metodo_pago ?>">
            </div>

        </div>

        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="bi bi-file-earmark-text"></i> Cargar informacion mediante archivo
        </h5>

        <div class="row">
            <div class="col-mb-12">
                <label for="formFile" class="form-label">Documento donde vienen los datos fiscales del cliente (no me acuerdo como se llama)</label>
                <input class="form-control" type="file" id="formFile">
            </div>
        </div>

    </div>
</form>


<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle"></i> Cancelar
    </button>
    <button type="button" class="btn btn-success" onclick="guardarFiscales()">
        <i class="bi bi-save"></i> Guardar
    </button>
</div>