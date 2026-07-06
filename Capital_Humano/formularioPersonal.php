<?php 
require_once __DIR__ . '/../system/connection.php';
require '../system/constants.php'; 
?>

<!DOCTYPE html>
<html lang="es">

<head>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '../utilities/head.php'); ?>
</head>

<body>

<div class="wrapper">

<?php require_once '../utilities/sidebar.php'; Sidebar::render("Alta de Personal"); ?>

<div class="main-content">

<div class="container-fluid py-4">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-header bg-white border-0">
<h4 class="mb-0 fw-bold">Formulario de Personal</h4>
</div>

<div class="card-body">

<form id="formAltaPersonal">

<input type="hidden" id="id" name="id">

<!-- =========================
INFORMACIÓN PERSONAL
========================= -->

<h6 class="text-muted mb-3">Información Personal</h6>

<div class="row g-3">

<div class="col-md-4">
<label class="form-label">Nombre</label>
<input id="nombre" name="nombre" type="text" class="form-control uppercase-input" required>
</div>

<div class="col-md-4">
<label class="form-label">Apellido Paterno</label>
<input id="apellido_paterno" name="apellido_paterno" type="text" class="form-control uppercase-input" required>
</div>

<div class="col-md-4">
<label class="form-label">Apellido Materno</label>
<input id="apellido_materno" name="apellido_materno" type="text" class="form-control uppercase-input">
</div>

<div class="col-md-4">
<label class="form-label">CURP</label>
<input id="curp" name="curp" type="text" class="form-control uppercase-input">
</div>

<div class="col-md-4">
<label class="form-label">RFC</label>
<input id="rfc" name="rfc" type="text" class="form-control uppercase-input">
</div>

<div class="col-md-4">
<label class="form-label">NSS</label>
<input id="nss" name="nss" type="text" maxlength="11" class="form-control uppercase-input">
</div>

<div class="col-md-4">
<label class="form-label">Fecha Nacimiento</label>
<input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control uppercase-input">
</div>

<div class="col-md-4 mb-3">

<label>Estado de nacimiento</label>

<input 
type="text" 
id="estado_nombre" 
class="form-control uppercase-input" 
placeholder="Escribe el estado">

<input 
type="hidden" 
id="id_estado_nacimiento" 
name="id_estado_nacimiento">

</div>

<div class="col-md-4">
<label class="form-label">Sexo</label>

<select id="sexo" name="sexo" class="form-select">
<option value="">Seleccionar</option>
<option value="M">Masculino</option>
<option value="F">Femenino</option>
<option value="O">Otro</option>
</select>

</div>

</div>

<!-- =========================
CONTACTO
========================= -->

<h6 class="text-muted mt-4 mb-3">Contacto</h6>

<div class="row g-3">

<div class="col-md-4">
<label class="form-label">Correo</label>
<input id="correo" name="correo" type="email" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Teléfono</label>
<input id="telefono" name="telefono" type="text" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Teléfono Emergencia</label>
<input id="telefono_emergencia" name="telefono_emergencia" type="text" class="form-control">
</div>

</div>

<!-- =========================
INFORMACIÓN LABORAL
========================= -->

<h6 class="text-muted mt-4 mb-3">Información Laboral</h6>

<div class="row g-3">

<div class="col-md-4">
<label class="form-label">Número Empleado</label>
<input id="numero_empleado" name="numero_empleado" type="text" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label">Departamento</label>

<select id="id_departamento" name="id_departamento" class="form-select">
<option value="">Seleccionar</option>
<option value="1">OPERADOR</option>
<option value="2">OPERACIONES</option>
</select>

</div>

<div class="col-md-4">
<label class="form-label">Puesto</label>

<select id="id_puesto" name="id_puesto" class="form-select">
<option value="">Seleccionar</option>
<option value="1">OPERADOR 3.5</option>
<option value="2">OPERADOR RABON</option>
<option value="3">OPERADOR TORTON</option>
<option value="4">OPERADOR TRACTO</option>
<option value="5">OPERADOR FULL</option>
<option value="6">MANIOBRISTA</option>
</select>

</div>

<div class="col-md-4">
<label class="form-label">Fecha Ingreso</label>
<input id="fecha_ingreso" name="fecha_ingreso" type="date" class="form-control">
</div>

</div>

<!-- =========================
DOMICILIO
========================= -->

<hr class="mt-4">

<div class="d-flex justify-content-between align-items-center">

<div>
<h6 class="text-muted mb-0">Domicilio</h6>

<small class="text-muted">
Registrar dirección actual del empleado
</small>
</div>

<button 
type="button"
class="btn btn-outline-primary"
data-bs-toggle="modal"
data-bs-target="#modalDomicilio">

