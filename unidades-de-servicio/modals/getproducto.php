 <?php 
require '../../system/connection.php';
$id_sucursal = $_POST['id_sucursal'] ?? null; 
$db = new MySQL();

$p="";
$p=$p."SELECT id, ";
$p=$p." descProducto ";
$p=$p." FROM productos ";
$p=$p." WHERE (Status IS NULL OR Status != 'eliminado')";

$productosResult = $db->consulta($p);
$productos = [];
while ($row = $db->fetch_array($productosResult)) {
    $productos[] = $row;
}
?>


<div class="modal-body">
                    <form id="formAgregarProducto">
                    <input type="hidden" name="id_sucursal" value="<?= intval($unidad['id_sucursal']) ?>">
                    <input type="hidden" name="nombre_producto" id="nombre_producto">
                    <input type="hidden" name="nombre_variante" id="nombre_variante">
                    <div class="mb-3">
                        <label for="producto" class="form-label">Tipo de Producto</label>
                        <select class="form-select" id="producto" name="id_producto" required>
                        <option value="">Selecciona un producto</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['descProducto']) ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="variante" class="form-label">Variante</label>
                        <select class="form-select" id="variante" name="id_variante" required>
                        <option value="">Selecciona una variante</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_precio" class="form-label">Tipo de Precio</label>
                        <select class="form-select" id="tipo_precio" name="tipo_precio" required>
                            <option value="">Selecciona una variante</option>
                            <option value="Fijo">Fijo</option>
                            <option value="Variable">Variable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Ingresa el precio" step="0.01" min="0" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </form>
                </div>