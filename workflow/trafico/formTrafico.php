<?php
$data = $_POST;
$servicio = json_decode($data['servicio'], true); 

$id = $servicio['id'] ?? null;
$id_operador = $_POST['id_operador'] ?? null;
$id_unidad = $_POST['id_unidad'] ?? null;
$id_remolque = $_POST['id_remolque'] ?? null;
$id_remolque2 = $_POST['id_remolque2'] ?? null;
$id_dolly = $_POST['id_dolly'] ?? null;
$config_vehicular = $_POST['config_vehicular'] ?? null;
?>
<div class="modal-header">
    <h5 class="modal-title">
        Asignar Operador / Unidad
    </h5>
</div>

<form id="formServiciosTrafico">
    <div class="modal-body">

        <!-- Campos ocultos: se reenvían tal cual para no pisar datos del servicio -->
        <input type="hidden" name="action" value="agregarOperadorUnidad">
        <input type="hidden" name="id" value="<?= $id ?>">

        <h5 class="border-bottom pb-2 mb-3" style="color: #007AA3">
            <i class="bi bi-truck me-1"></i> Asignación de unidad y operador
        </h5>

        <div class="row g-3">
            <!-- Operador -->
            <div class="col-md-6">
                <label class="form-label">
                    Nombre del operador <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="operador_busqueda"
                        placeholder="Buscar operador..."
                        autocomplete="off">

                    <div id="lista_operadores"
                        class="list-group shadow-sm"
                        style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
                </div>
                <input type="hidden" name="id_operador" id="id_operador" value="<?= $id_operador ?>">
            </div>
            <!-- Número económico (unidad) -->
            <div class="col-md-6">
                <label class="form-label">
                    Número económico <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="eco_busqueda"
                        placeholder="Buscar unidad..."
                        autocomplete="off">

                    <div id="lista_unidades"
                        class="list-group shadow-sm"
                        style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
                </div>
                <input type="hidden" name="id_unidad" id="id_unidad" value="<?= $id_unidad ?>">
            </div>
            <!-- Seleccionar numero de remolques -->
            <div id="input_num_remolques" class="col-md-6" style="display: none;">
                <label class="form-label">
                    Número de remolques <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="number"
                        class="form-control"
                        id="numero_remolques"
                        placeholder="Cantidad de remolques..."
                        max="2"
                        min="1"
                        oninput="if (this.value > 2) this.value = 2;">
                </div>                
            </div>
            <!-- Remolque 1 — oculto por defecto -->
            <div id="campo_remolque1" class="col-md-6" style="display: none;">
                <label class="form-label">
                    Eco remolque <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="remolque1_busqueda"
                        placeholder="Buscar remolque..."
                        autocomplete="off">
                    <div id="lista_remolque1"
                        class="list-group shadow-sm"
                        style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
                </div>
                <input type="hidden" name="id_remolque" id="id_remolque" value="<?= $id_remolque ?>">
            </div>

            <!-- Remolque 2 — oculto por defecto -->
            <div id="campo_remolque2" class="col-md-6" style="display: none;">
                <label class="form-label">
                    Eco segundo remolque <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="remolque2_busqueda"
                        placeholder="Buscar remolque..."
                        autocomplete="off">
                    <div id="lista_remolque2"
                        class="list-group shadow-sm"
                        style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
                </div>
                <input type="hidden" name="id_remolque2" id="id_remolque2" value="<?= $id_remolque2 ?>">
            </div>

            <!-- Dolly en caso de dos remolques — oculto por defecto -->
            <div id="campo_dolly" class="col-md-6" style="display: none;">
                <label class="form-label">
                    Eco dolly <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                        class="form-control"
                        id="dolly_busqueda"
                        placeholder="Buscar dolly..."
                        autocomplete="off">
                    <div id="lista_dolly"
                        class="list-group shadow-sm"
                        style="position: fixed; z-index: 9999; min-width: 200px; display: none;"></div>
                </div>
                <input type="hidden" name="id_dolly" id="id_dolly" value="<?= $id_dolly ?>">
            </div>

            <!-- Configuracion vehicular — oculto por defecto -->
            <div id="conf_vehicular" class="col-md-6">
                <label class="form-label">
                    Coniguración vehicular <span class="text-danger">*</span>
                </label>
                <input class="form-control" type="text" name="config_vehicular" id="config_vehicular" value="<?= $config_vehicular ?>" placeholder="Configuración vehicular...">
            </div>
        </div>

        <!-- modal -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-target="#traficoModal" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
            </button>
            <button type="button" class="btn btn-success" onclick="Trafico.guardar()">
                <i class="bi bi-save"></i> Guardar
            </button>
        </div>

</form>

