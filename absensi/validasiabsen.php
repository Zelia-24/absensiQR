<?php
require_once "../config/database.php";

$siswa_id = $_GET['siswa_id'];
$tgl = date("Y-m-d");

$data = $conn->query("
    SELECT * FROM absensi 
    WHERE siswa_id=$siswa_id AND tanggal='$tgl'
")->fetch_assoc();

if (!$data) {
    echo "Belum absen hari ini";
    exit;
}

echo "<h3>Status Absensi Hari Ini</h3>";
echo "Masuk : " . $data['jam_masuk'] . "<br>";
echo "Keluar: " . $data['jam_keluar'] . "<br>";
echo "Status: " . $data['status'] . "<br>";
