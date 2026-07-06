<?php
require '../system/connection.php';
require '../system/constants.php';
require_once '../utilities/sidebar.php'; 
    Sidebar::render("Detalle de unidad");
$id_sucursal = $_GET['id_sucursal'] ?? null;

if (!$id_sucursal) {
    die("ID de unidad no especificado.");
}

$db = new MySQL();
$q="";
$q=$q."SELECT *";
$q = $q."FROM sucursales WHERE id_sucursal = " . intval($id_sucursal);
$rs = $db->consulta($q);
$unidad = $db->fetch_array($rs);

if (!$unidad) {
    die("Unidad no encontrada.");
}

$plantillaData = [];
$p = "";
$p = $p."SELECT p.nombre_usuario, ";
$p = $p." p.id_plantilla, ";
$p = $p." u.email, ";
$p = $p." u.idRol ";
$p = $p." FROM plantilla p ";
$p = $p." JOIN usuarios u ON p.id_usuario = u.id ";
$p = $p." WHERE p.id_sucursal = " . intval($id_sucursal);
$p = $p." AND (p.status IS NULL OR p.status != 'eliminado')";

$result = $db->consulta($p);
while ($row = $db->fetch_array($result)) {
    $plantillaData[] = $row;
}

$serviosData = [];
$s="";
$s=$s."SELECT nombre_producto, ";
$s=$s." nombre_variante, ";
$s=$s." id_servicio, ";
$s=$s." tipo_precio, ";
$s=$s." precio ";
$s=$s." FROM servicios ";
$s=$s."WHERE id_sucursal = " . intval($id_sucursal);
$s=$s." AND (Status IS NULL OR Status != 'eliminado')";

$result = $db->consulta($s);
while ($row = $db->fetch_array($result)) {
    $serviosData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>


    <script>
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
function getDoc(){
  var id_sucursal = document.querySelector('#id-sucursal').dataset.id;
  var url = './modals/getDoc.php';
  var param = "id_sucursal=" + encodeURIComponent(id_sucursal);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result').innerHTML = _request.responseText;
        }.bind(this)
    });
}
function getplantilla(){
    var id_sucursal = document.querySelector('#id-sucursal').dataset.id;
    var url='./modals/getplantilla.php';
    var param = "id_sucursal=" + encodeURIComponent(id_sucursal);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result2').innerHTML = _request.responseText;
        }.bind(this)
    });   
}
function getproducto(){
    var id_sucursal = document.querySelector('#id-sucursal').dataset.id;
    var url='./modals/getproducto.php';
    var param = "id_sucursal=" + encodeURIComponent(id_sucursal);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result3').innerHTML = _request.responseText;
        }.bind(this)
    });   
}
    </script>
