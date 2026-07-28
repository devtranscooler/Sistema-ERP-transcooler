<?php
// Incluir tu archivo de conexión a la base de datos

$db = new MySQL();

// Obtener parámetro de búsqueda si existe
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// Preparar la consulta SQL
if (!empty($busqueda)) {
    // Búsqueda con filtro (ajusta los campos según necesites buscar)
    $sql = "SELECT sc.*, 
            DATE_FORMAT(sc.fec_alta, '%d/%m/%Y %H:%i') as fecha_formateada
            FROM servicios_cliente sc
            WHERE sc.id LIKE '%$busqueda%' 
            OR sc.id_cliente LIKE '%$busqueda%'
            OR sc.id_servicio_cliente LIKE '%$busqueda%'
            ORDER BY sc.id DESC";
} else {
    // Mostrar todos los registros (los más recientes primero)
    $sql = "SELECT sc.*, 
            DATE_FORMAT(sc.fec_alta, '%d/%m/%Y %H:%i') as fecha_formateada
            FROM servicios_cliente sc
            ORDER BY sc.id DESC";
}

// Ejecutar consulta
$resultado = $db->consulta($sql);
?>

<style>
    /* Estilo para el input de búsqueda con icono */
        .search-wrapper {
            position: relative;
        }
        
        .search-wrapper input {
            padding-left: 45px; /* Espacio para el icono */
            border-radius: 8px;
            border: 2px solid #dee2e6;
            transition: all 0.3s;
        }
        
        .search-wrapper input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .search-wrapper .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
            pointer-events: none; /* El icono no interfiere con el click */
        }
</style>
<!-- ========================================
            CONTENEDOR PRINCIPAL
======================================== -->
<div class="tab-content mt-2 p-3 border border-2 rounded bg-info-subtle">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-headset"></i> Servicios a Cliente
        </h2>
        <span class="badge bg-primary" style="font-size: 1rem;">
            Total: <span id="totalRegistros">0</span>
        </span>
    </div>

    <!-- ========================================
        BARRA DE BÚSQUEDA Y BOTÓN AGREGAR
    ======================================== -->
    <div class="row g-1 mb-2">
        <!-- Campo de búsqueda con icono de lupa -->
        <div class="col-md-10">
            <div class="search-wrapper">
                <!-- Icono de lupa dentro del input -->
                <i class="bi bi-search search-icon"></i>

                <!-- Input de búsqueda -->
                <input
                    type="text"
                    class="form-control form-control"
                    id="inputBusqueda"
                    name="buscar"
                    placeholder="Buscar por ID, Cliente, Servicio..."
                    value="<?php echo htmlspecialchars($busqueda); ?>"
                    autocomplete="off">
            </div>
            <!-- Pequeño texto de ayuda -->
            <small class="text-muted ms-2">
                <i class="bi bi-info-circle"></i> La búsqueda se actualiza automáticamente
            </small>
        </div>

        <!-- Botón Agregar que abre el modal -->
        <div class="col-md-2">
            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalAgregarServicio">
                <i class="bi bi-plus-circle"></i> Agregar Servicio
            </button>
        </div>
    </div>

    <!-- ========================================
    TABLA DE RESULTADOS
    ======================================== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="table-responsive tabla-servicios">
                <table class="table table-hover table-striped mb-0" id="tablaServicios">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Cliente</th>
                            <th>ID Servicio</th>
                            <th>Usuario Alta</th>
                            <th>Fecha Alta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTabla">
                        <?php
                        // Aquí mostraremos los resultados de la BD
                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                echo "<tr>";
                                echo "<td><strong>#{$fila['id']}</strong></td>";
                                echo "<td>{$fila['id_cliente']}</td>";
                                echo "<td>{$fila['id_servicio_cliente']}</td>";
                                echo "<td>{$fila['id_usuario_alta']}</td>";
                                echo "<td>{$fila['fecha_formateada']}</td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-sm btn-primary' onclick='verDetalle({$fila['id']})'>
                                            <i class='bi bi-eye'></i>
                                        </button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='sin-resultados'>
                                        <i class='bi bi-inbox' style='font-size: 3rem;'></i><br>
                                        No se encontraron servicios
                                    </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
