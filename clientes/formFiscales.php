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
        <input type="hidden" name="action" value="<?= 'agregarFiscales' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <!-- ================= DOMICILIO FISCAL ================= -->
        <h5 class="border-bottom pb-2 mb-3">
            <i class="bi bi-geo-alt"></i> Domicilio fiscal
        </h5>

        <div class="row">

            <div class="col-md-6">
                <label class="form-label">Vialidad *</label>
                <input type="text" class="form-control" name="calle"
                    value="<?= $calle ?>" required>
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
            <i class="bi bi-file-earmark-text"></i> Cargar información mediante archivo
        </h5>

        <div class="row">
            <div class="col-12">
                <label for="formFile" class="form-label">
                    Documento donde vienen los datos fiscales del cliente
                </label>
                <div class="input-group">
                    <input class="form-control" type="file" id="formFile"
                        accept=".pdf,image/jpeg,image/png,image/tiff">
                    <button class="btn btn-primary" type="button" id="btnProcesarDoc"
                            onclick="procesarDocumentoFiscal()">
                        <i class="bi bi-cpu"></i> Procesar
                    </button>
                </div>
            </div>
        </div>

        <!-- Barra de progreso (oculta por defecto) -->
        <div class="row mt-2" id="rowProgreso" style="display:none !important">
            <div class="col-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated
                        bg-primary" style="width:100%" role="progressbar">
                        Analizando documento…
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerta de resultado (oculta por defecto) -->
        <div class="row mt-2" id="rowAlerta" style="display:none !important">
            <div class="col-12">
                <div id="alertaOCR" class="alert mb-0" role="alert"></div>
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

<script>
function procesarDocumentoFiscal() {
    const input       = document.getElementById('formFile');
    const btnProcesar = document.getElementById('btnProcesarDoc');

    if (!input.files || input.files.length === 0) {
        mostrarAlertaOCR('warning', 'Selecciona un archivo primero.');
        return;
    }

    const archivo = input.files[0];
    const MAX_MB  = 10;

    if (archivo.size > MAX_MB * 1024 * 1024) {
        mostrarAlertaOCR('danger', `El archivo supera los ${MAX_MB} MB permitidos.`);
        return;
    }

    ocultarAlertaOCR();
    document.getElementById('rowProgreso').style.setProperty('display', 'block', 'important');
    btnProcesar.disabled = true;

    const formData = new FormData();
    formData.append('archivo', archivo);

    fetch('ai.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (!res.ok) return res.json().then(e => { throw new Error(e.error || `HTTP ${res.status}`) });
        return res.json();
    })
    .then(json => {
        if (!json.success) throw new Error(json.error || 'Error desconocido');
        rellenarFormularioFiscal(json.datos);
        mostrarAlertaOCR('success',
            '<i class="bi bi-check-circle-fill"></i> ' +
            'Datos capturados correctamente. Revisa y completa los campos faltantes.'
        );
    })
    .catch(err => {
        mostrarAlertaOCR('danger',
            '<i class="bi bi-exclamation-triangle-fill"></i> ' + err.message
        );
    })
    .finally(() => {
        document.getElementById('rowProgreso').style.setProperty('display', 'none', 'important');
        btnProcesar.disabled = false;
    });
}

function rellenarFormularioFiscal(datos) {
    const mapa = {
        rfc:           'RFC',
        regimen:       'regimen_fiscal',
        codigo_postal: 'codigo_postal',
        num_ext:       'num_ext',
        num_int:       'num_int',
        calle:         'calle',
    };

    Object.entries(mapa).forEach(([clave, nombre]) => {
        if (!datos[clave]) return;
        const el = document.querySelector(`[name="${nombre}"]`);
        if (!el) return;
        el.value = datos[clave];
        el.classList.add('is-valid');
    });

    if (datos.rfc) {
        const tipoEl = document.querySelector('[name="tipo_persona"]');
        if (tipoEl) {
            tipoEl.value = datos.rfc.length === 12 ? 'moral' : 'fisica';
        }
    }
}

function mostrarAlertaOCR(tipo, html) {
    const row    = document.getElementById('rowAlerta');
    const alerta = document.getElementById('alertaOCR');
    alerta.className = `alert alert-${tipo} mb-0`;
    alerta.innerHTML = html;
    row.style.setProperty('display', 'block', 'important');
}

function ocultarAlertaOCR() {
    document.getElementById('rowAlerta').style.setProperty('display', 'none', 'important');
}

function guardarFiscales(){
    const form = document.getElementById("formClienteFiscales");
    const formData = new FormData(form);
    fetch("clientes.api.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("globalModal")
                );
                modal.hide();

                //  Recargar tabla
                cargarClientes();

                //  Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Datos fiscales guardados correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo guardar el cliente'
                });
            }
        })
        .catch(error => {
            console.error("Error al guardar:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar'
            });
        });
}
</script>