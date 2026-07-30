import { fetchConsoleControlServices, fetchControlConsoleUnitType } from "./api.js"
import { getElements } from "./elements.js"
import { initializeEvents } from "./events.js"
import { handleDetailLogService } from "./handleDetailLogServiceModal.js"
import { handleReminderEmailService } from "./handleReminderEmailService.js"
import { loadServices } from "./handleRenderServices.js"
import { stateVars } from "./state.js"
import { handleStageServiceUpdate } from './handleStageServiceUpdate.js'
import { renderUnitTypeOptions } from "./helper.js"


let elements = null

document.addEventListener('DOMContentLoaded', async() => {

    elements = getElements();

    const unitTypes = await fetchControlConsoleUnitType();
    renderUnitTypeOptions(unitTypes);

    initializeEvents(elements);

    await loadServices(elements)
})

document.addEventListener("click", async(event) => {

    const pageBtn = event.target.closest('.pagination-btn');
    
    if (pageBtn) {
        stateVars.currentPage = Number(pageBtn.dataset.page);
        await loadServices(elements);
        return;
    }

    /** Modal detalle log servicio */
    const detailLogService = event.target.closest(".open-detail-log-service-modal");

    if (detailLogService) {
        await handleDetailLogService(detailLogService, elements);
        return;
    }

    /** Modal enviar recordatorio etapa servicio */
    const reminderEmailModal = event.target.closest('.open-send-reminder-service-modal')
    
    if(reminderEmailModal) {
        await handleReminderEmailService(reminderEmailModal, elements)
        return
    }

    /** Modal actualizar estatus de etapa de servicio */
    const stageServiceUpdateModal = event.target.closest('.open-assign-stage-service-modal')

    if(stageServiceUpdateModal) {
        handleStageServiceUpdate(stageServiceUpdateModal, elements)
        return
    }
});
