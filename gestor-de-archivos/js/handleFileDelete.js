import { fileDelete } from "../../js/components/file-manager/FileDelete.js";
import { stateVars } from "./state.js";

const modalFileDelete = new bootstrap.Modal(document.getElementById('fileDeleteModal'));

export const handleFileDelete = (trigger, elements, selectedFileToDelete) => {

    stateVars.selectedFileToDelete = trigger.dataset.fileDeleteId;

    const fileName = trigger.dataset.fileDeleteName

    modalFileDelete.show();

    elements.containerFileDelete.innerHTML = '';
    elements.containerFileDelete.insertAdjacentHTML('beforeend', fileDelete(fileName));

    return stateVars.selectedFileToDelete
}