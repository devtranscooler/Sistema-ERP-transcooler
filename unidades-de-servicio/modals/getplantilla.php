 <?php 
require '../../system/connection.php';

$id_sucursal = $_POST['id_sucursal'] ?? null; 

$db = new MySQL();
$u="";
$u=$u."SELECT id, ";
$u=$u." CONCAT(nombre,' ',apellidoP,' ',apellidoM) as nombreCompleto ";
$u=$u." from usuarios ";
$u=$u." WHERE (estatus IS NULL OR estatus != 'eliminado') ";
$usersResult = $db->consulta($u);
$users = [];
while ($row = $db->fetch_array($usersResult)) {
    $users[] = $row;
}
 ?>
<div class="modal-body">
                        <form id="plantillaForm">
                            <input type="hidden" name="id_sucursal" value="<?= htmlspecialchars($id_sucursal) ?>">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Seleccionar Usuario</label>
                                <select class="form-select" id="usuario" name="usuario" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nombreCompleto']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>