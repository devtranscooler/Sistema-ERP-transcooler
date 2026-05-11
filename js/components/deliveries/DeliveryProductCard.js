export function DeliveryProductCard(productsContainer, products) {

    productsContainer.html = "";

    const html = products.map((product, index) => {
        const isLast = index === products.length - 1;
        return `
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center gap-2 px-2 py-3 ${!isLast ? 'border-bottom pb-3 mb-3' : ''} flex-wrap">
                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                            <div
                                class="bg-light rounded-4 border d-flex justify-content-center align-items-center flex-shrink-0"
                                style="width:48px; height:48px;">
                                <i class="bi bi-archive fs-6 text-secondary"></i>
                            </div>
                            <div class="min-w-0">
                                <h6 class="mb-0 fw-semibold text-truncate">
                                    ${product.nombre ?? 'S/D'}
                                </h6>
                                <small class="text-secondary text-truncate d-block">
                                    ${product.nombre_cliente ?? 'S/D'} · Clave: ${product.clave ?? 'S/D'}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <div class="bg-light rounded-3 px-2 py-1 border text-center">
                                <small class="text-secondary d-block lh-1">
                                    Peso
                                </small>
                                <small class="fw-medium text-dark">
                                    ${product.peso ?? 'S/D'}
                                </small>
                            </div>
                            <div class="bg-light rounded-3 px-2 py-1 border text-center">
                                <small class="text-secondary d-block lh-1">
                                    Cantidad
                                </small>
                                <small class="fw-medium text-dark">
                                    ${product.cantidad ?? 'S/D'}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    `;
    }).join("")

    return productsContainer.innerHTML = html
}