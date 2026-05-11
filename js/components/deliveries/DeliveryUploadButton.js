export function DeliveryUploadButton(delivery) {
    return `
        <div class="d-flex flex-column justify-content-center align-items-center text-center flex-grow-1 mt-4">
            <button
                class="btn btn-primary rounded-circle pulse-button d-flex justify-content-center align-items-center flex-shrink-0 open-evidence-modal"
                style="width:70px; height:70px; cursor:pointer;"
                data-delivery-id="${delivery.id_reparto}">
                    <i class="bi bi-cloud-upload text-white fs-2"></i>
            </button>
            <span class="mt-3">
                Haz clic en el botón verde para seleccionar tus imagenes
            </span>
            <span class="mt-2 small text-secondary">
                Archivos permitidios:
                    <i class="bi bi-images"></i>
                        imagenes
            </span>
        </div>
    `;
}