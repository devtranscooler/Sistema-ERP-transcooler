<?php 
require '../../../system/connection.php';
$producto_id = $_POST['producto_id'];
$db = new MySQL();
?>
<div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta Variante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"  enctype="multipart/form-data">
                        <input type="hidden" id="id_variante" name="id_variante" value="">    
                        <div class="row">
                                <div class="col-md-4 ">
                                    <label for="nombrev" class="form-label">Nombre Variante</label>
                                    <input type="text" class="form-control" id="nombrev" name="nombrev" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="minutos" class="form-label">Minutos</label>
                                    <input type="text" class="form-control" id="minutos" name="minutos">
                                </div>
                                <div class="col-md-4">
                                    <label for="tolerancia" class="form-label">Tolerancia</label>
                                    <input type="text" class="form-control" id="tolerancia" name="tolerancia">
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-3 ">
                                    <input type="hidden" value="<?=$producto_id?>" id="idproducto" name="idproducto">
                                    <label for="recurrencia" class="form-label">Recurrencia</label>
                                    <select class="form-control" id="recurrencia" name="recurrencia">
                                    <?php

                                    $q="";
                                    $q=$q." select idCatReferencia";
                                    $q=$q."     ,descripcion";
                                    $q=$q." from catRecurrencia ";

                                    $rs = $db->consulta($q);

                                    if($db->num_rows($rs)>0){
                                        while($fields = $db->fetch_array($rs)){
                                    ?>

                                        <option value="<?=$fields['idCatReferencia']?>"><?=$fields['descripcion']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                        </div>
                        <br>
                            <button type="submit" id="saveButton" class="btn btn-success">Guardar</button>


                            <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>