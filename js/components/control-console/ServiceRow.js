export const fileItemService = (itemService) => {

    return `
    <tr>
        <td>
            <div class="fw-bold">
                ${itemService?.placas_unidad ?? 'S/P'}
            </div>

            <small class="text-muted">
                Tipo unidad: <strong>${itemService?.tipo_unidad ?? 'S/I'}</strong>
            </small>
        </td>
        <td> 
            
            <div class="fw-bold">
                Servicio: #${itemService?.id_servicio ?? 0}
            </div>

            <small class="text-muted">
                Tipo servicio: <strong> ${itemService?.tipo_servicio ?? 'S/I' }  </strong>
            </small>
        </td>
        <td> 
            <div class="d-flex flex-column gap-1">
                <span class="badge rounded-pill bg-primary w-75"> 
                    <i class="bi bi-truck me-1"></i> ${itemService?.nombre_etapa_servicio ?? 'Sin estatus' } 
                </span> 
                <span  class="badge rounded-pill bg-light text-dark border w-75"> ${itemService?.nombre_estatus_etapa_servicio ?? 'Sin estatus' } </span>
            </div>
        </td>
        <td> ${itemService?.nombre_operador ?? 'S/I' } ${itemService?.tipo_cliente ?? 'S/C'} </td>
        <td>
            <div>
                ${itemService?.fecha_descarga ?? 'S/I'}
            </div>

        </td>
        <td> 
            <span class="${bgColorByDaysRange(itemService?.dias_transcurridos_desde_descarga)}">
                ${itemService?.dias_transcurridos_desde_descarga ?? 'S/I' } 
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-start gap-2 gap-md-1">
                <button 
                    class="btn btn-sm btn-secondary rounded-circle open-detail-log-service-modal" 
                    data-bs-toggle="tooltip" 
                    title="Ver historial"
                    data-item-service-id="${itemService?.id_servicio ?? 0}"
                    data-item-nombre-operador="${itemService?.nombre_operador ?? 'S/I'}"
                    data-item-placas-unidad="${itemService?.placas_unidad ?? 'S/I'}"
                    data-item-tipo-unidad="${itemService?.tipo_unidad ?? 'S/I'}">
                        <i class="bi bi-clock-history"></i>
                </button>
                ${
                    itemService?.etapa_servicio_log_id !== null
                        ? `
                            <button 
                                class="btn btn-sm btn-dark rounded-circle open-send-reminder-service-modal"
                                data-bs-toggle="tooltip" 
                                title="Enviar correo de alerta"
                                data-item-service-alert-id="${itemService?.id_servicio ?? 0}"
                                data-item-service-alert-nombre-operador="${itemService?.nombre_operador ?? 'S/I'}"
                                data-item-service-alert-placas-unidad="${itemService?.placas_unidad ?? 'S/I'}"
                                data-item-service-alert-tipo-unidad="${itemService?.tipo_unidad ?? 'S/I'}">
                                    <i class="bi bi-send-plus"></i>
                            </button>`
                        : ''
                }
                <button 
                    class="btn btn-sm btn-primary rounded-circle open-assign-stage-service-modal"
                    data-bs-toggle="tooltip" 
                    title="Actualizar estatus de servicio"
                    data-stage-service-service-id="${itemService?.id_servicio ?? 0}"
                    data-stage-service-nombre-operador="${itemService?.nombre_operador ?? 'S/I'}"
                    data-stage-service-placas-unidad="${itemService?.placas_unidad ?? 'S/I'}"
                    data-stage-service-tipo-unidad="${itemService?.tipo_unidad ?? 'S/I'}">
                        <i class="bi bi-ui-checks"></i>
                </button>
            </div>
        </td>
    </tr>`
}

const bgColorByDaysRange = (numberDays) => {

    const colors = {
        0: 'badge text-bg-success',
        1: 'badge text-bg-success',
        2: 'badge text-bg-success',
        3: 'badge text-bg-warning',
        4: 'badge text-bg-danger'
    } 

    return numberDays >= 4 ? colors[4] : colors[numberDays]
}
