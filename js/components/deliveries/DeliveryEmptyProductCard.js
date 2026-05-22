export function DeliveryEmptyProductCard(productsContainer) {

    productsContainer.innerHTML = "";

    const html = `
        <div class="row p-3 my-3">
            <h2 class="text-secondary text-center fs-3"> 
                Reparto sin productos asignados
                    <span class="d-block"> <i class="bi bi-truck-flatbed fs-1"></i> </span>
            </h2>
        </div>
    `;

    return productsContainer.innerHTML = html
}