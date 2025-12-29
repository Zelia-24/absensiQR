<?php
require_once "../config/database.php";

$siswa_id = $_GET['siswa_id'];
$jam = date("H:i:s");
$tgl = date("Y-m-d");

// insert absen masuk
$stmt = $conn->prepare("
    INSERT INTO absensi (siswa_id, tanggal, jam_masuk)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iss", $siswa_id, $tgl, $jam);

if ($stmt->execute()) {
    echo "<h3>Absen Masuk Berhasil</h3>";
    echo "<p>Jam: $jam</p>";
} else {
    echo "Gagal absen atau sudah absen hari ini";
}
