<?php
require '../system/connection.php';
require '../system/constants.php';
require_once '../utilities/sidebar.php'; 
    Sidebar::render("Clientes");
$id_cliente = $_GET['id_cliente'] ?? null;

if (!$id_cliente) {
    die("ID de cliente no especificado.");
}

$db = new MySQL();
$q="";
$q=$q." SELECT * ";
$q=$q." FROM clientes ";
$q=$q." WHERE id_cliente = " . intval($id_cliente);
$rs = $db->consulta($q);
$cliente = $db->fetch_array($rs);

if (!$cliente) {
    die("Cliente no encontrado.");
}
$contactosData = [];
$c="";
$c=$c."SELECT id_contacto, ";
$c=$c."nombrecompleto, ";
$c=$c."email_contacto, "; 
$c=$c."telefono, "; 
$c=$c."celular "; 
$c=$c."FROM contactos "; 
$c=$c."WHERE id_cliente = " . intval($id_cliente);
$c=$c. " AND (status IS NULL OR status != 'eliminado')";
$result = $db->consulta($c);
while ($row = $db->fetch_array($result)) {
    $contactosData[] = $row;
}
$productosData = [];

$p="";
$p=$p."SELECT pc.id_producto_cliente, ";
$p=$p."pc.sucursal_id, ";
$p=$p."s.nombre_unidad AS sucursal_nombre, ";
$p=$p."pc.producto_id, ";
$p=$p."p.descProducto AS producto_nombre, ";
$p=$p."pc.variante_id, ";
$p=$p."v.nombre AS variante_nombre, ";
$p=$p."pc.cantidad, ";
$p=$p."pc.descuento, ";
$p=$p."pc.precio, ";
$p=$p."pc.precio_final, ";
$p=$p."pc.total_pagar, ";
$p=$p."pc.recurrencia ";
$p=$p."FROM productos_clientes pc ";
$p=$p."LEFT JOIN sucursales s ON pc.sucursal_id = s.id_sucursal ";
$p=$p."LEFT JOIN productos p ON pc.producto_id = p.id ";
$p=$p."LEFT JOIN variantes v ON pc.variante_id = v.id_variantes ";
$p=$p." WHERE pc.id_cliente = " . intval($id_cliente) ;
$p=$p." AND (pc.status IS NULL OR pc.status != 'eliminado')  ";

$result = $db->consulta($p);
while ($row = $db->fetch_array($result)) {
    $productosData[] = $row;
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
function getDocC(){
  var id_cliente = document.querySelector('#id-cliente').dataset.id;
  var url = './modals/getDocC.php';
  var param = "id_cliente=" + encodeURIComponent(id_cliente);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result').innerHTML = _request.responseText;
        }.bind(this)
    });
}

function getcontacto(){
    var id_cliente = document.querySelector('#id-cliente').dataset.id;
    var url='./modals/getcontacto.php';
    var param = "id_cliente=" + encodeURIComponent(id_cliente);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result1').innerHTML = _request.responseText;
        }.bind(this)
    });   
}

