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
} elseif (!empty($_SESSION['user_id'])) {
    $id = (int) $_SESSION['user_id'];
} else {
    header("Location: /absensiQR/siswa/siswa.php");
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
    WHERE s.user_id = ?
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
<title>Profil Siswa</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    box-sizing:border-box;
    font-family:'Poppins',sans-serif
}
body{
    margin:0;
    background:linear-gradient(135deg,#e8f0ff,#f6f9ff);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px
}
.card{
    background:#fff;
    width:100%;
    max-width:720px;
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,.1);
    overflow:hidden
}
.header{
    background:linear-gradient(135deg,#2a6df4,#4f8dff);
    color:#fff;
    padding:30px;
    text-align:center
}
.header h2{
    margin:0;
    font-weight:600
}
.header p{
    margin-top:6px;
    opacity:.9;
    font-size:14px
}
.content{
    padding:30px
}
.info{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:30px
}
.item{
    background:#f4f7ff;
    padding:16px 18px;
    border-radius:14px
}
.item span{
    display:block;
    font-size:12px;
    color:#6b7280;
    margin-bottom:4px
}
.item strong{
    font-size:15px;
    color:#111827
}
.qr{
    text-align:center;
    margin-top:10px
}
.qr img{
    width:200px;
    padding:12px;
    background:#fff;
    border-radius:16px;
    border:1px solid #e5e7eb;
    box-shadow:0 8px 20px rgba(0,0,0,.08)
}
.footer{
    padding:20px 30px;
    background:#f9fafb;
    text-align:center
}
.footer a{
    display:inline-block;
    background:#2a6df4;
    color:#fff;
    padding:12px 28px;
    border-radius:999px;
    text-decoration:none;
    font-weight:500;
    transition:.3s
}
.footer a:hover{
    background:#1f5de0
}

@media(max-width:600px){
    .info{
        grid-template-columns:1fr
    }
}
</style>
</head>

<body>

<div class="card">
    <div class="header">
        <h2>Profil Siswa</h2>
        <p>Sistem Absensi QR Code</p>
    </div>

    <div class="content">
        <div class="info">
            <div class="item">
                <span>NIS</span>
                <strong><?= htmlspecialchars($data['nis']) ?></strong>
            </div>
            <div class="item">
                <span>Nama Lengkap</span>
                <strong><?= htmlspecialchars($data['nama']) ?></strong>
            </div>
            <div class="item">
                <span>Kelas</span>
                <strong><?= htmlspecialchars($data['nama_kelas']) ?></strong>
            </div>
            <div class="item">
                <span>Jurusan</span>
                <strong><?= htmlspecialchars($data['nama_jurusan']) ?></strong>
            </div>
        </div>

        <?php if (!empty($data['qr_code'])): ?>
        <div class="qr">
            <img src="/absensiQR/<?= htmlspecialchars($data['qr_code']) ?>" alt="QR Code Siswa">
        </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <a href="/absensiQR/siswa/siswa.php">Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>
