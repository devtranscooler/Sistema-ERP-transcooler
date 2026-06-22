export const fileDelete = (fileName) => {
    return `
        <div class="p-3">
            <div class="fs-2 text-danger d-flex justify-content-center">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <p class="text-center fs-6"> 
                ¿Estas seguro de querer eliminar este archivo ?
                    <span class="d-block fw-bold fs-5"> ${fileName ?? 'Unknown'} </span>
            </p>
        </div>
    `;
}