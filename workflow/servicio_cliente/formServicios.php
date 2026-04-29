<?php
    $id = $_POST['id'] ?? null;
    $id_cliente = $_POST['id_cliente'] ?? null;
    $shipment = $_POST['shipment'] ?? null;
    $origen = $_POST['origen'] ?? null;
    $fecha_carga = $_POST['fecha_carga'] ?? null;
    $fecha_descarga = $_POST['fecha_descarga'] ?? null;
    $tipo_servicio = $_POST['tipo_servicio'] ?? null;
    $id_usuario_alta = $_POST['id_usuario_alta'] ?? null;
    $num_repartos = $_POST['num_repartos'] ?? null;
    $fec_alta = $_POST['fec_alta'] ?? null;
    $tipo_viaje = $_POST['tipo_viaje'] ?? null;
    $status = $_POST['status'] ?? null;
?>
<div class="modal-header">
    <h5 class="modal-title">
        <?php if ($id) {
            echo "Editar Servicio";
        } else {
            echo "Nuevo Servicio";
        } ?>
    </h5>
</div>
<!-- ============================================================
FORMULARIO PARA AGREGAR NUEVO SERVICIO
============================================================ -->
<!-- Formulario que enviará datos por POST -->
<form id="formServicios">
    <div class="modal-body">
        <input type="hidden" name="action" value="<?= $id ? 'actualizar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="id_usuario_alta" value="<?= $id_usuario_alta ?>">
        <input type="hidden" name="fec_alta" value="<?= $fec_alta ?>">
        <input type="hidden" name="status" value="<?= $status ?>">

        <h5 class="border-bottom pb-2 mb-3" style="color: #007AA3">
            <i class="bi bi-truck me-1"></i> Identificación
        </h5>

        <div class="row ">
            <!-- Cliente -->
            <div class="col-md-4">
                <label class="form-label">
                    Cliente <span class="text-danger">*</span>
                </label>

                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="cliente_busqueda"
                        placeholder="Buscar cliente..."
                        autocomplete="off">

                    <div id="lista_clientes"
                        class="list-group shadow-sm"
                        style="
                            position: fixed;
                            z-index: 9999;
                            min-width: 200px;
                            display: none;
                        "></div>
                </div>

                <input type="hidden" name="id_cliente" id="id_cliente" value="<?= $id_cliente ?>">
            </div>
            <!-- Shipment -->
            <div class="col-md-4">
                <label class="form-label">Shipment <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="shipment" id="shipment" value="<?= $shipment ?>" required>
            </div>
            <!-- Tipo de servicio -->
            <div class="col-md-4">
                <label for="tipo_servicio" class="form-label">
                    <i class="bi bi-flag"></i> Tipo de servicio <span class="text-danger">*</span>
                </label>
                <select class="form-control" id="tipo_servicio" name="tipo_servicio" required>
                    <option value="">Seleccione servicio</option>
                    <option value="spot"      <?= $tipo_servicio == 'spot'      ? 'selected' : '' ?>>Spot</option>
                    <option value="dedicado"  <?= $tipo_servicio == 'dedicado'  ? 'selected' : '' ?>>Dedicado</option>
                </select>
            </div>
            <!-- Origen -->
            <div class="col-md-8">
                <label class="form-label">Origen <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="origen" id="origen" value="<?= $origen ?>" required>
            </div>
            <!-- Tipo de viaje -->
            <div class="col-md-4">
                <label for="tipo_viaje" class="form-label">
                    <i class="bi bi-flag"></i> Tipo de viaje <span class="text-danger">*</span>
                </label>
                <select class="form-control" id="tipo_viaje" name="tipo_viaje" required>
                    <option value="">Seleccione viaje</option>
                    <option value="local"      <?= $tipo_viaje == 'local'      ? 'selected' : '' ?>>Local</option>
                    <option value="foraneo"  <?= $tipo_viaje == 'foraneo'  ? 'selected' : '' ?>>Foraneo</option>
                </select>
            </div>
            <!-- Fecha carga -->
            <div class="col-md-4">
                <label class="form-label">Fecha carga <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="datetime-local" class="form-control" name="fecha_carga" id="fecha_carga" value="<?= $fecha_carga ?>" required>
            </div>
            <!-- Fecha descarga -->
            <div class="col-md-4">
                <label class="form-label">Fecha descarga <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="datetime-local" class="form-control" name="fecha_descarga" id="fecha_descarga" value="<?= $fecha_descarga ?>" required>
            </div>
            <!-- Num repartos -->
            <div class="col-md-4">
                <label class="form-label">Número de repartos <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="number" class="form-control" name="num_repartos" id="num_repartos" value="<?= $num_repartos ?>" min="1" max="10" required oninput="validarRepartos(this)">
            </div>
            <!-- Direcciones de repartos -->
            <div class="col-12" id="contenedor_repartos">
                <!-- Los campos se generan aquí con JS -->
            </div>
        </div>
    </div>
