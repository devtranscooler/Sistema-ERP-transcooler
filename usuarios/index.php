<?php
require '../system/connection.php';
require '../system/constants.php';

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
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

        function editPassword(_counter) {
            document.getElementById('tdPassword' + _counter).style.display = 'none';
            document.getElementById('inputPassword' + _counter).style.display = 'block';

            var inputs = document.getElementById('inputPassword' + _counter).getElementsByTagName('input');

            for (j = 0; j < inputs.length; j++) {
                inputs[j].select();
            }
        }


        function getList(page) {
            window.location.href = './index.php?page=' + page;
        }

        function getBulkUsuario() {
            var url = './getBulkUsuario.php';
            var param = "";

            new Ajax.Request(url, {
                parameters: param,
                method: 'POST',
                onComplete: function(_request) {
                    document.getElementById('result').innerHTML = _request.responseText;
                }.bind(this)
            });
        }

        function getSingleUsuario() {
            var url = './getSingleUsuario.php';
            var param = "";

            new Ajax.Request(url, {
                parameters: param,
                method: 'POST',
                onComplete: function(_request) {
                    document.getElementById('result').innerHTML = _request.responseText;
                }.bind(this)
            });
        }

        //// CARGA MASIVA DE USUARIO

        function cargarUsuarios() {
            var formulario = document.getElementById('alta');
            formulario.action = "./cargarUsuarios.php";

            //Validamos archivo
            let fileInput = jQuery('#documento')[0].files[0];

            if (!fileInput) {
                alert('Por favor seleccione un archivo .csv');
                return;
            }

            formulario.submit();
            /*  
            

            var url='./cargarUsuarios.php';
            var param = "";

            let formData = new FormData();
            formData.append( 'file', fileInput );
            formData.append("image_name", "googlelogo");
            formData.append("image_type", "csv");

            new Ajax.Request(url,{
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              mimeType: "multipart/form-data",
              onFailure: function(){
                alert('Ocurrio un error');
              },
              onComplete:function(_request){     
                alert(_request.responseText);
                  //document.getElementById('result').innerHTML = _request.responseText;
              }.bind(this)
            });   

              */
        }
        /////
    </script>
</head>

