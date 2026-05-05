<?php
    $data = $_POST;

    $servicio = json_decode($data['servicio'], true);
    $repartos = json_decode($data['repartos'], true);    

    // Datos del servicio
    $id               = $servicio['id'] ?? null;
    $shipment         = $servicio['shipment'] ?? 'N/A';
    $fecha_carga      = $servicio['fecha_carga'] ?? 'N/A';
    $fecha_descarga   = $servicio['fecha_descarga'] ?? 'N/A';
    $tipo_servicio    = $servicio['tipo_servicio'] ?? 'N/A';
    $fec_alta         = $servicio['fec_alta'] ?? 'N/A';
    $tipo_viaje       = $servicio['tipo_viaje'] ?? 'N/A';
    $origen           = $servicio['origen'] ?? 'N/A';
    $status           = $servicio['status'] ?? 'N/A';
    $nombreUsuarioAlta= $servicio['nombreUsuarioAlta'] ?? 'N/A';
    $num_repartos = $servicio['num_repartos'] ?? 'N/A';

    //Datos de operador y unidad
    $nombre_razon     = $servicio['nombre_razon'] ?? 'N/A';
    $eco              = $servicio['eco'] ?? 'N/A';
    $nombreOperador   = $servicio['nombreOperador'] ?? 'N/A';

    $badge = match($status) {
        'activo'    => 'success',
        'eliminado' => 'danger',
        default     => 'secondary'
    }
?>

<head><link rel="stylesheet" href="/styles/style.css"></head>

<div class="modal-header servicio-modal">
    <h5 class="modal-title">
        <i class="bi bi-journal-text me-2"></i> Detalle del servicio
        <span class="badge bg-<?= $badge ?> ms-2" style="font-size: 0.75rem"><?= ucfirst($status) ?></span>
    </h5>
</div>

