// ===============================
// MÓDULO OPERADOR - SOLICITUDES
// ===============================

function guardarSolicitud() {

    // ===============================
    // OBTENER VALORES
    // ===============================

    let tipo = $("#tipo").val();
    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();
    let comentario = $("#comentario").val();

    // ===============================
    // VALIDACIONES
    // ===============================

    if (tipo === "" || tipo === null) {
        alert("Selecciona un tipo de solicitud");
        return;
    }

    if (fecha_inicio === "") {
        alert("Selecciona la fecha de inicio");
        return;
    }

    if (fecha_fin === "") {
        alert("Selecciona la fecha fin");
        return;
    }

    if (fecha_inicio > fecha_fin) {
        alert("La fecha inicio no puede ser mayor a la fecha fin");
        return;
    }

    // ===============================
    // AJAX
    // ===============================

    $.ajax({

        url: "/Capital_Humano/ajax/solicitudes.ajax.php",
        type: "POST",

        data: {
            accion: "crear",
            tipo: tipo,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin,
            comentario: comentario
        },

        beforeSend: function () {

            console.log("Enviando solicitud...");

        },

        success: function(response){

            console.log(response);

            if(response.status){

                alert(response.mensaje);

                limpiarFormulario();

                location.reload();

            } else {

                alert(response.mensaje);

            }

        },

        error: function (xhr, status, error) {

            console.error(xhr.responseText);

            alert("Error en la petición AJAX");

        }

    });

}

// ===============================
// LIMPIAR FORMULARIO
// ===============================

function limpiarFormulario() {

    $("#tipo").val("");
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#comentario").val("");

}

// ===============================
// CALCULAR DÍAS AUTOMÁTICOS
// ===============================

function calcularDias() {

    let inicio = $("#fecha_inicio").val();
    let fin = $("#fecha_fin").val();

    if (inicio !== "" && fin !== "") {

        let fechaInicio = new Date(inicio);
        let fechaFin = new Date(fin);

        let diferencia = fechaFin - fechaInicio;

        let dias = (diferencia / (1000 * 60 * 60 * 24)) + 1;

        console.log("Días solicitados:", dias);

    }

}

// ===============================
// EVENTOS
// ===============================

$(document).ready(function () {

    $("#fecha_inicio").on("change", calcularDias);
    $("#fecha_fin").on("change", calcularDias);

});