</head>
<body onclick="closeMenu(event)">

        <div id="contenido-unidades">
                <div style="padding: 20px;">
                    <div class="header-container">
                        <div>
                            <h4 class="titulo-seccion">Detalle de Unidad</h4>
                            <p id="id-sucursal" data-id="<?= htmlspecialchars($unidad['id_sucursal']) ?>">
                            <strong>ID:</strong> <?= htmlspecialchars($unidad['id_sucursal']) ?>
                            </p>
                        </div>
                        <div class="edit-holder">
                                    <button type="button" class="btn btn-edit" data-unidad='<?= json_encode($unidad) ?>' title="Editar Unidad">
                                        <i class="bi bi-pencil-square"></i> Editar Unidad
                                    </button>
                        </div>
                 </div>
                </div>

                <div class="contenedor-datos-principales">
                    <div class="grid-item">
                         <p><strong>Nombre de la unidad:</strong> <?= htmlspecialchars($unidad['nombre_unidad']) ?></p>
                    </div>
                    <div class="grid-item">
                        <p><strong>Estado:</strong> <?= htmlspecialchars($unidad['estado']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Municipio:</strong> <?= htmlspecialchars($unidad['municipio']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Fondo:</strong> <?= htmlspecialchars($unidad['fondo'] ?? 'N/A') ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Modelo de negocio:</strong> <?= htmlspecialchars($unidad['modelo_negocio']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Socio:</strong> <?= htmlspecialchars($unidad['socio'] ?? 'N/A') ?> %</p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Empresa:</strong> <?= htmlspecialchars($unidad['empresa'] ?? 'N/A') ?> %</p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Renta: $</strong> <?= htmlspecialchars($unidad['renta'] ?? 'N/A') ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Operadora: </strong> <?= htmlspecialchars($unidad['operadora'] ?? 'N/A') ?></p>
                    </div>
                    
                    <div class="grid-item">
                        <p><strong>Codigo Unidad: </strong> <?= htmlspecialchars($unidad['codigo_unidad'] ?? 'N/A') ?></p>
                    </div>
                    
                    <div class="grid-item">
                        <p><strong>Fee: </strong> <?= htmlspecialchars($unidad['fee'] ?? 'N/A') ?></p>
                    </div>
                    
                    <div class="grid-item">
                        <p><strong>Sistema Gestion: </strong> <?= htmlspecialchars($unidad['sistema_gestion'] ?? 'N/A') ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Link Reporte: </strong> <?= htmlspecialchars($unidad['link_reporte'] ?? 'N/A') ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Direccion: </strong> <?= htmlspecialchars($unidad['direccion'] ?? 'N/A') ?></p>
                    </div>

                </div>

                <div class="contenedor-opciones">
                    <div class="contenedor" id="plantillas">
                        <div class="encabezado">
                            <p>Plantilla</p>
                            <button type="button"class="small-add-btn" title="Agregar módulo" data-bs-toggle="modal" data-bs-target="#plantillaModal" onClick="getplantilla()">+</button>
                        </div>

                        <div class="opciones">
                            <div class="opcion">
                                <p class="grid-item"><strong>Nombre</strong></p>
                                <p class="grid-item"><strong>Correo</strong></p>
                                <p class="grid-item"><strong>Rol</strong></p>
                                <p class="grid-item"><strong>Acciones</strong></p>
                            </div>
                            <?php foreach ($plantillaData as $item): ?>
                                <div class="opcion">
                                    <p class="grid-item"><?= htmlspecialchars($item['nombre_usuario']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['email']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['idRol']) ?></p>
                                    <p class="grid-item"> &nbsp;&nbsp;&nbsp;
                                        <a href="#" class="delete-btn" data-id="<?= $item['id_plantilla'] ?>" title="Eliminar">
                                            <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                                        </a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="contenedor" id="servicios">
                        <div class="encabezado">
                            <p>Servicios</p>
                            <button type="button"class="small-add-btn" title="Agregar módulo" data-bs-toggle="modal" data-bs-target="#addProductoModal" onClick="getproducto()">+</button>
                        </div>  

                        <div class="opciones">
                            <div class="opcion">
                                <p class="grid-item"><strong>Tipo</strong></p>
                                <p class="grid-item"><strong>Variante</strong></p>
                                <p class="grid-item"><strong>Tipo de Precio</strong></p>
                                <p class="grid-item"><strong>Precio</strong></p>
                                <p class="grid-item"><strong>Acciones</strong></p>
                            </div>
                            <?php foreach ($serviosData as $item): ?>
                                <div class="opcion">
                                    <p class="grid-item"><?= htmlspecialchars($item['nombre_producto']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['nombre_variante']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['tipo_precio']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['precio']) ?></p>
                                    <p class="grid-item">
                                        <span class="action-buttons">
                                            <a href="#" class="edit-btn" data-productos='<?= json_encode($item) ?>' title="Editar">
                                                <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                                            </a>
                                            <a href="#" class="deleteB-btn" data-id="<?= $item['id_servicio'] ?>" title="Eliminar">
                                                <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                                            </a>
                                        </span>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                            
                        </div>
                    </div>

                    <div class="contenedor" id="upload">
                        <div class="encabezado">
                            <p>Documentos</p>
                            <button class="small-add-btn" title="Agregar módulo">+</button>
                        </div>      

                        <div class="upload">
                            <p>Tipo</p>
                            <button type="button" class="upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" onClick="getDoc()">
                            <i class="bi bi-upload"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta de Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"  enctype="multipart/form-data">
                        <input type="hidden" id="id_sucursal" name="id_sucursal">
                        <div class="row">
                                <div class="col-md-4 ">
                                    <label for="nombre_unidad" class="form-label">Nombre de la Sucursal</label>
                                    <input type="text" class="form-control" id="nombre_unidad" name="nombre_unidad" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado">
                                </div>
                                <div class="col-md-4">
                                    <label for="municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                 <div class="col-md-4">
                                    <label for="modelo_negocio" class="form-label">Modelo de Negocio</label>
                                        <select class="form-select" id="modelo_negocio" name="modelo_negocio" required>
                                            <option value="">Selecciona un Modelo de Negocio</option>
                                            <option value="Renta">Renta</option>
                                            <option value="Comparticion">Comparticion</option>
                                            <option value="Servicio">Servicio</option>
                                            <option value="Comparticion +  Fee">Comparticion +  Fee</option>
                                        </select>
                                </div>
                             <div class="col-md-4">
                                <label for="socio" class="form-label">Porcentaje Socio</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="socio" name="socio" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="empresa" class="form-label">Porcentaje Empresa</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="empresa" name="empresa" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="fondo" class="form-label">Fondo</label>
                                    <input type="text" class="form-control" id="fondo" name="fondo">
                                </div>
                                 <div class="col-md-4">
                                    <label for="renta" class="form-label">Renta</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="renta" name="renta">
                                    <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="operadora" class="form-label">Operadora</label>
                                        <select class="form-select" id="operadora" name="operadora" required>
                                            <option value="">Selecciona una Operadora</option>
                                            <option value="BNI Estacionamientos S.A de C.V">BNI Estacionamientos S.A de C.V</option>
                                            <option value="Bengala Valet S.A de C.V">Bengala Valet S.A de C.V</option>
                                        </select>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="codigo_unidad" class="form-label">Codigo de Unidad</label>
                                    <input type="text" class="form-control" id="codigo_unidad" name="codigo_unidad">
                                </div>
                                 <div class="col-md-4">
                                    <label for="fee" class="form-label">Fee</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="fee" name="fee">
                                    <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="sistema_gestion" class="form-label">Sistema de Gestion</label>
                                        <select class="form-select" id="sistema_gestion" name="sistema_gestion" required>
                                            <option value="">Selecciona un Sistema de Gestion</option>
                                            <option value="SIAP">SIAP</option>
                                            <option value="PARKIMOVIL">PARKIMOVIL</option>
                                            <option value="CONEKTA">CONEKTA</option>
                                            <option value="">AZTEK</option>
                                            <option value="TRAFICO ALTO">TRAFICO ALTO</option>
                                            <option value="MAYPAR">MAYPAR</option>
                                            <option value="No tiene Sistema de Gestion">No tiene Sistema de Gestion</option>
                                            <option value="ACCESA">ACCESA</option>
                                            <option value="N/A">N/A</option>
                                            <option value="YAWI">YAWI</option>
                                        </select>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="link_reporte" class="form-label">Link Reporte</label>
                                    <input type="url" class="form-control" id="link_reporte" name="link_reporte" placeholder="https://ejemplo.com">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion" class="form-label">Direccion</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="email_gerente" class="form-label">Email gerente</label>
                                    <input type="email" class="form-control" id="email_gerente" name="email_gerente">
                                </div>
                                <div class="col-md-4">
                                    <label for="email_coordinador" class="form-label">Email coordinador</label>
                                    <input type="email" class="form-control" id="email_coordinador" name="email_coordinador">
                                </div>
                                <div class="col-md-4">
                                    <label for="email_encargado" class="form-label">Email encargado</label>
                                    <input type="email" class="form-control" id="email_encargado" name="email_encargado">
                                </div>
                        </div>
                        <br>
                            <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="plantillaModal" tabindex="-1" aria-labelledby="plantillaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar a Plantilla</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div id="result2"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addProductoModal" tabindex="-1" aria-labelledby="addProductoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div id="result3"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="result"></div>
                </div>
            </div>
        </div>


<script>
jQuery(document).on("click", ".btn-edit", function () {
    const data = jQuery(this).data("unidad");
    const unidad = typeof data === "string" ? JSON.parse(data) : data;

    jQuery("#addModalLabel").text("Editar Unidad");

    jQuery("#nombre_unidad").val(unidad.nombre_unidad || '');
        jQuery("#estado").val(unidad.estado || '');
        jQuery("#municipio").val(unidad.municipio || '');
        jQuery("#modelo_negocio").val(unidad.modelo_negocio || '');
        jQuery("#socio").val(unidad.socio || '');
        jQuery("#empresa").val(unidad.empresa || '');
        jQuery("#fondo").val(unidad.fondo || '');
        jQuery("#renta").val(unidad.renta || '');
        jQuery("#operadora").val(unidad.operadora || '');
        jQuery("#codigo_unidad").val(unidad.codigo_unidad || '');
        jQuery("#fee").val(unidad.fee || '');
        jQuery("#sistema_gestion").val(unidad.sistema_gestion || '');
        jQuery("#link_reporte").val(unidad.link_reporte || '');
        jQuery("#direccion").val(unidad.direccion || '');
        jQuery("#email_gerente").val(unidad.email_gerente || '');
        jQuery("#email_coordinador").val(unidad.email_coordinador || '');
        jQuery("#email_encargado").val(unidad.email_encargado || '');

    jQuery("#id_sucursal").val(unidad.id_sucursal); 

    jQuery("#updateButton").show();

    new bootstrap.Modal(document.getElementById('addModal')).show();
});

jQuery("#updateButton").click(function () {
    const formData = new FormData(jQuery("#addForm")[0]);

    jQuery.ajax({
        url: "./dao/updateSucursal.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#id_sucursal").val('');
            location.reload();
        },
        error: function (xhr, status, error) {
            alert("Error al actualizar.");
            console.log(error);
        }
    });
});
jQuery(document).on("submit", "#plantillaForm", function (e) {
    e.preventDefault();
    const formData = jQuery(this).serialize();

    jQuery.post('./dao/addPlantilla.php', formData, function (response) {
        alert(response);
        jQuery("#plantillaModal").modal('hide');
        jQuery("#plantillaForm")[0].reset();

        // Optional: reload while keeping id_sucursal
        const id = document.querySelector('#id-sucursal')?.dataset.id;
        if (id) {
            location.href = location.pathname + "?id_sucursal=" + encodeURIComponent(id);
        } else {
            location.reload();
        }
    }).fail(function () {
        alert("Error al agregar usuario a plantilla.");
    });
});

jQuery(document).on('change', '#producto', function () { 
    const productoId = jQuery(this).val();
    const productoName = jQuery('#producto option:selected').text();
    jQuery('#nombre_producto').val(productoName);

    if (!productoId) {
        jQuery('#variante').html('<option value="">Selecciona una variante</option>');
        jQuery('#nombre_variante').val('');
        return;
    }

    jQuery.ajax({
        url: './dao/get_variantes.php',
        type: 'POST',
        data: { producto_id: productoId },
        success: function (data) {
            const variantes = JSON.parse(data);
            let options = '<option value="">Selecciona una variante</option>';
            variantes.forEach(function (v) {
                options += `<option value="${v.id_variantes}">${v.nombre}</option>`;
            });
            jQuery('#variante').html(options);
        },
        error: function () {
            alert('Error al cargar variantes');
        }
    });
});
jQuery(document).on('change', '#variante', function () {
    const varianteName = jQuery('#variante option:selected').text();
    jQuery('#nombre_variante').val(varianteName);
});

jQuery(document).on("submit", "#formAgregarProducto", function (e) {
    e.preventDefault();
    const formData = jQuery(this).serialize();

    jQuery.post('./dao/addServicio.php', formData, function (response) {
        alert(response);
        jQuery("#addProductoModal").modal('hide');
        jQuery("#formAgregarProducto")[0].reset();

        // Optional: reload while preserving query param like id_sucursal
        const id = document.querySelector('#id-sucursal')?.dataset.id;
        if (id) {
            location.href = location.pathname + "?id_sucursal=" + encodeURIComponent(id);
        } else {
            location.reload();
        }
    }).fail(function () {
        alert("Error al agregar Producto a Servicios.");
    });
});
jQuery(document).on("click", ".delete-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = jQuery(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Usuario de la Sucursal?")) {
            jQuery.post("./dao/deleteUserSuc.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
jQuery(document).on("click", ".deleteB-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = jQuery(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Servicio de la Sucursal?")) {
            jQuery.post("./dao/deleteServicioSuc.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
</script>
</body>
</html>