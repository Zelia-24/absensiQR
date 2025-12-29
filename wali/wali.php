<?php
session_start();
if ($_SESSION['role'] !== 'wali') {
    die("Akses ditolak");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Wali Kelas</title>
    <link rel="stylesheet" href="../../public/css/style_dashboard.css">
</head>
<body>

<header class="dashboard-header">
    <h1>Dashboard Wali Kelas</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Wali Kelas: <?= $_SESSION['name']; ?></h2>

    <div class="card-grid">
        <div class="card">
            <h3>Rekap Absensi</h3>
            <p>Absensi siswa kelas binaan</p>
        </div>
        <div class="card">
            <h3>Laporan Bulanan</h3>
            <p>Unduh laporan kehadiran</p>
        </div>
    </div>
</div>

</body>
</html>