MODAL PARA AGREGAR NUEVO SERVICIO
============================================================ -->
<div class="modal fade" id="modalAgregarServicio" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Encabezado del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    <i class="bi bi-plus-circle-fill"></i> Agregar Nuevo Servicio al Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Cuerpo del Modal con el Formulario -->
            <div class="modal-body">
                <!-- Formulario que enviará datos por POST -->
                <form id="formServicio" action="procesar_servicio.php" method="POST">

                    <div class="row g-3">

                        <!-- ID / Referencia / Shipment -->
                        <div class="col-md-4">
                            <label for="id_shipment" class="form-label">
                                <i class="bi bi-tag"></i> ID / Referencia / Shipment
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="id_shipment"
                                name="id_shipment"
                                required
                                placeholder="Ej: SHP-2024-001">
                        </div>

                        <!-- Número de Económico -->
                        <div class="col-md-4">
                            <label for="unidad" class="form-label">
                                <i class="bi bi-truck"></i> Número de Económico
                            </label>
                            <select class="form-control" id="unidad" name="unidad" required>
                                <option value="">Seleccione una unidad</option>
                                <option value="213">213</option>
                                <option value="214">214</option>
                                <option value="215">215</option>
                            </select>
                        </div>

                        <!-- Remolque / Refrigeración -->
                        <div class="col-md-4">
                            <label for="equipo" class="form-label">
                                <i class="bi bi-snow"></i> Remolque / Refrigeración
                            </label>
                            <select class="form-control" id="equipo" name="equipo">
                                <option value="">Seleccione equipo</option>
                                <option value="externo">Externo</option>
                            </select>
                        </div>

                        <!-- Nombre del Operador -->
                        <div class="col-md-6">
                            <label for="operador" class="form-label">
                                <i class="bi bi-person"></i> Nombre del Operador
                            </label>
                            <select class="form-control" id="operador" name="operador">
                                <option value="">Seleccione operador</option>
                                <!-- Aquí cargarías los operadores desde tu BD -->
                            </select>
                        </div>

                        <!-- Otro Operador -->
                        <div class="col-md-6">
                            <label for="otro_operador" class="form-label">
                                <i class="bi bi-person-plus"></i> Otro Operador
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="otro_operador"
                                name="otro_operador"
                                placeholder="Si no está en la lista">
                        </div>

                        <!-- Origen -->
                        <div class="col-md-4">
                            <label for="origen" class="form-label">
                                <i class="bi bi-geo-alt"></i> Origen
                            </label>
                            <select class="form-control" id="origen" name="origen">
                                <option value="">Seleccione origen</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Otro Origen -->
                        <div class="col-md-4">
                            <label for="otro_origen" class="form-label">
                                <i class="bi bi-geo"></i> Otro Origen
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="otro_origen"
                                name="otro_origen"
                                placeholder="Si no está en la lista">
                        </div>

                        <!-- Destino General -->
                        <div class="col-md-4">
                            <label for="destino_general" class="form-label">
                                <i class="bi bi-flag"></i> Destino General
                            </label>
                            <select class="form-control" id="destino_general" name="destino_general">
                                <option value="">Seleccione zona</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Destino Específico 1 -->
                        <div class="col-md-6">
                            <label for="destino_especifico" class="form-label">
                                <i class="bi bi-pin-map"></i> Destino Específico 1
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="destino_especifico"
                                name="destino_especifico"
                                placeholder="Dirección específica">
                        </div>

                        <!-- Otro Destino -->
                        <div class="col-md-6">
                            <label for="otro_destino" class="form-label">
                                <i class="bi bi-signpost"></i> Otro Destino
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="otro_destino"
                                name="otro_destino"
                                placeholder="Destino alternativo">
                        </div>

                        <!-- Tipo de Viaje -->
                        <div class="col-md-4">
                            <label for="tipo_viaje" class="form-label">
                                <i class="bi bi-compass"></i> Tipo de Viaje
                            </label>
                            <select class="form-control" id="tipo_viaje" name="tipo_viaje">
                                <option value="">Seleccione tipo</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Cliente Factura -->
                        <div class="col-md-4">
                            <label for="cliente_factura" class="form-label">
                                <i class="bi bi-receipt"></i> Cliente Factura
                            </label>
                            <select class="form-control" id="cliente_factura" name="cliente_factura">
                                <option value="">Seleccione cliente</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Otro Cliente -->
                        <div class="col-md-4">
                            <label for="otro_cliente" class="form-label">
                                <i class="bi bi-building"></i> Otro Cliente
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="otro_cliente"
                                name="otro_cliente"
                                placeholder="Si no está en la lista">
                        </div>

                        <!-- Operación -->
                        <div class="col-md-6">
                            <label for="operacion" class="form-label">
                                <i class="bi bi-gear"></i> Operación
                            </label>
                            <select class="form-control" id="operacion" name="operacion">
                                <option value="">Seleccione operación</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Solicitante -->
                        <div class="col-md-6">
                            <label for="solicitante" class="form-label">
                                <i class="bi bi-person-check"></i> Solicitante
                            </label>
                            <select class="form-control" id="solicitante" name="solicitante">
                                <option value="">Seleccione responsable</option>
                                <!-- Cargar desde BD -->
                            </select>
                        </div>

                        <!-- Fecha y Hora de Carga -->
                        <div class="col-md-3">
                            <label for="f_carga" class="form-label">
                                <i class="bi bi-calendar-date"></i> Fecha de Carga
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                id="f_carga"
                                name="f_carga">
                        </div>
                        <div class="col-md-3">
                            <label for="h_carga" class="form-label">
                                <i class="bi bi-clock"></i> Hora de Carga
                            </label>
                            <input
                                type="time"
                                class="form-control"
                                id="h_carga"
                                name="h_carga">
                        </div>

                        <!-- Fecha y Hora de Descarga -->
                        <div class="col-md-3">
                            <label for="f_descarga" class="form-label">
                                <i class="bi bi-calendar-check"></i> Fecha de Descarga
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                id="f_descarga"
                                name="f_descarga">
                        </div>
                        <div class="col-md-3">
                            <label for="h_descarga" class="form-label">
                                <i class="bi bi-clock-fill"></i> Hora de Descarga
                            </label>
                            <input
                                type="time"
                                class="form-control"
                                id="h_descarga"
                                name="h_descarga">
                        </div>

                        <!-- Separador visual -->
                        <div class="col-md-12">
                            <hr class="my-3">
                            <h5><i class="bi bi-truck-flatbed"></i> Información de Repartos</h5>
                        </div>

                        <!-- Número de Repartos -->
                        <div class="col-md-12">
                            <label for="n_repartos" class="form-label">
                                <i class="bi bi-hash"></i> Número de Repartos
                            </label>
                            <input
                                type="number"
                                class="form-control w-25"
                                id="n_repartos"
                                name="n_repartos"
                                min="0"
                                max="10"
                                placeholder="0">
                        </div>

                        <!-- Destinos de Reparto (10 campos) -->
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 1</label>
                            <input type="text" class="form-control" name="r1" placeholder="Dirección reparto 1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 2</label>
                            <input type="text" class="form-control" name="r2" placeholder="Dirección reparto 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 3</label>
                            <input type="text" class="form-control" name="r3" placeholder="Dirección reparto 3">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 4</label>
                            <input type="text" class="form-control" name="r4" placeholder="Dirección reparto 4">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 5</label>
                            <input type="text" class="form-control" name="r5" placeholder="Dirección reparto 5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 6</label>
                            <input type="text" class="form-control" name="r6" placeholder="Dirección reparto 6">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 7</label>
                            <input type="text" class="form-control" name="r7" placeholder="Dirección reparto 7">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 8</label>
                            <input type="text" class="form-control" name="r8" placeholder="Dirección reparto 8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 9</label>
                            <input type="text" class="form-control" name="r9" placeholder="Dirección reparto 9">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destino Reparto 10</label>
                            <input type="text" class="form-control" name="r10" placeholder="Dirección reparto 10">
                        </div>

                        <!-- CP que Sustituye -->
                        <div class="col-md-12">
                            <label for="cp_sustituye" class="form-label">
                                <i class="bi bi-arrow-repeat"></i> CP que Sustituye
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="cp_sustituye"
                                name="cp_sustituye"
                                placeholder="Código postal que reemplaza">
                        </div>

                    </div>
                </form>
            </div>

            <!-- Footer del Modal con botones -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" form="formServicio" class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar Servicio
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    // Función de búsqueda
    document.getElementById('inputBusqueda').addEventListener('keyup', function() {
        // Obtener el valor que escribió el usuario
        let busqueda = this.value.trim();

        // Si hay búsqueda, hacemos la petición
        if (busqueda.length > 0) {
            // Redirigir con el parámetro de búsqueda
            window.location.href = '?buscar=' + encodeURIComponent(busqueda);
        } else {
            // Si borra todo, mostrar todo
            window.location.href = window.location.pathname;
        }
    });

    // Función para ver detalle de un servicio
    function verDetalle(id) {
        alert('Ver detalle del servicio #' + id);
    }

    // Contar registros en la tabla al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Contar filas de la tabla (excluyendo el encabezado)
        let filas = document.querySelectorAll('#tablaServicios tbody tr');
        let total = filas.length;

        // Actualizar el badge con el total
        document.getElementById('totalRegistros').textContent = total;
    });

    // Validación del formulario antes de enviar
    document.getElementById('formServicio').addEventListener('submit', function(e) {
        // Aquí puedes agregar validaciones personalizadas
        let shipment = document.getElementById('id_shipment').value;
        let unidad = document.getElementById('unidad').value;

        if (!shipment || !unidad) {
            e.preventDefault(); // Detener el envío
            alert('Por favor completa los campos obligatorios');
            return false;
        }
        //console.log('Enviando formulario...');
    });
</script>