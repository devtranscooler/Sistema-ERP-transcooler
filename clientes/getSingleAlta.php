<div class="modal-header">
    <h5 class="modal-title" id="addModalLabel">Alta de Cliente</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="addForm"  enctype="multipart/form-data">
    <input type="hidden" id="id" name="id">
    <div class="row">
            <div class="col-md-4 ">
                <label for="nombre_razon" class="form-label">Nombre o Razon Social</label>
                <input type="text" class="form-control" id="nombre_razon" name="nombre_razon" required>
            </div>
            <div class="col-md-4">
                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                <select class="form-select" id="tipo_cliente" name="tipo_cliente" required>
                    <option value="">Selecciona un Tipo de Cliente</option>
                    <option value="Dedicado">Dedicado</option>
                    <option value="Spot">Spot</option>
                </select>
            </div>
            <div class="col-md-4">
                <!--label for="regimen" class="form-label">Regimen</label>
                <select class="form-select" id="regimen" name="regimen" required>
                    <option value="">Selecciona una Regimen</option>
                    <option value="Publico en General">Publico en General</option>
                    <option value="Persona Moral">Persona Moral</option>
                    <option value="Persona Fisica con Actividad Empresarial">Persona Fisica con Actividad Empresarial</option>
                </select-->
            </div>
    </div>
    <br>
    <div class="row">
                <div class="col-md-8">
                <label for="calle" class="form-label">Calle</label>
                <input type="text" class="form-control" id="calle" name="calle">
            </div>
            <div class="col-md-4">
                <!--label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" id="municipio" name="municipio"-->
            </div>
            <!--div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado">
            </div-->
    </div>
    <br>
    <div class="row">
            <div class="col-md-4">
                <label for="num_ext" class="form-label">Numero Exterior</label>
                <input type="text" class="form-control" id="num_ext" name="num_ext">
            </div>
            <div class="col-md-4">
                <label for="num_int" class="form-label">Numero Interior</label>
                <input type="text" class="form-control" id="num_int" name="num_int">
            </div>
            <div class="col-md-4">
                <label for="codigo_postal" class="form-label">Codigo Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
            </div>
    </div>
    <br>
    <!--div class="row">
            <div class="col-md-4">
                <label for="credito" class="form-label">Credito</label>
                <input type="text" class="form-control" id="credito" name="credito">
            </div>
                <div class="col-md-4">
                <label for="stp" class="form-label">STP</label>
                <input type="text" class="form-control" id="stp" name="stp">
            </div>
    </div-->
    <br>
        <button type="submit" id="saveButton" class="btn btn-success">Guardar</button>

        <button type="button" id="updateButton" class="btn btn-primary" style="display: none;">Actualizar</button>
    </form>
</div>