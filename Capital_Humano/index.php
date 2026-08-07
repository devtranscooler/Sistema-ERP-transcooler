<?php 
require '../system/connection.php';
require '../system/constants.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require '../utilities/head.php'; ?>
</head>

<body>

<?php require '../utilities/sidebar.php'; 
Sidebar::render("Gestión de Operadores") ?>

<div class="container-fluid">

<nav aria-label="breadcrumb">
<ol class="breadcrumb mb-1">
<li class="breadcrumb-item">Inicio</li>
<li class="breadcrumb-item active">Operadores</li>
</ol>
</nav>

<div class="row align-items-center">
<div class="col-md-6">
<h2 class="fw-bold mb-0">Gestión de Operadores</h2>
</div>

<div class="col-md-6 text-md-end">
<button class="btn btn-primary"
onclick="window.location.href='formularioPersonal.php'">
Nuevo Personal
</button>
</div>
</div>

<div class="mt-3">
<div class="row">

<div class="col-md-4">
<input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre...">
</div>

<div class="col-md-4">
<select class="form-select" id="filtroDepartamento">
<option value="">Todos</option>
</select>
</div>

<div class="col-md-4">
<select class="form-select" id="filtroEstatus">
<option value="">Todos</option>
</select>
</div>

</div>
</div>

<div class="table-responsive mt-3">
<table class="table table-hover" id="tablaOperadores">
<thead>
<tr>
<th data-campo="noEmpleado" onclick="ordenar('noEmpleado')">ID</th>
<th data-campo="nombreCompleto" onclick="ordenar('nombreCompleto')">Nombre</th>
<th data-campo="departamento" onclick="ordenar('departamento')">Departamento</th>
<th data-campo="puesto" onclick="ordenar('puesto')">Puesto</th>
<th data-campo="estatus" onclick="ordenar('estatus')" style="cursor:pointer;"> Estatus </th>
<th data-campo="licencia_estatus" onclick="ordenar('licencia_estatus')">Licencia</th>
<th data-campo="apto_estatus" onclick="ordenar('apto_estatus')">Apto Medico</th>
<th>Acciones</th>
</tr>
</thead>
<tbody></tbody>
</table>
</div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalLicencias" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Documentos del Operador</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form id="formLicencias">

<input type="hidden" id="doc_id_empleado">

<div class="row">
<div class="col-md-4 mb-3">
<label>Tipo de documento</label>
<select class="form-select" id="tipo_documento_id" required>
<option value="">Selecciona</option>
<option value="1">Licencia</option>
<option value="2">Apto médico</option>
<option value="3">R-Control</option>
</select>
</div>

<div class="col-md-4 mb-3 d-none" id="grupo_tipo_licencia">
<label>Tipo de Licencia</label>
<select class="form-select" id="tipo_licencia">
<option value="">Selecciona</option>
<option value="B">Tipo B</option>
<option value="C">Tipo C</option>
<option value="D">Tipo D</option>
<option value="E">Tipo E</option>
</select>
</div>

<div class="col-md-4 mb-3">
<label>Folio</label>
<input type="text" class="form-control" id="folio" required>
</div>

<div class="col-md-4 mb-3">
<label>Fecha de vencimiento</label>
<input type="date" class="form-control" id="fecha_vencimiento">
</div>
</div>

<button type="submit" class="btn btn-primary w-100">
Guardar documento
</button>

</form>

<hr>

<h6>Documentos registrados</h6>

<div class="table-responsive">
<table class="table table-sm">
<thead>
<tr>
<th>Tipo</th>
<th>Folio</th>
<th>Vencimiento</th>
<th>Licencia</th>
<th>Estatus</th>
<th></th>
</tr>
</thead>
<tbody id="tablaDocumentos"></tbody>
</table>
</div>

</div>

</div>
</div>
</div>

<!-- MODAL ARCHIVOS -->
<div class="modal fade" id="modalArchivos" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Subir Documentos</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form id="formArchivo" enctype="multipart/form-data">

<input type="hidden" id="archivo_id_empleado">

<div class="mb-3">
<label>Tipo de documento</label>
<input type="text" id="archivo_tipo" class="form-control" required>
</div>

<div class="mb-3">
<label>Archivo</label>
<input type="file" id="archivo_file" class="form-control" required>
</div>

