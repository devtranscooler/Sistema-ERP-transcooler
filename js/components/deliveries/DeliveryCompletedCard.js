export function DeliveryCompletedCard() {
    return `
        <div class="d-flex flex-column justify-content-center align-items-center text-center flex-grow-1 mt-4">
            <button
                class="btn btn-success rounded-circle d-flex justify-content-center align-items-center flex-shrink-0"
                style="width:70px; height:70px; cursor:not-allowed;">
                    <i class="bi bi-check2-circle fs-2"></i>
            </button>
            <span class="mt-3">
                Reparto completado
            </span>
        </div>
    `;
}