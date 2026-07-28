  <?php 
require '../../system/connection.php';
$id_cliente = $_POST['id_cliente'] ?? null; 
 ?>
 <div class="modal-header">
                        <h5 class="modal-title">Agregar Contacto a Clientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contactoForm">
                            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($id_cliente) ?>">
                            <div class="mb-3">
                                <label for="nombrecompleto" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombrecompleto" name="nombrecompleto" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_contacto" class="form-label">Email Contacto</label>
                                <input type="email" class="form-control" id="email_contacto" name="email_contacto" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="celular" class="form-label">Celular</label>
                                <input type="text" class="form-control" id="celular" name="celular" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>