<!DOCTYPE html>
<html lang="es">

<body>
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-2">
                <li class="breadcrumb-item">
                    <i class="bi bi-house-door me-1"></i>Inicio
                </li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-headset me-1"></i>Servicios
                </li>
            </ol>
        </nav>

        <!-- Encabezado -->
        <div class="row align-items-center my-3">
            <div class="col-md-6">
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-headset text-primary"></i>
                    Gestión de Servicios
                </h2>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-primary shadow-sm"
                        onclick="Servicios.abrirModal('servicio_cliente/formServicios.php')">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Servicio
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-card">
            <div class="row">
                <div class="col-md-12 p-0">
                    <label for="filtroIdServicio" class="form-label small text-muted">
                        <i class="bi bi-search"></i> Buscar
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            name="idServicio"
                            id="filtroIdServicio"
                            class="form-control border-start-0 px-2"
                            placeholder="Buscar...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive my-3">
            <table class="table table-hover table-striped aligmiddle mb-0" id="tablaServicios">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Id Cliente</th>
                        <th>Shipment</th>
                        <th>Tipo de servicio</th>
                        <th>Fecha carga</th>
                        <th>Fecha descarga</th>
                        <th>Id Usuario</th>
                        <th>Num repartos</th>
                        <th>Fecha alta</th>
                        <th class="text-center" style="width: 200px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargan via servicioClientes.js -->
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="row mt-2 align-items-center">
            <div class="col-md-6">
                <div id="info-paginacion-servicios" class="text-muted"></div>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-md-end justify-content-center mb-0"
                        id="paginacion-servicios"></ul>
                </nav>
            </div>
        </div>

        <!-- MODAL SERVICIOS -->
        <div class="modal fade" id="serviciosModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" id="serviciosModalContent" style="overflow-y: auto;">
                </div>
            </div>
        </div>

    </div>
</body>

<script src="servicio_cliente/servicioClientes.js"></script>

</html>