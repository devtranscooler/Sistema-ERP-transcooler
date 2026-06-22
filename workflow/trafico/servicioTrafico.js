const Trafico = (() => {
    let paginaActual         = 1;
    const registrosPorPagina = 15;
    let timeout              = null;

    const MODAL_ID         = "traficoModal";
    const MODAL_CONTENT_ID = "traficoModalContent";
    const PAGINACION_ID    = "paginacion-trafico";
    const INFO_PAG_ID      = "info-paginacion-trafico";

    function init() {
        if (!document.getElementById("tablaServiciosTrafico")) return;
        document.addEventListener("DOMContentLoaded", () => cargar());

        document.getElementById("filtroIdServicioTrafico")
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
                        <div class="d-flex justify-content-center gap-2 gap-md-2">
                            <button type="button" class="btn btn-sm btn-primary p-2 rounded-circle"
                                    onclick="Trafico.setEcoAndOperator(${s.id})"
                                    title="Asignar Eco y operador">
                                <i class="bi bi-truck"></i>
                            </button>
                            ${
                                s.status_operativo === 'traslapado'
                                    ? `<button 
                                            type="button"
                                            onclick="Trafico.deliveryReassignment(${s.id})"
                                            class="btn btn-sm btn-dark p-2 rounded-circle">
                                                <i class="bi bi-arrow-left-right"></i>
                                        </button>`
                                    : ''
                            }
                            <button type="button" class="btn btn-sm btn-success p-2 rounded-circle"
                                    onclick="Trafico.ver(${s.id})"
                                    title="Ver detalles">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        }

        document.querySelector("#tablaServiciosTrafico tbody").innerHTML = html;
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
                onclick="Trafico.cargar(${i})" style="cursor:pointer">
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
            const modalEl = document.getElementById(MODAL_ID);
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        })
        .catch((err) => {
            console.error("Trafico.abrirModal error:", err);
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo cargar el formulario" });
        });
    }

    function cargar(page = 1) {
        paginaActual = page;
        const idServicio = document.getElementById("filtroIdServicioTrafico")?.value ?? "";

        const fd = new FormData();
        fd.append("action",  "listar");
        fd.append("page",    page);
        fd.append("limit",   registrosPorPagina);
        fd.append("filtroIdServicioTrafico", idServicio);
        fd.append("context", "trafico");

        fetch("servicio_cliente/servicios.api.php", { method: "POST", body: fd })
            .then((r) => r.json())
            .then((res) => {
                pintarTabla(res.data);
                pintarPaginacion(res.total);
            })
            .catch((err) => {
                console.error("Trafico.cargar error:", err);
                Swal.fire({ icon: "error", title: "Error", text: "No se pudieron cargar los servicios" });
            });
    }

    function setEcoAndOperator(id) {
        fetch("servicio_cliente/servicios.api.php", {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:    `action=find&id=${id}`,
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) {
                abrirModal("trafico/formTrafico.php", res.data);
            } else {
                Swal.fire({ icon: "error", title: "Error", text: "No se pudo cargar el servicio" });
            }
        })
        .catch((err) => console.error("Trafico.setEcoAndOperator error:", err));
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
        .catch((err) => console.error("Trafico.ver error:", err));
    }

    function deliveryReassignment(id) {
        fetch("servicio_cliente/servicios.api.php", {
            method:  "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:    `action=find&id=${id}`,
        })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) abrirModal("/workflow/components/DeliveryReassignmentModal.php", res.data);
        })
        .catch((err) => console.error("Trafico.deliveryAssignment error:", err));
    }

    function guardar() {
        const form = document.getElementById("formServiciosTrafico");
        if (!form) return;

        let errores = [];

        // 🔹 Obtener valores (IDs reales)
        const operador = document.getElementById('id_operador').value;
        const unidad   = document.getElementById('id_unidad').value;

        // 🔹 Validaciones básicas
        if (!operador) errores.push('Debes seleccionar un operador');
        if (!unidad) errores.push('Debes seleccionar una unidad');

        // 🔹 Número de remolques (solo si está visible)
        const divRemolques = document.getElementById('input_num_remolques');
        const numRemolquesInput = document.getElementById('numero_remolques');

        if (divRemolques.style.display !== 'none') {
            if (!numRemolquesInput.value) {
                errores.push('Debes indicar el número de remolques');
            }
        }

        // 🔹 Remolque 1
        const rem1Div = document.getElementById('campo_remolque1');
        if (rem1Div.style.display !== 'none') {
            const rem1 = document.getElementById('id_remolque').value;
            if (!rem1) errores.push('Debes seleccionar el remolque 1');
        }

        // 🔹 Remolque 2
        const rem2Div = document.getElementById('campo_remolque2');
        if (rem2Div.style.display !== 'none') {
            const rem2 = document.getElementById('id_remolque2').value;
            if (!rem2) errores.push('Debes seleccionar el remolque 2');
        }

        // 🔹 Dolly
        const dollyDiv = document.getElementById('campo_dolly');
        if (dollyDiv.style.display !== 'none') {
            const dolly = document.getElementById('id_dolly').value;
            if (!dolly) errores.push('Debes seleccionar el dolly');
        }

        // 🚨 Si hay errores → NO enviar
        if (errores.length > 0) {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                html: errores.join('<br>')
            });
            return;
        }

        // 🚀 Si todo está bien → enviar
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
            console.error("Trafico.guardar error:", err);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al guardar"
            });
        });
    }

    init();

    return { cargar, setEcoAndOperator, ver, deliveryReassignment, guardar, abrirModal };
})();