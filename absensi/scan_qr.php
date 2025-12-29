<?php
require_once "../config/database.php";

if (!isset($_POST['nis'])) die("QR tidak valid");

$nis = $_POST['nis'];
$tgl = date("Y-m-d");

// cari siswa
$siswa = $conn->query("
    SELECT siswa_id FROM siswa 
    WHERE nis='$nis' AND is_active=1
")->fetch_assoc();

if (!$siswa) die("Siswa tidak ditemukan");

$siswa_id = $siswa['siswa_id'];

// cek sudah absen?
$cek = $conn->query("
    SELECT * FROM absensi 
    WHERE siswa_id=$siswa_id AND tanggal='$tgl'
");

if ($cek->num_rows == 0) {
    header("Location: absen_masuk.php?siswa_id=$siswa_id");
} else {
    header("Location: absen_keluar.php?siswa_id=$siswa_id");
}
exit;
