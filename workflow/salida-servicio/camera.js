function openCamera(videoElement) {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            videoElement.srcObject = stream;
            videoElement.play();
            window.currentStream = stream; // opcional para detener luego
        })
        .catch((error) => {
            console.error("Error cámara:", error);
            alert("No se pudo acceder a la cámara");
        });
}