<button class="btn btn-primary w-100">Subir</button>

</form>

<hr>

<h6>Archivos</h6>
<ul id="listaArchivos"></ul>

</div>

</div>
</div>
</div>


<script>

let timeout = null;
let modalLicencias = null;
let editandoDocumento = null;

/* SORT */
let datosOperadores = [];
let campoOrden = null;
let direccionOrden = "asc";
let modalArchivos = null;

/*  FUNCIONES GLOBALES */
window.editarOperador = function(id){
    window.location.href = `formularioPersonal.php?id=${id}`;
};

window.abrirModalArchivos = function(id){

    document.getElementById("archivo_id_empleado").value = id;

    if(!modalArchivos){
        modalArchivos = new bootstrap.Modal(document.getElementById('modalArchivos'));
    }

    cargarArchivos(id);
    modalArchivos.show();
};

window.abrirModalLicencias = function(id){
    document.getElementById("formLicencias").reset();
    document.getElementById("doc_id_empleado").value = id;
    editandoDocumento = null;

    cargarDocumentos(id);

    if(!modalLicencias){
        modalLicencias = new bootstrap.Modal(document.getElementById('modalLicencias'));
    }

    modalLicencias.show();
};

/* DOM READY */
document.addEventListener("DOMContentLoaded", () => {

    cargarOperadores();

    const filtroNombre = document.getElementById("filtroNombre");
    const filtroDepartamento = document.getElementById("filtroDepartamento");
    const filtroEstatus = document.getElementById("filtroEstatus");
    const tipoDoc = document.getElementById("tipo_documento_id");
    const formLicencias = document.getElementById("formLicencias");

    if(filtroNombre){
        filtroNombre.addEventListener("keyup", () => {
            clearTimeout(timeout);
            timeout = setTimeout(cargarOperadores, 300);
        });
    }

    if(filtroDepartamento) filtroDepartamento.addEventListener("change", cargarOperadores);
    if(filtroEstatus) filtroEstatus.addEventListener("change", cargarOperadores);

    if(tipoDoc){
        tipoDoc.addEventListener("change", function(){
            const grupo = document.getElementById("grupo_tipo_licencia");

            if(this.value == "1"){
                grupo.classList.remove("d-none");
            }else{
                grupo.classList.add("d-none");
                document.getElementById("tipo_licencia").value = "";
            }
        });
    }

    if(formLicencias){
        formLicencias.addEventListener("submit", function(e){
            e.preventDefault();

            const formData = new FormData();
            const tipoDocumento = document.getElementById("tipo_documento_id").value;

            formData.append("id_empleado", document.getElementById("doc_id_empleado").value);
            formData.append("tipo_documento_id", tipoDocumento);
            formData.append("folio", document.getElementById("folio").value);
            formData.append("fecha_vencimiento", document.getElementById("fecha_vencimiento").value);

            if(tipoDocumento == "1"){
                formData.append("tipo_licencia", document.getElementById("tipo_licencia").value);
            }

             console.log("EDITANDO:", editandoDocumento);

            if(editandoDocumento){
                formData.append("action","actualizarDocumento");
                formData.append("id_documento", editandoDocumento);
            }else{
                formData.append("action","guardarDocumento");
            }

            fetch("apiPersonal.php",{ method:"POST", body:formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === "ok"){
                    alert(data.message);
                    editandoDocumento = null;
                    formLicencias.reset();
                    cargarDocumentos(document.getElementById("doc_id_empleado").value);
                }else{
                    alert(data.message);
                }
            });
        });
    }

});

document.getElementById("formArchivo").addEventListener("submit", function(e){

    e.preventDefault();

    const formData = new FormData();

    formData.append("action", "subirArchivo");
    formData.append("id_empleado", document.getElementById("archivo_id_empleado").value);
    formData.append("tipo", document.getElementById("archivo_tipo").value);
    formData.append("archivo", document.getElementById("archivo_file").files[0]);

    fetch("apiPersonal.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        if(res.status === "ok"){
            alert("Archivo subido");
            cargarArchivos(document.getElementById("archivo_id_empleado").value);
        }else{
            alert(res.message);
        }

    });

});

/* DATA */

