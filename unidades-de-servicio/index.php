<?php
require '../system/connection.php';
require '../system/constants.php';

$page=isset($_REQUEST['page']) ? $_REQUEST['page']:1;
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
        function getList(page){
            window.location.href = './index.php?page='+page;
        }
        function getBulkSucursal(){
                var url='./getBulkSucursal.php';
                var param = "";

                new Ajax.Request(url,{
                    parameters: param,
                    method:'POST',
                    onComplete:function(_request){     
                    document.getElementById('result').innerHTML = _request.responseText;
                    }.bind(this)
                });   
            }

            function getSingleSucursal(){
                var url='./getSingleSucursal.php';
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
    
    <?php
   //Cambiar Ruta;
    require_once '../utilities/sidebar.php'; 
    Sidebar::render("Sucursales");
    ?>

    <div class="content">
        <h1>Sucursales</h1>

    <table class="table table-hover">
        <thead class="thead-light ">
            <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre de la Unidad</th>
            <th scope="col">Estado</th>
            <th scope="col">Municipio</th>
            <th scope="col">Modelo de Negocio</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
<?php

$db = new MySQL();

$counter=1;
$fieldnumber=10;

        $q="";
        $q=$q." select id_sucursal";
        $q=$q."     ,nombre_unidad";
        $q=$q."     ,estado";
        $q=$q."     ,municipio";
        $q=$q."     ,socio";
        $q=$q."     ,empresa";
        $q=$q."     ,fondo";
        $q=$q."     ,renta";
        $q=$q."     ,modelo_negocio";
        $q=$q." from sucursales ";
        $q=$q." WHERE (Status IS NULL OR Status != 'eliminado')";

    $rs = $db->consulta($q);

    $totalRow = $db->num_rows($rs);

    $q=$q." LIMIT ".(($page-1)*$fieldnumber).", ".($page*$fieldnumber)." ";

    $rs = $db->consulta($q);

    if($db->num_rows($rs)>0){
?>
<tbody>
<tr>

<?php
    while($fields = $db->fetch_array($rs)){
?>  
       <tr class="row-link" data-id="<?=$fields['id_sucursal']?>" style="cursor:pointer;">
            
                <th scope="row"><?=$fields['id_sucursal']?></th>
                <td><?=$fields['nombre_unidad']?></td>
                <td><?=$fields['estado']?></td>
                <td><?=$fields['municipio']?></td>
                <td><?=$fields['modelo_negocio']?></td>
                <td>
                        <div class="action-buttons">
                            <a href="#" class="edit-btn" data-sucursales='<?= json_encode($fields) ?>' title="Editar">
                                <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                            </a>
                            
                            <a href="#" class="delete-btn" data-id="<?= $fields['id_sucursal'] ?>" title="Eliminar">
                                <img src="https://cdn-icons-png.flaticon.com//512/5028/5028066.png" width="20" alt="Delete">
                            </a>
                        </div>
                        </td>
                    </tr>
                </tr>
<?php
        $counter++;
    }
}
?>
  </tbody>
</table>

<?php
$totalPage = ceil($totalRow/$fieldnumber);
if ($totalPage>1){
  $disabledPrevious = "";
  $disabledNext = "";
  if ($page==1)
    $disabledPrevious = "disabled";
  if ($page == $totalPage)
    $disabledNext = "disabled";
?>    

<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">

    <li class="page-item <?=$disabledPrevious?>">
      <a class="page-link" onclick="return getList(<?=($page-1)?>);" tabindex="-1" style="cursor:pointer;">Previous</a>
    </li>
<?php

$i=1;
while ($i < ($totalPage+1)) {
  if ($page == $i)
    echo '<li class="page-item"><a class="page-link" onclick="return getList('.$i.');" style="cursor:pointer;text-decoration:underline;">'.$i.'</a></li>';
  else
    echo '<li class="page-item"><a class="page-link" onclick="return getList('.$i.');" style="cursor:pointer;">'.$i.'</a></li>';

  $i++;
}

?>
  <li class="page-item <?=$disabledNext?>">
    <a class="page-link" onclick="return getList(<?=($page+1)?>);" style="cursor:pointer;">Next</a>
  </li>

</ul>
</nav>
<?php
}
?>

<!--Paginado old-->
<table width="99%" border=0 align="center" cellspacing=2>
        <tr>
        <td class="Center" width="90%">&nbsp;</td>
      <td class="Right" width="10%">
      <?php echo "Página ".$page." de ".$totalPage."."?>
      </td>
    </tr>
</table>
</div> 
<!-- Boton -->
<div class="fab-container">
  <button type="button" class="btn btn-primary btn-lg rounded-circle fab-btn" id="mainFabBtn">
    <i class="bi bi-plus"></i>
  </button>
  <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-1" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getSingleSucursal()">
    <i class="bi bi-buildings"></i>
  </button>
  <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-2" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getBulkSucursal()">
    <i class="bi-file-earmark-arrow-up-fill"></i>
  </button>
</div>

<!-- Formulario -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div id="result"></div>
                </div>
            </div>
        </div>

<!-- AJAX to Submit Form Without Refresh -->
<script>


 jQuery(document).ready(function () {
    // Initialize modal
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
        const fabContainer = document.querySelector('.fab-container');
        const mainFabBtn = document.getElementById('mainFabBtn');

            mainFabBtn.addEventListener('click', () => {
                fabContainer.classList.toggle('show-options');
        });
            
    // Form submit for new entries
    jQuery("#addForm").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (jQuery("#updateButton").is(":visible")) return;
        
        const formData = jQuery(this).serialize();
        jQuery.post("./dao/insertSucursal.php", formData, function (response) {
            alert(response);
            jQuery("#addModal").modal("hide"); 
            jQuery("#addForm")[0].reset(); 
            jQuery("#id_sucursal").val('');
            location.reload();
        });
    });

    // Update button click
    jQuery(document).on("click", "#updateButton", function () {
        const formData = jQuery("#addForm").serialize();
        jQuery.post("./dao/updateSucursal.php", formData, function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#id_sucursal").val('');
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            location.reload();
        });
    });

    // Edit button click - fixed version
    jQuery(document).on("click", ".edit-btn", function (e) {
        e.preventDefault();
        e.stopPropagation();
        jQuery.ajax({
                url: './getSingleSucursal.php',
                success: function (response) {
            jQuery("#result").html(response);
        // Get the product data from data attribute
        const sucursalData = jQuery(e.currentTarget).data('sucursales');
        const sucursales = typeof sucursalData === 'string' ? JSON.parse(sucursalData) : sucursalData;
        
            jQuery("#addModalLabel").text("Editar Sucursal");

            jQuery("#nombre_unidad").val(sucursales.nombre_unidad || '');
            jQuery("#estado").val(sucursales.estado || '');
            jQuery("#municipio").val(sucursales.municipio || '');
            jQuery("#modelo_negocio").val(sucursales.modelo_negocio || '');
            jQuery("#socio").val(sucursales.socio || '');
            jQuery("#empresa").val(sucursales.empresa || '');
            jQuery("#fondo").val(sucursales.fondo || '');
            jQuery("#renta").val(sucursales.renta || '');
            jQuery("#operadora").val(sucursales.operadora || '');
            jQuery("#codigo_unidad").val(sucursales.codigo_unidad || '');
            jQuery("#fee").val(sucursales.fee || '');
            jQuery("#sistema_gestion").val(sucursales.sistema_gestion || '');
            jQuery("#link_reporte").val(sucursales.link_reporte || '');
            jQuery("#direccion").val(sucursales.direccion || '');
            jQuery("#email_gerente").val(sucursales.email_gerente || '');
            jQuery("#email_coordinador").val(sucursales.email_coordinador || '');
            jQuery("#email_encargado").val(sucursales.email_encargado || '');

            jQuery("#id_sucursal").val(sucursales.id_sucursal);

            jQuery("#saveButton").hide();
            jQuery("#updateButton").show();
        
        // Show the modal
        addModal.show();
        }
      });
    });

    // Delete button click
  jQuery(document).on("click", ".delete-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = jQuery(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Sucursal?")) {
            jQuery.post("./dao/deleteSucursales.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
});
jQuery('#addModal').on('hidden.bs.modal', function () {
    jQuery("#result").empty(); 
    jQuery("#addModalLabel").text("Alta de Sucursal");
});


jQuery(document).on("click", ".row-link", function () {
    const id = jQuery(this).data("id");
    window.location.href = `detail_unidad.php?id_sucursal=${id}`;
});
</script>

</body>
</html>
<?php