</form>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-target="#serviciosModal" data-bs-dismiss="modal">
        <i class="bi bi-x-circle"></i> Cancelar
    </button>
    <button type="button" class="btn btn-success" onclick="Servicios.guardar()">
        <i class="bi bi-save"></i> Guardar
    </button>
</div>

<script>
    function initBuscador({ inputId, listaId, hiddenId, action, labelKey, apiUrl, fetchAction  }) {
        const input       = document.getElementById(inputId);
        const lista       = document.getElementById(listaId);
        const inputHidden = document.getElementById(hiddenId);
        if (inputHidden.value && fetchAction) {
            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=${fetchAction}&id=${inputHidden.value}`
            })
            .then(res => res.json())
            .then(result => {
                if (result.data) {
                    input.value = result.data[labelKey];
                }
            })
            .catch(err => console.error(`Error precargando [${fetchAction}]:`, err));
        }
        function posicionarLista() {
            const rect = input.getBoundingClientRect();
            lista.style.top   = rect.bottom + 'px';
            lista.style.left  = rect.left + 'px';
            lista.style.width = rect.width + 'px';
        }

        input.addEventListener('input', async function () {
            const valor = this.value.trim();

            if (valor.length < 2) {
                lista.style.display = 'none';
                lista.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=${action}&term=${encodeURIComponent(valor)}`
                });

                const result = await response.json();
                const data   = result.data ?? [];

                lista.innerHTML = '';

                if (data.length === 0) {
                    lista.innerHTML = '<span class="list-group-item text-muted">Sin resultados</span>';
                } else {
                    data.forEach(item => {
                        const el = document.createElement('a');
                        el.classList.add('list-group-item', 'list-group-item-action');
                        el.textContent = item[labelKey];
                        el.href = '#';

                        el.addEventListener('click', (e) => {
                            e.preventDefault();
                            input.value       = item[labelKey];
                            inputHidden.value = item.id;
                            lista.style.display = 'none';
                            lista.innerHTML     = '';

                            if (item.tipo_servicio) {
                                document.getElementById('tipo_servicio').value = item.tipo_servicio;
                            }

                            if (item.origen) {
                                document.getElementById('origen').value = item.origen;
                            }
                        });

                        lista.appendChild(el);
                    });
                }

                posicionarLista();
                lista.style.display = 'block';

            } catch (err) {
                console.error(`Error en buscador [${action}]:`, err);
            }
        });

        document.querySelector('.modal')?.addEventListener('scroll', () => {
            if (lista.style.display !== 'none') posicionarLista();
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !lista.contains(e.target)) {
                lista.style.display = 'none';
                lista.innerHTML = '';
            }
        });
    }
    initBuscador({
        inputId:     'cliente_busqueda',
        listaId:     'lista_clientes',
        hiddenId:    'id_cliente',
        action:      'buscar_clientes',
        fetchAction: 'find_cliente',
        labelKey:    'nombre_razon',
        apiUrl:      '../clientes/clientes.api.php'
    });

    /* Repartos */
    document.getElementById('num_repartos').addEventListener('input', function () {
        const cantidad = parseInt(this.value) || 0;
        const contenedor = document.getElementById('contenedor_repartos');
        contenedor.innerHTML = '';

        for (let i = 1; i <= cantidad; i++) {
            contenedor.innerHTML += `
                <div class="col-md-8 mt-2 mb-3">
                    <label class="form-label">Reparto No.${i} <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input
                            type="text"
                            class="form-control"
                            id="destino_busqueda_${i}"
                            placeholder="Buscar destino por nombre o calle..."
                            autocomplete="off"
                        >
                        <div id="lista_destino_${i}"
                            class="list-group shadow-sm"
                            style="position:fixed;z-index:9999;min-width:200px;display:none;">
                        </div>
                    </div>
                    <input type="hidden" name="id_destino[]" id="id_destino_${i}">
                </div>
            `;
        }

        // Iniciar buscador para cada reparto generado
        for (let i = 1; i <= cantidad; i++) {
            initBuscador({
                inputId:  `destino_busqueda_${i}`,
                listaId:  `lista_destino_${i}`,
                hiddenId: `id_destino_${i}`,
                action:   'buscar_destinos',
                labelKey: 'nombre',
                apiUrl:   '../destinos/destinos.api.php'
            });
        }
    });

    function validarRepartos(input) {
        let valor = parseInt(input.value) || 0;

        if (valor > 10) input.value = 10;
        if (valor < 1) input.value = 1;
    }

</script>

