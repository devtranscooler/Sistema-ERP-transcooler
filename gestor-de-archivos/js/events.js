import { handleFileSelection } from "./fileHandler.js";
import { updateDropdownText } from "./helper.js";
import { stateVars } from "./state.js";
import { handleRenderFiles, loadFiles } from "./handleRenderFiles.js";
import { 
    fetchMediaFileManager, 
    uploadManagerFile, 
    sendRequestPermission, 
    deletefile 
} from "./api.js";
import { filesNotFound } from "../../js/components/file-manager/FilesNotFound.js";

export function initializeEvents(elements) {

    let fileExtensionFilter = "";

    const {
        inptSearchFile,
        btnSearchFile,
        uploadBtn,
        fileInput,
        filterFileExtension,
        uploadModal,
        selectRequestPermissionUser,
        btnSendFiles,
        btnRequestPermission,
        btnConfirmDelete,
        deleteModal,
        authUserId,
        btnResetSearch
    } = elements;

    btnSearchFile.addEventListener('click', async(e) => {
        e.preventDefault();
        
        const search = inptSearchFile.value.trim();

        if (search.length < 4) {
            alert("Ingresa al menos 4 caracteres");
            return;
        }

        stateVars.searchFileName = search;
        stateVars.currentPage = 1;

        btnResetSearch.classList.remove("d-none");

        await loadFiles(elements);
    })

    uploadBtn.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (event) => {
        handleFileSelection(event, elements);
    });

    filterFileExtension.addEventListener("click", async(e) => {

        e.preventDefault();

        const item = e.target.closest(".dropdown-item");
        if (!item) return;
        
        fileExtensionFilter = item.dataset.fileExtension;
        updateDropdownText(item);

        stateVars.extensionFilter = item.dataset.fileExtension === "all" ? "" : item.dataset.fileExtension;
        stateVars.currentPage = 1;

        updateDropdownText(item);

        await loadFiles(elements);
    });

    btnSendFiles.addEventListener('click', async(e) => {
        e.preventDefault()

        if(stateVars.selectedFiles.length === 0) {
            alert('Debes seleccionar un archivo')
            return
        }

        btnSendFiles.setAttribute("disabled", true)

        try {

            const formData = new FormData();

            formData.append("tipo_recurso", "GESTOR_ARCHIVOS");
            formData.append("tipo_recurso_id", 1);
            formData.append("modulo_servicio", "files-manager");
            formData.append("user_id", authUserId.value)
            stateVars.selectedFiles.forEach(item => {
                const file = item.file ? item.file : item;
                formData.append("files[]", file);
            });

            await uploadManagerFile(formData)

            await loadFiles(elements);

            const modalInstance = bootstrap.Modal.getInstance(elements.uploadModal);
            modalInstance?.hide();

            stateVars.selectedFiles = []

            await Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Archivo guradado con éxito",
                timer: 2000,
                showConfirmButton: false
            });


        } catch (error) {
            
            console.error(error)
            await Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "Hubo un error al guardar tu archivo, intentalo de nuevo más tarde",
                timer: 2000,
            });

        } finally {
            btnSendFiles.removeAttribute("disabled")
        }
    })

    uploadModal.addEventListener('hidden.bs.modal', () => {
        elements.containerFilesPreview.innerHTML = '';
        elements.fileInput.value = '';
        stateVars.selectedFiles = [];
    });

    selectRequestPermissionUser.addEventListener('change', (event) => {
        stateVars.userAprobedId = event.target.value
    });

    btnRequestPermission.addEventListener('click', async(e) => {

        e.preventDefault()

        if(!stateVars.userAprobedId) {
            return alert('Debes seleccionar un usuario'); 
        }

        btnRequestPermission.setAttribute("disabled", true)
        
        try {

            const formData = new FormData();

            formData.append("usuario_solicitante_id", parseInt(authUserId.value))
            formData.append("usuario_aprobador_id", parseInt(stateVars.userAprobedId))
            formData.append("media_id", parseInt(stateVars.selectedFileToRequestPermission))
            formData.append("estatus", 'pendiente')

            await sendRequestPermission(formData)

            await loadFiles(elements);

            const modalInstance = bootstrap.Modal.getInstance(elements.requestPermissionModal);
            modalInstance?.hide();

            stateVars.userAprobedId = null
            stateVars.selectedFileToRequestPermission = null

            await Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Solicitud enviada con éxito",
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {
            console.error(error)
            await Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: error.message,
                timer: 2000,
            });
        } finally {
            btnRequestPermission.removeAttribute("disabled")
        }
    })

    btnConfirmDelete.addEventListener("click", async(e) => {

        if(!stateVars.selectedFileToDelete) return
        
        try {

            await deletefile(stateVars.selectedFileToDelete)

            await loadFiles(elements);

            const modalInstance = bootstrap.Modal.getInstance(elements.deleteModal);
            modalInstance?.hide();
            
            stateVars.selectedFileToDelete = null;
            
            await Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "Archivo eliminado con éxito",
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {
            console.error(error)
            await Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "Hubo un error al eliminar el archivo, intentalo de nuevo más tarde",
                timer: 2000,
            });
        }
    })

    deleteModal.addEventListener('hidden.bs.modal', () => {
        stateVars.selectedFileToDelete = null;
    });

    btnResetSearch.addEventListener('click', async(e) => {
        e.preventDefault()
        
        btnResetSearch.classList.add("d-none")
        inptSearchFile.value = ""
        stateVars.searchFileName = ''

        await loadFiles(elements);
    })
}