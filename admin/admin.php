<?php
/* =========================================================
   SISTEM ABSENSI QR - SINGLE FILE DASHBOARD
   ========================================================= */

session_start();

/* ================== CONFIG ================== */
$conn = mysqli_connect("localhost", "root", "", "db_absensi");
if (!$conn) die("Koneksi database gagal");

define('BASE_URL', '/absensi/public');

/* ================== AUTH SIMULASI ================== */
/* hapus ini jika sudah ada login asli */
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'name' => 'Administrator'
    ];
}

/* ================== QUERY DASHBOARD ================== */

// Total siswa aktif
$totalSiswa = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM siswa WHERE is_active = 1")
)['total'];

// Hadir hari ini
$hadir = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total 
        FROM absensi 
        WHERE tanggal = CURDATE() 
        AND status IN ('Hadir','Terlambat')
    ")
)['total'];

// Izin hari ini
$izin = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total 
        FROM absensi 
        WHERE tanggal = CURDATE() 
        AND status = 'Izin'
    ")
)['total'];

// Alfa hari ini
$alfa = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total 
        FROM absensi 
        WHERE tanggal = CURDATE() 
        AND status = 'Alfa'
    ")
)['total'];

/* ================== GRAFIK 7 HARI ================== */
$qGrafik = mysqli_query($conn, "
    SELECT 
        tanggal,
        SUM(status='Hadir') AS hadir,
        SUM(status='Terlambat') AS terlambat,
        SUM(status='Izin') AS izin,
        SUM(status='Alfa') AS alfa
    FROM absensi
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY tanggal
    ORDER BY tanggal
");

$tanggal = $hadirArr = $terlambatArr = $izinArr = $alfaArr = [];

while ($r = mysqli_fetch_assoc($qGrafik)) {
    $tanggal[] = $r['tanggal'];
    $hadirArr[] = (int)$r['hadir'];
    $terlambatArr[] = (int)$r['terlambat'];
    $izinArr[] = (int)$r['izin'];
    $alfaArr[] = (int)$r['alfa'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Absensi QR</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{margin:0;font-family:Poppins;background:#f4f6f9}
.wrapper{display:flex;height:100vh}
.sidebar{width:250px;background:#1e40af;color:#fff;padding:20px}
.sidebar h2{text-align:center}
.sidebar a{display:block;color:#fff;padding:12px;border-radius:8px;text-decoration:none;margin:6px 0}
.sidebar a:hover{background:rgba(255,255,255,.2)}
.content{flex:1;display:flex;flex-direction:column}
.navbar{background:#fff;padding:15px 25px;display:flex;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,.08)}
.main{padding:25px;overflow:auto}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px}
.card{background:#fff;padding:20px;border-radius:14px;box-shadow:0 6px 15px rgba(0,0,0,.08)}
.card h3{margin:0}
.card p{font-size:32px;font-weight:600;margin:10px 0 0}
</style>
</head>

<body>
<div class="wrapper"><?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sekolah XYZ</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-container">
            <img src="public/img//logo.png" alt="Logo Sekolah" class="logo">
            <h1 class="school-name">Sekolah XYZ</h1>
        </div>
        <nav class="nav">
            <a href="auth/login.php" class="btn-login">Login</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <section class="info-sekolah">
            <h2>Selamat Datang di Sekolah XYZ</h2>
            <p>
                Sekolah XYZ merupakan lembaga pendidikan unggulan yang berfokus pada pengembangan akademik, 
                karakter, dan kreativitas siswa. Kami menyediakan fasilitas modern dan guru profesional 
                untuk mendukung pembelajaran berkualitas.
            </p>
            <p>
                Silakan login sesuai peran Anda: <strong>Siswa, Guru, Petugas, atau Admin</strong>.
            </p>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        &copy; <?= date('Y') ?> Sekolah XYZ. All Rights Reserved.
    </footer>
</body>
</html>


<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ABSENSI QR</h2>
    <a href="admin.php"><i class="fa fa-chart-bar"></i> Dashboard</a>
    <a href="#"><i class="fa fa-clock"></i> Absensi</a>
    <a href="#"><i class="fa fa-qrcode"></i> Scan QR</a>
    <a href="../laporan/laporan_absensi.php"><i class="fa fa-table"></i> Rekap Absensi</a>
    <a href="../siswa/siswa.php"><i class="fa fa-users"></i> Data Siswa</a>
    <a href="../auth/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

<div class="navbar">
    <div><strong>Sistem Absensi QR</strong></div>
    <div><i class="fa fa-user"></i> <?= $_SESSION['user']['name'] ?></div>
</div>

<div class="main">
    <h2>Dashboard</h2>

    <!-- RINGKASAN -->
    <div class="grid">
        <div class="card"><h3>Total Siswa</h3><p><?= $totalSiswa ?></p></div>
        <div class="card"><h3>Hadir</h3><p><?= $hadir ?></p></div>
        <div class="card"><h3>Izin</h3><p><?= $izin ?></p></div>
        <div class="card"><h3>Alfa</h3><p><?= $alfa ?></p></div>
    </div>

    <br>

    <!-- GRAFIK -->
    <div class="card">
        <h3>Grafik Absensi 7 Hari Terakhir</h3>
        <canvas id="grafik"></canvas>
    </div>
</div>

</div>
</div>

<script>
new Chart(document.getElementById('grafik'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($tanggal) ?>,
        datasets: [
            { label: 'Hadir', data: <?= json_encode($hadirArr) ?> },
            { label: 'Terlambat', data: <?= json_encode($terlambatArr) ?> },
            { label: 'Izin', data: <?= json_encode($izinArr) ?> },
            { label: 'Alfa', data: <?= json_encode($alfaArr) ?> }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
</body>
</html>
