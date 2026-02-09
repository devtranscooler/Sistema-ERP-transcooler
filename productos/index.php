<?php
require '../system/connection.php';
require '../system/constants.php';
require_once '../utilities/sidebar.php'; 
    Sidebar::render("Productos");
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
<?php
$db = new MySQL();
$conn = $db->getConexion();

$sql="";
$sql=$sql."SELECT id, "; 
$sql=$sql."descProducto ";
$sql=$sql."FROM productos ";
$sql=$sql."WHERE (Status IS NULL OR Status != 'eliminado')";
$result = $conn->query($sql);
?>

    <div id="contenido-unidades">
        <h2 class="titulo-seccion">Productos</h2>

        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="contenedor">
            <div class="encabezado">
                <p>(ID: <?= htmlspecialchars($row['id']) ?>) <?= htmlspecialchars($row['descProducto']) ?></p>
                <div class="btn-group">
                    <a href="/productos/variantes/index.php?producto_id=<?= urlencode($row['id']) ?>" class="small-add-btn" title="Agregar módulo">+</a>
                   
                    <a href="#" class="icon-btn edit-producto-btn" title="Editar" data-producto='<?= json_encode($row) ?>'>
                        <img src="https://cdn-icons-png.flaticon.com/512/10336/10336582.png" width="20" alt="Edit">
                    </a>
                   
                    <a href="#" class="icon-btn delete-producto-btn" title="Eliminar" data-id="<?= $row['id'] ?>">
                        <img src="https://cdn-icons-png.flaticon.com/512/5028/5028066.png" width="20" alt="Delete">
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
  
<!-- Boton -->
<div class="fab-container">
    <button type="button" class="btn btn-primary btn-lg rounded-circle fab-btn" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus"></i>
    </button>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" method="post">
                                <div class="form-group">
                                    <label for="nombreProducto" class="form-label">Nombre del producto</label>
                                    <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                                    <input type="hidden" id="id_producto" name="id_producto">
                                </div>
                            <br>
                            <button type="submit" id="saveButton" class="btn btn-success">Guardar</button>
                            <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<script> 
jQuery(document).ready(function () {
    // Initialize modal
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    
    // Form submit for new entries
    jQuery("#addForm").submit(function (event) {
        event.preventDefault();
        if (jQuery("#updateButton").is(":visible")) return;
        
        const formData = jQuery(this).serialize();
        jQuery.post("./dao/insertProducto.php", formData, function (response) {
            alert(response);
            jQuery("#addModal").modal("hide");
            jQuery("#addForm")[0].reset();
            jQuery("#id_producto").val('');
            jQuery("#saveButton").show();
            jQuery("#updateButton").hide();
            location.reload();
        });
    });

    // Update button click
    jQuery("#updateButton").click(function () {
        const formData = jQuery("#addForm").serialize();
        jQuery.post("./dao/updateProducto.php", formData, function (response) {
            alert(response);
            addModal.hide();
            location.reload();
        });
    });

    // Edit button click - fixed version
    jQuery(document).on("click", ".edit-producto-btn", function (e) {
        e.preventDefault();
        
        // Get the product data from data attribute
        const productData = jQuery(this).data('producto');
        
        // Parse the JSON if it's a string (shouldn't be necessary with your current code)
        const producto = typeof productData === 'string' ? JSON.parse(productData) : productData;
        
        // Update modal fields with product data
        jQuery("#addModalLabel").text("Editar Producto");
        jQuery("#nombreProducto").val(producto.descProducto);
        jQuery("#id_producto").val(producto.id);
        
        // Show update button and hide save button
        jQuery("#saveButton").hide();
        jQuery("#updateButton").show();
        
        // Show the modal
        addModal.show();
    });
jQuery('#addModal').on('hidden.bs.modal', function () {
    jQuery("#addForm")[0].reset();
    jQuery("#id_producto").val('');
    jQuery("#saveButton").show();
    jQuery("#updateButton").hide();
    jQuery("#addModalLabel").text("Alta producto"); // Reset the title
});
    // Delete button click
    jQuery(document).on("click", ".delete-producto-btn", function (e) {
        e.preventDefault();
        const id = jQuery(this).data("id");
        if (confirm("¿Estás seguro de que deseas eliminar este Producto?")) {
            jQuery.post("./dao/deleteProducto.php", { id: id }, function (response) {
                alert(response);
                location.reload();
            }).fail(function (xhr, status, error) {
                alert("Error al eliminar.");
                console.log(error);
            });
        }
    });
});
</script>



</body>
</html>