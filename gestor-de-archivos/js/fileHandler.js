import { filePreviewCard } from "../../js/components/file-manager/FilePreviewCard.js";
import { stateVars } from "./state.js";

export function handleFileSelection(event, elements) {

    const {
        containerFilesPreview,
        fileInput
    } = elements;

    const files = Array.from(event.target.files);

    files.forEach(file => {

        const fileId = crypto.randomUUID();

        stateVars.selectedFiles.push({
            id: fileId,
            file
        });

        const wrapper = document.createElement('div');

        wrapper.innerHTML = filePreviewCard(file, fileId);

        containerFilesPreview.appendChild(wrapper);
    });

    fileInput.value = '';
}

export const removeSelectedFile = (fileId, container) => {

    stateVars.selectedFiles = stateVars.selectedFiles.filter(
        item => item.id !== fileId
    );

    const card = container.querySelector(`[data-file-id="${fileId}"]`);

    card?.remove();
};

export const handleRemovePreviewFile = (trigger, elements) => {

    const fileId = trigger.dataset.fileId;

    removeSelectedFile(fileId, elements.containerFilesPreview);
};