<body onclick="closeMenu(event)">

    <?php
    //Cambiar Ruta;
    require_once '../utilities/sidebar.php';
    Sidebar::render("Usuarios");
    ?>

    <div class="content">
        <h1>Usuarios</h1>
        <table class="table table-hover">
            <thead class="thead-light ">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Contraseña</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <?php

            $db = new MySQL();

            $counter = 1;
            $fieldnumber = 20;

            $q = "";
            $q = $q . " select u.id";
            $q = $q . "     ,u.nombre, u.apellidoP, u.apellidoM ";
            $q = $q . "     ,CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) as nombreCompleto";
            $q = $q . "     ,u.idRol";
            $q = $q . "     ,u.email";
            $q = $q . "     ,cr.rol_descripcion";
            $q = $q . " from usuarios u ";
            $q = $q . " LEFT JOIN cat_rol cr ON u.idRol = cr.id_rol ";
            $q = $q . "WHERE (u.estatus IS NULL OR u.estatus != 'eliminado')";

            //echo $q;


            $totalRow = $db->num_rows($rs);

            $q = $q . " LIMIT " . (($page - 1) * $fieldnumber) . ", " . ($page * $fieldnumber) . " ";

            $rs = $db->consulta($q);

            if ($db->num_rows($rs) > 0) {
            ?>
                <tbody>
                    <tr>

                        <?php
                        while ($fields = $db->fetch_array($rs)) {
                        ?>


                            <th scope="row"><?= $fields['id'] ?></th>
                            <td><?= $fields['nombreCompleto'] ?></td>


                            <form action="./dao/updatePassword.php" method="POST" target="_self">
                                <input type="hidden" id="ID_USUARIO" name="ID_USUARIO" value="<?php echo $fields['id'] ?>">
                                <td class="tdCenter">

                                    <span name="tdPassword<?= $counter ?>" id="tdPassword<?= $counter ?>" style="display:block;cursor:pointer;" onClick="editPassword(<?= $counter ?>);">
                                        <strong>&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</strong>
                                    </span>
                                    <span name="inputPassword<?= $counter ?>" id="inputPassword<?= $counter ?>" style="display:none" ;>
                                        <input type="text" name="PASSWORD" id="PASSWORD" value="********" onChange="if(this.value != '') this.form.submit();" maxlength="50">
                                    </span>

                                </td>
                            </form>

                            <td><?= $fields['rol_descripcion'] ?></td>
                            <td><?= $fields['email'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <img src="../img/editing.png" width="20" onClick="window.open('./dao/setPrivilegios.php?idUsuario=<?= $fields['id'] ?>&name=<?= $fields['nombreCompleto'] ?>', '_blank', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=500px,height=680px');" style="cursor:pointer">
                                    <a href="#" class="edit-btn" data-usuario='<?= json_encode($fields) ?>' title="Editar">
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
        $totalPage = ceil($totalRow / $fieldnumber);
        if ($totalPage > 1) {
            $disabledPrevious = "";
            $disabledNext = "";
            if ($page == 1)
                $disabledPrevious = "disabled";
            if ($page == $totalPage)
                $disabledNext = "disabled";
        ?>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">

                    <li class="page-item <?= $disabledPrevious ?>">
                        <a class="page-link" onclick="return getList(<?= ($page - 1) ?>);" tabindex="-1" style="cursor:pointer;">Previous</a>
                    </li>
                    <?php

                    $i = 1;
                    while ($i < ($totalPage + 1)) {
                        if ($page == $i)
                            echo '<li class="page-item"><a class="page-link" onclick="return getList(' . $i . ');" style="cursor:pointer;text-decoration:underline;">' . $i . '</a></li>';
                        else
                            echo '<li class="page-item"><a class="page-link" onclick="return getList(' . $i . ');" style="cursor:pointer;">' . $i . '</a></li>';

                        $i++;
                    }

                    ?>
                    <li class="page-item <?= $disabledNext ?>">
                        <a class="page-link" onclick="return getList(<?= ($page + 1) ?>);" style="cursor:pointer;">Next</a>
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
                    <?php echo "Página " . $page . " de " . $totalPage . "." ?>
                </td>
            </tr>
        </table>
        <div class="fab-container">
            <button type="button" class="btn btn-primary btn-lg rounded-circle fab-btn" id="mainFabBtn">
                <i class="bi bi-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-1" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getSingleUsuario()">
                <i class="bi bi-person-plus"></i>
            </button>
            <button type="button" class="btn btn-secondary rounded-circle fab-option fab-option-2" data-bs-toggle="modal" data-bs-target="#addModal" onClick="getBulkUsuario()">
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

        <script>
            jQuery(document).ready(function() {

                //<!--AJAX to Submit Form Without Refresh -->
                const fabContainer = document.querySelector('.fab-container');
                const mainFabBtn = document.getElementById('mainFabBtn');

                mainFabBtn.addEventListener('click', () => {
                    fabContainer.classList.toggle('show-options');
                });

                jQuery(document).on("click", ".edit-btn", function() {
                    isEditMode = true;
                    const data = jQuery(this).data("usuario");
                    const usuario = typeof data === "string" ? JSON.parse(data) : data;

                    jQuery("#addModalLabel").text("Editar Usuario");
                    jQuery("#mode").val("update");
                    jQuery("#nombre").val(usuario.nombre || '');
                    jQuery("#apellidoP").val(usuario.apellidoP || '');
                    jQuery("#apellidoM").val(usuario.apellidoM || '');
                    jQuery("#idRol").val(usuario.idRol || '');
                    jQuery("#email").val(usuario.email || '');
                    jQuery("#fecNac").val(usuario.fecNac || '');
                    jQuery("#noEmpleado").val(usuario.noEmpleado || '');
                    jQuery("#movil").val(usuario.movil || '');
                    jQuery("#fecContratacion").val(usuario.fecContratacion || '');
                    jQuery("#diasVacaciones").val(usuario.diasVacaciones || '');
                    jQuery("#diasVacDisfrutados").val(usuario.diasVacDisfrutados || '');
                    jQuery("#estatus").val(usuario.estatus || '');
                    jQuery("#puesto").val(usuario.puesto || '');
                    jQuery("#area").val(usuario.area || '');
                    jQuery("#cedis").val(usuario.cedis || '');
                    jQuery("#telefono").val(usuario.telefono || '');
                    jQuery("#jefeInmediato").val(usuario.jefeInmediato || '');
                    jQuery("#id").val(usuario.id);

                    jQuery("#saveButton").hide();
                    jQuery("#updateButton").show();


                    const modalInstance = new bootstrap.Modal(document.getElementById('addModal'));
                    modalInstance.show();
                });


                jQuery(document).on("click", ".delete-btn", function(e) {
                    e.preventDefault();
                    const id = jQuery(this).data("id");

                    if (confirm("¿Estás seguro de que deseas eliminar este Usuario?")) {
                        jQuery.post("./dao/deleteUsuario.php", {
                            id: id
                        }, function(response) {
                            alert(response);
                            location.reload();
                        }).fail(function(xhr, status, error) {
                            alert("Error al eliminar.");
                            console.log(error);
                        });
                    }
                });

            });
        </script>
</body>

</html>