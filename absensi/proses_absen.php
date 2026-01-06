<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =====================
   VALIDASI LOGIN SISWA
===================== */
if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'siswa' ||
    !isset($_SESSION['siswa_id'])
) {
    die("Akses ditolak. Silakan login kembali.");
}

/* =====================
   VALIDASI INPUT
===================== */
if (!isset($_POST['kode']) || trim($_POST['kode']) === '') {
    die("Kode absensi tidak boleh kosong");
}

$kode     = trim($_POST['kode']);
$siswa_id = (int) $_SESSION['siswa_id'];

$tanggal = date("Y-m-d");
$jam     = date("H:i:s");

/* =====================
   CEK KODE ABSENSI
===================== */
$cekKode = $conn->prepare("
    SELECT kode 
    FROM absensi_kode
    WHERE kode = ? AND aktif = 1
    LIMIT 1
");
$cekKode->bind_param("s", $kode);
$cekKode->execute();
$kodeValid = $cekKode->get_result()->fetch_assoc();

if (!$kodeValid) {
    die("❌ Kode absensi tidak valid atau sudah tidak aktif");
}

/* =====================
   CEK ABSENSI HARI INI
===================== */
$cekAbsen = $conn->prepare("
    SELECT jam_masuk, jam_keluar
    FROM absensi
    WHERE siswa_id = ? AND tanggal = ?
    LIMIT 1
");
$cekAbsen->bind_param("is", $siswa_id, $tanggal);
$cekAbsen->execute();
$absen = $cekAbsen->get_result()->fetch_assoc();

/* =====================
   PROSES ABSENSI
===================== */
if (!$absen) {

    // ABSEN MASUK
    $stmt = $conn->prepare("
        INSERT INTO absensi (siswa_id, tanggal, jam_masuk, status)
        VALUES (?, ?, ?, 'Hadir')
    ");
    $stmt->bind_param("iss", $siswa_id, $tanggal, $jam);
    $stmt->execute();

    $pesan = "✅ Absen masuk berhasil";
    $info  = "Jam masuk: $jam";

} elseif (empty($absen['jam_keluar'])) {

    // ABSEN KELUAR
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
    die("⚠️ Kamu sudah melakukan absen masuk dan keluar hari ini");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Absensi</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{
    background:#f2f7ff;
    font-family:Poppins,sans-serif;
    padding:40px
}
.card{
    background:#fff;
    max-width:420px;
    margin:auto;
    padding:30px;
    border-radius:18px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
    text-align:center
}
h3{color:#2a6df4}
p{margin:10px 0}
a{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#fff;
    background:#2a6df4;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600
}
</style>
</head>
<body>

<div class="card">
    <h3><?= htmlspecialchars($pesan) ?></h3>
    <p><?= htmlspecialchars($info) ?></p>

    <a href="../siswa/dashboard_siswa.php">Kembali ke Dashboard</a>
</div>

</body>
</html>
