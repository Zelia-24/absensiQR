<?php
session_start();
if ($_SESSION['role'] !== 'walikelas') {
    die("Akses ditolak");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Wali Kelas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #e6f0ff;
            color: #333;
        }

        .dashboard-header {
            background-color: #1e3a8a;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dashboard-header h1 {
            font-size: 24px;
        }

        .dashboard-header .logout {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .dashboard-header .logout:hover {
            background-color: #1d4ed8;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .container h2 {
            margin-bottom: 30px;
            color: #1e3a8a;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Card clickable */
        .card {
            display: block; /* supaya a bisa jadi card */
            background-color: #fff;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none; /* hapus garis bawah link */
            color: inherit; /* teks tetap berwarna normal */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .card p {
            color: #555;
        }

        @media (max-width: 600px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .dashboard-header .logout {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header class="dashboard-header">
    <h1>Dashboard Wali Kelas</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Wali Kelas: <?= $_SESSION['nama']; ?></h2>

    <div class="card-grid">
    <a href="rekap_absensi.php" class="card">
        <h3>Rekap Absensi</h3>
        <p>Absensi siswa kelas binaan</p>
    </a>
    <a href="laporan_bulanan.php" class="card">
        <h3>Laporan Bulanan</h3>
        <p>Unduh laporan kehadiran</p>
    </a>
    <a href="../absensi/scan_qr.php" class="card">
        <h3>Absen QR</h3>
        <p>Scan QR untuk absen wali kelas</p>
    </a>
</div>

</div>

</body>
</html>