function cargarOperadores(){

const params = new URLSearchParams({
    action: "listar",
    nombre: document.getElementById("filtroNombre").value,
    departamento: document.getElementById("filtroDepartamento").value,
    estatus: document.getElementById("filtroEstatus").value
});

fetch("apiPersonal.php?" + params)
.then(res => res.json())
.then(res => {
    if(res.status !== "ok") return;

    datosOperadores = res.data;
    pintarTabla(datosOperadores);
});

}

/* SORT */
function ordenar(campo){

    if(campoOrden === campo){
        direccionOrden = direccionOrden === "asc" ? "desc" : "asc";
    }else{
        campoOrden = campo;
        direccionOrden = "asc";
    }

    const prioridad = {
        "VENCIDO": 1,
        "POR_VENCER": 2,
        "VIGENTE": 3,
        "SIN_FECHA": 4
    };

    const dataOrdenada = [...datosOperadores].sort((a,b) => {

        let valA = a[campo] ?? "";
        let valB = b[campo] ?? "";

        if(
            campo === "licencia_estatus" ||
            campo === "apto_estatus"
        ){

            valA = prioridad[valA] ?? 99;
            valB = prioridad[valB] ?? 99;

        }else{

            valA = valA.toString().toLowerCase();
            valB = valB.toString().toLowerCase();

        }

        if(valA < valB) return direccionOrden === "asc" ? -1 : 1;
        if(valA > valB) return direccionOrden === "asc" ? 1 : -1;

        return 0;
    });

    pintarTabla(dataOrdenada);
    
    actualizarIndicadoresSort();
}

function actualizarIndicadoresSort(){

    const ths = document.querySelectorAll("#tablaOperadores th");

    ths.forEach(th => {

        const campo = th.getAttribute("data-campo");

        // Limpiar contenido (quitamos flechas previas)
        let texto = th.innerText.replace(" ↑", "").replace(" ↓", "");
        th.innerText = texto;

        // Si es el campo activo, agregamos flecha
        if(campo === campoOrden){

            if(direccionOrden === "asc"){
                th.innerText += " ↑";
            }else{
                th.innerText += " ↓";
            }
        }
    });
}

/* UI */

function badgeDocumento(estatus){

if(!estatus) return `<span class="badge bg-secondary">Sin doc</span>`;

let clase = "bg-secondary";

if(estatus === "VENCIDO") clase = "bg-danger";
if(estatus === "POR_VENCER") clase = "bg-warning";
if(estatus === "VIGENTE") clase = "bg-success";

return `<span class="badge ${clase}">${estatus}</span>`;
}

function pintarTabla(data){

let html = "";

if(!data || data.length === 0){
html = `<tr><td colspan="8">Sin resultados</td></tr>`;
}else{

data.forEach(op => {

let badge = op.estatus === "Inactivo" ? "bg-danger" : "bg-success";

html += `
<tr>
<td>${op.noEmpleado|| ''}</td>
<td>${op.nombreCompleto || ''}</td>
<td>${op.departamento || ''}</td>
<td>${op.puesto || ''}</td>
<td class="text-center">

<div class="dropdown">

<button 
class="btn btn-sm dropdown-toggle
${op.estatus === 'Activo' ? 'btn-success' : ''}
${op.estatus === 'Inactivo' ? 'btn-danger' : ''}
${op.estatus === 'Incapacidad' ? 'btn-warning text-dark' : ''}"
type="button"
data-bs-toggle="dropdown">

${op.estatus}

</button>

<ul class="dropdown-menu">

<li>
<a class="dropdown-item"
href="#"
onclick="event.preventDefault(); cambiarEstatus(${op.id}, 'Activo')">
Alta
</a>
</li>

<li>
<a class="dropdown-item"
href="#"
onclick="event.preventDefault(); cambiarEstatus(${op.id}, 'Inactivo')">
Baja
</a>
</li>

<li>
<a class="dropdown-item"
href="#"
onclick="event.preventDefault(); cambiarEstatus(${op.id}, 'Incapacidad')">
Incapacidad
</a>
</li>

</ul>

</div>

</td>
<td>${badgeDocumento(op.licencia_estatus)}</td>
<td>${badgeDocumento(op.apto_estatus)}</td>
<td class="text-center">
<div class="dropdown">

<button 
class="btn btn-sm btn-outline-secondary"
type="button"
data-bs-toggle="dropdown">

⋮
</button>

<ul class="dropdown-menu dropdown-menu-end">

<li>
<a class="dropdown-item"
href="#"
onclick="editarOperador(${op.id})">
Editar
</a>
</li>

<li>
<a class="dropdown-item"
href="#"
onclick="abrirModalLicencias(${op.id})">
Licencias
</a>
</li>

<li>
<a class="dropdown-item"
href="#"
onclick="abrirModalArchivos(${op.id})">
Documentos
</a>
</li>

</ul>
</div>
</td>
</tr>
`;
});

}

document.querySelector("#tablaOperadores tbody").innerHTML = html;

}

