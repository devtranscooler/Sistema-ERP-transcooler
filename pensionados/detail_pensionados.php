<?php
require '../system/connection.php';
require '../system/constants.php';
require_once '../utilities/sidebar.php'; 
    Sidebar::render("Pensionados");
$id_pensionados = $_GET['id_pensionados'] ?? null;

if (!$id_pensionados) {
    die("ID de unidad no especificado.");
}

$db = new MySQL();
$q="";
$q=$q."SELECT p.id_pensionados, ";
$q=$q." p.id_pensionados, ";
$q=$q." p.nombre_pensionado, ";
$q=$q." p.email, ";
$q=$q." p.id_sucursal, ";
$q=$q." p.celular, ";
$q=$q." p.id_cliente, ";
$q=$q." s.nombre_unidad ";
$q=$q." FROM pensionados p ";
$q=$q." LEFT JOIN sucursales s ON p.id_sucursal = s.id_sucursal ";
$q=$q." WHERE p.id_pensionados = " . intval($id_pensionados);
$rs = $db->consulta($q);
$pensionado = $db->fetch_array($rs);

if (!$pensionado) {
    die("Pensionado no encontrado.");
}
$su="";
$su=$su."";
$su=$su."SELECT id_sucursal, ";
$su=$su." nombre_unidad ";
$su=$su." FROM sucursales ";
$su=$su." WHERE (status IS NULL OR status != 'eliminado')";

