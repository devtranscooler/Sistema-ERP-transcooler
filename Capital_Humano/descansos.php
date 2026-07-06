<?php

session_start();

$idUsuario = $_SESSION['ID_USUARIO'];

require_once __DIR__ . '/controllers/SolicitudesControlador.php';

$solicitudes = SolicitudesControlador::listarSolicitudesOperador($idUsuario);

?>

<!DOCTYPE html>
                </select>
            </div>

            <div class="col-md-3">
                <label>Fecha inicio</label>
                <input type="date" id="fecha_inicio" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Fecha fin</label>
                <input type="date" id="fecha_fin" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Comentario</label>
                <input type="text" id="comentario" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary mt-3" onclick="guardarSolicitud()">
            Enviar solicitud
        </button>

    </div>


    <h3>Mis solicitudes</h3>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Días</th>
                <th>Estatus</th>
            </tr>
        </thead>

        <tbody>

            <?php while($row = $solicitudes->fetch_assoc()){ ?>

                <tr>
                    <td><?= $row['id_solicitud'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= $row['fecha_inicio'] ?></td>
                    <td><?= $row['fecha_fin'] ?></td>
                    <td><?= $row['dias_solicitados'] ?></td>
                    <td><?= $row['estatus'] ?></td>
                </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/Capital_Humano/assets/js/operador.js"></script>

</body>
</html>