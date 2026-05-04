<?php
    $data = $_POST;

    $id_servicio = json_decode($_POST['servicio'] ?? '{}', true);  

?>

    <style>
        .pulse-button {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
                0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
            }
                70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }
                100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }
    </style>

    <div class="modal-header dar-salida-modal">
        <h5 class="modal-title">
            <i class="bi bi-arrow-return-right"></i> Dar Salida
            <!-- <span class="badge bg-danger ms-2" style="font-size: 0.75rem">Modal prueba</span> -->
        </h5>
    </div>

    <div class="modal-body dar-salida-modal">

        <!-- Begin Upload File Options -->
        <div class="d-flex justify-content-center align-items-center">
            <ul class="nav nav-pills mb-3 border p-2 bg-light rounded-pill shadow" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">
                        <i class="bi bi-camera me-2"></i> 
                            Abrir camara
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link gx-2 rounded-pill" id="pills-images-tab" data-bs-toggle="pill" data-bs-target="#pills-images" type="button" role="tab" aria-controls="pills-images" aria-selected="false">
                        <i class="bi bi-images me-2"></i> 
                            Seleccionar
                    </button>
                </li>
            </ul>
        </div>
        <!-- End Upload File Options -->


        <!-- Container Tabs -->
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">

                <!-- Begin Camera Photo Preview -->
                <div id="preview-container" class="row g-2 row-cols-2 row-cols-sm-3 row-cols-md-4"></div>
                <!-- Begin Camera Photo Preview -->
                
                <!-- Begin Camera Preview -->
                <div class="row">
                    <div class="d-flex flex-column align-items-center mt-4 mt-md-5 d-none px-2 px-sm-0" id="camera-container">
                        
                        <video 
                            id="video" 
                            autoplay 
                            playsinline 
                            class="w-100 w-sm-75 w-md-50 rounded-3" 
                            style="max-height: 270px; border-radius: 2rem; margin-top: 2rem;"></video>

                        <button type="button" class="btn btn-success mt-2 rounded-5" id="btn-take-photo">
                            Tomar foto
                        </button>

                    </div>
                </div>
                <!-- End Camera Preview -->

                <!-- Begin Canvas -->
                <div class="row mt-2">
                    <canvas id="canvas" class="d-none"></canvas>
                </div>
                <!-- Begin Canvas -->
                

            </div>

            <div class="tab-pane fade" id="pills-images" role="tabpanel" aria-labelledby="pills-images-tab">
                <div class="p-3 mt-2 rounded-3">
                    <div class="d-flex flex-column align-items-center mt-3">
                        <button class="btn btn-success rounded-circle pulse-button" style="cursor: pointer;" id="openDialogFilesBtn">
                            <i class="bi bi-cloud-upload text-white" style="font-size: 4rem;"></i>
                        </button>
                        <input 
                            type="file" 
                            id="fileInput" 
                            accept="image/*" 
                            multiple 
                            hidden>
                        <span class="mt-2 text-center"> Haz clic en el botón de arriba para seleccionar tus archivos </span>
                        <span class="mt-2 small text-secondary"> Archivos permitidios: <i class="bi bi-images"></i> imagenes </span>
                    </div>
                </div>

                <div id="previewSelectFilesContainer" class="mt-3 d-flex gap-2 flex-wrap"></div>
            </div>

        </div>
        <!-- End Container Tabs -->
        
    </div>

    <div class="modal-footer dar-salida-modal">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="close-modal">
            <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
        <button type="button" class="btn btn-primary" id="send-images">
            Guardar
        </button>
    </div>

    <script>

        (function () {

            const uploadBtn = document.getElementById('openDialogFilesBtn');
            const fileInput = document.getElementById('fileInput');
            const previewSelectFilesContainer = document.getElementById('previewSelectFilesContainer');
        
            let selectedFiles = [];
            let stream = null;

            
            document.getElementById("pills-general-tab").addEventListener("click", openCamera);

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

            document.getElementById("btn-take-photo").addEventListener("click", takePhoto);

            function takePhoto() {
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

            function addToPreview(file) {
                if (selectedFiles.length >= 10) {
                    alert("Máximo 10 imágenes");
                    return;
                }

                selectedFiles.push(file);

                const contenedor = document.getElementById("preview-container");

                const col = document.createElement("div");
                col.className = "col-md-3 position-relative";

                const url = URL.createObjectURL(file);

                col.innerHTML = `
                    <div class="card shadow">
                        <img src="${url}" class="card-img-top" 
                            style="height: 150px; object-fit: cover;">
                        
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-2">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                `;

                // eliminar imagen
                col.querySelector("button").addEventListener("click", () => {
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    col.remove();
                });

                contenedor.appendChild(col);
            }

            function stopCamera() {
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
                        const card = document.createElement('div');
                        card.className = 'card shadow position-relative p-2';
                        card.style.width = '120px';

                        card.innerHTML = `
                            <img src="${e.target.result}" class="card-img-top"
                                style="height: 100px; object-fit: cover;">

                            <button 
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-2 delete-btn"
                                data-id="${fileId}">
                                <i class="bi bi-trash3"></i>
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
                }
            });

            document.getElementById("send-images").addEventListener("click", handleSubmit)

            async function handleSubmit() {
                const urlDomain = window.location.origin;
                const servicioId = <?= $id_servicio ?>;

                const btn = document.getElementById("send-images");

                if (selectedFiles.length === 0) {
                    return alert("Debes tomar o seleccionar al menos una foto");
                }

                try {

                    btn.disabled = true;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = "Subiendo...";

                    const formData = new FormData();

                    formData.append("tipo_recurso", "SERVICIO");
                    formData.append("tipo_recurso_id", servicioId);
                    formData.append("modulo_servicio", "arribo");

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

                    await updateStatus(servicioId);

                    selectedFiles = [];
                    document.getElementById("preview-container").innerHTML = "";

                    window.dispatchEvent(new Event("salida:recargar"));

                    closeModal();

                    await Swal.fire({
                        icon: "success",
                        title: "¡Éxito!",
                        text: "Servicio actualizado correctamente",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    

                } catch (err) {
                    console.error("Error en upload:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: err.message || "Ocurrió un error en el proceso"
                    });
                } finally {

                    btn.disabled = false;
                    btn.innerHTML = "Guardar";
                }
            }

            async function updateStatus(id) {
                const urlDomain = window.location.origin

                try {
                    const response = await fetch(`${urlDomain}/workflow/servicio_cliente/servicios.api.php`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=actualizarTracking&id=${id}&tracking=Finalizado`
                    });

                    const res = await response.json();

                    if (!res.success) {
                        throw new Error("Error al actualizar estatus");
                    }

                    return res;

                } catch (err) {
                    console.error("Error updateStatus:", err);
                    throw err;
                }
            }

            function closeModal() {
                const modalElement = document.getElementById("salidaModal");

                const modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (modalInstance) {
                    modalInstance.hide();
                }
            }

        })();

    </script>