$sucursalesResult = $db->consulta($su);
$sucursales = [];
while ($row = $db->fetch_array($sucursalesResult)) {
    $sucursales[] = $row;
}
$autosData = [];
$a="";
$a=$a."SELECT id_pensionado, ";
$a=$a." modelo, ";
$a=$a." placas, ";
$a=$a." year, ";
$a=$a." marca ";
$a=$a." FROM autos "; 
$a=$a."WHERE id_pensionado = " . intval($id_pensionados);
$a=$a." AND (status IS NULL OR status != 'eliminado') ";
$result = $db->consulta($a);
while ($row = $db->fetch_array($result)) {
    $autosData[] = $row;
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
        
    </script>
</head>
<body onclick="closeMenu(event)">

            <div id="contenido-unidades">
                 <div style="padding: 20px;">
                    <div class="header-container">
                        <div>
                            <h4 class="titulo-seccion">Pensionados</h4>
                            <p id="id-usuario"><strong>ID:</strong> <?= htmlspecialchars($pensionado['id_pensionados']) ?></p>
                        </div>
                        <div class="edit-holder">
                                    <button type="button" class="btn btn-edit" data-pensionado='<?= json_encode($pensionado) ?>' title="Editar Pensinado">
                                        <i class="bi bi-pencil-square"></i> Editar Pension
                                    </button>
                        </div>
                 </div>
                </div>

                <div class="contenedor-datos-principal">
                    <div class="grid-item">
                        <p><strong>Nombre Completo del Usuario: </strong> <?= htmlspecialchars($pensionado['nombre_pensionado']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Correo electronico: </strong> <?= htmlspecialchars($pensionado['email']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Sucursal: </strong> <?= htmlspecialchars($pensionado['nombre_unidad']) ?></p>
                    </div>

                    <div class="grid-item">
                        <p><strong>Celular: </strong> <?= htmlspecialchars($pensionado['celular']) ?></p>
                    </div>
                </div>

                <div class="contenedor-opciones">
                    <div class="contenedor" id="autos">
                        <div class="encabezado">
                            <p>Autos</p>
                            <button class="small-add-btn" title="Agregar módulo">+</button>
                            
                        </div>

                        <div class="opciones" >
                            <div class="opcion">
                                <p class="grid-item"><strong>Marca</strong></p>
                                <p class="grid-item"><strong>Modelo</strong></p>
                                <p class="grid-item"><strong>Año</strong></p>
                                <p class="grid-item"><strong>Placas</strong></p>
                                <p class="grid-item"><strong>Foto</strong></p>
                                <p class="grid-item"><strong>Eliminar</strong></p>
                            </div>
                            <?php foreach ($autosData as $item): ?>
                                <div class="opcion">
                                    <p class="grid-item"><?= htmlspecialchars($item['marca']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['modelo']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['year']) ?></p>
                                    <p class="grid-item"><?= htmlspecialchars($item['placas']) ?></p>
                                    <p class="grid-item"></p>
                                    <p class="grid-item"> &nbsp;&nbsp;&nbsp;
                                        <a href="#" class="deleteA-btn" data-id="<?= $item['id_pensionado'] ?>" title="Eliminar">
                                            <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                                        </a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="contenedor" id="productos">
                        <div class="encabezado">
                            <p>Tarjeta de Acceso</p>
                            <button class="small-add-btn" title="Agregar módulo">+</button>
                        </div>  

                        <div class="opciones">
        
                        </div>

                    </div>

                    <div class="contenedor" id="upload">
                        <div class="encabezado">
                            <p>Documentos</p>
                        </div>      

                        <div class="upload">
                            <p>Tipo</p>
                            <button class="upload-btn" title="Subir documento">
                            <i class="bi bi-upload"></i>
                        </div>

                        <div class="encabezado">
                            <p>Comprobante Domicilio</p>
                        </div>      

                        <div class="upload">
                            <p>Tipo</p>
                            <button class="upload-btn" title="Subir documento">
                            <i class="bi bi-upload"></i>
                        </div>
              
                        <div class="encabezado">
                            <p>Comprobante Domicilio</p>
                        </div>      

                        <div class="upload">
                            <p>Tipo</p>
                            <button class="upload-btn" title="Subir documento">
                            <i class="bi bi-upload"></i>
                        </div>
                    </div>

                    
<!-- Boton -->
        <div class="modal fade" id="addModalPensionado" tabindex="-1" aria-labelledby="addModalLabelPensionado" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta de Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addFormPensionado"  enctype="multipart/form-data">
                            <input type="hidden" id="id_cliente" name="id_cliente">
                            <input type="hidden" id="id_pensionados" name="id_pensionados">
                        <div class="row">
                                <div class="col-md-4 ">
                                    <label for="nombre_pensionado" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" id="nombre_pensionado" name="nombre_pensionado" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="col-md-4">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular">
                                </div>
                        </div>
                        <br>
                       <div class="row">
                                 <div class="col-md-4">
                                    <label for="idsucursal" class="form-label">Sucursal</label>
                                    <select class="form-select" id="idsucursal" name="idsucursal" required>
                                    <option value="">Selecciona una Sucursal</option>
                                    <?php foreach ($sucursales as $s): ?>
                                        <option value="<?= $s['id_sucursal'] ?>"><?= htmlspecialchars($s['nombre_unidad']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                        </div>
                        <br>
                              <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

         <div class="modal fade" id="AutoModal" tabindex="-1" aria-labelledby="AutoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Auto a Pensionado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="AutoForm">
                            <input type="hidden" name="id_pensionados" value="<?= intval($pensionado['id_pensionados']) ?>">
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca" required>
                            </div>
                            <div class="mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" required>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Año</label>
                                <input type="text" class="form-control" id="year" name="year" required>
                            </div>
                            <div class="mb-3">
                                <label for="placas" class="form-label">Placas</label>
                                <input type="text" class="form-control" id="placas" name="placas" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).on("click", ".btn-edit", function (e) {
    e.preventDefault(); 
    e.stopPropagation();
    const data = $(this).data("pensionado");
    const pensionado = typeof data === "string" ? JSON.parse(data) : data;

    $("#addModalLabel").text("Editar Pensionado");

    $("#nombre_pensionado").val(pensionado.nombre_pensionado || '');
    $("#email").val(pensionado.email || '');
    $("#idsucursal").val(pensionado.id_sucursal || '');
    $("#celular").val(pensionado.celular || '');
    $("#id_cliente").val(pensionado.id_cliente);
    $("#id_pensionados").val(pensionado.id_pensionados);

    $("#updateButton").show();

    const modalInstance = new bootstrap.Modal(document.getElementById('addModalPensionado'));
    modalInstance.show();
});

$("#updateButton").click(function () {
    const formData = new FormData($("#addFormPensionado")[0]);

    $.ajax({
        url: "./dao/updatePensionado.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert(response);
            $("#addModalPensionado").modal("hide");
            $("#addFormPensionado")[0].reset();
            $("#id_pensionados").val('');
            $("#saveButton").show();
            $("#updateButton").hide();
            location.reload();
        },
        error: function (xhr, status, error) {
            alert("Error al actualizar.");
            console.log(error);
        }
    });
});

$(document).on("click", "#autos .small-add-btn", function () {
    new bootstrap.Modal(document.getElementById('AutoModal')).show();
});
$("#AutoForm").on("submit", function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.post('./dao/addAuto.php', formData, function (response) {
        alert(response);
        $("#AutoModal").modal('hide');
        $("#AutoForm")[0].reset();
        location.reload();
    }).fail(function () {
        alert("Error al agregar Auto al Pensionado.");
    });
});

$(document).on("click", ".deleteA-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = $(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Auto?")) {
            $.post("./dao/deleteAuto.php", { id: id }, function (response) {
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