<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal SMP Pelita Bumi</title>
    <link rel="stylesheet" href="public/css/style1.css">
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="logo-container">
        <img src="public/images/logo.png" alt="Logo Sekolah" class="logo">
        <h1 class="school-name">SMP Pelita Bumi</h1>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">

    <!-- Hero -->
    <section class="info-sekolah">
        <h2>Portal Sistem Akademik & Absensi</h2>
        <p>
            Selamat datang di <strong>SMP Pelita Bumi</strong>, sekolah menengah pertama
            yang berkomitmen membentuk generasi berprestasi, berkarakter, dan berakhlak mulia.
        </p>
        <p>
            Sistem ini digunakan untuk <strong>absensi siswa berbasis QR Code</strong>,
            monitoring kehadiran, dan pengelolaan data akademik.
        </p>
    </section>

    <!-- Role Login -->
    <section class="role-section">
        <h3>Login Sesuai Peran</h3>
        <div class="role-grid">

            <a href="auth/login.php?role=siswa" class="role-card">
                <span>👨‍🎓</span>
                <h4>Siswa</h4>
                <p>Melakukan absensi dan melihat riwayat kehadiran</p>
            </a>

            <a href="auth/login.php?role=guru" class="role-card">
                <span>👩‍🏫</span>
                <h4>Guru</h4>
                <p>Monitoring absensi dan data kelas</p>
            </a>

            <a href="auth/login.php?role=wali" class="role-card">
                <span>🧑‍🏫</span>
                <h4>Wali Kelas</h4>
                <p>Rekap kehadiran siswa per kelas</p>
            </a>

            <a href="auth/login.php?role=admin" class="role-card">
                <span>🛠️</span>
                <h4>Admin</h4>
                <p>Kelola pengguna dan sistem</p>
            </a>

        </div>
    </section>

</main>

<!-- Footer -->
<footer class="footer">
    &copy; <?= date('Y') ?> SMP Pelita Bumi | Sistem Informasi Sekolah
</footer>

</body>
</html>
