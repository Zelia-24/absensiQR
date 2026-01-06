<?php
session_start();
require "../config/database.php";

$kode = $_POST['kode'] ?? '';

if (!$kode) {
    die("Kode absensi tidak boleh kosong");
}

/* VALIDASI QR */
if (!isset($_SESSION['kode_absen']) || $kode !== $_SESSION['kode_absen']) {
    die("Kode QR tidak valid atau sudah kadaluarsa");
}

/* JIKA BELUM LOGIN → LOGIN DULU */
if (!isset($_SESSION['user'])) {
    $_SESSION['pending_absen'] = true;
    header("Location: ../auth/login.php");
    exit;
}

/* AMBIL DATA SISWA */
$q = mysqli_query($conn,"
    SELECT siswa_id FROM siswa
    WHERE user_id=".$_SESSION['user']['id']."
");
$s = mysqli_fetch_assoc($q);

if (!$s) {
    die("Akun tidak terhubung dengan data siswa");
}

/* SIMPAN ABSENSI */
mysqli_query($conn,"
    INSERT INTO absensi (siswa_id,tanggal,jam_masuk,status)
    VALUES ({$s['siswa_id']},CURDATE(),CURTIME(),'Hadir')
");

unset($_SESSION['pending_absen']);

echo "<h2>✅ Absensi berhasil</h2>";
echo "<a href='index.php'>Kembali</a>";