<div class="modal-body servicio-modal">

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">
                <i class="bi bi-info-circle-fill"></i> Información general
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link gx-2" id="pills-repartos-tab" data-bs-toggle="pill" data-bs-target="#pills-repartos" type="button" role="tab" aria-controls="pills-repartos" aria-selected="false">
                <i class="bi bi-box2-heart-fill"></i> Repartos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link gx-2" id="pills-images-tab" data-bs-toggle="pill" data-bs-target="#pills-images" type="button" role="tab" aria-controls="pills-images" aria-selected="false">
                <i class="bi bi-images"></i> Imagenes
            </button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
            <section>

                <!-- IDENTIFICACIÓN -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-hash" style="font-size: 0.85rem"></i> Identificación
                    </div>
                    <div class="section-content">
                        <div class="info-field">
                            <div class="info-label">ID Servicio</div>
                            <div class="info-value">#<?= $id ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Shipment</div>
                            <div class="info-value"><?= $shipment ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Cliente</div>
                            <div class="info-value"><?= $nombre_razon ?></div>
                        </div>
                    </div>
                </div>

                <!-- OPERACIÓN -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-truck" style="font-size: 0.85rem"></i> Operación
                    </div>
                    <div class="section-content">
                        <div class="info-field">
                            <div class="info-label">Eco unidad</div>
                            <div class="info-value"><?= $eco ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Operador</div>
                            <div class="info-value"><?= $nombreOperador ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Tipo de servicio</div>
                            <div class="info-value"><?= ucfirst($tipo_servicio) ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Tipo de viaje</div>
                            <div class="info-value"><?= ucfirst($tipo_viaje) ?></div>
                        </div>
                    </div>
                </div>              
                
                <!-- REPARTOS -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-truck" style="font-size: 0.85rem"></i> Repartos
                    </div>
                    <div class="section-content">
                        <div class="info-field">
                            <div class="info-label">Inicio de ruta:</div>
                            <div class="info-value"><?= $repartos[0]['origen_inicio'] ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Destino de ruta:</div>
                            <div class="info-value" style="text-transform: uppercase;"><?= end($repartos)['destino_final'] ?></div>
                        </div>
                    </div>   
                </div>

                <!-- FECHAS -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-calendar3" style="font-size: 0.85rem"></i> Fechas
                    </div>
                    <div class="section-content">
                        <div class="info-field">
                            <div class="info-label">Fecha de carga</div>
                            <div class="info-value"><?= $fecha_carga ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Fecha de descarga</div>
                            <div class="info-value"><?= $fecha_descarga ?></div>
                        </div>
                        <div class="info-field">
                            <div class="info-label">Fecha de alta</div>
                            <div class="info-value"><?= $fec_alta ?></div>
                        </div>
                    </div>
                </div>

                <!-- REGISTRO -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-person-check" style="font-size: 0.85rem"></i> Registro
                    </div>
                    <div class="section-content">
                        <div class="info-field">
                            <div class="info-label">Usuario alta</div>
                            <div class="info-value"><?= $nombreUsuarioAlta ?></div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
        <div class="tab-pane fade" id="pills-images" role="tabpanel" aria-labelledby="pills-images-tab">
            <section>
                <div class="row justify-content-start" id="content-tab-images"></div>
            </section>
        </div>
        <div class="tab-pane fade" id="pills-repartos" role="tabpanel" aria-labelledby="pills-repartos-tab">
            <section>
                <!-- Repartos -->
                <div class="info-section">                      
                    <?php if (!empty($repartos)): ?>
                        <div class="row">
                        <?php foreach ($repartos as $index => $reparto): ?>
                            <div class="col-12 col-md-12">
                                <div class="reparto-card my-2">
                                    <div class="reparto-header">
                                        Reparto <?= $index + 1 ?>
                                    </div>
                                    <div class="reparto-body">
                                        <?php if (!empty($reparto['origen'])): ?>
                                            <p>
                                                <strong>Origen:</strong>
                                                <?= $reparto['origen'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($reparto['origen_inicio'])): ?>
                                            <p>
                                                <strong>Origen inicial:</strong>
                                                <?= $reparto['origen_inicio'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <p>
                                            <strong>Destino:</strong>
                                            <?= $reparto['destino'] ?>
                                        </p>
                                        <?php if (!empty($reparto['destino_final'])): ?>
                                            <p>
                                                <strong>Destino final:</strong>
                                                <?= $reparto['destino_final'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="productos-section">
                                            <strong>Productos a entregar:</strong>
                                            <?php if (!empty($reparto['productos'])): ?>
                                                <table class="productos-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Cantidad</th>
                                                            <th>Peso</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($reparto['productos'] as $producto): ?>
                                                            <tr>
                                                                <td><?= $producto['producto_nombre'] ?></td>
                                                                <td><?= $producto['cantidad'] ?></td>
                                                                <td><?= $producto['peso'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p>No hay productos registrados.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<div class="modal-footer servicio-modal">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Cerrar
    </button>
</div>

<script>    

    document.getElementById("pills-images-tab").addEventListener("click", () => {
        getMediaByService();
    });

    function getMediaByService() {
        const urlDomain = window.location.origin
        fetch(`${urlDomain}/public/index.php/api/media?tipo_recurso=SERVICIO&tipo_recurso_id=${<?= $id ?>}`)
        .then(r => r.json())
        .then(res => {
            if (res.data) {
                printGallery(res.data);
            } else {
                emptyMediaFiles()
            }
        })
        .catch(err => {
            console.error(err)
            
        });
    }

    function generateCard(idMedia, path, autor) {
        const col = document.createElement("div");
        col.className = "col-md-3";
        col.className += " my-2"; 

        col.innerHTML = `
            <div class="card shadow h-100">
                <img src="${path}" 
                    class="card-img-top object-fit-cover p-1 rounded-3"
                    alt="${idMedia}" 
                    loading="lazy"
                    style="width: 100%; height: 200px;">
                
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="text-secondary" style="font-size: 14px">
                            Subido por
                        </span>
                        <button class="btn btn-primary btn-sm mb-0">
                            ${autor}
                        </button>
                    </div>
                </div>
            </div>
        `;

        return col;
    }

    function printGallery(data) {
        const contenedor = document.getElementById("content-tab-images");
        contenedor.innerHTML = ""; // limpiar antes de pintar


        data.forEach(item => {
            const rutaCompleta = `https://storage.googleapis.com/transcooler/${item.ruta}`; // ajusta según tu servidor

            const autor = `${item.nombre} ${item.apellidoP}`;

            const card = generateCard(data.id_media, rutaCompleta, autor);

            contenedor.appendChild(card);
        });
    }

    function emptyMediaFiles() {
        const contenedor = document.getElementById("content-tab-images");
        contenedor.innerHTML = ""

        const col = document.createElement("div");
        col.innerHTML = `
            <div class="mt-2 d-flex flex-column align-items-center gap-2">
                <p class="fs-4"> No hay imagenes relacionadas a este servicio </p>
                <i class="bi bi-images fs-2"></i>
            </div>
        `;

        contenedor.appendChild(col)
    }
</script>