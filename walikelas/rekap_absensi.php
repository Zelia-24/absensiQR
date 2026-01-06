<?php
session_start();
require_once "../config/database.php"; // sesuaikan path database

if ($_SESSION['role'] !== 'walikelas') {
    die("Akses ditolak");
}

// Ambil kelas wali
$kelas_id = $_SESSION['kelas_id'] ?? null;

if (!$kelas_id) {
    die("Kelas tidak ditemukan");
}

// Query absensi
$query = "SELECT s.nama, a.tanggal, a.status 
          FROM absensi a
          JOIN siswa s ON a.siswa_id = s.siswa_id
          WHERE s.kelas_id = ?
          ORDER BY a.tanggal DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $kelas_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #e6f0ff; }
        table { border-collapse: collapse; width: 100%; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #1e3a8a; color: #fff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        h2 { color: #1e3a8a; margin-bottom: 20px; }
        a { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #2563eb; }
    </style>
</head>
<body>

<h2>Rekap Absensi Kelas Anda</h2>
<a href="dashboard_walikelas.php">← Kembali ke Dashboard</a>

<table>
    <tr>
        <th>Nama Siswa</th>
        <th>Tanggal</th>
        <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= htmlspecialchars($row['tanggal']); ?></td>
        <td><?= htmlspecialchars($row['status']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
