    <div class="modal-header dar-salida-modal">
        <h5 class="modal-title">
            <i class="bi bi-file-earmark-excel"></i> Dar Salida
            <!-- <span class="badge bg-danger ms-2" style="font-size: 0.75rem">Modal prueba</span> -->
        </h5>
    </div>

    <div class="modal-body dar-salida-modal">

        <!-- Begin Upload File Options -->
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="border rounded-pill p-2 d-flex shadow-sm">
                    <button type="button" class="btn btn-primary rounded-pill fw-bold" id="openCamera"> 
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

        <div class="row">
            <div class="d-flex flex-column align-items-center mt-6">
                <video id="video" autoplay playsinline class="w-50 rounded-3"></video>
                <button type="button" class="btn btn-success mt-2 rounded-5" id="takePhoto">
                        Tomar foto
                </button>
                <canvas id="canvas" class="d-none"></canvas>
                <img id="preview" class="img-fluid mt-2 d-none" />
            </div>
        </div>

    </div>

    <div class="modal-footer dar-salida-modal">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
    </div>
