<?php
session_start();
require_once __DIR__ . "/../config/database.php";

if (
    !isset($_SESSION['user_id'], $_SESSION['role']) ||
    $_SESSION['role'] !== 'siswa'
) {
    die("Akses ditolak");
}

$siswa_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM absensi
    WHERE siswa_id = ?
    ORDER BY tanggal DESC
");
$stmt->bind_param("i", $siswa_id);
$stmt->execute();
$result = $stmt->get_result();
?>
