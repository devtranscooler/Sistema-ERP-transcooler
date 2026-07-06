<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/sidebar.php';

Sidebar::render("Asistencia Operadores");
?>

<div style="margin-left: 250px; padding: 20px; padding-top: 80px;">

    <h2 class="mb-4">
        <i class="bi bi-people"></i> Tablero de Operadores
    </h2>

    <!-- CONTENEDOR -->
    <div id="listaOperadores"></div>

</div>


<script>

// 🚀 Cargar operadores al iniciar
document.addEventListener("DOMContentLoaded", function(){
    cargarOperadores();
});


// 🔄 Obtener lista desde backend
function cargarOperadores(){

    fetch('/Capital_Humano/ajax/asistencia_operadores.ajax.php')
    .then(res => res.json())
    .then(data => {

        let contenedor = document.getElementById("listaOperadores");
        contenedor.innerHTML = "";

        data.forEach(op => {

            let color = obtenerColor(op.estatus);

            contenedor.innerHTML += `
                <div class="card mb-3 shadow-sm" style="border-left: 6px solid ${color};">
                    
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h5 class="mb-1">${op.nombre}</h5>
                            <small class="text-muted">
                                ${op.estatus ?? 'SIN REGISTRO'} 
                                ${op.id_viaje ? ' | Viaje: ' + op.id_viaje : ''}
                            </small>
                        </div>

                        <div class="d-flex gap-2">

                            <button class="btn btn-success btn-sm"
                                onclick="actualizar(${op.id}, 'EN_VIAJE')">
                                Viaje
                            </button>

                            <button class="btn btn-warning btn-sm"
                                onclick="actualizar(${op.id}, 'DISPONIBLE')">
                                Disponible
                            </button>

                            <button class="btn btn-danger btn-sm"
                                onclick="actualizar(${op.id}, 'SIN_ACTIVIDAD')">
                                No laboró
                            </button>

                            <button class="btn btn-secondary btn-sm"
                                onclick="actualizar(${op.id}, 'DESCANSO')">
                                Descanso
                            </button>

                        </div>

                    </div>
                </div>
            `;
        });
    });
}


// 🎨 Colores por estatus
function obtenerColor(estatus){

    switch(estatus){
        case 'EN_VIAJE': return '#28a745';
        case 'DISPONIBLE': return '#ffc107';
        case 'SIN_ACTIVIDAD': return '#dc3545';
        case 'DESCANSO': return '#6c757d';
        case 'PERMISO': return '#17a2b8';
        default: return '#cccccc';
    }
}


// ⚙️ Actualizar estatus
function actualizar(id_usuario, estatus){

    fetch('/Capital_Humano/ajax/asistencia_operadores.ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_usuario=${id_usuario}&estatus=${estatus}`
    })
    .then(res => res.text())
.then(data => {
    console.log(data);
})

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

}



</script>