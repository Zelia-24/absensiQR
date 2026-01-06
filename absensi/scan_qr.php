<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Absensi QR Siswa</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- ICON -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<!-- QR -->
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
*{
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    margin:0;
    background:linear-gradient(135deg,#e3f2fd,#bbdefb);
    min-height:100vh;
}

/* NAVBAR */
.navbar{
    background:#2196f3;
    color:#fff;
    padding:14px 24px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar .logo{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:600;
}

.navbar img{
    width:36px;
}

.navbar a{
    color:#fff;
    text-decoration:none;
    font-weight:500;
}

/* CONTAINER */
.container{
    max-width:900px;
    margin:40px auto;
    padding:0 16px;
}

/* HEADER */
.header{
    text-align:center;
    margin-bottom:24px;
}

.header h2{
    color:#0d47a1;
    margin-bottom:6px;
}

.header p{
    color:#555;
}

/* CARD */
.card{
    background:#fff;
    border-radius:16px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
    padding:28px;
}

/* QR BOX */
.qr-box{
    display:flex;
    justify-content:center;
    margin-bottom:20px;
}

#preview{
    width:320px;
    height:240px;
    border-radius:12px;
    overflow:hidden;
    border:4px solid #bbdefb;
}

/* FORM */
.form{
    margin-top:20px;
}

.form label{
    font-weight:600;
    color:#0d47a1;
}

.form input{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:16px;
    border-radius:10px;
    border:1px solid #90caf9;
    font-size:15px;
}

.form input:focus{
    outline:none;
    border-color:#2196f3;
}

.form button{
    width:100%;
    padding:14px;
    background:#2196f3;
    color:#fff;
    border:none;
    border-radius:12px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
}

.form button:hover{
    background:#1976d2;
}

/* FOOTER */
.footer{
    text-align:center;
    margin-top:30px;
    color:#555;
    font-size:14px;
}
</style>
</head>
<body>

<!-- CONTENT -->
<div class="container">

    <div class="header">
        <h2>Selamat Datang 👋</h2>
        <p>Silakan scan QR Code atau masukkan kode absensi</p>
    </div>

    <div class="card">

        <div class="qr-box">
            <div id="preview"></div>
        </div>

        <form method="post" action="../proses_absen.php" class="form">
            <label>Kode Absensi</label>
            <input type="text" name="kode" id="kode"
                   placeholder="Hasil scan QR / input manual" required>

            <button type="submit">
                <i class="fa fa-check-circle"></i> Absen Sekarang
            </button>
        </form>

    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> Sistem Absensi QR
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const html5QrCode = new Html5Qrcode("preview");

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length) {
            html5QrCode.start(
                devices[0].id,
                { fps: 10, qrbox: { width: 220, height: 220 } },
                qrCodeMessage => {
                    document.getElementById("kode").value = qrCodeMessage;
                }
            );
        }
    }).catch(err => {
        alert("Tidak bisa mengakses kamera");
        console.error(err);
    });

});
</script>

</body>
</html>
