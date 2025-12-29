<?php
require_once "../config/database.php";

$siswa_id = $_GET['siswa_id'];
$jam = date("H:i:s");
$tgl = date("Y-m-d");

// update jam keluar
$stmt = $conn->prepare("
    UPDATE absensi 
    SET jam_keluar=? 
    WHERE siswa_id=? AND tanggal=?
");
$stmt->bind_param("sis", $jam, $siswa_id, $tgl);

if ($stmt->execute()) {
    echo "<h3>Absen Keluar Berhasil</h3>";
    echo "<p>Jam: $jam</p>";
} else {
    echo "Gagal absen keluar";
}
