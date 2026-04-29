<!DOCTYPE html>
<html lang="es">

<body>  
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <i class="bi bi-house-door me-1"></i>Inicio
                </li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-truck me-1"></i>Mesa de control
                </li>
            </ol>
        </nav>

        <!-- Encabezado -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-truck text-primary"></i>
                    Asignación de producto
                </h2>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-card">
            <div class="row">
                <div class="col-md-12">
                    <label for="filtroIdServicioMesaControl" class="form-label small text-muted">
                        <i class="bi bi-search"></i> Buscar
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            name="idServicio"
                            id="filtroIdServicioMesaControl"
                            class="form-control border-start-0 ps-0"
                            placeholder="Buscar...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive mb-0">
            <table class="table table-hover aligmiddle mb-0" id="tablaServiciosMesaControl">
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
                    <!-- Los datos se cargan via servicioMesaControl.js -->
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="row mt-2 align-items-center">
            <div class="col-md-6">
                <div id="info-paginacion-mesa-control" class="text-muted"></div>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-md-end justify-content-center mb-0" id="paginacion-mesa-control"></ul>
                </nav>
            </div>
        </div>

        <!-- MODAL TRÁFICO -->
        <div class="modal fade" id="mesaControlModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" id="mesaControlModalContent" style="overflow-y: auto;">
                </div>
            </div>
        </div>

    </div>
</body>

<script src="mesa-de-control/servicioMesaControl.js"></script>

</html>