const Salida = (() => {
    let paginaActual         = 1;
    const registrosPorPagina = 15;
    let timeout              = null;

    const MODAL_ID         = "salidaModal";
    const MODAL_CONTENT_ID = "salidaModalContent";
    const PAGINACION_ID    = "paginacion-salida";
    const INFO_PAG_ID      = "info-paginacion-salida";

    function init() {
        if (!document.getElementById("tablaServiciosSalida")) return;
        document.addEventListener("DOMContentLoaded", () => cargar());

        document.getElementById("filtroIdServicioSalida")
                .addEventListener("keyup", () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => cargar(), 300);
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
                                onclick="Salida.darSalida(${s.id})"
                                title="Dar salida a la unidad">
                            <i class="bi bi-capslock-fill"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success"
                                onclick="Salida.ver(${s.id})"
                                title="Ver detalles">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </td>
                </tr>`;
            });
        }

        document.querySelector("#tablaServiciosSalida tbody").innerHTML = html;
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
                onclick="Salida.cargar(${i})" style="cursor:pointer">
                <span class="page-link">${i}</span>
            </li>`;
        }
        document.getElementById(PAGINACION_ID).innerHTML = html;
    }

    function abrirModal(url, data = {}) {
        fetch(url, {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:    new URLSearchParams(data),
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
            const modalEl = document.getElementById(MODAL_ID);
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        })
        .catch((err) => {
            console.error("Salida.abrirModal error:", err);
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo cargar el formulario" });
        });
    }

    function cargar(page = 1) {
        paginaActual = page;
        const idServicio = document.getElementById("filtroIdServicioSalida")?.value ?? "";

        const fd = new FormData();
        fd.append("action",  "listar");
        fd.append("page",    page);
        fd.append("limit",   registrosPorPagina);
        fd.append("filtroIdServicioSalida", idServicio);
        fd.append("context", "salida");

        fetch("servicio_cliente/servicios.api.php", { method: "POST", body: fd })
            .then((r) => r.json())
            .then((res) => {
                pintarTabla(res.data);
                pintarPaginacion(res.total);
            })
            .catch((err) => {
                console.error("Salida.cargar error:", err);
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
        .catch((err) => console.error("Salida.ver error:", err));
    }

    function darSalida(id) {    
        abrirModal("/workflow/salida-servicio/darSalida.php", {
            id: id
        });
    }
    

    function guardar() {
        /* const form = document.getElementById("formServiciosSalida");
        if (!form) return;

        let errores = [];

        const operador = document.getElementById('id_operador').value;
        const unidad   = document.getElementById('id_unidad').value;

        if (!operador) errores.push('Debes seleccionar un operador');
        if (!unidad) errores.push('Debes seleccionar una unidad');

        if (errores.length > 0) {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                html: errores.join('<br>')
            });
            return;
        }

        fetch("servicio_cliente/servicios.api.php", {
            method: "POST",
            body: new FormData(form),
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById(MODAL_ID))?.hide();
                cargar();
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Servicio guardado correctamente",
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message || "No se pudo guardar el servicio"
                });
            }
        })
        .catch((err) => {
            console.error("Salida.guardar error:", err);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al guardar"
            });
        }); */
    }

    window.addEventListener("salida:recargar", () => {
        cargar();
    });

    init();

    return { cargar, ver, guardar, abrirModal, darSalida };
})();