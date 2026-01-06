<?php
session_start();

// Cek apakah user login sebagai guru
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../auth/login.php");
    exit;
}

// Koneksi database
require_once __DIR__ . "/../config/database.php";

// Ambil data mapel
$sql = "SELECT mapel_id, nama_mapel, created_at FROM mapel ORDER BY nama_mapel ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal / Mata Pelajaran</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    margin:0;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg,#e3f2fd,#90caf9);
    color:#0d47a1;
}

.dashboard-header {
    background-color: #1565c0;
    color: white;
    padding: 20px 30px;
    display:flex;
    justify-content: space-between;
    align-items:center;
    box-shadow:0 4px 8px rgba(0,0,0,0.2);
}
.dashboard-header h1 {margin:0; font-size:24px;}
.dashboard-header .logout {
    background-color: #bbdefb;
    color: #1565c0;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}
.dashboard-header .logout:hover { background-color:#90caf9; }

.container {
    max-width:800px;
    margin:40px auto;
    padding:0 20px;
}
.container h2 {
    text-align:center;
    margin-bottom:30px;
    font-size:28px;
}

.table-wrapper {
    overflow-x:auto;
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
}

table {
    width:100%;
    border-collapse:collapse;
}
table th, table td {
    padding:12px;
    text-align:center;
    border-bottom:1px solid #bbdefb;
}
table th {
    background-color:#1565c0;
    color:white;
    font-weight:600;
    border-radius:6px;
}
table tr:hover {
    background-color:#e3f2fd;
}

@media (max-width:600px){
    .dashboard-header h1{font-size:20px;}
    .container h2{font-size:22px;}
    table th, table td{padding:8px;}
}
</style>
</head>
<body>

<header class="dashboard-header">
    <h1>Data Mata Pelajaran</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Halo, <?= htmlspecialchars($_SESSION['nama']); ?></h2>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['mapel_id']); ?></td>
                            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Belum ada data mata pelajaran.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
