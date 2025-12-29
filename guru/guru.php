<?php
session_start();
if ($_SESSION['role'] !== 'guru') {
    die("Akses ditolak");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Guru</title>
    <link rel="stylesheet" href="../../public/css/style_dashboard.css">
</head>
<body>

<header class="dashboard-header">
    <h1>Dashboard Guru</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Halo, <?= $_SESSION['name']; ?></h2>

    <div class="card-grid">
        <div class="card">
            <h3>Absensi Siswa</h3>
            <p>Lihat absensi per kelas</p>
        </div>
        <div class="card">
            <h3>Jadwal Mengajar</h3>
            <p>Informasi jadwal harian</p>
        </div>
    </div>
</div>

</body>
</html>
