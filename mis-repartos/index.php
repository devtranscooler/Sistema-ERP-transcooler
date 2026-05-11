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
        
        <title> Mis repartos </title>
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
                        <i class="bi bi-truck me-1"></i> Mis repartos 
                    </li>
                </ol>
            </nav>
            <!-- Termina Breadcrumb -->
            
            <!-- Mis Sección título -->
            <div class="row align-items-center my-3">
                <div class="col-12 col-md-6">
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-truck text-primary"></i>
                        Mis repartos
                    </h2>
                </div>
                <div class="col-12 col-md-6 mt-3 md-mt-0">
                    <h2 class="text-secondary mb-0 text-md-end fs-5">
                        <i class="bi bi-person"></i>
                            <?= $_SESSION['NAME'] ?? 'S/N' ?>
                    </h2>
                </div>
            </div>
            <!-- Termina Sección título -->


            <!-- Repartos -->
            <div id="deliveriesContainer"></div>
            <!-- Termina Repartos -->

            <!-- Modal productos reparto -->
            <?= require 'modalProductos.php'; ?>
            <!-- Termina Modal productos reparto -->

            <!-- Modal Carga archivos -->
            <div class="modal fade" id="evidenceModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="evidenceModalTitle">
                                <i class="bi bi-cloud-upload"></i>
                                    Adjuntar evidencia
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">

                            <!-- Formulario Evidencias y estatus -->
                            <div class="row g-4 px-2 px-md-3">

                                <!-- Evidencias -->
                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="mb-1 fw-semibold"> Agregar evidencias </h6>
                                            <small class="text-secondary"> Toma una foto o selecciona imágenes </small>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <button id="btn-open-camera" class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:52px; height:52px;">
                                                <i class="bi bi-camera fs-5"></i>
                                            </button>
                                            
                                            <div>
                                                <button id="open-dialog-file-btn" class="btn btn-light border rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:52px; height:52px;">
                                                    <i class="bi bi-image fs-5 text-secondary"></i>
                                                </button>
                                                <input 
                                                    type="file" 
                                                    id="file-input" 
                                                    accept="image/*" 
                                                    multiple 
                                                    hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Termina Evidencias -->

                                <!-- Estatus -->
                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="mb-1 fw-semibold"> Resultado del reparto </h6>
                                            <small class="text-secondary"> Indica si la entrega fue exitosa </small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm delivery-status-btn active-status" data-status="Completado">
                                                <i class="bi bi-check2-circle"></i>
                                                <span> Aceptado </span>
                                            </button>

                                            <button type="button" class="btn btn-light border rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm delivery-status-btn"  data-status="Rechazado">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <span>  Rechazado </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Terina Estatus -->

                            </div>
                            <!-- Termina Formulario Evidencias y estatus -->

                            <!-- Vista previa camara -->
                            <div class="mt-3 px-3">

                                <div class="d-none" id="text-preview-camera-files">
                                    <h6 class="mb-1 fw-semibold"> Vista previa </h6>
                                    <small class="text-secondary"> Archivos seleccionados </small>
                                </div>

                                <!-- Begin Camera Photo Preview -->
                                <div id="preview-container" class="row g-2 row-cols-2 row-cols-sm-3 row-cols-md-4 mt-3"></div>
                                <!-- Begin Camera Photo Preview -->

                                <!-- Begin Camera Preview -->
                                <div class="row">
                                    <div class="d-flex flex-column align-items-center mt-4 mt-md-5 d-none px-2 px-sm-0" id="camera-container">
                                        
                                        <video 
                                            id="video" 
                                            autoplay 
                                            playsinline 
                                            class="w-100 w-sm-75 w-md-50 rounded-3" 
                                            style="max-height: 270px; border-radius: 2rem; margin-top: 2rem;"></video>

                                        <button type="button" class="btn btn-success mt-2 rounded-5" id="btn-take-photo">
                                            Tomar foto
                                        </button>

                                    </div>
                                </div>
                                <!-- End Camera Preview -->

                                <!-- Canvas -->
                                <div class="row mt-2">
                                    <canvas id="canvas" class="d-none"></canvas>
                                </div>
                                <!-- Termina Canvas -->
                            </div>
                            <!-- Termina vista previa camara -->

                            <!-- Contenedor Vista Previa Archivos -->
                            <div class="px-3 mt-3">
                                <div class="d-none" id="text-preview-files-selected">
                                    <h6 class="mb-1 fw-semibold"> Vista previa </h6>
                                    <small class="text-secondary"> Archivos seleccionados </small>
                                </div>
                                <div id="preview-select-files-container" class="d-flex gap-2 flex-wrap mt-3"></div>
                            </div>
                            <!-- Termina cOntenedor Vista Previa Archivos -->

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="close-modal">
                                Cerrar
                            </button>
                            <button type="button" class="btn btn-primary" id="send-images">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal carga archivos -->

        </div>

        <script type="module" src="./main.js" ></script>

    </body>
</html>