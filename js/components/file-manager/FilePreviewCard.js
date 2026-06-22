import { getFilePreview } from "../../../gestor-de-archivos/js/helper.js"

export const filePreviewCard = (file, fileId) => {

    const { name, type } = file

    const preview = getFilePreview(file);

    let content = '';

    if (preview.type === 'image') {

        const imageUrl = URL.createObjectURL(file);

        content = `
            <img
                src="${imageUrl}"
                class="img-fluid rounded"
                style="width:100%;height:100%;object-fit:cover;"
            >
        `;
    } else {
        content = `
            <i class="bi ${preview.icon} fs-2 ${preview.color}"></i>
        `;
    }

    return `<div class="w-100 border bg-light rounded-3 my-3" data-file-id="${fileId}">
        <div class="d-flex justify-content-between align-items-center p-1">
            <div class="d-flex justify-content-between align-items-center gap-2 px-1">
                <div class="border rounded-3 py-1 px-3 d-flex justify-content-center align-items-center bg-white" style="width: 3rem; height: 3rem">
                    ${content}
                </div>
                <span> 
                    ${name}
                        <span class="d-block" style="font-size: 12px"> ${type} </span>
                </span>
            </div>
            <button class="btn btn-light btn-sm rounded-circle text-danger btn-remove-file" data-file-id="${fileId}">
                <i class="bi bi-trash fs-3"></i>
            </button>
        </div>
    </div>`
}
