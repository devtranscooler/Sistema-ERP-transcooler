<?php
    require '../system/connection.php';
    require '../system/constants.php';
?>

<!DOCTYPE html>
    <html lang="es">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
        
        <title> Mesa de control </title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function closeMenu(event) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('open') && !sidebar.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        }
        </script>

        <style>

            body {
                font-family: "Nunito", sans-serif;
                font-optical-sizing: auto;
            }

            .pulse-button {
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {
                    0% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(37, 24, 209, 0.7);
                }
                    70% {
                    transform: scale(1.05);
                    box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
                }
                    100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
                }
            }

            .delivery-status-btn{
                transition: all .2s ease;
            }

            .delivery-status-btn:hover{
                transform: translateY(-1px);
            }

            .active-status{
                background-color: #212529 !important;
                color: white !important;
                border-color: #212529 !important;
            }

            .product-skeleton {
                padding: 18px 10px;
                border-bottom: 1px solid #e9ecef;
            }

            .skeleton-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                flex-shrink: 0;
            }

            .skeleton-box {
                width: 84px;
                height: 52px;
                border-radius: 12px;
            }

            .skeleton-title {
                width: 220px;
                height: 18px;
                border-radius: 6px;
            }

            .skeleton-subtitle {
                width: 140px;
                height: 14px;
                border-radius: 6px;
            }

            @media (max-width: 576px) {
                .skeleton-title {
                    width: 140px;
                }

                .skeleton-subtitle {
                    width: 100px;
                }

                .skeleton-box {
                    width: 70px;
                }
            }

    </style>

    </head>
    <body onclick="closeMenu(event)">

        <?php require_once '../utilities/sidebar.php'; Sidebar::render("Workflow"); ?>

        <input type="hidden" name="auth_user_id" id="auth_user_id" value="<?= $_SESSION['ID_USUARIO'] ?? null ?> ">

        <div class="container-fluid p-3">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb my-2">
                    <li class="breadcrumb-item">
                        <i class="bi bi-house-door me-1"></i> Inicio
                    </li>
                    <li class="breadcrumb-item active">
                        <i class="bi bi-headset me-1"></i> Mesa control 
                    </li>
                </ol>
            </nav>
            <!-- Termina Breadcrumb -->

            <!--  -->
            <div class="row align-items-center my-3">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-headset text-primary"></i>
                            Mesa de control
                    </h2>
                </div>
            </div>

            <div class="mb-3">
                <div class="row g-2 d-flex justify-content-end">
                    <div class="col-12 col-md-2">
                        <div class="w-100">
                            <div class="dropdown w-100">
                                <button id="btnFiltroEstatusMesaControl" class="btn btn-secondary border dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-truck"></i>  Filtrar por tipo de unidad
                                </button>
                                <ul class="dropdown-menu px-1" id="dropdownFiltroEstatusMesaControl">
                                    <li>
                                        <a class="dropdown-item active rounded" href="#" data-unit-type="">
                                            Todo
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="w-100">
                            <div class="dropdown w-100">
                                <button id="btnFilterCustomerType" class="btn btn-light border dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person"></i>  Filtrar por tipo de cliente
                                </button>
                                <ul class="dropdown-menu px-1 dropdown-filter-customer-type" id="filterCustomerType">

                                    <li>
                                        <a class="dropdown-item dropdown-item-customer-type" href="#" data-customer-type="all">
                                            Todo
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item dropdown-item-customer-type" href="#" data-customer-type="spot">
                                            Spot
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item dropdown-item-customer-type" href="#" data-customer-type="nacional">
                                            Nacional 
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item dropdown-item-customer-type" href="#" data-customer-type="extranjero">
                                            Extranjero
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-0">
                <table class="table table-hover table-striped aligmiddle mb-0" id="tablaServiciosMesaControl">
                    <thead>
                        <tr>
                            <th> Unidad </th>
                            <th> Servicio </th>
                            <th> Última etapa servicio </th>
                            <th> Operador </th>
                            <th> Fecha descarga </th>
                            <th> Dias desde descarga </th>
                            <th class="text-center" style="width: 200px;"> Acciones </th>
                        </tr>
                    </thead>
                    <tbody id="container-services"></tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center my-4">
                        <div id="pagination-container" class="d-flex justify-content-center mt-4"> </div>
                    </div>
                </div>
            </div>
            <!-- Termina Paginación -->

            <!-- Modal Información archivo -->
            <div class="modal fade" id="serviceLogDetailModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titleItemDetailLogService">
                                <i class="bi bi-info-circle"></i>
                                    Historial del servicio
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="container-detail-log-service">
                                <!-- INFORMACION GENERAL -->
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <small class="text-muted d-block"> Servicio </small>
                                                <span class="fw-bold" id="service-number-detail"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block"> Placas </small>
                                                <span class="fw-bold" id="service-license-plates-unit-detail"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block"> Tipo unidad </small>
                                                <span class="fw-bold" id="service-unit-type-detail"></span>
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-12">
                                                <small class="text-muted d-block"> Operador </small>
                                                <span class="fw-bold" id="service-operator-name-detail"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TITULO HISTORIAL -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0"> Historial del servicio </h6>
                                    <span class="badge bg-primary" id="service-total-logs-detail"></span>
                                </div>

                                <!-- TIMELINE -->
                                <div class="position-relative ps-4" style="max-height: 400px; overflow-y:auto;">
                                    <!-- LINEA VERTICAL -->
                                    <div class="position-absolute top-0 bottom-0" style="left: 10px; width:2px; background:#dee2e6;"></div>
                                    
                                    <div id="container-record-logs-services"></div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal Información archivo -->

            <!-- Modal enviar recordatorio servicio -->
            <div class="modal fade" id="serviceReminderEmailModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titleReminderEmailodal">
                                <i class="bi bi-envelope"></i>
                                    Enviar alerta de estatus de servicio
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">

                                <!-- Usuario -->
                                <div class="mb-4">
                                    <label for="recipient_user_id" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i> Usuario destinatario
                                    </label>

                                    <select id="recipient_user_id" name="recipient_user_id" class="form-select"></select>

                                    <div class="form-text mt-1">
                                        El usuario seleccionado recibirá un correo electrónico con el
                                        estado actual del servicio.
                                    </div>
                                </div>

                                <!-- Comentarios -->
                                <div>

                                    <label for="comentario_reminder" class="form-label fw-semibold">
                                        <i class="bi bi-chat-left me-2 text-primary"></i> Comentarios
                                        <span class="text-muted fw-normal"> (Opcional) </span>
                                    </label>

                                    <textarea
                                        id="comentario_reminder"
                                        rows="5"
                                        class="form-control shadow-sm"
                                        maxlength="500"
                                        placeholder="Agrega información adicional para el destinatario..."></textarea>

                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">
                                            Este comentario será incluido en el correo.
                                        </small>
                                        <small class="text-muted">
                                            0 / 500
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="container-fluid mt-4">
                                <div class="alert alert-light border d-flex align-items-start mb-4">
                                    <i class="bi bi-envelope text-primary fs-4 me-3"></i>
                                    <div>
                                        <strong>
                                            Recordatorio
                                        </strong>
                                        <div class="small text-muted">
                                            Se enviará un correo al usuario seleccionado con la información
                                            actual del servicio y su etapa para dar seguimiento.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="button" id="btn-send-reminder-email" class="btn btn-primary"> Enviar </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal enviar recordatorio servicio -->

            <!-- Modal actualizar estatus servicio -->
            <div class="modal fade" id="stageServicestatusUpdateModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titleReminderEmailodal">
                                <i class="bi bi-arrow-clockwise"></i>
                                    Actualizar estatus de servicio
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">

                                <!-- Etapa -->
                                <div class="mb-4">
                                    <label for="update_etapa_servicio_id" class="form-label fw-semibold">
                                        <i class="bi bi-list me-2 text-primary"></i> Seleccionar etapa de servicio
                                    </label>
                                    <select id="update_etapa_servicio_id" name="update_etapa_servicio_id" class="form-select"></select>
                                </div>

                                <!-- Estatus etapa -->
                                <div class="mb-4">
                                    <label for="update_status_etapa_servicio_id" class="form-label fw-semibold">
                                        <i class="bi bi-list-nested me-2 text-primary"></i> Seleccionar estatus de servicio
                                    </label>
                                    <select id="update_status_etapa_servicio_id" name="update_status_etapa_servicio_id" class="form-select"></select>
                                </div>

                            </div>
                            
                            <!-- Comentarios -->
                            <div class="px-2">

                                <label for="update_comments_etapa_servicio" class="form-label fw-semibold">
                                    <i class="bi bi-chat-left me-2 text-primary"></i> Comentarios
                                    <span class="text-muted fw-normal"> (Opcional) </span>
                                </label>

                                <textarea
                                    id="update_comments_etapa_servicio"
                                    rows="5"
                                    class="form-control shadow-sm"
                                    maxlength="500"
                                    placeholder="Agrega información adicional para el estatus..."></textarea>

                                <div class="d-flex justify-content-end mt-1">
                                    <small class="text-muted">
                                        0 / 500
                                    </small>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="button" id="btn-update-stage-status-service" class="btn btn-primary"> Actualizar estatus </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal actualizar estatus servicio -->

        </div>

        <script src="js/main.js" type="module"></script>

    </body>