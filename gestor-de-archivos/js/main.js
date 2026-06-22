import { getElements } from "./elements.js";
import { initializeEvents } from "./events.js";
import { fetchMediaFileManager, fetchUsersAdmin } from "./api.js";
import { optionPermissionUser } from "../../js/components/file-manager/OptionPermissionUser.js";
import { handleRequestPermission } from "./handleRequestPermission.js";
import { handleRemovePreviewFile } from "./fileHandler.js";
import { handleFileDetail } from "./handleFileDetail.js";
import { handleRenderFiles, loadFiles } from "./handleRenderFiles.js";
import { handleFileDelete } from "./handleFileDelete.js";
import { stateVars } from "./state.js";

let elements = null

document.addEventListener('DOMContentLoaded', async() => {

    elements = getElements();

    initializeEvents(elements);

    await loadFiles(elements);
});


document.addEventListener("click", async(event) => {

    const pageBtn = event.target.closest('.pagination-btn');

    if (pageBtn) {
        stateVars.currentPage = Number(pageBtn.dataset.page);
        await loadFiles(elements);
        return;
    }

    /** Modal solicitud de descarga */
    const permissionTrigger = event.target.closest(".open-request-permission-download-modal");

    if (permissionTrigger) {
        await handleRequestPermission(permissionTrigger, elements);
        return;
    }

    /** Modal información archivo */
    const fileDetailTrigger = event.target.closest(".open-file-info-modal");

    if(fileDetailTrigger) {
        await handleFileDetail(fileDetailTrigger, elements)
        return
    }

    /** Boton Eliminar archivo */
    const removeTrigger = event.target.closest(".btn-remove-file");

    if (removeTrigger) {
        handleRemovePreviewFile(removeTrigger, elements);
        return;
    }
    
    /** Modal eliminar archivo */
    const fileDeleteTrigger = event.target.closest('.open-delete-file-modal')

    if(fileDeleteTrigger) {
        handleFileDelete(fileDeleteTrigger, elements, stateVars.selectedFileToDelete)
        return
    }
    
});
