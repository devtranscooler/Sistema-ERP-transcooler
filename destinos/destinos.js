let paginaActual = 1;
const registrosPorPagina = 15;
let timeout = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarDestinos();
})

//  Event listeners para filtros con debouncing en el nombre
document.getElementById("filtroNombre").addEventListener("keyup", function () {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        cargarDestinos()
    }, 300)
});

function pintarTabla(data) {
    let html = "";

    if (data.length === 0) {
        html = `
                <tr>
                    <td colspan="8" class="text-center py-5" >
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No se encontraron destinos</p>
                    </td>
                </tr>
            `;
    } else {
        data.forEach(destino => {
            html += `
                <tr>
                    <td class="text-center fw-bold">#${destino.id}</td>
                    <td>${destino.nombre}</td>
                    <td>${destino.calle}</td>
                    <td>${destino.numero_interior}</td>
                    <td>${destino.numero_exterior}</td>
                    <td>${destino.ciudad}</td>
                    <td>${destino.pais}</td>
                    <td>${destino.codigo_postal}</td>
                    <td>${destino.municipio}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary" onclick="editar(${destino.id})" title="Editar destino">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="ver(${destino.id})" title="Ver detalles">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminar(${destino.id})" title="Eliminar destino">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>`;
        });
    }
    document.querySelector("#tablaDestinos tbody").innerHTML = html;
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
            <li class="page-item ${i === paginaActual ? "active" : ""}" onclick="cargarDestinos(${i})" style="cursor: pointer" >
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
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById("globalModalContent").innerHTML = html;
        document.getElementById("globalModalContent")
            .querySelectorAll("script")
            .forEach(scriptViejo => {
                const scriptNuevo = document.createElement("script");
                scriptNuevo.textContent = scriptViejo.textContent;
                document.body.appendChild(scriptNuevo);
                scriptNuevo.remove();
            });
        const modal = new bootstrap.Modal(document.getElementById("globalModal"));
        modal.show();
    })
    .catch(error => {
        console.error("Error cargando modal:", error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el formulario' });
    });
}

function cargarDestinos(page = 1) {
    const nombre = document.getElementById("filtroNombre").value;

    const formData = new FormData();
    formData.append('action', 'listar');
    formData.append('page', page);
    formData.append('limit', registrosPorPagina);
    formData.append('nombre', nombre);

    fetch("destinos.api.php", {
        method: "POST",
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            pintarTabla(response.data)
            pintarPaginacion(response.total)
        })
        .catch(error => {
            console.error("Error al cargar los destinos:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los destinos'
            });
        })
}

function ver(id) {

    const formData = new FormData();
    formData.append('action', 'find');
    formData.append('id', id);
    fetch("destinos.api.php", {
        method: 'POST',
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('verDestino.php', response.data)
            }
        })
}

function editar(id) {
    fetch("destinos.api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `action=find&id=${id}`
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('formDestinos.php', response.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el destino'
                });
            }
        })
        .catch(error => {
            console.error("Error al editar:", error);
        });
}

function guardar() {
    const form = document.getElementById("formDestinos");
    const formData = new FormData(form);
    fetch("destinos.api.php", {
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
                cargarDestinos();

                //  Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Destino guardado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo guardar el destino'
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
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("destinos.api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=eliminar&id=${id}`
            })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        cargarDestinos();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo cargar el destino'
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al editar:", error);
                });

        }
    });
}