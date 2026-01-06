<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] !== 'walikelas') {
    die("Akses ditolak");
}

$kelas_id = $_SESSION['kelas_id'] ?? null;
if (!$kelas_id) {
    die("Kelas tidak ditemukan");
}

// Ambil bulan dari query param atau default bulan ini
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// Query absensi per bulan
$query = "SELECT s.nama, COUNT(CASE WHEN a.status='Hadir' THEN 1 END) AS hadir,
                 COUNT(CASE WHEN a.status='Sakit' THEN 1 END) AS sakit,
                 COUNT(CASE WHEN a.status='Izin' THEN 1 END) AS izin,
                 COUNT(CASE WHEN a.status='Alpa' THEN 1 END) AS alpa
          FROM siswa s
          LEFT JOIN absensi a ON s.siswa_id = a.siswa_id AND MONTH(a.tanggal)=? AND YEAR(a.tanggal)=?
          WHERE s.kelas_id = ?
          GROUP BY s.siswa_id";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $bulan, $tahun, $kelas_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #e6f0ff; }
        table { border-collapse: collapse; width: 100%; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #1e3a8a; color: #fff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        h2 { color: #1e3a8a; margin-bottom: 20px; }
        a { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #2563eb; }
    </style>
</head>
<body>

<h2>Laporan Absensi Bulanan</h2>
<a href="dashboard_walikelas.php">← Kembali ke Dashboard</a>

<form method="get" style="margin-bottom:20px;">
    <label>Bulan: <input type="number" name="bulan" value="<?= $bulan; ?>" min="1" max="12"></label>
    <label>Tahun: <input type="number" name="tahun" value="<?= $tahun; ?>" min="2000" max="<?= date('Y'); ?>"></label>
    <button type="submit">Tampilkan</button>
</form>

<table>
    <tr>
        <th>Nama Siswa</th>
        <th>Hadir</th>
        <th>Sakit</th>
        <th>Izin</th>
        <th>Alpa</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= $row['hadir']; ?></td>
        <td><?= $row['sakit']; ?></td>
        <td><?= $row['izin']; ?></td>
        <td><?= $row['alpa']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
