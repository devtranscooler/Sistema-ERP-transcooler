import { DeliveryOriginCard } from "./DeliveryOriginCard.js";
import { DeliveryDestinationCard } from "./DeliveryDestinationCard.js";
import { DeliveryUploadEvidenceCard } from "./DeliveryUploadEvidenceCard.js";

export function DeliveryCard(container, deliveries) {

    deliveriesContainer.innerHTML = "";

    const html = deliveries.map(delivery => {
        return `
            <div class="row p-3 my-3">
                <div class="col-md-12 border bg-light p-3 rounded-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-secondary"> Reparto ${delivery.numero_reparto} </button>
                        <button 
                            class="btn btn-dark rounded-pill open-products-modal"
                            data-prod-delivery-id="${delivery.id_reparto}"> 
                                Productos a entregar 
                        </button>
                    </div>
                    <div class="my-3 px-0">
                        <div class="row g-4">
                            ${DeliveryOriginCard(delivery)}
                            ${DeliveryDestinationCard(delivery)}
                            ${DeliveryUploadEvidenceCard(delivery)}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join("")

    return deliveriesContainer.innerHTML = html;
}