<?php
session_start();
require_once "../config/database.php";

/* =====================
   VALIDASI LOGIN
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak");
}

/* =====================
   AMBIL DATA ABSENSI
===================== */
$siswa_id = $_SESSION['siswa_id'];

$data = $conn->query("
    SELECT *
    FROM absensi
    WHERE siswa_id = $siswa_id
    ORDER BY tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Absensi</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:Poppins,sans-serif;
    background:#f2f7ff;
    padding:40px
}
.card{
    background:#fff;
    max-width:800px;
    margin:auto;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.1)
}
h3{color:#2a6df4;text-align:center}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px
}
th,td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:center
}
th{
    background:#f2f7ff
}
a{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#2a6df4;
    font-weight:600
}
.btn{
    background:#2a6df4;
    color:#fff;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px
}
</style>
</head>

<body>

<div class="card">
    <h3>📊 Riwayat Kehadiran</h3>

    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
        </tr>

        <?php if ($data->num_rows > 0): ?>
            <?php while ($a = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $a['tanggal'] ?></td>
                    <td><?= $a['jam_masuk'] ?? '-' ?></td>
                    <td><?= $a['jam_keluar'] ?? '-' ?></td>
                    <td><?= $a['status'] ?? 'Hadir' ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada data absensi</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="export_excel.php" class="btn">⬇ Export Excel</a>
    <br><br>
    <a href="../siswa/dashboard_siswa.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>
