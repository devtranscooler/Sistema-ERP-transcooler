export function DeliveryEmptyCard(deliveriesContainer) {

    deliveriesContainer.innerHTML = "";

    const html = `
        <div class="row p-3 my-3">
            <h2 class="text-secondary text-center fs-3"> 
                No tienes repartos asignados por el momento, intentalo de nuevo más tarde 
                    <span class="d-block"> <i class="bi bi-truck-flatbed fs-1"></i> </span>
            </h2>
        </div>
    `;

    return deliveriesContainer.innerHTML = html
}