export const recordLogsServicesDetail = (logService) => {
    return `
    <div class="position-relative mb-4">
        <div class="position-absolute rounded-circle bg-primary" style="width:14px; height:14px; left:-20px; top:5px;"></div>
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-primary mb-2"> ${logService?.nombre_etapa_servicio ?? 'S/I' } </span>
                        <div class="fw-bold"> ${logService?.nombre_estatus_etapa_servicio ?? 'S/I' } </div>
                    </div>
                    <small class="text-muted"> ${logService?.fecha_creacion_log ?? 'SIN FECHA DE REGISTRO' } </small>
                </div>
                <small class="text-muted"> Registrado por: ${logService?.nombre_usuario_registro_log ?? 'S/I' } </small>
                <p class="mb-0 mt-2 small"> Tracking: ${logService?.tracking ?? 'S/I' } </p>
            </div>
        </div>
    </div>
    `
}