export function DeliveryDestinationCard(delivery) {

    const colors = {
        Completado: "badge text-bg-success",
        Rechazado: "badge text-bg-danger",
        Pendiente: "badge text-bg-secondary"
    }

    let bgColorStatus = colors[delivery.status] ?? colors.Pendiente

    return `
        <div class="col-12 col-md-6 col-xl-4">
            <div class="bg-white rounded-4 p-3 p-md-4 border shadow-sm h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle border d-flex justify-content-center align-items-center flex-shrink-0"
                            style="width:50px; height:50px;">
                            <i class="bi bi-geo-alt fs-5 text-dark"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Destino</h5>
                            <small class="text-secondary">Punto de llegada</small>
                        </div>
                    </div>
                    <span class="badge rounded-pill ${bgColorStatus} px-3 py-2 fw-normal">
                        ${delivery.status}
                    </span>
                </div>
                <div class="mt-4 pb-3 border-bottom flex-grow-1">
                    <p class="mb-0 text-secondary lh-sm text-break"> Dirección: ${delivery.direccion_destino ?? 'S/D'} </p>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <small class="text-secondary">
                        <i class="bi bi-calendar3 me-1"></i>
                            ${delivery.fecha_creacion ?? 'S/D'}
                    </small>
                    <small class="fw-semibold">
                        ${delivery.status ?? 'S/D'}
                    </small>
                </div>
            </div>
        </div>
    `;
}