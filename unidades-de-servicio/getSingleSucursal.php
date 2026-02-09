<div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Alta de Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"  enctype="multipart/form-data">
                        <input type="hidden" id="id_sucursal" name="id_sucursal">
                        <div class="row">
                                <div class="col-md-4 ">
                                    <label for="nombre_unidad" class="form-label">Nombre de la Sucursal</label>
                                    <input type="text" class="form-control" id="nombre_unidad" name="nombre_unidad" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado">
                                </div>
                                <div class="col-md-4">
                                    <label for="municipio" class="form-label">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                 <div class="col-md-4">
                                    <label for="modelo_negocio" class="form-label">Modelo de Negocio</label>
                                        <select class="form-select" id="modelo_negocio" name="modelo_negocio" required>
                                            <option value="">Selecciona un Modelo de Negocio</option>
                                            <option value="Renta">Renta</option>
                                            <option value="Comparticion">Comparticion</option>
                                            <option value="Servicio">Servicio</option>
                                            <option value="Comparticion +  Fee">Comparticion +  Fee</option>
                                        </select>
                                </div>
                             <div class="col-md-4">
                                <label for="socio" class="form-label">Porcentaje Socio</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="socio" name="socio" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="empresa" class="form-label">Porcentaje Empresa</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="empresa" name="empresa" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="fondo" class="form-label">Fondo</label>
                                    <input type="text" class="form-control" id="fondo" name="fondo">
                                </div>
                                 <div class="col-md-4">
                                    <label for="renta" class="form-label">Renta</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="renta" name="renta">
                                    <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="operadora" class="form-label">Operadora</label>
                                        <select class="form-select" id="operadora" name="operadora" required>
                                            <option value="">Selecciona una Operadora</option>
                                            <option value="BNI Estacionamientos S.A de C.V">BNI Estacionamientos S.A de C.V</option>
                                            <option value="Bengala Valet S.A de C.V">Bengala Valet S.A de C.V</option>
                                        </select>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="codigo_unidad" class="form-label">Codigo de Unidad</label>
                                    <input type="text" class="form-control" id="codigo_unidad" name="codigo_unidad">
                                </div>
                                 <div class="col-md-4">
                                    <label for="fee" class="form-label">Fee</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="fee" name="fee">
                                    <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="sistema_gestion" class="form-label">Sistema de Gestion</label>
                                        <select class="form-select" id="sistema_gestion" name="sistema_gestion" required>
                                            <option value="">Selecciona un Sistema de Gestion</option>
                                            <option value="SIAP">SIAP</option>
                                            <option value="PARKIMOVIL">PARKIMOVIL</option>
                                            <option value="CONEKTA">CONEKTA</option>
                                            <option value="">AZTEK</option>
                                            <option value="TRAFICO ALTO">TRAFICO ALTO</option>
                                            <option value="MAYPAR">MAYPAR</option>
                                            <option value="No tiene Sistema de Gestion">No tiene Sistema de Gestion</option>
                                            <option value="ACCESA">ACCESA</option>
                                            <option value="N/A">N/A</option>
                                            <option value="YAWI">YAWI</option>
                                        </select>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="link_reporte" class="form-label">Link Reporte</label>
                                    <input type="url" class="form-control" id="link_reporte" name="link_reporte" placeholder="https://ejemplo.com">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion" class="form-label">Direccion</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="email_gerente" class="form-label">Email gerente</label>
                                    <input type="email" class="form-control" id="email_gerente" name="email_gerente">
                                </div>
                                <div class="col-md-4">
                                    <label for="email_coordinador" class="form-label">Email coordinador</label>
                                    <input type="email" class="form-control" id="email_coordinador" name="email_coordinador">
                                </div>
                                <div class="col-md-4">
                                    <label for="email_encargado" class="form-label">Email encargado</label>
                                    <input type="email" class="form-control" id="email_encargado" name="email_encargado">
                                </div>
                        </div>
                        <br>
                            <button type="submit" id="saveButton" class="btn btn-success">Guardar</button>

                            <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
                        </form>
                    </div>