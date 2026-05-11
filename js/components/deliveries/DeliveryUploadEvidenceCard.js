import { DeliveryCompletedCard } from "./DeliveryCompletedCard.js"
import { DeliveryRejectedCard } from "./DeliveryRejectedCard.js"
import { DeliveryUploadButton } from "./DeliveryUploadButton.js"

export function DeliveryUploadEvidenceCard(delivery) {


    const buttonsType = {
        Completado: DeliveryCompletedCard(),
        Rechazado: DeliveryRejectedCard(),
        Pendiente: DeliveryUploadButton(delivery)
    }

    let btnStatus = buttonsType[delivery.status] ?? buttonsType.Pendiente

    return `
        <div class="col-12 col-md-6 col-xl-4">
            <div class="bg-white rounded-4 p-3 p-md-4 border shadow-sm h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle border d-flex justify-content-center align-items-center flex-shrink-0"
                            style="width:50px; height:50px;">
                            <i class="bi bi-camera fs-5 text-dark"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Adjuntar evidencia</h5>
                            <small class="text-secondary">
                                Agrega imagenes de evidencia
                            </small>
                        </div>
                    </div>
                </div>
                ${btnStatus}
            </div>
        </div>
    `;
}