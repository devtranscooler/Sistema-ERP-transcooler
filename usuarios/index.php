<?php require '../system/connection.php'; require '../system/constants.php';; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

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
</head>

<body onclick="closeMenu(event)">
    <?php require_once '../utilities/sidebar.php'; Sidebar::render("Gestión de Usuarios"); ?>

    <div class="container-fluid">
        <!-- Breadcrumb mejorado con íconos -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <i class="bi bi-house-door me-1"></i>Inicio
                </li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-people me-1"></i>Usuarios
                </li>
            </ol>
        </nav>

        <!-- Encabezado con mejor espaciado y diseño -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-people-fill text-primary"></i>
                    Gestión de Usuarios
                </h2>
            </div>

            <div class="col-md-6 text-md-end">
                <!-- Botones con mejor diseño y spacing -->
                <button class="btn btn-primary  shadow-sm" onclick="abrirModal('formUsuario.php')">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
                </button>
                <button class="btn btn-success shadow-sm ms-2">
                    <i class="bi bi-file-earmark-excel me-2"></i>Exportar
                </button>
            </div>
        </div>

        <!-- Filtros dentro de un card con mejor organización -->
        <div class="filter-card">
            <div class="row">
                <!-- Campo de búsqueda por nombre -->
                <div class="col-md-5">
                    <label for="filtroNombre" class="form-label small text-muted">
                        <i class="bi bi-person-square"></i> Buscar por nombre
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            name="nombre"
                            id="filtroNombre"
                            class="form-control border-start-0 ps-0"
                            placeholder="Escribe el nombre del usuario...">
                    </div>
                </div>

                <!-- Filtro por rol -->
                <div class="col-md-3">
                    <label for="filtroRol" class="form-label small text-muted">
                        <i class="bi bi-person-vcard"></i> Rol
                    </label>
                    <select class="form-select" id="filtroRol">
                        <option value="">Todos los roles</option>
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                        <option value="3">Supervisor</option>
                    </select>
                </div>

                <!-- Filtro por fecha -->
                <div class="col-md-4">
                    <label for="filtroFecha" class="form-label small text-muted">
                        <i class="bi bi-calendar-day"></i> Fecha de contratación
                    </label>
                    <input type="date"
                        name="fecContratacion"
                        id="filtroFecha"
                        class="form-control">
                </div>
            </div>
        </div>

        <div class="table-responsive mb-0">
            <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Movil</th>
                        <th>CEDIS</th>
                        <th>Puesto</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center" style="width: 200px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargan -->
                </tbody>
            </table>
        </div>

        <div class="row mt-2 align-items-center">
            <div class="col-md-6">
                <div id="info-paginacion" class="text-muted"></div>
            </div>
            <div class="col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-md-end justify-content-center mb-0" id="paginacion"></ul>
                </nav>
            </div>
        </div>

        <!-- MODAL GLOBAL -->
        <div class="modal fade" id="globalModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" id="globalModalContent" style="overflow-y: auto;">
                </div>
            </div>
        </div>

    </div>

</body>

