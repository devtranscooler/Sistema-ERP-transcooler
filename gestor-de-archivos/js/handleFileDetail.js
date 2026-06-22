import { fileDetail } from "../../js/components/file-manager/FileDetail.js";
import { fetchFileDetail } from "./api.js";

const modalFileDetail = new bootstrap.Modal(document.getElementById('fileDetailModal'));

export const handleFileDetail = async(trigger, elements) => {
    const mediaId = trigger.dataset.fileInfoId;
    modalFileDetail.show();

    const itemDetailFile = await fetchFileDetail(mediaId)

    elements.containerFileDetail.innerHTML = '';
    elements.containerFileDetail.insertAdjacentHTML('beforeend', fileDetail(itemDetailFile));

    return mediaId;
}