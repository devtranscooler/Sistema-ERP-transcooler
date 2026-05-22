import { DeliveryCard } from "../js/components/deliveries/DeliveryCard.js";
import { DeliveryEmptyCard } from "../js/components/deliveries/DeliveryEmptyCard.js";
import { DeliveryProductCard } from "../js/components/deliveries/DeliveryProductCard.js";
import { DeliveryEmptyProductCard } from "../js/components/deliveries/DeliveryEmptyProductCard.js";

import { 
    uploadBtn,
    fileInput,
    previewSelectFilesContainer,
    selectedFiles,
    handleSubmit 
} from "../js/upload-files/upload.js";

const deliveriesContainer = document.getElementById('deliveriesContainer')
const productsContainer = document.getElementById("products-delivery-container")
const authUser = document.getElementById("auth_user_id").value

const evidenceModal = new bootstrap.Modal(
    document.getElementById("evidenceModal")
);

const productsModal = new bootstrap.Modal(
    document.getElementById("productsModal")
)

const evidenceInput = document.getElementById("evidenceInput");
const uploadEvidenceBtn = document.getElementById("uploadEvidenceBtn");
const evidenceModalTitle = document.getElementById("evidenceModalTitle");

export const sendForm = document.getElementById("send-images")

let deliverySelected = null;
let deliveryProductSelected = null;
let deliveryStatusSelected = "Completado";

document.addEventListener("DOMContentLoaded", async () => {
    await getDeliveries();
});

async function getDeliveries() {
    try {

        const urlDomain = window.location.origin;

        const response = await fetch(`${urlDomain}/public/index.php/api/operator-deliveries/${authUser}`);

        const data = await response.json();

        if(response.ok){
            return DeliveryCard(deliveriesContainer, data.data)
        } 

        return DeliveryEmptyCard(deliveriesContainer)
        
    } catch (error) {
        console.error(error)
    }
}

document.addEventListener("click", (event) => {

    const button = event.target.closest(".open-evidence-modal");

    if (!button) return;

    deliverySelected = button.dataset.deliveryId;

    evidenceModalTitle.innerHTML = `
        <i class="bi bi-cloud-upload"></i>
            Adjuntar evidencia para reparto ${deliverySelected}
    `;

    evidenceModal.show();

});

/** Modal Productos */
document.addEventListener("click", (event) => {

    /** Modal Productos */
    const buttonProductDelivery = event.target.closest(".open-products-modal");

    if (buttonProductDelivery){
        deliveryProductSelected = buttonProductDelivery.dataset.prodDeliveryId;

        productsModal.innerHTML = `
            <i class="bi bi-cloud-upload"></i>
                Productos de reparto
        `;

        productsModal.show();
        getProductsByDelivery(deliveryProductSelected)
    }
    /** Termina Modal Productos */

    /** Estatus Reparto */
    const statusButton = event.target.closest(".delivery-status-btn");

    if (statusButton) {

        document.querySelectorAll(".delivery-status-btn").forEach(btn => {
            btn.classList.remove("active-status", "btn-dark", "btn-danger");
            btn.classList.add("btn-light", "border");
        });

        const status = statusButton.dataset.status;

        if (status === "Completado") {
            statusButton.classList.add("active-status", "btn-dark", "text-success");
        }

        if (status === "Rechazado") {
            statusButton.classList.add("active-status", "btn-dark", "text-danger");
        }

        statusButton.classList.remove("btn-light");

        deliveryStatusSelected = status;
    }
    /** Termina Estatus Reparto */

});
/** Termina Modal Productos */


sendForm.addEventListener("click", async(e) => {

    if (selectedFiles.length === 0) {
        return alert("Debes tomar o seleccionar al menos una foto desde main.js");
    }

    try {

        const uploadPromise = handleSubmit({
            tipo_recurso: "REPARTOS",
            tip_recurso_id: deliverySelected,
            modulo_servicio: "repartos",
            user_id: authUser
        });

        const statusPromise = updateDeliveryStatus(
            deliverySelected,
            deliveryStatusSelected
        );

        const [
            uploadResult,
            statusResult
        ] = await Promise.all([
            uploadPromise,
            statusPromise
        ]);

        evidenceModal.hide();

        Swal.fire({
            icon: "success",
            title: "Completado",
            text: "Evidencias y estatus actualizados"
        });
        
    } catch (error) {
        console.error(error);

        Swal.fire({
            icon: "error",
            title: "Error",
            text:
                error.message ||
                "Ocurrió un error en el proceso"
        });
    } finally {
        
    }
})

async function getProductsByDelivery(deliveryId) {
    try {

        const urlDomain = window.location.origin;

        const response = await fetch(`${urlDomain}/public/index.php/api/products-delivery/${deliveryId}`);

        const data = await response.json();

        if(response.ok){
            return DeliveryProductCard(productsContainer, data.data)
        } 

        return DeliveryEmptyProductCard()
        
    } catch (error) {
        console.error(error)
    } 
}


async function updateDeliveryStatus(deliveryId, statusSelected) {

    const urlDomain = window.location.origin;

    try {

        const response = await fetch(`${urlDomain}/public/index.php/api/deliveries/${deliveryId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                status: statusSelected
            })
        });

        const res = await response.json();

        if (!response.ok) {
            throw new Error(res.message || "Error al actualizar status");
        }

        return res;
        
    } catch (error) {
        console.error(error)
        throw error;
    }
}