/* DOCUMENTOS */
function cargarDocumentos(id){

const tabla = document.getElementById("tablaDocumentos");

tabla.innerHTML = `<tr><td colspan="6" class="text-center">Cargando...</td></tr>`;

fetch(`apiPersonal.php?action=listarDocumentos&id_empleado=${id}`)
.then(res => res.json())
.then(res => {

if(res.status !== "ok"){
tabla.innerHTML = `<tr><td colspan="6">Error</td></tr>`;
return;
}

if(!res.data.length){
tabla.innerHTML = `<tr><td colspan="6">Sin documentos</td></tr>`;
return;
}

let html = "";

res.data.forEach(doc => {

let badge = "bg-secondary";
if(doc.estatus_vencimiento === "VENCIDO") badge = "bg-danger";
if(doc.estatus_vencimiento === "POR_VENCER") badge = "bg-warning";
if(doc.estatus_vencimiento === "VIGENTE") badge = "bg-success";

html += `
<tr>
<td>${doc.tipo_documento}</td>
<td>${doc.folio}</td>
<td>${doc.fecha_vencimiento ?? ''}</td>
<td>${doc.tipo_licencia ?? '-'}</td>
<td><span class="badge ${badge}">${doc.estatus_vencimiento}</span></td>
<td>
<button class="btn btn-sm btn-outline-primary"
onclick="editarDocumentoDesdeTabla(${doc.id_documento})">Editar</button>
</td>
</tr>
`;
});

tabla.innerHTML = html;

});

}

function editarDocumentoDesdeTabla(id){
fetch(`apiPersonal.php?action=obtenerDocumento&id_documento=${id}`)
.then(res => res.json())
.then(res => {
console.log("RESPUESTA:", res);    
if(res.status !== "ok") return;
editarDocumento(res.data);
});
}

function editarDocumento(doc){
editandoDocumento = doc.id_documento;
document.getElementById("tipo_documento_id").value = doc.tipo_documento_id;
document.getElementById("folio").value = doc.folio;
document.getElementById("fecha_vencimiento").value = doc.fecha_vencimiento;
document.getElementById("tipo_licencia").value = doc.tipo_licencia ?? "";
if(doc.tipo_documento_id == "1"){
    document.getElementById("grupo_tipo_licencia").classList.remove("d-none");
}
}


function cargarArchivos(id){

fetch(`apiPersonal.php?action=listarArchivos&id_empleado=${id}`)
.then(res => res.json())
.then(res => {

    const lista = document.getElementById("listaArchivos");

    if(res.status !== "ok"){
        lista.innerHTML = "Error";
        return;
    }

    let html = "";

    res.data.forEach(doc => {
        html += `
        <li>
            ${doc.tipo} -
            <a href="${doc.ruta}" target="_blank">Ver</a>
        </li>
        `;
    });

    lista.innerHTML = html;

});
}


function cambiarEstatus(id, nuevoEstatus){

    if(!confirm("¿Deseas actualizar el estatus del operador?")){
        cargarOperadores(); // revierte visualmente
        return;
    }

    const mapaEstatus = {
        "Activo": 1,
        "Inactivo": 2,
        "Incapacidad": 3
    };


    
    const formData = new FormData();

    formData.append("action", "cambiarEstatus");
    formData.append("id_empleado", id);
    formData.append("id_estatus", mapaEstatus[nuevoEstatus]);

    fetch("apiPersonal.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        if(res.status === "ok"){
            // opcional: feedback visual
            cargarOperadores();
        }else{
            alert(res.message);
            cargarOperadores();
        }

    })
    .catch(() => {
        alert("Error de conexión");
        cargarOperadores();
    });
}

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

</body>
</html>