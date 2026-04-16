<div class="tab-content mt-2 p-3 border border-2 border-danger rounded bg-danger-subtle">
    <h2>Panel Principal</h2>

    <div class="row mt-3">

        <div class="row g-3">
            <div class="col-md-4">
                <label for="id_shipment" class="form-label">ID / Referencia / Shipment</label>
                <input type="text" class="form-control" id="id_shipment" name="id_shipment" required>
            </div>

            <div class="col-md-4">
                <label for="unidad" class="form-label">Número de Económico</label>
                <select class="form-control" id="unidad" name="unidad" required>
                    <option value="">Seleccione una unidad</option>
                    <option value="213">213</option>
                    <option value="214">214</option>
                    <option value="215">215</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="equipo" class="form-label">Remolque / Refrigeración</label>
                <select class="form-control" id="equipo" name="equipo">
                    <option value="">Seleccione equipo</option>
                    <option value="externo">Externo</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="operador" class="form-label">Nombre del Operador</label>
                <select class="form-control" id="operador" name="operador">
                    <option value="">Seleccione operador</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="otro_operador" class="form-label">Otro Operador</label>
                <input type="text" class="form-control" id="otro_operador" name="otro_operador">
            </div>

            <div class="col-md-4">
                <label for="origen" class="form-label">Origen</label>
                <select class="form-control" id="origen" name="origen">
                    <option value="">Seleccione origen</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="otro_origen" class="form-label">Otro Origen</label>
                <input type="text" class="form-control" id="otro_origen" name="otro_origen">
            </div>

            <div class="col-md-4">
                <label for="destino_general" class="form-label">Destino General</label>
                <select class="form-control" id="destino_general" name="destino_general">
                    <option value="">Seleccione zona</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="destino_especifico" class="form-label">Destino Específico 1</label>
                <input type="text" class="form-control" id="destino_especifico" name="destino_especifico">
            </div>

            <div class="col-md-6">
                <label for="otro_destino" class="form-label">Otro Destino</label>
                <input type="text" class="form-control" id="otro_destino" name="otro_destino">
            </div>

            <div class="col-md-4">
                <label for="tipo_viaje" class="form-label">Tipo de Viaje</label>
                <select class="form-control" id="tipo_viaje" name="tipo_viaje">
                    <option value="">Seleccione tipo</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="cliente_factura" class="form-label">Cliente Factura</label>
                <select class="form-control" id="cliente_factura" name="cliente_factura">
                    <option value="">Seleccione cliente</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="otro_cliente" class="form-label">Otro Cliente</label>
                <input type="text" class="form-control" id="otro_cliente" name="otro_cliente">
            </div>

            <div class="col-md-6">
                <label for="operacion" class="form-label">Operación</label>
                <select class="form-control" id="operacion" name="operacion">
                    <option value="">Seleccione operación</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="solicitante" class="form-label">Solicitante</label>
                <select class="form-control" id="solicitante" name="solicitante">
                    <option value="">Seleccione responsable</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="f_carga" class="form-label">Fecha de Carga</label>
                <input type="date" class="form-control" id="f_carga" name="f_carga">
            </div>
            <div class="col-md-3">
                <label for="h_carga" class="form-label">Hora de Carga</label>
                <input type="time" class="form-control" id="h_carga" name="h_carga">
            </div>
            <div class="col-md-3">
                <label for="f_descarga" class="form-label">Fecha de Descarga</label>
                <input type="date" class="form-control" id="f_descarga" name="f_descarga">
            </div>
            <div class="col-md-3">
                <label for="h_descarga" class="form-label">Hora de Descarga</label>
                <input type="time" class="form-control" id="h_descarga" name="h_descarga">
            </div>

            <div class="col-md-12">
                <hr>
                <label for="n_repartos" class="form-label">Número de Repartos</label>
                <input type="number" class="form-control w-25" id="n_repartos" name="n_repartos">
            </div>

            <div class="col-md-4"><label class="form-label">Destino Reparto 1</label><input type="text" class="form-control" name="r1"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 2</label><input type="text" class="form-control" name="r2"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 3</label><input type="text" class="form-control" name="r3"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 4</label><input type="text" class="form-control" name="r4"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 5</label><input type="text" class="form-control" name="r5"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 6</label><input type="text" class="form-control" name="r6"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 7</label><input type="text" class="form-control" name="r7"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 8</label><input type="text" class="form-control" name="r8"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 9</label><input type="text" class="form-control" name="r9"></div>
            <div class="col-md-4"><label class="form-label">Destino Reparto 10</label><input type="text" class="form-control" name="r10"></div>

            <div class="col-md-12">
                <label for="cp_sustituye" class="form-label">CP que Sustituye</label>
                <input type="text" class="form-control" id="cp_sustituye" name="cp_sustituye">
            </div>
        </div>
    </div>
</div>