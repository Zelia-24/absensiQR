const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");
const result = document.getElementById("scan-result");

function scanQR() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            result.innerHTML = "QR Terdeteksi: " + code.data;
            kirimAbsen(code.data);
            return;
        }
    }
    requestAnimationFrame(scanQR);
}

function kirimAbsen(nis) {
    fetch("scan_qr.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "nis=" + encodeURIComponent(nis)
    })
    .then(res => res.text())
    .then(data => {
        result.innerHTML = data;
    });
}

scanQR();
