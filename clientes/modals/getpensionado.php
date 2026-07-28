<?php 
require '../../system/connection.php';
$id_cliente = $_POST['id_cliente'] ?? null; 

$db = new MySQL();
$s="";
$s=$s."SELECT id_sucursal, ";
$s=$s."nombre_unidad ";
$s=$s."FROM sucursales ";
$s=$s."WHERE (status IS NULL OR status != 'eliminado')";

$sucursalesResult = $db->consulta($s);
$sucursales = [];
while ($row = $db->fetch_array($sucursalesResult)) {
    $sucursales[] = $row;
}
?>

<div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta de Pensionado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addFormPensionado"  enctype="multipart/form-data">
                            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($id_cliente) ?>">
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
                              <button type="submit" id="saveButton" class="btn btn-primary">Guardar Pensionado</button>
                        </form>
                    </div>