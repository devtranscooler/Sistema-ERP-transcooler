<?php
require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

$sql_tractos="SELECT eco FROM cat_unidades WHERE control_km=1 ORDER BY eco";
$rs_tractos=$db->consulta($sql_tractos);
$tractos=[];
while($row=$db->fetch_array($rs_tractos)){$tractos[]=$row['eco'];
}

$sql_remolques="SELECT eco FROM cat_unidades WHERE control_km=0 ORDER BY eco";
$rs_remolques=$db->consulta($sql_remolques);
$remolques=[];
while($row=$db->fetch_array($rs_remolques)){$remolques[]=$row['eco'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/head.php'); ?>
<title>Registrar Falla</title>
</head>
<body onclick="closeMenu(event)">
<?php
require_once '../utilities/sidebar.php';
Sidebar::render("Registrar Falla");
?>
<div class="container-fluid">
<nav class="breadcrumb mb-4">Inicio / Reporte Fallas / Registrar</nav>
<div class="card form-card">
<form action="guardar_falla.php" method="POST" enctype="multipart/form-data">
<div class="col-md-4">
<label>Tipo de reporte *</label>
<select name="tipo_reporte" class="form-select" required>
<option value="">Seleccionar</option>
<option value="auxilio">Auxilio</option>
<option value="mecanica">Falla Mecánica</option>
<option value="talacha">Talacha</option>
</select>
</div>
<div class="section-title">Información General</div>
<div class="row">
<div class="col-md-3">
<label>Número Económico *</label>
<select id="eco" name="eco" class="form-select" required>
<option value="">Seleccionar unidad</option>
<?php foreach($tractos as $eco): ?>
<option value="<?= $eco ?>"><?= $eco ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-3">
<label>Remolque *</label>
<select name="remolque" class="form-select" required>
<option value="">Seleccionar remolque</option>
<?php foreach($remolques as $eco): ?>
<option value="<?= $eco ?>"><?= $eco ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-3">
<label>Operador *</label>
<select name="operador" id="operador" class="form-select" required>
<option value="">Seleccionar</option>
<?php
$sql_operadores = "SELECT id, nombre, apellidoP, apellidoM, movil FROM usuarios WHERE puesto_id IS NOT NULL ORDER BY apellidoP, apellidoM, nombre";
$rs_operadores = $db->consulta($sql_operadores);
while($op = $db->fetch_array($rs_operadores)):
$nombre_completo = trim($op['nombre'] . ' ' . $op['apellidoP'] . ' ' . $op['apellidoM']);
?>
<option value="<?= $op['id'] ?>" data-movil="<?= $op['movil'] ?>"><?= $nombre_completo ?></option>
<?php endwhile; ?>
</select>
</div>
</div>
<br>
<div class="row">
<div class="col-md-4">
<label>Teléfono *</label>
<input name="telefono" id="telefono" class="form-control" required>
</div>
<div class="col-md-4">
<label>Cliente *</label>
<input type="text" id="cliente" name="cliente" class="form-control" required>
</div>
<div class="col-md-4">
<label>Tipo de Carga *</label>
<select name="tipo_carga" class="form-select" required>
<option>Refrigerada</option>
<option>Congelada</option>
<option>Seca</option>
<option>Vacia</option>
</select>
</div>
</div>
<br>
<div class="section-title">Ubicación</div>
<div class="row">
<div class="col-md-6">
<label>Link ubicación *</label>
<input type="text" id="link_ubicacion" name="link_ubicacion" class="form-control" readonly required>
</div>
<div class="col-md-6">
<label>Detenida en *</label>
<input type="text" id="ubicacion_actual" name="ubicacion_actual" class="form-control" readonly required>
</div>
</div>
<br>
<br>
<div class="section-title">Estatus Operativo</div>
<div class="row g-3">
<div class="col-md-3">
<label>Status *</label>
<select name="estatus" class="form-select" required>
<option value="">Seleccionar</option>
<option value="PENDIENTE">PENDIENTE</option>
<option value="EN PROCESO">EN PROCESO</option>
<option value="CERRADA">CERRADA</option>
</select>
</div>
<div class="col-md-3">
<label>Status operativo *</label>
<select name="estatus_operativo" class="form-select" required>
<option value="">Seleccionar</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
</select>
</div>
<div class="col-md-3">
<label>Origen *</label>
<input type="text" id="origen" name="origen" class="form-control" required>
</div>
<div class="col-md-3">
<label>Destino *</label>
<input type="text" id="destino" name="destino" class="form-control" required>
</div>
<div class="col-md-3">
<label>Temperatura Actual *</label>
<input type="number" step="0.1" name="temp_actual" class="form-control" required>
</div>
<div class="col-md-3">
<label>Temperatura Solicitada *</label>
<input type="number" step="0.1" name="temp_solicitada" class="form-control" required>
</div>
</div>
<div class="section-title">Tipo de Falla</div>
<div class="row">
<div class="col-md-6">
<label>Tipo de falla *</label>
<select id="tipo_falla" name="tipo_falla" class="form-select" required>
<option value="">Seleccionar</option>
<option value="MOTRIZ">Falla Motriz</option>
<option value="REFRIGERACION">Falla Refrigeración</option>
<option value="LLANTAS">Falla de Llantas</option>
</select>
</div>
</div>
<div id="subseccion"></div>
<br>
<div id="contenedor_motriz" style="display:none">
<div class="row">
<div class="col-md-6">
<label>Grupo motor *</label>
<select id="grupo_motor" name="grupo_motor" class="form-select">
<option value="">Seleccionar</option>
<option>UNIDAD</option>
<option>MOTOR</option>
<option>TREN MOTRIZ</option>
<option>ELECTRICO</option>
<option>DIRECCION Y RODAMIENTO</option>
<option>FRENOS Y SISTEMA DE AIRE</option>
<option>CHASIS Y SUSPENSION</option>
<option>CARROCERIA</option>
</select>
</div>
</div>
</div>
<div id="contenedor_refrigeracion" style="display:none">
<label>Elija la falla presentada *</label>
<select name="falla_refrigeracion" class="form-select">
<option>Thermo se apaga</option>
<option>Thermo no enciende</option>
<option>Thermo alarmado</option>
<option>No baja temperatura</option>
<option>Temperatura elevada</option>
<option>Display apagado</option>
<option>Falla de defrost</option>
<option>Fuga de aceite en thermo</option>
<option>Thermo sin batería</option>
<option>Falla de marcha del thermo</option>
<option>Código Hi Press</option>
<option>Falla de encendido automático</option>
<option>Thermo se prende y apaga</option>
<option>Sin corriente eléctrica</option>
<option>Difusor no enciende</option>
<option>Temperatura fuera de rango</option>
</select>
</div>
<div id="contenedor_llantas" style="display:none">
<div class="row">
<div class="col-md-6">
<label>Tipo unidad *</label>
<select name="tipo_llanta" class="form-select">
<option>Tracto</option>
<option>Remolque</option>
<option>Thorton</option>
<option>Rabon</option>
<option>Dolly</option>
</select>
</div>
</div>
</div>
<br>
<div id="motriz" style="display:none">
<label>Grupo</label>
<select name="grupo_motor" class="form-select">
<option>UNIDAD</option>
<option>MOTOR</option>
<option>TREN MOTRIZ</option>
<option>ELECTRICO</option>
<option>DIRECCION Y RODAMIENTO</option>
<option>FRENOS Y SISTEMA DE AIRE</option>
<option>CHASIS Y SUSPENSION</option>
<option>CARROCERIA</option>
</select>
</div>
<div id="refrigeracion" style="display:none">
<label>Falla thermo</label>
<select name="falla_thermo" class="form-select">
<option>Thermo se apaga</option>
<option>Thermo no enciende</option>
<option>Thermo alarmado</option>
<option>No baja temperatura</option>
</select>
</div>
<div id="llantas" style="display:none">
<div class="row">
<div class="col-md-6">
<label>Tipo unidad</label>
<select name="tipo_llanta" class="form-select">
<option>Tracto</option>
<option>Remolque</option>
<option>Thorton</option>
<option>Rabon</option>
<option>Dolly</option>
</select>
</div>
<div class="col-md-6">
<label>Posición</label>
<input type="number" name="posicion_llanta" class="form-control">
</div>
</div>
</div>
<br>
<div class="section-title">Descripción General De La Falla</div>
<textarea name="descripcion" class="form-control" rows="6" required></textarea>
<br>
<div class="section-title">Evidencia</div>
<div class="col-12">
<label class="form-label">Evidencia fotográfica *</label>
<input type="file" name="evidencias[]" multiple accept="image/*,video/*" class="form-control">
<div class="form-text">Máximo 10 archivos (solo demostración visual)</div>
</div>
<br>
<div class="text-end">
<a href="javascript:history.back()" class="btn btn-secondary me-2">Cancelar</a>
<button type="submit" class="btn btn-danger" id="btnEnviar">Enviar</button>
</div>
<?php if(isset($_GET['guardado'])): ?>
<div class="alert alert-success">Reporte registrado correctamente</div>
<?php endif; ?>
</form>
</div>
</div>
</body>
</html>
<style>
body{background:#f4f6f9;}
.form-card{background:#fff;padding:35px;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);}
.section-title{font-size:20px;font-weight:800;color:#063a61;margin-top:30px;margin-bottom:18px;padding-left:12px;border-left:6px solid #063a61;}
.form-label,label{font-weight:800;font-size:15px;color:#063a61;margin-bottom:10px;display:block;letter-spacing:.2px;}
.form-control,.form-select{height:48px;border-radius:10px;border:1px solid #d8dce1;transition:.25s;}
textarea.form-control{height:auto;resize:none;}
.form-control:focus,.form-select:focus{border-color:#063a61;box-shadow:0 0 0 .15rem rgba(6,58,97,0.15);}
.form-text{font-size:12px;color:#6f6f6f;}
.btn-danger{padding:10px 25px;border-radius:10px;font-weight:700;}
.row{margin-bottom:18px;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('operador').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var telefono = selectedOption.getAttribute('data-movil') || '';
        document.getElementById('telefono').value = telefono;
    });
    document.querySelector('form').addEventListener('submit', function(e) {
        var btn = document.getElementById('btnEnviar');
        if (btn.disabled) {
            e.preventDefault();
            return false;
        }
        if (!this.checkValidity()) {
            return true;
        }
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
    });
});
const tipo = document.getElementById('tipo_falla');
const subseccion = document.getElementById('subseccion');
tipo.addEventListener('change', cargarTipo);
function cargarTipo(){
subseccion.innerHTML='';
if(tipo.value==='MOTRIZ'){
subseccion.innerHTML=`
<br>
<label>Grupo motor *</label>
<select id="grupo_motor" name="grupo_motor" class="form-select">
<option value="">Seleccionar</option>
<option value="UNIDAD">UNIDAD</option>
<option value="MOTOR">MOTOR</option>
<option value="TREN">TREN MOTRIZ</option>
<option value="ELECTRICO">ELECTRICO</option>
<option value="DIRECCION">DIRECCION Y RODAMIENTO</option>
<option value="FRENOS">FRENOS Y SISTEMA DE AIRE</option>
<option value="CHASIS">CHASIS Y SUSPENSION</option>
<option value="CARROCERIA">CARROCERIA</option>
</select>
<div id="detalle_motriz"></div>
`;
document.getElementById('grupo_motor').addEventListener('change', cargarMotor);
}
if(tipo.value==='REFRIGERACION'){
subseccion.innerHTML=`
<br>
<label>Elija la falla presentada</label>
<select name="detalle_falla" class="form-select">
<option>Thermo se apaga</option>
<option>Thermo no enciende</option>
<option>Thermo alarmado</option>
<option>No baja temperatura</option>
<option>Temperatura elevada</option>
<option>Display apagado</option>
<option>Falla de defrost</option>
<option>Fuga de aceite en thermo</option>
<option>Thermo sin batería</option>
<option>Falla de marcha del thermo</option>
<option>Código Hi Press</option>
<option>Falla de encendido automático</option>
<option>Thermo se prende y apaga</option>
<option>Sin corriente eléctrica</option>
<option>Difusor no enciende</option>
<option>Temperatura fuera de rango</option>
</select>
`;
}
if(tipo.value==='LLANTAS'){
subseccion.innerHTML=`
<br>
<label>Tipo unidad</label>
<select id="tipo_llanta" name="tipo_llanta" class="form-select">
<option value="">Seleccionar</option>
<option value="TRACTO">Tracto</option>
<option value="REMOLQUE">Remolque</option>
<option value="THORTON">Thorton</option>
<option value="RABON">Rabon</option>
<option value="DOLLY">Dolly</option>
</select>
<div id="detalle_llanta"></div>
`;
document.getElementById('tipo_llanta').addEventListener('change', cargarLlanta);
}
}
function cargarMotor(){
const valor=this.value;
let opciones='';
const catalogos={
UNIDAD:['Unidad no enciende','Unidad se apaga en ruta','Pérdida de potencia','Sobrecalentamiento de unidad','Testigos/códigos en tablero','Variación de aceite','Fuga de combustible','Fuga de anticongelante','Sin diésel','Problemas de habilitado/GPS','Vibraciones o ruidos extraños','Cofre suelto o apertura de cofre'],
MOTOR:['Fuga de anticongelante','Manguera de anticongelante rota','Manguera del turbo dañada/safada','Pérdida de potencia del motor','Código revisar motor','Aumento de emisiones','Fuga de aceite','Banda accesorios rota','Banda alternador dañada','Variación presión aceite','Sobrecalentamiento motor','Fallo bomba combustible','Motor se apaga','Otro'],
TREN:['Clutch pegado','Pedal clutch sin presión','No entran velocidades','No realiza cambios','Convertidor no responde','Macho dañado','Cable macho sin corriente','Ruido transmisión','Tracto sin fuerza','Diferencial/freno escape','Otro'],
ELECTRICO:['Baterías descargadas','Baterías infladas','Corto eléctrico','Luces apagadas','Faro fundido','Caja fusibles dañada','Display dañado','Alternador sin carga','Cable tierra flojo','Fallo ABS','Códigos eléctricos','Sin corriente','Cable 7 vías','Otro'],
DIRECCION:['Baleros calientes','Vibración en dirección','Llantas disparejas','Espejo se mueve','Problemas dirección','Rodamiento dañado','Riesgo desprendimiento','Otro'],
FRENOS:['Fuga de aire','No carga aire','Bolsas dañadas','Bolsas no suben','Rotochamber dañado','Frenos bajos','Pedal al fondo','Balatas calientes','Manguera rota','Válvula niveladora','Compresor falla','Cámara dañada','Otro'],
CHASIS:['Muelles rotos','Loderas desoldadas','Estribo desoldado','Defensa desoldada','Suspensión ladeada','Tornillos rotos','Fisura bolsa aire','Ejes desalineados','Chasis raspando','Faldones desprendidos','Otro'],
CARROCERIA:['Puertas dañadas','Bisagras rotas','Cuartos faltantes','Elevador dañado','Caja sin luces','Piso dañado','Desprendimiento puertas','Loderas dañadas','Problemas remolque','Fuga estructural','Otro']
};
if(catalogos[valor]){
opciones+='<label class="form-label">Elija la falla presentada</label><select name="detalle_falla" class="form-select" required>';
catalogos[valor].forEach(x=>{opciones+='<option>'+x+'</option>';});
opciones+='</select>';
}
document.getElementById('detalle_motriz').innerHTML=opciones;
}
document.addEventListener('DOMContentLoaded', function() {document.getElementById('eco').addEventListener('change', function() {const eco = this.value;
        console.log('ECO seleccionado:', eco);
        if (!eco) {
            document.getElementById('origen').value = '';
            document.getElementById('destino').value = '';
            document.getElementById('cliente').value = '';
            return;
        }
        consultarUbicacion.call(this); // Primero la ubicación (Samsara)
        consultarDatosSheet(eco);// Luego los datos del Google Sheet
    });});
async function consultarDatosSheet(eco) {
    try {console.log('Consultando datos para ECO:', eco);
        const response = await fetch('consultar_google_sheets.php?eco=' + eco);
        const data = await response.json();
        console.log('Respuesta:', data);
        
        if (data.ok) {
            document.getElementById('origen').value = data.origen || '';
            document.getElementById('destino').value = data.destino || '';
            document.getElementById('cliente').value = data.cliente || '';
        } else {console.log('No se encontraron datos:', data.mensaje);
        }
    } catch (error) {console.error('Error:', error);
    }
}
async function consultarUbicacion(){
const eco=this.value;
if(!eco){return;}
try{
const response=await fetch('consultar_samsara.php?eco='+eco);
const data=await response.json();
if(data.ok){
document.getElementById('link_ubicacion').value=data.maps;
document.getElementById('ubicacion_actual').value=data.ubicacion;
}else{
alert(data.mensaje);
}
}catch(error){
console.log(error);
alert('Error consultando Samsara');
}
}
function cargarLlanta(){
const tipo=this.value;
let posiciones=0;
if(tipo==="TRACTO"){posiciones=10;}
if(tipo==="REMOLQUE"){posiciones=8;}
if(tipo==="THORTON"){posiciones=8;}
if(tipo==="RABON"){posiciones=6;}
if(tipo==="DOLLY"){posiciones=6;}
let opciones='';
for(let i=1;i<=posiciones;i++){
opciones+='<option>'+i+'</option>';
}
document.getElementById('detalle_llanta').innerHTML=`
<br>
<div class="row">
<div class="col-md-4">
<label class="form-label">Posición de la llanta *</label>
<select name="posicion_llanta" class="form-select" required>${opciones}</select>
</div>
<div class="col-md-8">
<label class="form-label">Tipo de daño *</label>
<select name="tipo_danio" class="form-select" required>
<option value="">Seleccionar</option>
<option>Llanta ponchada</option>
<option>Llanta volada</option>
<option>Llanta baja de aire</option>
</select>
</div>
</div>
`;
}
function closeMenu(event){const sidebar=document.getElementById('sidebar');if(sidebar.classList.contains('open')&&!sidebar.contains(event.target)){sidebar.classList.remove('open');}}
</script>