<script>
    function initBuscador({
        inputId,
        listaId,
        hiddenId,
        action,
        labelKey,
        apiUrl,
        fetchAction,
        onSelect
    }) {
        const input = document.getElementById(inputId);
        const lista = document.getElementById(listaId);
        const inputHidden = document.getElementById(hiddenId);

        // Si algún elemento no existe en el DOM simplemente salimos (evita el crash)
        if (!input || !lista || !inputHidden) {
            console.warn(`initBuscador: no se encontró algún elemento (${inputId}, ${listaId}, ${hiddenId})`);
            return;
        }

        // Precargar el label si ya hay un id guardado
        if (inputHidden.value && fetchAction) {
            fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=${fetchAction}&id=${inputHidden.value}`
                })
                .then(res => res.json())
                .then(result => {
                    if (result.data) input.value = result.data[labelKey];
                })
                .catch(err => console.error(`Error precargando [${fetchAction}]:`, err));
        }

        function posicionarLista() {
            const rect = input.getBoundingClientRect();
            lista.style.top = rect.bottom + 'px';
            lista.style.left = rect.left + 'px';
            lista.style.width = rect.width + 'px';
        }

        input.addEventListener('input', async function() {
            const valor = this.value.trim();

            if (valor.length < 2) {
                lista.style.display = 'none';
                lista.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=${action}&term=${encodeURIComponent(valor)}`
                });

                const result = await response.json();
                const data = result.data ?? [];

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
                            input.value = item[labelKey];
                            inputHidden.value = item.id;
                            lista.style.display = 'none';
                            lista.innerHTML = '';
                            if (onSelect) onSelect(item);
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

    /* Buscar unidades */
    initBuscador({
        inputId: 'eco_busqueda',
        listaId: 'lista_unidades',
        hiddenId: 'id_unidad',
        action: 'buscar_unidades',
        fetchAction: 'find_unidad',
        labelKey: 'eco',
        apiUrl: '../unidades/unidades.api.php',

        onSelect: (item) => {
            const campoRemolques = document.getElementById('input_num_remolques');
            const inputRemolques = document.getElementById('numero_remolques');
            const tiposConRemolque = ['5a Rueda', 'Full'];

            if (tiposConRemolque.includes(item.tipo_unidad)) {
                campoRemolques.style.display = 'block';
                inputRemolques.setAttribute('required', 'required');
            } else {
                campoRemolques.style.display = 'none';
                inputRemolques.removeAttribute('required');
                inputRemolques.value = '';
                toggleRemolque(0);
            }
        }
    });

    /* Buscar operadores */
    initBuscador({
        inputId: 'operador_busqueda',
        listaId: 'lista_operadores',
        hiddenId: 'id_operador',
        action: 'buscar_operadores',
        fetchAction: 'find_operador',
        labelKey: 'nombreOperador',
        apiUrl: '../usuarios/usuarios.api.php'
    });

    // Listener que controla qué campos de remolque se muestran
    document.getElementById('numero_remolques').addEventListener('input', function() {
        const cantidad = parseInt(this.value);
        toggleRemolque(cantidad);
    });

    function toggleRemolque(cantidad) {
        const campo1 = document.getElementById('campo_remolque1');
        const campo2 = document.getElementById('campo_remolque2');
        const campo3 = document.getElementById('campo_dolly');
        const input1 = document.getElementById('id_remolque');
        const input2 = document.getElementById('id_remolque2');
        const input3 = document.getElementById('id_dolly');
        const busq1 = document.getElementById('remolque1_busqueda');
        const busq2 = document.getElementById('remolque2_busqueda');
        const busq3 = document.getElementById('dolly_busqueda');

        if (cantidad >= 1) {
            campo1.style.display = 'block';
            input1.setAttribute('required', 'required');
            input1.removeAttribute('disabled');
        } else {
            campo1.style.display = 'none';
            input1.removeAttribute('required');
            input1.setAttribute('disabled', 'disabled');
            input1.value = '';
            busq1.value = '';
        }

        if (cantidad === 2) {
            campo2.style.display = 'block';
            input2.setAttribute('required', 'required');
            input2.removeAttribute('disabled');
            /* Mostrar campos de dolly */
            campo3.style.display = 'block';
            input3.setAttribute('required', 'required');
            input3.removeAttribute('disabled');
        } else {
            campo2.style.display = 'none';
            input2.removeAttribute('required');
            input2.setAttribute('disabled', 'disabled');
            input2.value = '';
            busq2.value = '';
            /* Quitar campos de dolly */
            campo3.style.display = 'none';
            input3.removeAttribute('required');
            input3.setAttribute('disabled', 'disabled');
            input3.value = '';
            busq3.value = '';
        }
    }
    /* Buacsrdor remolque 1 */
    initBuscador({
        inputId: 'remolque1_busqueda',
        listaId: 'lista_remolque1',
        hiddenId: 'id_remolque',
        action: 'buscar_remolques',
        fetchAction: 'find_remolque',
        labelKey: 'eco',
        apiUrl: '../unidades/unidades.api.php'
    });
    /* Buacsrdor remolque 2 */
    initBuscador({
        inputId: 'remolque2_busqueda',
        listaId: 'lista_remolque2',
        hiddenId: 'id_remolque2',
        action: 'buscar_remolques',
        fetchAction: 'find_remolque',
        labelKey: 'eco',
        apiUrl: '../unidades/unidades.api.php'
    });
    /* Buacsrdor dolly */
    initBuscador({
        inputId: 'dolly_busqueda',
        listaId: 'lista_dolly',
        hiddenId: 'id_dolly',
        action: 'buscar_dollys',
        fetchAction: 'find_dolly',
        labelKey: 'eco',
        apiUrl: '../unidades/unidades.api.php'
    });


</script>
