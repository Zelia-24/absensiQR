<?php
/* =====================================================
   DASHBOARD SISWA – SINGLE FILE FINAL
===================================================== */

session_start();
require_once "../config/database.php";

/* =====================
   VALIDASI AKSES
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak");
}

/* =====================
   AMBIL DATA SISWA
===================== */
$siswa = $conn->query("
    SELECT s.*, k.nama_kelas
    FROM siswa s
    JOIN kelas k ON s.kelas_id = k.kelas_id
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- STYLE -->
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: #f2f7ff;
            color: #333;
        }

        /* HEADER */
        header {
            background: linear-gradient(135deg, #1e3c72, #2a6df4);
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        /* CONTAINER */
        .container {
            padding: 40px;
        }

        /* MENU */
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin: 30px 0 40px;
        }

        .menu-card {
            background: #fff;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,.08);
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: .3s;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0,0,0,.15);
        }

        .menu-card h3 {
            margin-top: 0;
            color: #1e3c72;
        }

        /* SISWA */
        .siswa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .siswa-card {
            background: #fff;
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,.08);
            text-align: center;
            position: relative;
        }

        .kelas {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #2a6df4;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #2a6df4;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 22px;
            font-weight: 600;
        }

        h4 {
            margin: 10px 0 5px;
        }

        small {
            color: #666;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <h2>Dashboard Siswa</h2>
    <a href="../../auth/logout.php">Logout</a>
</header>

<!-- CONTENT -->
<div class="container">
    <h3>Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h3>
    <p>Selamat datang di sistem absensi sekolah</p>

    <!-- MENU (BISA DIKLIK) -->
    <div class="menu">
        <a href="camera.php" class="menu-card">
            <h3>📷 Absensi QR</h3>
            <p>Lakukan absensi dengan scan QR Code</p>
        </a>

        <a href="riwayat_kehadiran.php" class="menu-card">
            <h3>📊 Riwayat Kehadiran</h3>
            <p>Lihat kehadiran harian</p>
        </a>

        <a href="siswa_detail.php" class="menu-card">
            <h3>👤 Profil Siswa</h3>
            <p>Informasi data diri siswa</p>
        </a>
    </div>

    <!-- DATA SISWA -->
    <h3>Data Siswa</h3>
    <div class="siswa-grid">
        <?php while ($s = $siswa->fetch_assoc()): ?>
            <div class="siswa-card">
                <span class="kelas"><?= htmlspecialchars($s['nama_kelas']) ?></span>

                <div class="avatar">
                    <?= strtoupper($s['nama'][0]) ?>
                </div>

                <h4><?= htmlspecialchars($s['nama']) ?></h4>
                <small>NIS: <?= htmlspecialchars($s['nis']) ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
