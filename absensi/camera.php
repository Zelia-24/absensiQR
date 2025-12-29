<?php
require_once "../config/database.php";
?>

<h3>Scan QR Absensi</h3>

<video id="preview" width="300"></video>

<form method="POST" action="scan_qr.php">
    <input type="hidden" name="nis" id="nis">
</form>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const scanner = new Html5Qrcode("preview");

scanner.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    qrCodeMessage => {
        document.getElementById("nis").value = qrCodeMessage;
        document.forms[0].submit();
        scanner.stop();
    }
);
</script>
