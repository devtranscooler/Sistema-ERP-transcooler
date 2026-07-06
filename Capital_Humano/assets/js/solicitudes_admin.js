// ============================
// VARIABLES GLOBALES
// ============================

let modalSolicitud;

// ============================
// INICIO
// ============================

document.addEventListener("DOMContentLoaded", () => {


modalSolicitud = new bootstrap.Modal(
    document.getElementById("modalSolicitud")
);

cargarSolicitudes();


});

// ============================
// CARGAR SOLICITUDES
// ============================

function cargarSolicitudes() {


const estatus =
    document.getElementById("filtroEstatus").value;

const tipo =
    document.getElementById("filtroTipo").value;

const operador =
    document.getElementById("buscarOperador").value;

fetch(
    "../../controllers/SolicitudesAdminControlador.php?" +
    new URLSearchParams({
        accion: "listar",
        estatus,
        tipo,
        operador
    })
)

.then(response => response.json())

.then(data => {

    renderizarTabla(data.solicitudes);

    actualizarIndicadores(data.indicadores);

})

.catch(error => {

    console.error(error);

    alert(
        "Error al cargar solicitudes"
    );

});


}

// ============================
// TABLA
// ============================

function renderizarTabla(solicitudes) {


const tbody =
    document.getElementById(
        "tbodySolicitudes"
    );

tbody.innerHTML = "";

if (!solicitudes.length) {

    tbody.innerHTML = `
        <tr>
            <td colspan="9"
                class="text-center text-muted">
                No hay registros
            </td>
        </tr>
    `;

    return;
}

solicitudes.forEach(row => {

    let badge = "";

    switch(row.estatus){

        case "PENDIENTE":

            badge =
            '<span class="badge bg-warning text-dark">Pendiente</span>';

        break;

        case "AUTORIZADO":

            badge =
            '<span class="badge bg-success">Autorizado</span>';

        break;

        case "RECHAZADO":

            badge =
            '<span class="badge bg-danger">Rechazado</span>';

        break;

        case "CANCELADO":

            badge =
            '<span class="badge bg-secondary">Cancelado</span>';

        break;
    }

    const boton =
        row.estatus === "PENDIENTE"
        ?
        `
        <button
            class="btn btn-sm btn-primary"
            onclick="abrirSolicitud(${row.id_solicitud})">

            Gestionar

        </button>
        `
        :
        `
        <span class="text-muted">
            Procesada
        </span>
        `;

    tbody.innerHTML += `

        <tr>

            <td>${row.id_solicitud}</td>

            <td>${row.operador}</td>

            <td>${row.tipo}</td>

            <td>${row.fecha_inicio}</td>

            <td>${row.fecha_fin}</td>

            <td>${row.dias_solicitados}</td>

            <td>${badge}</td>

            <td>${row.fecha_registro}</td>

            <td>${boton}</td>

        </tr>

    `;
});


}

// ============================
// INDICADORES
// ============================

function actualizarIndicadores(data) {

document.getElementById(
    "totalPendientes"
).textContent =
    data.pendientes;

document.getElementById(
    "totalAutorizadas"
).textContent =
    data.autorizadas;

document.getElementById(
    "totalRechazadas"
).textContent =
    data.rechazadas;


}

// ============================
// DETALLE
// ============================

function abrirSolicitud(id) {


fetch(
    "../../controllers/SolicitudesAdminControlador.php?" +
    new URLSearchParams({
        accion: "detalle",
        id
    })
)

.then(response => response.json())

.then(data => {

    document.getElementById(
        "idSolicitud"
    ).value =
        data.id_solicitud;

    document.getElementById(
        "modalOperador"
    ).value =
        data.operador;

    document.getElementById(
        "modalTipo"
    ).value =
        data.tipo;

    document.getElementById(
        "modalInicio"
    ).value =
        data.fecha_inicio;

    document.getElementById(
        "modalFin"
    ).value =
        data.fecha_fin;

    document.getElementById(
        "modalMotivo"
    ).value =
        data.motivo ?? "";

    document.getElementById(
        "modalObservaciones"
    ).value =
        data.observaciones ?? "";

    document.getElementById(
        "comentarioAdmin"
    ).value = "";

    modalSolicitud.show();

})

.catch(error => {

    console.error(error);

    alert(
        "Error al cargar la solicitud"
    );

});


}

// ============================
// AUTORIZAR
// ============================

function autorizarSolicitud() {

const id =
    document.getElementById(
        "idSolicitud"
    ).value;

const comentario =
    document.getElementById(
        "comentarioAdmin"
    ).value;

procesarSolicitud(
    id,
    "AUTORIZADO",
    comentario
);


}

// ============================
// RECHAZAR
// ============================

function rechazarSolicitud() {

const id =
    document.getElementById(
        "idSolicitud"
    ).value;

const comentario =
    document.getElementById(
        "comentarioAdmin"
    ).value;

procesarSolicitud(
    id,
    "RECHAZADO",
    comentario
);


}

// ============================
// PROCESAR
// ============================

function procesarSolicitud(
id,
estatus,
comentario
) {


fetch(
    "../../controllers/SolicitudesAdminControlador.php",
    {
        method: "POST",

        headers: {
            "Content-Type":
            "application/json"
        },

        body: JSON.stringify({

            accion: "procesar",

            id,

            estatus,

            comentario

        })
    }
)

.then(response => response.json())

.then(data => {

    if(data.success){

        modalSolicitud.hide();

        cargarSolicitudes();

        alert(
            "Solicitud procesada correctamente"
        );

    }else{

        alert(
            data.message
        );

    }

})

.catch(error => {

    console.error(error);

    alert(
        "Error al procesar la solicitud"
    );

});


}
