let paginaActual = 1;
const registrosPorPagina = 15;
let timeout = null;

document.addEventListener("DOMContentLoaded", () => {
    cargarUnidades();
})

//  Event listeners para filtros con debouncing en el nombre
document.getElementById("filtroEco").addEventListener("keyup", function () {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        cargarUnidades()
    }, 300)
});

function pintarTabla(data) {
    let html = "";

    if (data.length === 0) {
        html = `
                <tr>
                    <td colspan="8" class="text-center py-5" >
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No se encontraron unidades</p>
                    </td>
                </tr>
            `;
    } else {
        data.forEach(unidad => {
            html += `
                <tr>
                    <td class="text-center fw-bold">#${unidad.id}</td>
                    <td>${unidad.eco}</td>
                    <td>${unidad.tipo_unidad}</td>
                    <td>${unidad.placas}</td>
                    <td>${unidad.no_motor}</td>
                    <td>${unidad.niv}</td>
                    <td class="text-center">
                        <div>
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick="editar(${unidad.id})"
                                    title="Editar unidad"
                                >
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-success"
                                    title="Ver detalles"
                                    onclick="ver(${unidad.id})">
                                <i class="bi bi-eye-fill"></i>
                            </button>     
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="eliminar(${unidad.id})"
                                    title="Eliminar unidad">
                                <i class="bi bi-trash-fill"></i>
                            </button>                                                
                        </div>
                    </td>
                </tr>`;
        });
    }
    document.querySelector("#tablaUnidades tbody").innerHTML = html;
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
            <li class="page-item ${i === paginaActual ? "active" : ""}" onclick="cargarUnidades(${i})" style="cursor: pointer" >
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

function cargarUnidades(page = 1) {
    const eco = document.getElementById("filtroEco").value;

    const formData = new FormData();
    formData.append('action', 'listar');
    formData.append('page', page);
    formData.append('limit', registrosPorPagina);
    formData.append('eco', eco);

    fetch("unidades.api.php", {
        method: "POST",
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            pintarTabla(response.data)
            pintarPaginacion(response.total)
        })
        .catch(error => {
            console.error("Error al cargar las unidades:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las unidades'
            });
        })
}

function ver(id) {

    const formData = new FormData();
    formData.append('action', 'find');
    formData.append('id', id);
    fetch("unidades.api.php", {
        method: 'POST',
        body: formData,
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('verUnidad.php', response.data)
            }
        })
}

function editar(id) {
    fetch("unidades.api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `action=find&id=${id}`
    })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                abrirModal('formUnidades.php', response.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la unidad'
                });
            }
        })
        .catch(error => {
            console.error("Error al editar:", error);
        });
}

function guardar() {
    const form = document.getElementById("formUnidades");
    const formData = new FormData(form);
    fetch("unidades.api.php", {
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
                cargarUnidades();

                //  Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Unidad guardada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo guardar la unidad'
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
            fetch("unidades.api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=eliminar&id=${id}`
            })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        cargarUnidades();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo cargar la unidad'
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al editar:", error);
                });

        }
    });
}