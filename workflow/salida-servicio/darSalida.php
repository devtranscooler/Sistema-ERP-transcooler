<?php
    $data = $_POST;

    $id_servicio = $data['id'] ?? null;
?>

    <div class="modal-header dar-salida-modal">
        <h5 class="modal-title">
            <i class="bi bi-arrow-return-right"></i> Dar Salida
            <!-- <span class="badge bg-danger ms-2" style="font-size: 0.75rem">Modal prueba</span> -->
        </h5>
    </div>

    <div class="modal-body dar-salida-modal">

        <!-- Begin Upload File Options -->
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="border rounded-pill p-2 d-flex shadow-sm">
                    <button type="button" class="btn btn-primary rounded-pill fw-bold" id="open-camera"> 
                        <i class="bi bi-camera me-2"></i>
                            Abrir camara 
                    </button>
                    <button type="button" class="btn"> 
                        <i class="bi bi-image me-2"></i>
                            Seleccionar 
                    </button>
                </div>
            </div>
        </div>
        <!-- End Upload File Options -->

        <!-- Begin Camera Preview -->
        <div class="row">
            <div class="d-flex flex-column align-items-center mt-6 d-none" id="camera-container">
                <video id="video" autoplay playsinline class="w-50 rounded-3" style="max-height: 270px; border-radius: 2rem; margin-top: 2rem;"></video>
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

        <!-- Begin Camera Photo Preview -->
        <div id="preview-container" class="row g-2"></div>
        <!-- Begin Camera Photo Preview -->
        
    </div>

    <div class="modal-footer dar-salida-modal">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="close-modal">
            <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
        <button type="button" class="btn btn-primary" id="send-images">
            Guardar
        </button>
    </div>

    <script>

        (function () {
        
            let selectedFiles = [];
            let stream = null;

            
            document.getElementById("open-camera").addEventListener("click", openCamera);

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

            document.getElementById("send-images").addEventListener("click", handleSubmit)

            async function handleSubmit() {
                const urlDomain = window.location.origin;
                const servicioId = <?= $id_servicio ?>;

                const btn = document.getElementById("send-images");

                // if (selectedFiles.length === 0) {
                //     return alert("Debes tomar o seleccionar al menos una foto");
                // }

                try {

                    btn.disabled = true;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = "Subiendo...";

                    const formData = new FormData();

                    formData.append("tipo_recurso", "SERVICIO");
                    formData.append("tipo_recurso_id", servicioId);
                    formData.append("modulo_servicio", "arribo");

                    selectedFiles.forEach(file => {
                        formData.append("file[]", file);
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