Agregar domicilio

</button>

</div>

<!-- =========================
BOTONES
========================= -->

<div class="d-flex justify-content-end gap-3 mt-4">

<a href="index.php" class="btn btn-light">
Cancelar
</a>

<button id="btnGuardar" type="submit" class="btn btn-primary">
Guardar Empleado
</button>

</div>

<!-- =========================
MODAL DOMICILIO
========================= -->

<div class="modal fade" id="modalDomicilio" tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content border-0 shadow">

<div class="modal-header">

<h5 class="modal-title fw-bold">
Domicilio del Empleado
</h5>

<button 
type="button" 
class="btn-close" 
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="row g-3">

<!-- CP -->

<div class="col-md-4">

<label class="form-label">
Código Postal
</label>

<input 
type="text"
id="codigo_postal"
name="codigo_postal"
class="form-control"
maxlength="5">

</div>

<!-- ESTADO -->

<div class="col-md-4">

<label class="form-label">
Estado
</label>

<input 
type="text"
id="dom_estado"
name="dom_estado"
class="form-control"
readonly>

</div>

<!-- MUNICIPIO -->

<div class="col-md-4">

<label class="form-label">
Municipio
</label>

<input 
type="text"
id="dom_municipio"
name="dom_municipio"
class="form-control"
readonly>

</div>

<!-- COLONIA -->

<div class="col-md-12">

<label class="form-label">
Colonia
</label>

<select 
id="id_cp"
name="id_cp"
class="form-select">

<option value="">
Seleccionar colonia
</option>

</select>

</div>

<!-- CALLE -->

<div class="col-md-6">

<label class="form-label">
Calle
</label>

<input 
type="text"
id="calle"
name="calle"
class="form-control text-uppercase">

</div>

<!-- NUMERO EXTERIOR -->

<div class="col-md-3">

<label class="form-label">
No. Exterior
</label>

<input 
type="text"
id="numero_exterior"
name="numero_exterior"
class="form-control text-uppercase">

</div>

<!-- NUMERO INTERIOR -->

<div class="col-md-3">

<label class="form-label">
No. Interior
</label>

<input 
type="text"
id="numero_interior"
name="numero_interior"
class="form-control">

</div>

<!-- REFERENCIA -->

<div class="col-md-12">

<label class="form-label">
Referencia
</label>

<textarea 
id="referencia"
name="referencia"
class="form-control text-uppercase"
rows="2"></textarea>

</div>

</div>

</div>

<div class="modal-footer">

<button 
type="button"
class="btn btn-light"
data-bs-dismiss="modal">

Cerrar

</button>

<button 
id="btnGuardarDomicilio"
type="button"
class="btn btn-primary"
data-bs-dismiss="modal">

Guardar domicilio

</button>

</div>

</div>
</div>
</div>



</div>
</div>
</div>
</div>
</div>

</form>

<script>

/* =========================
ESTADOS
========================= */

const inputEstado = document.getElementById("estado_nombre");

if(inputEstado){

    inputEstado.addEventListener("keyup", function(){

        const term = this.value;

        if(term.length < 2) return;

        fetch(`apiPersonal.php?action=buscarEstados&term=${term}`)
        .then(res => res.json())
        .then(res => {

            if(res.status !== "ok") return;

            mostrarOpcionesEstados(res.data);

        });

    });

}

/* =========================
BUSCAR CP
========================= */

const inputCP = document.getElementById("codigo_postal");

if(inputCP){

    inputCP.addEventListener("keyup", function(){

        const cp = this.value.trim();

        if(cp.length !== 5){

            document.getElementById("id_cp").innerHTML = `
                <option value="">
                    Seleccionar colonia
                </option>
            `;

            return;
        }

        fetch(`apiPersonal.php?action=buscarCP&cp=${cp}`)
        .then(res => res.json())
        .then(res => {

            console.log(res);

            if(res.status !== "ok"){
                return;
            }

            document.getElementById("dom_estado").value =
                res.estado || "";

            document.getElementById("dom_municipio").value =
                res.municipio || "";

            const select = document.getElementById("id_cp");

            select.innerHTML = `
                <option value="">
                    Seleccionar colonia
                </option>
            `;

            res.colonias.forEach(col => {

                select.innerHTML += `
                    <option value="${col.id_cp}">
                        ${col.colonia}
                    </option>
                `;
            });

        })
        .catch(error => {
            console.error(error);
        });

    });

}

/* =========================
FORM
========================= */

const form = document.getElementById("formAltaPersonal");

const idEmpleado =
    new URLSearchParams(window.location.search).get("id");

/* =========================
CARGAR EMPLEADO
========================= */

