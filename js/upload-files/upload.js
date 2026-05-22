export const uploadBtn = document.getElementById('open-dialog-file-btn');
export const fileInput = document.getElementById('file-input');
export const previewSelectFilesContainer = document.getElementById('preview-select-files-container');
export const textPreviewCameraFiles = document.getElementById("text-preview-camera-files")
export const textPreviewFilesSelected = document.getElementById("text-preview-files-selected")

const btnOpenCamera = document.getElementById("btn-open-camera")
const btnTakePhoto = document.getElementById("btn-take-photo")


export let selectedFiles = [];
let stream = null;

btnOpenCamera.addEventListener("click", openCamera);

async function openCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }
        });

        const video = document.getElementById("video");
        video.srcObject = stream;

        document.getElementById("camera-container").classList.remove("d-none");

    } catch (err) {
        console.error("Error cámara:", err);
        alert("No se pudo acceder a la cámara");
    }
}

btnTakePhoto.addEventListener("click", takePhoto);

export function takePhoto() {
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0);

    canvas.toBlob((blob) => {
        const file = new File([blob], `foto_${Date.now()}.jpg`, {
            type: "image/jpeg"
        });

        addToPreview(file);
        stopCamera();
    }, "image/jpeg");
}

export function addToPreview(file) {
    if (selectedFiles.length >= 10) {
        alert("Máximo 10 imágenes");
        return;
    }

    selectedFiles.push(file);

    textPreviewCameraFiles.classList.remove("d-none")
    const contenedor = document.getElementById("preview-container");

    const col = document.createElement("div");
    col.className = "col-md-3 position-relative";

    const url = URL.createObjectURL(file);

    col.innerHTML = `
        <div class="card shadow">
            <img src="${url}" class="card-img-top" 
                style="height: 150px; object-fit: cover;">
            
            <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-2">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    // eliminar imagen
    col.querySelector("button").addEventListener("click", () => {
        selectedFiles = selectedFiles.filter(f => f !== file);
        col.remove();
        textPreviewCameraFiles.classList.add("d-none")
    });

    contenedor.appendChild(col);
}

export function stopCamera() {

    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }

    document.getElementById("camera-container").classList.add("d-none");
}

uploadBtn.addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', (event) => {
    const files = Array.from(event.target.files);

    if (selectedFiles.length + files.length > 5) {
        alert('Solo puedes subir máximo 5 imágenes');
        return;
    }

    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;

        const fileId = Date.now() + Math.random();

        selectedFiles.push({ id: fileId, file });

        const reader = new FileReader();

        reader.onload = (e) => {
            textPreviewFilesSelected.classList.remove("d-none")
            const card = document.createElement('div');
            card.className = 'card shadow position-relative p-2';
            card.style.width = '120px';

            card.innerHTML = `
                <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                <button 
                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-2 delete-btn"
                    data-id="${fileId}">
                    <i class="bi bi-trash"></i>
                </button>
            `;

            previewSelectFilesContainer.appendChild(card);
        };

        reader.readAsDataURL(file);
    });

    fileInput.value = '';
});

previewSelectFilesContainer.addEventListener('click', (e) => {
    if (e.target.closest('.delete-btn')) {
        const btn = e.target.closest('.delete-btn');
        const id = btn.getAttribute('data-id');

        selectedFiles = selectedFiles.filter(f => f.id != id);

        btn.closest('.card').remove();
        textPreviewFilesSelected.classList.add("d-none")
    }
});

export async function handleSubmit(config) {
    
    const urlDomain = window.location.origin;

    const btn = document.getElementById("send-images");

    try {

        const { 
            tipo_recurso,
            tip_recurso_id,
            modulo_servicio,
            user_id
        } = config

        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = "Subiendo...";

        const formData = new FormData();

        formData.append("tipo_recurso", tipo_recurso);
        formData.append("tipo_recurso_id", tip_recurso_id);
        formData.append("modulo_servicio", modulo_servicio);
        formData.append("user_id", user_id)

        selectedFiles.forEach(item => {
            const file = item.file ? item.file : item;
            formData.append("files[]", file);
        });

        const response = await fetch(`${urlDomain}/public/index.php/api/media`, {
            method: "POST",
            body: formData
        });

        const res = await response.json();

        if (!res.status) {
            throw new Error("Error al subir archivos");
        }

        selectedFiles = [];
        document.getElementById("preview-container").innerHTML = "";

        await Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: "Servicio actualizado correctamente",
            timer: 2000,
            showConfirmButton: false
        });

        return {
            success: true,
            res
        }
        
    } catch (err) {
        
        console.error("Error en upload:", err);

        return {
            success: false,
            error
        };

    } finally {

        btn.disabled = false;
        btn.innerHTML = "Guardar";
    }
}