<?php
$repartos = isset($_POST['repartos']) 
    ? json_decode($_POST['repartos'], true) 
    : [];

$servicio = isset($_POST['servicio']) 
    ? json_decode($_POST['servicio'], true) 
    : [];

    
$id = $servicio['id'] ?? null;
$id_producto = $_POST['id_producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;
$peso_kg = $_POST['peso_kg'] ?? null;
$ruta = $_POST['ruta'] ?? null;
$kilometros = $_POST['kilometros'] ?? null;
$montoFlete = $_POST['montoFlete'] ?? null;
$folioPermiso = $_POST['folioPermiso'] ?? null;
$tipoPermiso = $_POST['tipoPermiso'] ?? null;
?>
<div class="modal-header">
    <h5 class="modal-title">
        Detalles de servicio
    </h5>
</div>

<form id="formServiciosMesaControl">
    <div class="modal-body">
        <!-- Campos ocultos: se reenvían tal cual para no pisar datos del servicio -->
        <input type="hidden" name="action" value="agregarProductosPermisos">
        <input type="hidden" name="servicio_id" value="<?= $id ?>">
        <div class="row g-3">
            <!-- Ruta -->
            <div class="col-md-12">
                <label class="form-label">Ruta <span class="text-danger"><span class="text-danger">*</span></span></label> 
                <div class="container full-width" style="margin: 10px;">
                    <div class="info-field box">
                        <div class="info-label">Origen de ruta:</div>
                        <div class="info-value"><?= $repartos[0]['origen_inicio'] ?></div>
                    </div>
                    <div class="info-field box">
                        <div class="info-label">Destino de ruta:</div>
                        <div class="info-value" style="text-transform: uppercase;"><?= end($repartos)['destino_final'] ?></div>
                    </div>
                </div>        
            </div>
            <!-- Kilometros -->
            <div class="col-md-6">
                <label class="form-label">Kilometros <span class="text-danger"><span
                            class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="kilometros" id="kilometros" value="<?= $kilometros ?>"
                    required placeholder="Kilometros de viaje...">
            </div>
            <!-- Monto del Flete -->
            <div class="col-md-6">
                <label class="form-label">Monto total del flete <span class="text-danger"><span
                            class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="montoFlete" id="montoFlete" value="<?= $montoFlete ?>"
                    required placeholder="Total del flete...">
            </div>

            <!-- Permisos de autotransporte -->
            <div class="col-md-6">
                <label class="form-label">Permiso de autotransporte <span class="text-danger"><span class="text-danger">*</span></span></label>
                <input type="text" class="form-control" name="tipoPermiso" id="tipoPermiso" value="<?= $tipoPermiso ?>" required placeholder="Permiso...">
            </div>
            <div class="col-md-6">   
                <label class="form-label">Folio de permiso <span class="text-danger"><span class="text-danger">*</span></span></label>             
                <input type="text" class="form-control" name="folioPermiso" id="folioPermiso" value="<?= $folioPermiso ?>" required placeholder="Folio...">
            </div>
            <!-- Producto -->
            <h5 class="border-bottom pb-2 mb-3" style="color: #007AA3">
                <i class="bi bi-truck me-1"></i> Asignación de productos
            </h5>
            <?php foreach ($repartos as $reparto): ?>
                <div class="card mb-3 p-3">
                    <h6>Entrega para <?= $reparto['destino'] ?></h6>

                    <div class="productos-reparto" data-reparto="<?= $reparto['id'] ?>">
                        <!-- Aquí se agregan productos dinámicamente -->
                    </div>

                    <button type="button" class="btn btn-sm btn-primary mt-2"
                        onclick="agregarProducto(<?= $reparto['id'] ?>)">
                        + Agregar producto
                    </button>
                </div>
            <?php endforeach; ?>
            

        <!-- modal -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-target="#mesaControlModal" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
            </button>
            <button type="button" class="btn btn-success" onclick="MesaControl.guardar()">
                <i class="bi bi-save"></i> Guardar
            </button>
        </div>
    </div>

</form>

<script>
    function initBuscadorProducto(container) {
        const input = container.querySelector('.producto_busqueda');
        const lista = container.querySelector('.lista_productos');
        const hidden = container.querySelector('.producto_id');

        if (!input || !lista || !hidden) return;

        function posicionarLista() {
            const rect = input.getBoundingClientRect();

            lista.style.top = rect.bottom + 'px';
            lista.style.left = rect.left + 'px';
            lista.style.width = rect.width + 'px';
        }

        input.addEventListener('input', async function () {

            const valor = this.value.trim();

            if (valor.length < 3) {
                lista.style.display = 'none';
                lista.innerHTML = '';
                return;
            }

            try {

                const query = new URLSearchParams({
                    nombre: valor,
                    page: 1,
                    per_page: 5
                });

                const response = await fetch(
                    `${window.location.origin}/public/index.php/api/products?${query}`,
                    {
                        method: 'GET'
                    }
                );

                const result = await response.json();

                const data = result.data ?? [];

                lista.innerHTML = '';

                if (data.length === 0) {

                    lista.innerHTML = `
                        <span class="list-group-item text-muted">
                            Sin resultados
                        </span>
                    `;

                } else {

                    data.forEach(item => {

                        const el = document.createElement('a');

                        el.href = '#';

                        el.classList.add(
                            'list-group-item',
                            'list-group-item-action'
                        );

                        el.textContent = item.nombre;

                        el.addEventListener('click', (e) => {

                            e.preventDefault();

                            input.value = item.nombre;

                            hidden.value = item.id;

                            lista.style.display = 'none';

                            lista.innerHTML = '';
                        });

                        lista.appendChild(el);
                    });
                }

                posicionarLista();

                lista.style.display = 'block';

            } catch (error) {

                console.error('Error buscando productos:', error);
            }
        });

        document.addEventListener('click', (e) => {

            if (!input.contains(e.target) &&
                !lista.contains(e.target)) {

                lista.style.display = 'none';
            }
        });
    }

    function agregarProducto(repartoId) {

        const contenedor = document.querySelector(
            `.productos-reparto[data-reparto="${repartoId}"]`
        );

        const index = contenedor.children.length;

        const uniqueId = `producto_${repartoId}_${index}`;

        const html = `
            <div class="row mb-3 producto-item border rounded p-2">

                <input type="hidden"
                    name="productos[${repartoId}][${index}][reparto_id]"
                    value="${repartoId}">

                <!-- PRODUCTO -->
                <div class="col-md-4">
                    <label class="form-label">
                        Producto
                    </label>

                    <div class="position-relative">

                        <input type="text"
                            class="form-control producto_busqueda"
                            placeholder="Buscar producto..."
                            autocomplete="off">

                        <div class="list-group shadow-sm lista_productos"
                            style="position: fixed;
                                z-index: 9999;
                                min-width: 200px;
                                display: none;">
                        </div>

                    </div>

                    <input type="hidden"
                        class="producto_id"
                        name="productos[${repartoId}][${index}][producto_id]">
                </div>

                <!-- CANTIDAD -->
                <div class="col-md-3">
                    <label class="form-label">
                        Cantidad
                    </label>

                    <input type="number"
                        class="form-control"
                        name="productos[${repartoId}][${index}][cantidad]"
                        min="1">
                </div>

                <!-- PESO -->
                <div class="col-md-3">
                    <label class="form-label">
                        Peso kg
                    </label>

                    <input type="number"
                        class="form-control"
                        name="productos[${repartoId}][${index}][peso]"
                        min="1">
                </div>

                <!-- ELIMINAR -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button"
                        class="btn btn-danger w-100"
                        onclick="this.closest('.producto-item').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

            </div>
        `;

        contenedor.insertAdjacentHTML("beforeend", html);

        const nuevaFila = contenedor.lastElementChild;

        initBuscadorProducto(nuevaFila);
    }
</script>