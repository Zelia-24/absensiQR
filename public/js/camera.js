const video = document.getElementById("camera");

function startCamera() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Browser tidak mendukung kamera");
        return;
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(stream => {
            video.srcObject = stream;
            video.play();
        })
        .catch(err => {
            console.error(err);
            alert("Kamera tidak bisa diakses");
        });
}

window.onload = startCamera;