<script>
    // Variables globales para control de paginación
    let paginaActual = 1;
    const registrosPorPagina = 15;
    let timeout = null;

    // Inicialización al cargar el documento
    document.addEventListener("DOMContentLoaded", () => {
        cargarUsuarios();
    });

    //  Event listeners para filtros con debouncing en el nombre
    document.getElementById("filtroNombre").addEventListener("keyup", function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            cargarUsuarios();
        }, 500)
    });

    // Filtros instantáneos para rol y fecha
    document.getElementById("filtroRol").addEventListener("change", () => cargarUsuarios());
    document.getElementById("filtroFecha").addEventListener("change", () => cargarUsuarios());

    /**
     * Función para cargar usuarios con filtros
     * @param {number} page - Número de página a cargar
     */
    function cargarUsuarios(page = 1) {

        paginaActual = page;

        // Obtener valores de los filtros
        const nombre = document.getElementById("filtroNombre").value;
        const rol = document.getElementById("filtroRol").value;
        const fecha = document.getElementById("filtroFecha").value;

        // Preparar datos para enviar
        const formData = new FormData();
        formData.append('action', 'listar');
        formData.append('page', page);
        formData.append('limit', registrosPorPagina);
        formData.append('nombre', nombre);
        formData.append('rol', rol);
        formData.append('fecContratacion', fecha);

        // Petición AJAX para obtener usuarios
        fetch("usuarios.api.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                pintarTabla(response.data);
                pintarPaginacion(response.total);
            })
            .catch(error => {
                console.error("Error al cargar usuarios:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los usuarios'
                });
            });
    }

    /**
     *  Función para pintar la tabla con los datos
     * @param {Array} data - Array de usuarios
     */
    function pintarTabla(data) {

        let html = "";

        //  Si no hay datos, mostrar mensaje
        if (data.length === 0) {
            html = `
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No se encontraron usuarios</p>
                    </td>
                </tr>
            `;
        } else {
            // Construir filas de la tabla
            data.forEach(usuario => {
                //  Determinar color del badge según el rol
                let rolClass = 'bg-secondary';
                if (usuario.rol_descripcion === 'Administrador') rolClass = 'bg-danger';
                else if (usuario.rol_descripcion === 'Supervisor') rolClass = 'bg-warning';
                else if (usuario.rol_descripcion === 'Usuario') rolClass = 'bg-info';

                html += `
                <tr>
                    <td class="text-center fw-bold">#${usuario.id}</td>
                    <td>${usuario.nombreCompleto}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.movil}</td>
                    <td>${usuario.cedis}</td>
                    <td>${usuario.puesto}</td>
                    <td class="text-center">
                        <span class="badge ${rolClass} role-badge">
                            ${usuario.rol_descripcion ?? 'Sin rol'}
                        </span>
                    </td>
                    <td class="text-center">
                        <div>
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick="editar(${usuario.id})"
                                    title="Editar usuario">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-success"
                                    title="Ver detalles"
                                    onclick="ver(${usuario.id})">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-warning"
                                    title="Cambiar estado">
                                <i class="bi bi-toggles"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="eliminar(${usuario.id})"
                                    title="Eliminar usuario">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            });
        }

        // Insertar HTML en la tabla
        document.querySelector("#tablaUsuarios tbody").innerHTML = html;
    }

    /**
     * Función para pintar la paginación
     * @param {number} totalRegistros - Total de registros
     */
    function pintarPaginacion(totalRegistros) {

        const totalPaginas = Math.ceil(totalRegistros / registrosPorPagina);
        let html = "";

        // Cálculo de rango mostrado
        const inicio = (paginaActual - 1) * registrosPorPagina + 1;
        let fin = paginaActual * registrosPorPagina;

        if (fin > totalRegistros) {
            fin = totalRegistros;
        }

        // Texto informativo
        let info = `
        <p>
            <i class="bi bi-info-circle me-1"></i>
            Mostrando <strong>${inicio}</strong> - <strong>${fin}</strong> de <strong>${totalRegistros}</strong> registros
        </p>`;

        // Botones de paginación
        for (let i = 1; i <= totalPaginas; i++) {
            html += `
            <li class="page-item ${i === paginaActual ? "active" : ""}" onclick="cargarUsuarios(${i})" style="cursor: pointer" >
                <span class="page-link">${i}</span>
            </li>
        `;
        }

        document.getElementById("info-paginacion").innerHTML = info;
        document.getElementById("paginacion").innerHTML = html;
    }

    /**
     * Función para abrir modal dinámico
     * @param {string} url - URL del contenido del modal
     * @param {object} data - Datos a enviar (opcional)
     */
    function abrirModal(url, data = {}) {

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams(data)
            })
            .then(res => res.text())
            .then(html => {
                // Inyectar contenido en el modal
                document.getElementById("globalModalContent").innerHTML = html;

                // Mostrar modal
                const modal = new bootstrap.Modal(
                    document.getElementById("globalModal")
                );
                modal.show();
            })
            .catch(error => {
                console.error("Error cargando modal:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el formulario'
                });
            });
    }

    /**
     * Función para editar un usuario
     * @param {number} id - ID del usuario a editar
     */
    function editar(id) {
        fetch("usuarios.api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=find&id=${id}`
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    abrirModal('formUsuario.php', response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el usuario'
                    });
                }
            })
            .catch(error => {
                console.error("Error al editar:", error);
            });
    }

    function ver(id) {
        fetch("usuarios.api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=find&id=${id}`
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    abrirModal('verUsuario.php', response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el usuario'
                    });
                }
            })
            .catch(error => {
                console.error("Error al editar:", error);
            });
    }

    /**
     *  Función para guardar usuario (crear o actualizar)
     */
    function guardarUsuario() {

        const form = document.getElementById("formUsuario");
        const formData = new FormData(form);

        fetch("usuarios.api.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("globalModal")
                    );
                    modal.hide();

                    //  Recargar tabla
                    cargarUsuarios();

                    //  Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Usuario guardado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'No se pudo guardar el usuario'
                    });
                }
            })
            .catch(error => {
                console.error("Error al guardar:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar'
                });
            });
    }

    /**
     * Función para eliminar usuario
     * @param {number} id - ID del usuario a eliminar
     */
    function eliminar(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("usuarios.api.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=eliminar&id=${id}`
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.success) {
                            cargarUsuarios();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo cargar el usuario'
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error al editar:", error);
                    });

            }
        });
    }
</script>

</html>