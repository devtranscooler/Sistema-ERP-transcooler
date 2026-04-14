const Servicios = (() => {
    let paginaActual         = 1;
    const registrosPorPagina = 15;
    let timeout              = null;

    const MODAL_ID         = "serviciosModal";
    const MODAL_CONTENT_ID = "serviciosModalContent";
    const PAGINACION_ID    = "paginacion-servicios";
    const INFO_PAG_ID      = "info-paginacion-servicios";

    function init() {
        if (!document.getElementById("tablaServicios")) return;

        document.addEventListener("DOMContentLoaded", () => {
            cargar();

            document.getElementById("filtroIdServicio")
                .addEventListener("keyup", () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => cargar(), 300);
                });
        });
    }

    function pintarTabla(data) {
        let html = "";

        if (data.length === 0) {
            html = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">No se encontraron servicios</p>
                </td>
            </tr>`;
        } else {
            data.forEach((s) => {
                html += `
                <tr>
                    <td class="text-center fw-bold">#${s.id}</td>
                    <td>${s.id_cliente}</td>
                    <td>${s.shipment ?? "N/A"}</td>
                    <td>${s.tipo_servicio ?? "N/A"}</td>
                    <td>${s.fecha_carga ?? "N/A"}</td>
                    <td>${s.fecha_descarga ?? "N/A"}</td>
                    <td>${s.id_usuario_alta}</td>
                    <td>${s.num_repartos}</td>
                    <td>${s.fec_alta}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary"
                                onclick="Servicios.editar(${s.id})"
                                title="Editar servicio">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success"
                                onclick="Servicios.ver(${s.id})"
                                title="Ver detalles">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="Servicios.eliminar(${s.id})"
                                title="Eliminar servicio">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>`;
            });
        }

        document.querySelector("#tablaServicios tbody").innerHTML = html;
    }

    function pintarPaginacion(totalRegistros) {
        const totalPaginas = Math.ceil(totalRegistros / registrosPorPagina);
        const inicio = (paginaActual - 1) * registrosPorPagina + 1;
        const fin    = Math.min(paginaActual * registrosPorPagina, totalRegistros);

        document.getElementById(INFO_PAG_ID).innerHTML = `
            <p>
                <i class="bi bi-info-circle me-1"></i>
                Mostrando <strong>${inicio}</strong> - <strong>${fin}</strong>
                de <strong>${totalRegistros}</strong> registros
            </p>`;

        let html = "";
        for (let i = 1; i <= totalPaginas; i++) {
            html += `
            <li class="page-item ${i === paginaActual ? "active" : ""}"
                onclick="Servicios.cargar(${i})" style="cursor:pointer">
                <span class="page-link">${i}</span>
            </li>`;
        }
        document.getElementById(PAGINACION_ID).innerHTML = html;
    }

    function abrirModal(url, data = {}) {
        fetch(url, {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                servicio: JSON.stringify(data.servicio),
                repartos: JSON.stringify(data.repartos)
            }),
        })
        .then((res) => res.text())
        .then((html) => {
            const contenido = document.getElementById(MODAL_CONTENT_ID);
            contenido.innerHTML = html;

            contenido.querySelectorAll("script").forEach((old) => {
                const s = document.createElement("script");
                s.textContent = old.textContent;
                document.body.appendChild(s);
                s.remove();
            });

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById(MODAL_ID)
            ).show();
        })
        .catch((err) => {
            console.error("Servicios.abrirModal error:", err);
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo cargar el formulario" });
        });
    }

    function cargar(page = 1) {
        paginaActual = page;
        const idServicio = document.getElementById("filtroIdServicio")?.value ?? "";

        const fd = new FormData();
        fd.append("action",           "listar");
        fd.append("page",             page);
        fd.append("limit",            registrosPorPagina);
        fd.append("filtroIdServicio", idServicio);
        fd.append("context",          "main");

        fetch("servicio_cliente/servicios.api.php", { method: "POST", body: fd })
            .then((r) => r.json())
            .then((res) => {
                pintarTabla(res.data);
                pintarPaginacion(res.total);
            })
            .catch((err) => {
                console.error("Servicios.cargar error:", err);
                Swal.fire({ icon: "error", title: "Error", text: "No se pudieron cargar los servicios" });
            });
    }

    function ver(id) {
        fetch("servicio_cliente/servicios.api.php", {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:    `action=find&id=${id}`,
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) abrirModal("servicio_cliente/verServicio.php", res.data);
        })
        .catch((err) => console.error("Servicios.ver error:", err));
    }

    function editar(id) {
        fetch("servicio_cliente/servicios.api.php", {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:    `action=find&id=${id}`,
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) {
                abrirModal("servicio_cliente/formServicios.php", res.data);
            } else {
                Swal.fire({ icon: "error", title: "Error", text: "No se pudo cargar el servicio" });
            }
        })
        .catch((err) => console.error("Servicios.editar error:", err));
    }

    function guardar() {
        const form = document.getElementById("formServicios");
        if (!form) return;

        fetch("servicio_cliente/servicios.api.php", {
            method: "POST",
            body:   new FormData(form),
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) {
                bootstrap.Modal.getInstance(
                    document.getElementById(MODAL_ID)
                )?.hide();
                cargar();
                Swal.fire({ icon: "success", title: "¡Éxito!", text: "Servicio guardado correctamente", timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: res.message || "No se pudo guardar el servicio" });
            }
        })
        .catch((err) => {
            console.error("Servicios.guardar error:", err);
            Swal.fire({ icon: "error", title: "Error", text: "Ocurrió un error al guardar" });
        });
    }

    function eliminar(id) {
        Swal.fire({
            title:              "¿Estás seguro?",
            text:               "Esta acción no se puede revertir",
            icon:               "warning",
            showCancelButton:   true,
            confirmButtonColor: "#d33",
            cancelButtonColor:  "#3085d6",
            confirmButtonText:  "Eliminar",
            cancelButtonText:   "Cancelar",
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch("servicio_cliente/servicios.api.php", {
                method:  "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body:    `action=eliminar&id=${id}`,
            })
            .then((r) => r.json())
            .then((res) => {
                if (res.success) {
                    cargar();
                } else {
                    Swal.fire({ icon: "error", title: "Error", text: "No se pudo eliminar el servicio" });
                }
            })
            .catch((err) => console.error("Servicios.eliminar error:", err));
        });
    }

    init();

    return { cargar, abrirModal, ver, editar, guardar, eliminar };
})();