function getpensionado(){
    var id_cliente = document.querySelector('#id-cliente').dataset.id;
    var url='./modals/getpensionado.php';
    var param = "id_cliente=" + encodeURIComponent(id_cliente);

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        document.getElementById('result2').innerHTML = _request.responseText;
        }.bind(this)
    });   
}
function getproductoC(){
    var id_cliente = document.querySelector('#id-cliente').dataset.id;
    var url='./modals/getproductoC.php';
    var param = "id_cliente=" + encodeURIComponent(id_cliente);

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
                            <h4 class="titulo-seccion">Clientes</h4>
                            <p id="id-cliente" data-id="<?= htmlspecialchars($cliente['id_cliente']) ?>">
                            <strong>ID:</strong> <?= htmlspecialchars($cliente['id_cliente']) ?>
                            </p>
                        </div>
                        <div class="edit-holder">
                                    <button type="button" class="btn btn-edit" data-cliente='<?= json_encode($cliente) ?>' title="Editar Unidad">
                                        <i class="bi bi-pencil-square"></i> Editar Cliente
                                    </button>
                        </div>
                 </div>
                </div>

                <div class="contenedor-datos-principales">
                    <div class="grid-item">
                        <p><strong>Tipo de Cliente </strong> <?= htmlspecialchars($cliente['tipo_cliente']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>C.P: </strong> <?= htmlspecialchars($cliente['codigo_postal']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Calle: </strong> <?= htmlspecialchars($cliente['calle']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Credito: </strong> <?= htmlspecialchars($cliente['credito']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Nombre o Razon Social: </strong> <?= htmlspecialchars($cliente['nombre_razon']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>STP: </strong> <?= htmlspecialchars($cliente['stp']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Municipio: </strong> <?= htmlspecialchars($cliente['municipio']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Numero Interior: </strong> <?= htmlspecialchars($cliente['num_int']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Numero exterior: </strong> <?= htmlspecialchars($cliente['num_ext']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Regimen </strong> <?= htmlspecialchars($cliente['regimen']) ?></p>
                    </div>
                    
                    <div class="grid-item">
                        <p><strong>Estado: </strong> <?= htmlspecialchars($cliente['estado']) ?></p>
                    </div>
                </div>

                <div class="contenedor-opciones">
                    <div class="contenedor" id="contactos">
                        <div class="encabezado">
                            <p>Contactos</p>
                            <button type="button"class="small-add-btn" title="Agregar módulo" data-bs-toggle="modal" data-bs-target="#contactoModal" onClick="getcontacto()">+</button>
                        </div>

                        <div class="opciones" >
                            <div class="opcion">
                                <p class="grid-item"><strong>Nombre Completo</strong></p>
                                <p class="grid-item"><strong>Correo</strong></p>
                                <p class="grid-item"><strong>Telefono</strong></p>
                                <p class="grid-item"><strong>Celular</strong></p>
                                <p class="grid-item"><strong>Eliminar</strong></p>
                            </div>
                            <?php foreach ($contactosData as $item): ?>
                                <div class="opcion">
                                    <p class="grid-item"><?= htmlspecialchars($item['nombrecompleto']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['email_contacto']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['telefono']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['celular']) ?></p>
                                    <p class="grid-item"> &nbsp;&nbsp;&nbsp;
                                        <a href="#" class="deleteA-btn" data-id="<?= $item['id_contacto'] ?>" title="Eliminar">
                                            <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                                        </a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                            
                        </div>
                    </div>

                    <div class="contenedor" id="productos">
                        <div class="encabezado">
                            <p>Productos</p>
                            <button type="button"class="small-add-btn" title="Agregar módulo" data-bs-toggle="modal" data-bs-target="#addProductoModal" onClick="getproductoC()">+</button>
                        </div>  

                        <div class="opciones">
                            <div class="opcion">
                                <p class="grid-item"><strong>Tipo de servicio</strong></p>
                                <p class="grid-item"><strong>Sucursal</strong></p>
                                <p class="grid-item"><strong>Variante</strong></p>
                                <p class="grid-item"><strong>Cantidad</strong></p>
                                <p class="grid-item"><strong>Recurrencia</strong></p>
                                <p class="grid-item"><strong>Acciones</strong></p>
                            </div>
                                   <?php foreach ($productosData as $item): ?>
                                <div class="opcion">
                                    <p class="grid-item"><?= htmlspecialchars($item['producto_nombre']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['sucursal_nombre']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['variante_nombre']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['cantidad']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['recurrencia']) ?></p>
                                    <p class="grid-item">
                                        <span class="action-buttons">
                                        <a href="#" class="edit-btn" data-productos='<?= json_encode($item) ?>' title="Editar">
                                            <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                                        </a>
                                    
                                        <a href="#" class="deleteB-btn" data-id="<?= $item['id_producto_cliente'] ?>" title="Eliminar">
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
                            <button class="upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" onClick="getDocC()">
                            <i class="bi bi-upload"></i>
                        </div>
                    </div>

                    <div class="contenedor-opciones">
                    <div class="contenedor" id="pension">
                        <div class="encabezado">
                            <p>Pensionados</p>
                            <button type="button"class="small-add-btn" title="Agregar módulo" data-bs-toggle="modal" data-bs-target="#addModalPensionado" onClick="getpensionado()">+</button>
                        </div>
                        <table class="table table-hover">
                                <thead class="thead-light ">
                                    <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Sucursal</th>
                                    <th scope="col">Celular</th>
                                    </tr>
                                </thead>
                        <?php

                        $db = new MySQL();

                        $counter=1;
                        $fieldnumber=10;

                                $q="";
                                $q=$q." select id_pensionados";
                                $q=$q."     ,s.nombre_unidad";
                                $q=$q."     ,p.nombre_pensionado";
                                $q=$q."     ,p.email";
                                $q=$q."     ,p.id_sucursal";
                                $q=$q."     ,p.celular";
                                $q=$q." from pensionados p ";
                                $q=$q." LEFT JOIN sucursales s ON p.id_sucursal = s.id_sucursal";
                                $q=$q." WHERE (p.status IS NULL OR p.status != 'eliminado')";
                            $rs = $db->consulta($q);

                            if($db->num_rows($rs)>0){
                        ?>
                        <tbody>
                        <tr>

                        <?php
                            while($fields = $db->fetch_array($rs)){
                        ?>  
                            <tr class="row-link" data-id="<?=$fields['id_pensionados']?>" style="cursor:pointer;">
                                    
                                        <th scope="row"><?=$fields['id_pensionados']?></th>
                                        <td><?=$fields['nombre_pensionado']?></td>
                                        <td><?=$fields['nombre_unidad']?></td>
                                        <td><?=$fields['celular']?></td>
                                            </tr>
                                        </tr>
                        <?php
                                $counter++;
                            }
                        }
                        ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
<!-- Boton -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta de Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"  enctype="multipart/form-data">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        <div class="row">
                                <div class="col-md-4 ">
                                    <label for="nombre_razon" class="form-label">Nombre o Razon Social</label>
                                    <input type="text" class="form-control" id="nombre_razon" name="nombre_razon" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                                    <select class="form-select" id="tipo_cliente" name="tipo_cliente" required>
                                        <option value="">Selecciona un Tipo de Cliente</option>
                                        <option value="Existente">Existente</option>
                                        <option value="Nuevo">Nuevo</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="regimen" class="form-label">Regimen</label>
                                    <select class="form-select" id="regimen" name="regimen" required>
                                        <option value="">Selecciona un Regimen</option>
                                        <option value="Publico en General">Publico en General</option>
                                        <option value="Persona Moral">Persona Moral</option>
                                        <option value="Persona Fisica con Actividad Empresarial">Persona Fisica con Actividad Empresarial</option>
                                    </select>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                 <div class="col-md-4">
                                    <label for="calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="calle" name="calle">
                                </div>
                                <div class="col-md-4">
                                    <label for="municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio">
                                </div>
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                 <div class="col-md-4">
                                    <label for="codigo_postal" class="form-label">Codigo Postal</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                                </div>
                                <div class="col-md-4">
                                    <label for="num_ext" class="form-label">Numero Exterior</label>
                                    <input type="text" class="form-control" id="num_ext" name="num_ext">
                                </div>
                                <div class="col-md-4">
                                    <label for="num_int" class="form-label">Numero Interior</label>
                                    <input type="text" class="form-control" id="num_int" name="num_int">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="credito" class="form-label">Credito</label>
                                    <input type="text" class="form-control" id="credito" name="credito">
                                </div>
                                 <div class="col-md-4">
                                    <label for="stp" class="form-label">STP</label>
                                    <input type="text" class="form-control" id="stp" name="stp">
                                </div>
                        </div>
                        <br>
                            <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="contactoModal" tabindex="-1" aria-labelledby="contactoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                   <div id="result1"></div>
                </div>
            </div>
        </div>

         <div class="modal fade" id="addProductoModal" tabindex="-1" aria-labelledby="addProductoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div id="result3"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModalPensionado" tabindex="-1" aria-labelledby="addModalLabelPensionado" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div id="result2"></div>
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
jQuery(document).on("click", ".btn-edit", function (e) {
    e.preventDefault(); 
    e.stopPropagation();
    const data = jQuery(this).data("cliente");
    const cliente = typeof data === "string" ? JSON.parse(data) : data;

    jQuery("#addModalLabel").text("Editar Cliente");

    jQuery("#nombre_razon").val(cliente.nombre_razon || '');
    jQuery("#tipo_cliente").val(cliente.tipo_cliente || '');
    jQuery("#regimen").val(cliente.regimen || '');
    jQuery("#calle").val(cliente.calle || '');
    jQuery("#municipio").val(cliente.municipio || '');
    jQuery("#estado").val(cliente.estado || '');
    jQuery("#codigo_postal").val(cliente.codigo_postal || '');
    jQuery("#num_ext").val(cliente.num_ext || '');
    jQuery("#num_int").val(cliente.num_int || '');
    jQuery("#credito").val(cliente.credito || '');
    jQuery("#stp").val(cliente.stp || '');
    jQuery("#id_cliente").val(cliente.id_cliente);

    jQuery("#saveButton").hide();
    jQuery("#updateButton").show();

    const modalInstance = new bootstrap.Modal(document.getElementById('addModal'));
    modalInstance.show();
});

jQuery("#updateButton").click(function () {
    const formData = new FormData(jQuery("#addForm")[0]);

    jQuery.ajax({
        url: "./dao/updatecliente.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#id_cliente").val('');
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            location.reload();
        },
        error: function (xhr, status, error) {
            alert("Error al actualizar.");
            console.log(error);
        }
    });
});

jQuery(document).on("submit", "#addFormPensionado", function (e) {
    e.preventDefault();
    const formData = jQuery(this).serialize();

    jQuery.post('./dao/addContacto.php', formData, function (response) {
        alert(response);
        jQuery("#addModalPensionado").modal('hide');
        jQuery("#addFormPensionado")[0].reset();

        const id = document.querySelector('#id-cliente')?.dataset.id;
        if (id) {
            location.href = location.pathname + "?id_cliente=" + encodeURIComponent(id);
        } else {
            location.reload();
        }
    }).fail(function () {
        alert("Error al agregar usuario a plantilla.");
    });
});

jQuery(document).on("submit", "#contactoForm", function (e) {
    e.preventDefault();
    const formData = jQuery(this).serialize();

    jQuery.post('./dao/addContacto.php', formData, function (response) {
        alert(response);
        jQuery("#contactoModal").modal('hide');
        jQuery("#contactoForm")[0].reset();

        const id = document.querySelector('#id-cliente')?.dataset.id;
        if (id) {
            location.href = location.pathname + "?id_cliente=" + encodeURIComponent(id);
        } else {
            location.reload();
        }
    }).fail(function () {
        alert("Error al agregar usuario a plantilla.");
    });
});

jQuery(document).on("submit", "#formAgregarProducto", function (e) {
    e.preventDefault();
    const formData = jQuery(this).serialize();

    jQuery.post('./dao/addProductoc.php', formData, function (response) {
        alert(response);
        jQuery("#addProductoModal").modal('hide');
        jQuery("#formAgregarProducto")[0].reset();

        const id = document.querySelector('#id-cliente')?.dataset.id;
        if (id) {
            location.href = location.pathname + "?id_cliente=" + encodeURIComponent(id);
        } else {
            location.reload();
        }
    }).fail(function () {
        alert("Error al agregar usuario a plantilla.");
    });
});

jQuery("#result3").on("change", "#sucursal", function () {
    const idSucursal = jQuery(this).val();
    jQuery("#productoSelect").html('<option value="">Cargando productos...</option>');
    jQuery("#variante").html('<option value="">Selecciona una variante</option>');
    jQuery("#precio").val('');

    if (idSucursal) {
        jQuery.post('./dao/getProductos.php', { id_sucursal: idSucursal }, function (data) {
            const productos = JSON.parse(data);
            let options = '<option value="">Selecciona un producto</option>';
            productos.forEach(prod => {
                options += `<option value="${prod.id_producto}" data-nombre="${prod.nombre_producto}">${prod.nombre_producto}</option>`;
            });
            jQuery("#productoSelect").html(options);
        });
    }
});

jQuery("#result3").on("change", "#productoSelect", function () {
    const idProducto = jQuery(this).val();
    const idSucursal = jQuery('#sucursal').val();

    if (!idProducto || !idSucursal) return;

    jQuery.post('./dao/getVariantes.php', {
        id_producto: idProducto,
        id_sucursal: idSucursal
    }, function (data) {
        const $varianteSelect = jQuery('#variante');
        $varianteSelect.empty().append('<option value="">Selecciona una variante</option>');

        data.forEach(v => {
            $varianteSelect.append(`
                <option value="${v.id_variante}" 
                        data-precio="${v.precio}" 
                        data-recurrencia="${v.descripcion}">
                    ${v.nombre_variante}
                </option>
            `);
        });
    }, 'json');
});

jQuery("#result3").on("change", "#variante", function () {
    const selected = jQuery(this).find('option:selected');
    const precio = selected.data('precio');
    const recurrencia = selected.data('recurrencia');

    jQuery('#precio').val(precio ?? '');
    jQuery('#recurrencia').val(recurrencia ?? '');
    calcularTotal();
});

function calcularTotal() {
    let precio = parseFloat(jQuery('#precio').val()) || 0;
    let descuento = parseFloat(jQuery('#descuento').val()) || 0;
    let cantidad = parseInt(jQuery('#cantidad').val()) || 0;

    let precioFinal = precio - (precio * (descuento / 100));
    precioFinal = parseFloat(precioFinal.toFixed(2)); 

    // Calculate total
    let total = cantidad * precioFinal;
    total = parseFloat(total.toFixed(2)); 

    // Set values
    jQuery('#precio_final').val(precioFinal);
    jQuery('#total').val(total);
}

jQuery('#result3').on('input', '#precio, #descuento, #cantidad', calcularTotal);

function waitForElement(selector, callback) {
    const interval = setInterval(() => {
        if (jQuery(selector).length) {
            clearInterval(interval);
            callback();
        }
    }, 20); // check every 20ms
}

jQuery(document).on("click", ".edit-btn", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const data = jQuery(this).data("productos");
    const productos = typeof data === "string" ? JSON.parse(data) : data;

    // Optionally remove or handle this correctly
    // setEditMode(true); ← make sure it doesn't fail or comment it out

    // Load modal template HTML via Ajax (if needed)
    jQuery.ajax({
        url: './modals/getproductoC.php',
        method: 'POST',
        data: { id_cliente: productos.id_cliente }, // optional: only if needed
        success: function (response) {
            jQuery("#result3").html(response); // Inject modal content into placeholder

            // Wait for DOM update
            setTimeout(() => {
                jQuery("#addProductoModalLabel").text("Editar Producto");

                jQuery("#sucursal").val(productos.sucursal_id || '');

                jQuery("#productoSelect").html(`<option value="${productos.producto_id}">${productos.producto_nombre}</option>`);
                jQuery("#productoSelect").val(productos.producto_id || '');

                jQuery("#variante").html(`
                    <option value="${productos.variante_id}" 
                            data-precio="${productos.precio}" 
                            data-recurrencia="${productos.recurrencia}">
                        ${productos.variante_nombre}
                    </option>
                `);
                jQuery("#variante").val(productos.variante_id || '');

                jQuery("#precio").val(productos.precio || '');
                jQuery("#cantidad").val(productos.cantidad || '');
                jQuery("#descuento").val(productos.descuento || '');
                jQuery("#precio_final").val(productos.precio_final || '');
                jQuery("#total").val(productos.total_pagar || '');
                jQuery("#recurrencia").val(productos.recurrencia || '');
                jQuery("#id_producto_cliente").val(productos.id_producto_cliente || '');

                jQuery("#saveButton").hide();
                jQuery("#updateButtonP").show();

                const modalInstance = new bootstrap.Modal(document.getElementById('addProductoModal'));
                modalInstance.show();
            }, 50); // small delay ensures modal content is in DOM
        }
    });
});
jQuery(document).on("click", "#updateButtonP", function () {
        const formData = jQuery("#formAgregarProducto").serialize();
        jQuery.post("./dao/updateProductoc.php", formData, function (response) {
            alert(response);
            jQuery("#addProductoModal").modal("hide");
            jQuery("#formAgregarProducto")[0].reset();
            jQuery("#sucursal").val('');
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            location.reload();
        });
    });
jQuery(document).on("click", ".deleteA-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = jQuery(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Contacto?")) {
            jQuery.post("./dao/deleteContacto.php", { id: id }, function (response) {
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

        if (confirm("¿Estás seguro de que deseas eliminar este Producto?")) {
            jQuery.post("./dao/deleteProductoC.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
jQuery(document).on("click", ".row-link", function () {
    const id = jQuery(this).data("id");
    window.location.href = `../pensionados/detail_pensionados.php?id_pensionados=${id}`;
});
</script>


</body>
</html>