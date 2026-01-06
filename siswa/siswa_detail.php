<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* =====================
   VALIDASI LOGIN SISWA
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: /absensiQR/index.php");
    exit;
}

/* =====================
   TENTUKAN ID SISWA
===================== */
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
} elseif (!empty($_SESSION['siswa_id'])) {
    $id = (int) $_SESSION['siswa_id'];
} else {
    header("Location: /absensiQR/siswa/dashboard_siswa.php");
    exit;
}

/* =====================
   AMBIL DATA SISWA
===================== */
$stmt = $conn->prepare("
    SELECT 
        s.nis,
        s.nama,
        s.qr_code,
        k.nama_kelas,
        j.nama_jurusan
    FROM siswa s
    JOIN kelas k ON s.kelas_id = k.kelas_id
    JOIN jurusan j ON k.jurusan_id = j.jurusan_id
    WHERE s.siswa_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    die("Data siswa tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Siswa</title>

<style>
body{
    font-family: Poppins, sans-serif;
    background:#f2f7ff;
    padding:40px
}
.card{
    background:#fff;
    max-width:500px;
    margin:auto;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.1)
}
h3{
    color:#2a6df4;
    text-align:center;
    margin-bottom:20px
}
p{margin:8px 0;font-size:15px}
.qr{text-align:center;margin-top:25px}
img{
    border:1px solid #ddd;
    padding:8px;
    border-radius:12px;
    background:#fff
}
a{
    display:block;
    text-align:center;
    margin-top:25px;
    text-decoration:none;
    color:#2a6df4;
    font-weight:600
}
</style>
</head>

<body>

<div class="card">
    <h3>Detail Siswa</h3>

    <p><b>NIS</b> : <?= htmlspecialchars($data['nis']) ?></p>
    <p><b>Nama</b> : <?= htmlspecialchars($data['nama']) ?></p>
    <p><b>Kelas</b> : <?= htmlspecialchars($data['nama_kelas']) ?></p>
    <p><b>Jurusan</b> : <?= htmlspecialchars($data['nama_jurusan']) ?></p>

    <?php if (!empty($data['qr_code'])): ?>
        <div class="qr">
            <img src="/absensiQR/<?= htmlspecialchars($data['qr_code']) ?>" width="200">
        </div>
    <?php endif; ?>

    <a href="/absensiQR/siswa/siswa.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>
