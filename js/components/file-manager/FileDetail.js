export const fileDetail = (itemFile) => {
    return `
        <div class="p-2">
            <p class="border-bottom" style="font-size: 12px;"> 
                Nombre de archivo <span class="d-block fs-6 fw-bold"> ${itemFile?.nombre_origen ?? 'Unknown'} </span> 
            </p>
            <p class="border-bottom" style="font-size: 12px;"> 
                Tipo <span class="d-block fs-6 fw-bold"> ${itemFile?.extension ?? 'Unknown'} </span> 
            </p>
            <p class="border-bottom" style="font-size: 12px;"> 
                Subido por <span class="d-block fs-6 fw-bold"> ${itemFile?.nombre_usuario_carga ?? 'Unknown'} </span> 
            </p>
            <p class="border-bottom" style="font-size: 12px;"> 
                Creado <span class="d-block fs-6 fw-bold"> ${itemFile?.created_at ?itemFile?.created_at.slice(0, 10) : 'Unknown'} </span> 
            </p>
        </div>
    `
}