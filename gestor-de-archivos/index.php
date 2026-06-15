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
        
        <title> Gestor de archivos </title>
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

            .hide-arrow::after {
                display: none !important;
            }

    </style>

    </head>
    <body onclick="closeMenu(event)">

        <?php require_once '../utilities/sidebar.php'; Sidebar::render("Workflow"); ?>

        <input type="hidden" name="auth_user_id" id="auth_user_id" value="<?= $_SESSION['ID_USUARIO'] ?? null ?> ">

        <div class="container-fluid p-4">

            <div class="my-4 p-3">
                <div class="d-flex justify-content-center align-items-center w-100">
                    <div class="position-relative w-70" style="width: 70%;">
                        
                        <input
                            type="text"
                            id="inpt-search-file"
                            name="inpt-search-file"
                            class="form-control form-control-lg rounded-pill pe-5"
                            placeholder="Buscar archivo...">
                        <button 
                            type="button" 
                            id="btnSearchFile"
                            class="btn btn-primary rounded-circle position-absolute end-0 top-50 translate-middle-y me-2 d-flex align-items-center justify-content-center" 
                            style="width: 40px; height: 40px;">
                                <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Título sección y botones grid y nuevo -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0"> Archivos </h3>
                    <small class="text-muted"> Gestión de archivos </small>
                </div>

                <div class="d-flex justify-content-between gap-2">
                    <button
                        class="btn btn-secondary d-none"
                        id="btn-reset-search">
                            <i class="bi bi-arrow-clockwise"></i>
                                Limpiar busqueda
                    </button>
                    <div class="btn-group">
                        <button id="btnFileExtensionFilter" type="button" class="btn btn-primary dropdown-toggle text-white" data-bs-toggle="dropdown" aria-expanded="false">
                            Filtrar
                        </button>
                        <ul class="dropdown-menu" id="dropdownFileExtensionFilter">
                            <li><a class="dropdown-item" href="#" data-file-extension="all"> Todo </a></li>
                            <li><a class="dropdown-item" href="#" data-file-extension="pdf"> PDF </a></li>
                            <li><a class="dropdown-item" href="#" data-file-extension="image"> Imagenes </a></li>
                            <li><a class="dropdown-item" href="#" data-file-extension="xlsx"> Excel </a></li>
                            <li><a class="dropdown-item" href="#" data-file-extension="docx"> Word </a></li>
                            <li><a class="dropdown-item" href="#" data-file-extension="csv"> CSV </a></li>
                        </ul>
                    </div>
                    <button
                        class="btn btn-dark"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadModal">
                            <i class="bi bi-plus"></i>
                                Nuevo
                    </button>
                </div>
            </div>
            <!-- Termina Título sección y botones grid y nuevo -->

            <!-- Vista Lista -->
            <div id="listView" class="row my-2"></div>
            <!-- Termina Vista Lista -->

            <!-- Paginación -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center my-4">
                        <div id="pagination-container" class="d-flex justify-content-center mt-4"> </div>
                    </div>
                </div>
            </div>
            <!-- Termina Paginación -->


            <!-- Modal Subir archivos -->
            <div class="modal fade" id="uploadModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="evidenceModalTitle">
                                <i class="bi bi-cloud-upload"></i>
                                    Subir archivos
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="w-100 border bg-light rounded-3" style="height: 15rem;">
                                <div class="d-flex flex-column align-items-center mt-5">
                                    <button class="btn btn-primary rounded-circle pulse-button" style="cursor: pointer;  width:70px; height:70px;" id="openDialogFilesBtn">
                                        <i class="bi bi-cloud-upload text-white fs-2"></i>
                                    </button>
                                    <input 
                                        type="file" 
                                        id="fileInput" 
                                        accept="image/*, .pdf, .xlsx, .xls, .csv, .doc, .docx" 
                                        hidden>
                                    <span class="mt-2 text-center"> Haz clic en el botón de arriba para seleccionar tus archivos </span>
                                </div>
                            </div>

                            <div id="container-files-preview"></div>
                            

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
            <!-- Termina Modal Subir archivos -->

            <!-- Modal Solicitar descarga -->
            <div class="modal fade" id="requestPermissionDownload" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestPermissionTitleModal">
                                <i class="bi bi-unlock2"></i>
                                    Solicitar permiso para descargar archivo
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="p-3">
                                <div>
                                    <label for="usuario_aprobador_id"> Solicitar permiso para descargar archivo a </label>
                                    <select class="form-select form-control-lg" aria-label="Default select example" id="usuario_aprobador_id" name="usuario_aprobador_id"></select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-request-permission">
                                Enviar solicitud
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal Solicitar descarga -->

            <!-- Modal Información archivo -->
            <div class="modal fade" id="fileDetailModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="fileDetailTitleModal">
                                <i class="bi bi-info-circle"></i>
                                    Detalles del archivo
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="container-file-details"></div>
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

            <!-- Modal Eliminar archivo -->
            <div class="modal fade" id="fileDeleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="fileDeleteTitleModal">
                                <i class="bi bi-trash"></i>
                                    Eliminar  archivo
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="container-file-delete"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                No, cancelar
                            </button>
                            <button type="button" class="btn btn-danger" id="btn-delete-file">
                                Si, Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina Modal Eliminar archivo -->

        </div>

        <script type="module" src="./js/main.js"></script>
    </body>
</html>