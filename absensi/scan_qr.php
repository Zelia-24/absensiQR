<?php
session_start();

/* =====================
   VALIDASI LOGIN SISWA
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Absensi QR Siswa</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
*{box-sizing:border-box;font-family:'Poppins',sans-serif}
body{
    margin:0;
    background:linear-gradient(135deg,#e3f2fd,#bbdefb);
    min-height:100vh
}
.container{
    max-width:900px;
    margin:40px auto;
    padding:0 16px
}
.header{text-align:center;margin-bottom:24px}
.header h2{color:#0d47a1;margin-bottom:6px}
.card{
    background:#fff;
    border-radius:16px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
    padding:28px
}
#preview{
    width:320px;
    height:240px;
    border-radius:12px;
    border:4px solid #bbdefb;
    margin:auto
}
.form{margin-top:20px}
.form input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #90caf9;
    margin-bottom:16px
}
.form button{
    width:100%;
    padding:14px;
    background:#2196f3;
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer
}
.form button:hover{background:#1976d2}
a.back{
    display:block;
    margin-top:20px;
    text-align:center;
    color:#0d47a1;
    text-decoration:none;
    font-weight:600
}
</style>
</head>

<body>
<div class="container">

    <div class="header">
        <h2>Scan QR Absensi</h2>
        <p>Scan QR atau isi kode manual</p>
    </div>

    <div class="card">

        <div id="preview"></div>

        <form method="POST" action="proses_absen.php" class="form">
            <input type="text" name="kode" id="kode"
                   placeholder="Hasil scan QR / input manual" required>

            <button type="submit">
                <i class="fa fa-check-circle"></i> Absen Sekarang
            </button>
        </form>

        <a href="../siswa/siswa.php" class="back">
            ← Kembali ke Dashboard
        </a>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const qr = new Html5Qrcode("preview");

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length > 0) {
            qr.start(
                devices[0].id,
                { fps: 10, qrbox: 220 },
                text => {
                    document.getElementById("kode").value = text;
                }
            );
        }
    }).catch(() => {
        alert("Kamera tidak dapat diakses");
    });
});
</script>

</body>
</html>
