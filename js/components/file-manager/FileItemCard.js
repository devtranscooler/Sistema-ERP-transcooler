import { getIconExtension } from "../../../gestor-de-archivos/js/helper.js"
import { getElements } from "../../../gestor-de-archivos/js/elements.js";

export const fileItemCard = (itemFile) => {

    const elements  = getElements()

    const preview = getIconExtension(itemFile?.extension);
    
    let content = '';
    
    if (preview.type === 'image') {

        const imageUrl = `https://storage.googleapis.com/transcooler/${itemFile?.ruta}`;

        content = `
            <img
                src="${imageUrl}"
                class="img-fluid rounded"
                style="width:100%;height:100%;object-fit:cover;"
                loading="lazy">
        `;
    } else {
        content = `
            <i class="bi ${preview.icon} fs-2 ${preview.color}"></i>
        `;
    }

    return `
        <div class="col-12 my-2">
            <div class="card bg-light rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div class="border rounded-3 py-1 px-2 d-flex justify-content-center align-items-center bg-white" style="width: 3.2rem; height: 3.2rem">
                                ${content}
                            </div>
                            <span> 
                                ${ itemFile?.nombre_origen ?? 'Desconocido' } 
                                    <span class="d-block" style="font-size: 12px;"> <i class="bi bi-calendar-event"></i> ${itemFile?.fecha_carga.slice(0, 10) } </span>
                            </span>
                        </div>
                        <div class="dropdown">
                            <!-- Botón circular -->
                            <button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-1 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 30px; height: 30px;">
                                <i class="bi bi-three-dots"></i>
                            </button>                            
                            <ul class="dropdown-menu">
                                ${permissionFileOption(itemFile)}  
                                <li> 
                                    <a 
                                        class="dropdown-item open-file-info-modal" 
                                        data-file-info-id="${itemFile?.media_id}"> 
                                            <i class="bi bi-info-circle"></i> Información del archivo </a>
                                    </li>                      
                                ${parseInt(elements.authUserId.value) === parseInt(itemFile?.id_usuario_creador) 
                                    ? `
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a 
                                                class="dropdown-item open-delete-file-modal" 
                                                data-file-delete-id="${itemFile?.media_id}"
                                                data-file-delete-name="${itemFile?.nombre_origen}"> 
                                                    <i class="bi bi-trash"></i> Eliminar 
                                            </a>
                                        </li>
                                    `
                                    :''
                                }
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `
}

const permissionFileOption = (itemFile) => {

    const downloadUrl = `https://storage.googleapis.com/transcooler/${itemFile?.ruta}`
    
    const options = {
        pendiente: `<li> <a class="dropdown-item"> <i class="bi bi-arrow-clockwise"></i> Solicitud pendiente </a></li>`,
        rechazado: `<li> <a class="dropdown-item"> <i class="bi bi-ban"></i> Descarga rechazada </a></li>`,
        aprobado: `
            <li> 
                <a class="dropdown-item" href="${downloadUrl}" download target="_blank"> 
                    <i class="bi bi-cloud-arrow-down"></i> Descargar 
                </a>
            </li>`,
        default: `
        <li> 
            <a class="dropdown-item open-request-permission-download-modal" data-media-id="${itemFile?.media_id}"> 
                <i class="bi bi-unlock2"></i> Solicitar descarga 
            </a>
        </li>`,
    }

    return options[itemFile?.media_solicitud_estatus] ?? options.default
}
