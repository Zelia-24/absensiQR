<?php
session_start();

// Pastikan user login dan role guru
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Guru</title>
<style>
/* ===== Reset & Font ===== */
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #e3f2fd, #90caf9);
    color: #0d47a1;
}

/* ===== Header ===== */
.dashboard-header {
    background-color: #1565c0;
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.dashboard-header h1 { margin:0; font-size:24px; }
.dashboard-header .logout {
    background-color: #bbdefb;
    color: #1565c0;
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.dashboard-header .logout:hover { background-color: #90caf9; }

/* ===== Container ===== */
.container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}
.container h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
}

/* ===== Card Grid ===== */
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* ===== Card as link ===== */
.card-link {
    display: block;
    text-decoration: none;
}
.card {
    background-color: white;
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.card h3 { margin-top:0; color:#1565c0; font-size:20px; }
.card p { color:#0d47a1; }
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
    background: #e3f2fd;
}

/* ===== Responsive ===== */
@media (max-width: 600px) {
    .dashboard-header h1 { font-size:20px; }
    .container h2 { font-size:22px; }
}
</style>
</head>
<body>

<header class="dashboard-header">
    <h1>Dashboard Guru</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Halo, <?= htmlspecialchars($_SESSION['nama']); ?></h2>

    <div class="card-grid">
        <!-- Absensi Siswa -->
        <a href="absensi_siswa.php" class="card-link">
            <div class="card">
                <h3>Absensi Siswa</h3>
                <p>Lihat absensi per kelas</p>
            </div>
        </a>

        <!-- Jadwal Mengajar -->
        <a href="jadwal_guru.php" class="card-link">
            <div class="card">
                <h3>Jadwal Mengajar</h3>
                <p>Informasi jadwal harian</p>
            </div>
        </a>

        <!-- Absensi QR Guru -->
        <a href="../absensi/scan_qr.php" class="card-link">
            <div class="card">
                <h3>Absensi QR Guru</h3>
                <p>Scan QR untuk absensi harian</p>
            </div>
        </a>
    </div>
</div>

</body>
</html>