if(idEmpleado){

fetch(`apiPersonal.php?action=obtener&id=${idEmpleado}`)
.then(res => res.json())
.then(data => {

    console.log(data);

    if(data.status !== "ok") return;

    const e = data.data;

    /* =========================
    DATOS PERSONALES
    ========================= */

    document.getElementById("id").value = e.id || "";

    document.getElementById("nombre").value = e.nombre || "";
    document.getElementById("apellido_paterno").value = e.apellidoP || "";
    document.getElementById("apellido_materno").value = e.apellidoM || "";

    document.getElementById("curp").value = e.curp || "";
    document.getElementById("rfc").value = e.rfc || "";
    document.getElementById("nss").value = e.nss || "";

    document.getElementById("fecha_nacimiento").value =
        e.fecNac ? e.fecNac.split(" ")[0] : "";

    document.getElementById("estado_nombre").value =
        e.estado_nombre || "";

    document.getElementById("id_estado_nacimiento").value =
        e.id_estado_nacimiento || "";

    document.getElementById("sexo").value =
        e.sexo || "";

    /* =========================
    CONTACTO
    ========================= */

    document.getElementById("correo").value =
        e.email || "";

    document.getElementById("telefono").value =
        e.movil || "";

    document.getElementById("telefono_emergencia").value =
        e.telefono_emergencia || "";

    /* =========================
    LABORAL
    ========================= */

    document.getElementById("numero_empleado").value =
        e.noEmpleado || "";

    document.getElementById("id_departamento").value =
        e.id_departamento || "";

    document.getElementById("id_puesto").value =
        e.id_puesto || "";

    document.getElementById("fecha_ingreso").value =
        e.fecContratacion || "";

    /* =========================
    DOMICILIO
    ========================= */

    document.getElementById("codigo_postal").value =
        e.codigo_postal || "";

    document.getElementById("dom_estado").value =
        e.estado_domicilio || "";

    document.getElementById("dom_municipio").value =
        e.municipio_nombre || "";

    document.getElementById("calle").value =
        e.calle || "";

    document.getElementById("numero_exterior").value =
        e.numero_exterior || "";

    document.getElementById("numero_interior").value =
        e.numero_interior || "";

    document.getElementById("referencia").value =
        e.referencia || "";

    /* =========================
    COLONIAS
    ========================= */

    if(e.codigo_postal){

        fetch(`apiPersonal.php?action=buscarCP&cp=${e.codigo_postal}`)
        .then(res => res.json())
        .then(cpData => {

            if(cpData.status !== "ok") return;

            const select = document.getElementById("id_cp");

            select.innerHTML = `
                <option value="">
                    Seleccionar colonia
                </option>
            `;

            cpData.colonias.forEach(col => {

                select.innerHTML += `
                    <option
                        value="${col.id_cp}"
                        ${col.id_cp == e.id_cp ? "selected" : ""}
                    >
                        ${col.colonia}
                    </option>
                `;

            });

        });

    }

    document.getElementById("btnGuardar").innerText =
        "Actualizar Empleado";

})
.catch(error => console.error(error));

}

/* =========================
GUARDAR / ACTUALIZAR
========================= */

form.addEventListener("submit", function(e){

e.preventDefault();

const formData = new FormData(form);

if(idEmpleado){
    formData.set("action","actualizar");
}else{
    formData.set("action","guardar");
}

console.log([...formData.entries()]);

fetch("apiPersonal.php",{
    method:"POST",
    body:formData
})
.then(res => res.json())
.then(data => {

    console.log(data);

    if(data.status === "ok"){

        alert(data.message || "Operación exitosa");

        window.location.href = "index.php";

    }else{

        alert(data.message || "Error");

    }

})
.catch(error => {

    console.error(error);

    alert("Error en el servidor");

});

});

/* =========================
LISTA ESTADOS
========================= */

function mostrarOpcionesEstados(data){

    let lista = document.getElementById("listaEstados");

    if(!lista){

        lista = document.createElement("div");

        lista.id = "listaEstados";

        lista.classList.add("list-group");

        inputEstado.parentNode.appendChild(lista);
    }

    lista.innerHTML = "";

    data.forEach(estado => {

        const item = document.createElement("button");

        item.type = "button";

        item.classList.add(
            "list-group-item",
            "list-group-item-action"
        );

        item.textContent = estado.nombre;

        item.onclick = () => {

            document.getElementById("estado_nombre").value =
                estado.nombre;

            document.getElementById("id_estado_nacimiento").value =
                estado.id_estado;

            lista.innerHTML = "";
        };

        lista.appendChild(item);
    });

}

document.querySelectorAll(
        'input[type="text"], textarea'
    ).forEach(input => {

        input.addEventListener("input", function(){

            this.value = this.value.toUpperCase();

        });

    });

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