let paginaActual = 1;
const registrosPorPagina = 15;
let timeout = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarClientes();
})

//  Event listeners para filtros con debouncing en el nombre
document.getElementById("filtroRazon").addEventListener("keyup", function () {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        cargarClientes()
    }, 300)
});

function pintarTabla(data) {
    let html = "";

    if (data.length === 0) {
        html = `
                <tr>
                    <td colspan="8" class="text-center py-5" >
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No se encontraron clientes</p>
                    </td>
                </tr>
            `;
    } else {
        data.forEach(cliente => {
            html += `
                <tr>
                    <td class="text-center fw-bold">#${cliente.id}</td>
                    <td>${cliente.razon_social}</td>
                    <td>${cliente.RFC}</td>
                    <td>${cliente.correo}</td>
                    <td>${cliente.telefono}</td>
                    <td>${cliente.tipo_operacion}</td>
                    <td class="text-center">
                        <div>
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick="editar(${cliente.id})"
                                    title="Editar usuario"
                                >
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-success"
                                    title="Ver detalles"
                                    onclick="ver(${cliente.id})">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button type="button"
                                    onclick="fiscales(${cliente.id})"
                                    class="btn btn-sm btn-warning"
                                    title="Cambiar datos fiscales">
                                <i class="bi bi-receipt"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="eliminar(${cliente.id})"
                                    title="Eliminar usuario">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
        });
    }
    document.querySelector("#tablaClientes tbody").innerHTML = html;
}

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
            <li class="page-item ${i === paginaActual ? "active" : ""}" onclick="cargarClientes(${i})" style="cursor: pointer" >
                <span class="page-link">${i}</span>
            </li>
        `;
    }

    document.getElementById("info-paginacion").innerHTML = info;
    document.getElementById("paginacion").innerHTML = html;
}

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

function cargarClientes(page = 1) {
    const razonsocial = document.getElementById("filtroRazon").value;

    const formData = new FormData();
    formData.append('action', 'listar');
    formData.append('page', page);
    formData.append('limit', registrosPorPagina);
    formData.append('razon_social', razonsocial);

    fetch("clientes.api.php", {
        method: "POST",
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            pintarTabla(response.data)
            pintarPaginacion(response.total)
        })
        .catch(error => {
            console.error("Error al cargar los clientes:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los clientes'
            });
        })
}

function ver(id) {

    const formData = new FormData();
    formData.append('action', 'find');
    formData.append('id', id);

    fetch("clientes.api.php", {
        method: 'POST',
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('verCliente.php', response.data)
            }
        })
}

function editar(id) {
    fetch("clientes.api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `action=find&id=${id}`
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('formCliente.php', response.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el cliente'
                });
            }
        })
        .catch(error => {
            console.error("Error al editar:", error);
        });
}

function guardar() {
    const form = document.getElementById("formCliente");
    const formData = new FormData(form);
    fetch("clientes.api.php", {
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
                cargarClientes();

                //  Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'cliente guardado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo guardar el cliente'
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
            fetch("clientes.api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=eliminar&id=${id}`
            })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        cargarClientes();
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

function fiscales(id) {
    fetch("clientes.api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `action=find&id=${id}`
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('formFiscales.php', response.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el cliente'
                });
            }
        })
        .catch(error => {
            console.error("Error al editar:", error);
        });
}

