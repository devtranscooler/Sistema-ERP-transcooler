import { fecthConsoleControlStageServices } from "./api.js";
import { stateVars } from "./state.js";

const modalStageServiceUpdate = new bootstrap.Modal(document.getElementById('stageServicestatusUpdateModal'));
const selectUpdateStatusEtapaServicio = document.getElementById('update_status_etapa_servicio_id');

export const handleStageServiceUpdate = async(trigger, elements) => {
    const serviceId = trigger.dataset.stageServiceServiceId;
    stateVars.stageServiceSelectedId = serviceId;

    const currentEtapaServicioId = trigger.dataset.stageServiceEtapaServicioId;
    const currentEtapaServicioEstatusId = trigger.dataset.stageServiceEtapaServicioEstatusId;

    modalStageServiceUpdate.show();

    if(!serviceId) return

    //obtenemos el listado de etapas y estatus
    const getStageServices = await fecthConsoleControlStageServices();
    if(!getStageServices) return

    elements.selectUpdateEtapaServicio.innerHTML = '<option value="0">Selecciona una etapa</option>'
    selectUpdateStatusEtapaServicio.innerHTML = '<option value="0">Selecciona un estatus</option>'

    const etapasAgregadas = new Set();
    //primer select de etapas
    getStageServices.forEach(stage => {
        if (currentEtapaServicioId && Number(stage.etapa_id) < Number(currentEtapaServicioId)) return;

        if (!etapasAgregadas.has(stage.etapa_id)) {
            etapasAgregadas.add(stage.etapa_id);
            elements.selectUpdateEtapaServicio.insertAdjacentHTML('beforeend', `<option value="${stage.etapa_id}">${stage.nombre_etapa}</option>`);
        }
    });

    //segundo select de estatus
    const fillStatusOptions = (etapaId) => {
        selectUpdateStatusEtapaServicio.innerHTML = '<option value="0">Selecciona un estatus</option>'

        const esEtapaActual = currentEtapaServicioId && Number(etapaId) === Number(currentEtapaServicioId);

        getStageServices.forEach(function (stage) {
            if (stage.etapa_servicio_id != etapaId) return;

            if (esEtapaActual && currentEtapaServicioEstatusId && Number(stage.estatus_id) < Number(currentEtapaServicioEstatusId)) return;

            selectUpdateStatusEtapaServicio.innerHTML +=
                "<option value='" + stage.estatus_id + "'>" + stage.nombre_estatus + "</option>";
        });
    };

    elements.selectUpdateEtapaServicio.onchange = (event) => {
        fillStatusOptions(event.target.value);
    };

    //etapa y estatus actuales del servicio
    if (currentEtapaServicioId) {
        console.log(currentEtapaServicioId);
        elements.selectUpdateEtapaServicio.value = currentEtapaServicioId;
        fillStatusOptions(currentEtapaServicioId);

        if (currentEtapaServicioEstatusId) {
             console.log(currentEtapaServicioEstatusId);
            selectUpdateStatusEtapaServicio.value = currentEtapaServicioEstatusId;
        }
    }
}