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
                    <h5 class="modal-title">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                    <div class="modal-body">
                        <form id="formAgregarProducto">
                            <input type="hidden" name="id_cliente" value="<?= intval($id_cliente) ?>">
                            <input type="hidden" id="id_producto_cliente" name="id_producto_cliente">
 
                            <div class="mb-3">
                                <label for="sucursal" class="form-label">Sucursal</label>
                                <select class="form-select" id="sucursal" name="id_sucursal" required>
                                    <option value="">Selecciona una Sucursal</option>
                                    <?php foreach ($sucursales as $s): ?>
                                        <option value="<?= $s['id_sucursal'] ?>"><?= htmlspecialchars($s['nombre_unidad']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="productoSelect" class="form-label">Producto</label>
                                <select class="form-select" id="productoSelect" name="id_producto" required>
                                    <option value="">Selecciona un producto</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="variante" class="form-label">Variante</label>
                                <select class="form-select" id="variante" name="id_variante" required>
                                    <option value="">Selecciona una variante</option>
                                </select>
                            </div>
                          
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="precio" name="precio" placeholder="Ingresa el precio" step="0.01" min="0" readonly>
                            </div>
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" value="0" min="1">
                            </div>

                            <div class="form-group">
                                <label for="descuento">Descuento (%)</label>
                                <input type="number" id="descuento" name="descuento" class="form-control" value="0" min="0" max="15">
                            </div>
                            <div class="form-group">
                                <label for="precio_final">Precio Final</label>
                                <input type="text" id="precio_final" name="precio_final" class="form-control" readonly>
                            </div>
                
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="text" id="total" name="total_pagar" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                               <label for="recurrencia">Recurrencia</label>
                              <input type="text" id="recurrencia" name="recurrencia" class="form-control" readonly>
                            </div>
                            <br>
                            <button type="submit" id="saveButton" class="btn btn-primary">Guardar Producto</button>
                        
                            <button type="button" id="updateButtonP" class="btn btn-primary" style="display: none;">Actualizar Producto</button>
                        </form>
                    </div>