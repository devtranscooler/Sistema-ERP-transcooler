<?php
require '../../system/connection.php';
require '../../system/constants.php';
require_once '../../utilities/sidebar.php'; 
Sidebar::render("Variantes");
?>

<html lang="es">
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
function getVariante(){
    var producto_id = document.querySelector('#idproducto').value;
    var url='./modals/getSingleVariante.php';
    var param = "producto_id=" + encodeURIComponent(producto_id);            

                new Ajax.Request(url,{
                    parameters: param,
                    method:'POST',
                    onComplete:function(_request){     
                    document.getElementById('result').innerHTML = _request.responseText;
                    }.bind(this)
                });
            }
function getBulkVariante(){
                var url='./modals/getBulkVariante.php';
                var param = "";

                new Ajax.Request(url,{
                    parameters: param,
                    method:'POST',
                    onComplete:function(_request){     
                    document.getElementById('result').innerHTML = _request.responseText;
                    }.bind(this)
                });   
            }
    </script>
</head>
<body onclick="closeMenu(event)">

        <div id="contenido-unidades">
                <h1>Variantes</h1>
                <?php
                    $db = new MySQL();
                    $producto_id = isset($_GET['producto_id']) ? intval($_GET['producto_id']) : 0;
                    // Get product name
                    $producto_nombre = '';
                    $p="";
                    $p=$p."SELECT descProducto ";
                    $p=$p."FROM productos ";
                    $p=$p."WHERE id = $producto_id ";
                    $prod_result = $db->consulta($p);

                    if ($db->num_rows($prod_result) > 0) {
                        $prod_data = $db->fetch_array($prod_result);
                        $producto_nombre = $prod_data['descProducto'];
                    }
                ?>
                
                <h4 class="titulo-seccion"><?= htmlspecialchars($producto_nombre) ?></h4>
                <input type="hidden" value="<?= intval($producto_id) ?>" id="idproducto" name="idproducto">
            <table class="table table-hover">
                <thead class="thead-light ">
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Minutos</th>
                    <th scope="col">Tolerancia</th>
                    <th scope="col">Recurrencia</th>
                    <th scope="col">Acciones</th> 
                    </tr>
                </thead>
                <?php
                                $db = new MySQL();

                                    $q="";
                                    $q=$q." select id_variantes";
                                    $q=$q."     ,nombre";
                                    $q=$q."     ,minutos";
                                    $q=$q."     ,tolerancia";
                                    $q=$q."     ,id_productos";
                                    $q=$q."     ,v.recurrencia as recurrencia_id";
                                    $q=$q."     ,cR.descripcion as recurrencia";
                                    $q=$q." from variantes v";
                                    $q=$q." left join catRecurrencia cR";
                                    $q=$q."     on v.recurrencia = cR.idCatReferencia ";
                                    $q=$q."WHERE id_productos = $producto_id ";
                                    $q=$q."AND (Status IS NULL OR Status != 'eliminado')";
                                    


                                    //echo $q;

                                    $rs = $db->consulta($q);

                                    if($db->num_rows($rs)>0){
                                ?>
                                <tbody>
                                <tr>

                                <?php
                                    while($fields = $db->fetch_array($rs)){
                                ?>

                                
                                    <th scope="row"><?=$fields['id_variantes']?></th>
                                    <td><?=$fields['nombre']?></td>
                                    <td><?=$fields['minutos']?></td>
                                    <td><?=$fields['tolerancia']?></td>
                                    <td><?=$fields['recurrencia']?></td>
                                    <td>
                                    <div class="action-buttons">
                                        <a href="#" class="edit-btn" data-variante='<?= json_encode($fields) ?>' title="Editar">
                                            <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                                        </a>
                                        
                                        <a href="#" class="delete-btn" data-id="<?= $fields['id_variantes'] ?>" title="Eliminar">
                                            <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                                        </a>
                                    </div>
                                </td>
                                    </tr>
                                    <?php
                                    }
                                }else {
                                    echo '<tr><td colspan="5">No se encontraron variantes para este producto.</td></tr>';
                                }
                                ?>

            </table>

        </div>
        <div class="fab-container">
            <button type="button" class="btn btn-primary btn-lg rounded-circle fab-btn" id="mainFabBtn">
                <i class="bi bi-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-1" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getVariante()">
                <i class="bi bi-person-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-2" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getBulkVariante()">
                <i class="bi-file-earmark-arrow-up-fill"></i>
            </button>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div id="result"></div>
                </div>
            </div>
        </div>

<script>
jQuery(document).ready(function () {
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const fabContainer = document.querySelector('.fab-container');
    const mainFabBtn = document.getElementById('mainFabBtn');

    mainFabBtn.addEventListener('click', () => {
        fabContainer.classList.toggle('show-options');
    });

    jQuery(document).on("submit", "#addForm", function (event) {
        event.preventDefault();

        if (jQuery("#updateButton").is(":visible")) return;

        const formData = jQuery(this).serialize();
        jQuery.post("./dao/insertVariantes.php", formData, function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            jQuery("#id_variante").val('');
            location.reload(); 
        });
    });

    jQuery(document).on("click", "#updateButton", function () {
        const formData = jQuery("#addForm").serialize();
        jQuery.post("./dao/updateVariantes.php", formData, function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#id_variante").remove();
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            location.reload();
        });
    });

    // Delete
    jQuery(document).on("click", ".delete-btn", function (e) {
        e.preventDefault();
        const id = jQuery(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este elemento?")) {
            jQuery.post("./dao/deleteVariantes.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });

 jQuery(document).on("click", ".edit-btn", function (e) {
        e.preventDefault();
        e.stopPropagation();
        const varianteData = jQuery(e.currentTarget).data('variante');
        const variantes = typeof varianteData === 'string' ? JSON.parse(varianteData) : varianteData;

        jQuery.ajax({
            url: './modals/getSingleVariante.php',
            type: 'POST',
            data: {
                producto_id: variantes.id_productos // or variantes.producto_id
            },
            success: function (response) {
                jQuery("#result").html(response);

                jQuery("#addModalLabel").text("Editar Variante");
                jQuery("#nombrev").val(variantes.nombre || '');
                jQuery("#minutos").val(variantes.minutos || '');
                jQuery("#tolerancia").val(variantes.tolerancia || '');
                jQuery("#recurrencia").val(variantes.recurrencia_id || '');
                jQuery("#id_variante").val(variantes.id_variantes || '');

                jQuery("#saveButton").hide();
                jQuery("#updateButton").show();

                addModal.show();
            }
        });
    });
});

jQuery('#addModal').on('hidden.bs.modal', function () {
    jQuery("#result").empty(); 
    jQuery("#addModalLabel").text("Alta de Variante");
});
</script>


</body>
</html>