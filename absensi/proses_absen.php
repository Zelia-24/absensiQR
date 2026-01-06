<?php
session_start();
require_once "../config/database.php";

/* =====================
   VALIDASI LOGIN
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak. Silakan login kembali.");
}

/* =====================
   VALIDASI SESSION
===================== */
if (!isset($_SESSION['siswa_id'])) {
    die("Session siswa tidak ditemukan. Silakan login ulang.");
}

/* =====================
   VALIDASI INPUT
===================== */
if (empty($_POST['kode'])) {
    die("Kode absensi tidak boleh kosong");
}

$kode     = trim($_POST['kode']);   // hasil scan QR
$siswa_id = $_SESSION['siswa_id'];
$nis      = $_SESSION['nis'];
$tanggal  = date("Y-m-d");
$jam      = date("H:i:s");

/* =====================
   VALIDASI QR = NIS
===================== */
if ($kode !== $nis) {
    die("❌ QR Code tidak valid untuk akun ini");
}

/* =====================
   CEK ABSENSI HARI INI
===================== */
$cek = $conn->prepare("
    SELECT jam_masuk, jam_keluar
    FROM absensi
    WHERE siswa_id = ? AND tanggal = ?
");
$cek->bind_param("is", $siswa_id, $tanggal);
$cek->execute();
$data = $cek->get_result()->fetch_assoc();

/* =====================
   PROSES ABSENSI
===================== */
if (!$data) {

    $stmt = $conn->prepare("
        INSERT INTO absensi (siswa_id, tanggal, jam_masuk, status)
        VALUES (?, ?, ?, 'Hadir')
    ");
    $stmt->bind_param("iss", $siswa_id, $tanggal, $jam);
    $stmt->execute();

    $pesan = "✅ Absen masuk berhasil";
    $info  = "Jam masuk: $jam";

} elseif (empty($data['jam_keluar'])) {

    $stmt = $conn->prepare("
        UPDATE absensi
        SET jam_keluar = ?
        WHERE siswa_id = ? AND tanggal = ?
    ");
    $stmt->bind_param("sis", $jam, $siswa_id, $tanggal);
    $stmt->execute();

    $pesan = "✅ Absen keluar berhasil";
    $info  = "Jam keluar: $jam";

} else {
    die("⚠️ Kamu sudah absen masuk & keluar hari ini");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Absensi</title>
<style>
body{font-family:Arial;background:#eef2ff;padding:40px}
.box{
    background:#fff;
    max-width:400px;
    margin:auto;
    padding:25px;
    border-radius:14px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.15)
}
a{
    display:inline-block;
    margin-top:18px;
    background:#2563eb;
    color:#fff;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none
}
</style>
</head>
<body>

<div class="box">
    <h3><?= $pesan ?></h3>
    <p><?= $info ?></p>
    <a href="../siswa/dashboard_siswa.php">Kembali ke Dashboard</a>
</div>

</body>
</html>
