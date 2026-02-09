<?php
require '../system/connection.php';
require '../system/constants.php';

$page=isset($_REQUEST['page']) ? $_REQUEST['page']:1;
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

    
    <script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" type="text/javascript" ></script>

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

        function editPassword(_counter){
        document.getElementById('tdPassword' + _counter).style.display='none';
        document.getElementById('inputPassword' + _counter).style.display='block';

        var inputs = document.getElementById('inputPassword' + _counter).getElementsByTagName('input');

                for(j=0;j<inputs.length;j++){
                        inputs[j].select();
                        }       
        }
    </script>
    <style>
        .table tr {
            cursor: pointer;
        }
    </style>
</head>
<body onclick="closeMenu(event)">
    
    <?php
   //Cambiar Ruta;
    require_once '../utilities/sidebar.php'; 
    Sidebar::render("Clientes");
    ?>

    <div class="content">
        <h1>Clientes</h1>



    <table class="table table-hover">
        <thead class="thead-light ">
            <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre del Cliente</th>
            <th scope="col">Tipo del Cliente</th>
            <th scope="col">STP</th>
            <th scope="col">Regimen</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
<?php

$db = new MySQL();

$counter=1;
$fieldnumber=10;

        $q="";
        $q=$q." select id";
        $q=$q."     ,nombre_razon";
        $q=$q."     ,calle";
        $q=$q."     ,num_ext";
        $q=$q."     ,num_int";
        $q=$q."     ,codigo_postal";
        $q=$q."     ,tipo_cliente";
        $q=$q." from clientes ";
        $q=$q." WHERE (status IS NULL OR Status != 'eliminado')";

    //echo $q;

    $rs = $db->consulta($q);

    $totalRow = $db->num_rows($rs);

    $q=$q." LIMIT ".(($page-1)*$fieldnumber).", ".($page*$fieldnumber)." ";

    $rs = $db->consulta($q);

    if($db->num_rows($rs)>0){
?>
<tbody>


<?php
    while($fields = $db->fetch_array($rs)){
?>  
       <tr class="row-link" data-id="<?=$fields['id']?>" style="cursor:pointer;">
            
                <td scope="row"><?=$fields['id']?></td>
                <td><?=$fields['nombre_razon']?></td>
                <td><?=$fields['tipo_cliente']?></td>
                <td>
                        <div class="action-buttons">
                            <a href="#" class="edit-btn" data-clientes='<?= json_encode($fields) ?>' title="Editar">
                                <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                            </a>
                            
                            <a href="#" class="delete-btn" data-id="<?= $fields['id'] ?>" title="Eliminar">
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

<!--Paginado bootstrap-->

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
  <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-1" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getSingleAlta()">
    <i class="bi bi-person-plus"></i>
  </button>
  <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-2" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getBulkAlta()">
    <i class="bi-file-earmark-arrow-up-fill"></i>
  </button>
</div>

<!-- Formulario -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div id="result"><div>
                </div>
            </div>
        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX to Submit Form Without Refresh -->
<script>
  const fabContainer = document.querySelector('.fab-container');
  const mainFabBtn = document.getElementById('mainFabBtn');

  mainFabBtn.addEventListener('click', () => {
    fabContainer.classList.toggle('show-options');
  });
$(document).ready(function () {
    // Initialize modal
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    
    //alert("Entramos");

    // Form submit for new entries
    $("#addForm").submit(function (event) {
        //event.preventDefault();
        //event.stopPropagation();
        //if ($("#updateButton").is(":visible")) return;
        const formData = $("#addForm").serialize();
        //const formData = $(this).serialize();
        $.post("./dao/insertCliente.php", formData, function (response) {
            alert(response);
            $("#addModal").modal("hide"); 
            $("#addForm")[0].reset(); 
            $("#id").val('');
            location.reload();
        });
    });

    // Update button click
    $("#updateButton").click(function () {
        const formData = $("#addForm").serialize();
        $.post("./dao/updatecliente.php", formData, function (response) {
            alert(response);
            $("#addModal").modal("hide");
            $("#addForm")[0].reset();
            $("#id").val('');
            $("#saveButton").show();
            $("#updateButton").hide();
            location.reload();
        });
    });

    // Edit button click - fixed version
    $(document).on("click", ".edit-btn", function (e) {
        e.preventDefault();
        e.stopPropagation();
        // Get the product data from data attribute
        const clientData = $(this).data('clientes');
        
        // Parse the JSON if it's a string (shouldn't be necessary with your current code)
        const clientes = typeof clientData === 'string' ? JSON.parse(clientData) : clientData;
        
                $("#addModalLabel").text("Editar Cliente");

                $("#nombre_razon").val(clientes.nombre_razon || '');
                $("#tipo_cliente").val(clientes.tipo_cliente || '');
                $("#regimen").val(clientes.regimen || '');
                $("#calle").val(clientes.calle || '');
                $("#municipio").val(clientes.municipio || '');
                $("#estado").val(clientes.estado || '');
                $("#codigo_postal").val(clientes.codigo_postal || '');
                $("#num_ext").val(clientes.num_ext || '');
                $("#num_int").val(clientes.num_int || '');
                $("#credito").val(clientes.credito || '');
                $("#stp").val(clientes.stp || '');
                $("#id").val(clientes.id);

                $("#saveButton").hide();
                $("#updateButton").show();
        
        // Show the modal
        addModal.show();
    });

    // Delete button click
  $(document).on("click", ".delete-btn", function (e) {
         e.preventDefault(); 
         e.stopPropagation();
        const id = $(this).data("id");

        if (confirm("¿Estás seguro de que deseas eliminar este Sucursal?")) {
            $.post("./dao/deleteClientes.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
});
$('#addModal').on('hidden.bs.modal', function () {
    /*
    $("#addForm")[0].reset();
    $("#id_cliente").val('');
    $("#saveButton").show();
    $("#updateButton").hide();
    $("#addModalLabel").text("Alta de Sucursal"); // Reset the title
    */
});



function getList(page){
    window.location.href = './index.php?page='+page;
}
$(document).on("click", ".row-link", function () {
    const id = $(this).data("id");
    window.location.href = `detail_cliente.php?id=${id}`;
});

function getBulkAlta(){
    alert("Bulk Alta");

    var url='./getBulkAlta.php';
    var param = "";

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        //hideCostoMaterial(id);
        document.getElementById('result').innerHTML = _request.responseText;
        }.bind(this)
    });   
}

function getSingleAlta(){
    //alert("Single Alta");

    var url='./getSingleAlta.php';
    var param = "";
    //var param = 'idServicio='+idServicio;

    new Ajax.Request(url,{
        parameters: param,
        method:'POST',
        onComplete:function(_request){     
        //hideCostoMaterial(id);
        document.getElementById('result').innerHTML = _request.responseText;
        }.bind(this)
    });
}
</script>
</body>
</html>
<?php

