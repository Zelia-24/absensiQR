<?php
session_start();
require_once __DIR__ . "/../config/database.php";

/* Ambil siswa_id dari session */
$siswa_id = $_SESSION['siswa_id'] ?? null;

if (!$siswa_id) {
    die("Akses ditolak. Siswa belum login.");
}

/* Query absensi */
$query = "
    SELECT *
    FROM absensi
    WHERE siswa_id = '$siswa_id'
    ORDER BY tanggal DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>
