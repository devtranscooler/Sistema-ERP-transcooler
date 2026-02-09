<?php
require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();
$modulos_catalogo = $db->consulta("SELECT id, nombre FROM modulos_catalogo");
$modulos = [];
while ($row = $db->fetch_array($modulos_catalogo)) {
    $modulos[] = $row;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$q = "
    SELECT cr.id_rol, cr.rol_descripcion, cr.fecha_alta
    FROM cat_rol cr
";

//echo $modulos_usuario_query;
//die();

$modulos_usuario = [];
$result = $db->consulta($q);
while ($row = $db->fetch_array($result)) {
    $modulos_usuario[] = $row;
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
    <?php
    require_once '../utilities/sidebar.php'; 
    Sidebar::render("Roles");
    ?>
    <div class="content">
        <h1>Rol</h1>
                    <div class="datos-usuario">
                        <p><strong>ID:</strong> <?= htmlspecialchars($_SESSION['ID_USUARIO']) ?></p>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['NAME']) ?></p>
                    </div>
    
            <div class="modulos">
              <?php foreach ($modulos_usuario as $modulo): ?>
                <div class="modulo mb-4">
                    <span><?= htmlspecialchars($modulo['id_rol']) ?></span>
                    -
                    <span><?= htmlspecialchars($modulo['rol_descripcion']) ?></span>
                        
            
                </div>
            <?php endforeach; ?>
    </div>
<!-- Boton -->
<button type="button" class="btn btn-primary btn-lg rounded-circle fab-btn" data-bs-toggle="modal" data-bs-target="#moduloModal">
    <i class="bi bi-plus"></i>
</button>

        <div class="modal fade" id="moduloModal" tabindex="-1" aria-labelledby="moduloModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Modulo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="moduloForm">
                            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($_SESSION['ID_USUARIO']) ?>">
                            <div class="mb-3">
                                <label for="modulo" class="form-label">Seleccionar Modulo</label>
                                <select class="form-select" id="modulo" name="id_modulo" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($modulos as $modulo): ?>
                                        <option value="<?= $modulo['id'] ?>"><?= htmlspecialchars($modulo['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX to Submit Form Without Refresh -->
<script>
$(document).ready(function () {
    const modalEl = document.getElementById('moduloModal');
    const modalInstance = new bootstrap.Modal(modalEl);

   $("#moduloForm").submit(function (event) {
        event.preventDefault();
        const formData = $(this).serialize();

        $.post("./dao/insertModulo.php", formData, function (response) {
            alert(response);
            modalInstance.hide();
            $("#moduloForm")[0].reset();
            $("#id_modulo").val('');
            location.reload();
        });
    });
});
$(document).on('change', '.permiso-checkbox', function () {
    const checkbox = $(this);
    const idUsuario = checkbox.data('idusuario');
    const idModulo = checkbox.data('idmodulo');
    const permiso = checkbox.data('permiso');
    const valor = checkbox.is(':checked') ? 1 : 0;

    $.post('./dao/updatePermiso.php', {
        id_usuario: idUsuario,
        id_modulo: idModulo,
        permiso: permiso,
        valor: valor
    }, function (response) {
        console.log('Permiso actualizado:', response);
    }).fail(function () {
        alert('Error al actualizar el permiso.');
    });
});

function deleteModulo(idModulo) {
    if (!confirm("¿Estás seguro que deseas eliminar este módulo?")) return;

    $.post("./dao/deleteModulo.php", {
        id_usuario: <?= json_encode($id_usuario) ?>,
        id_modulo: idModulo
    }, function (response) {
        alert(response);
        location.reload(); // Refresh to reflect changes
    }).fail(function () {
        alert('Error al eliminar el módulo.');
    });
}
</script>

</body>
</html>
