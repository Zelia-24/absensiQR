<?php
session_start();
if ($_SESSION['role'] !== 'siswa') {
    die("Akses ditolak");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../../public/css/style_dashboard.css">
</head>
<body>

<header class="dashboard-header">
    <h1>Dashboard Siswa</h1>
    <a href="../../auth/logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <h2>Halo, <?= $_SESSION['name']; ?></h2>

    <div class="card-grid">
        <div class="card">
            <h3>Absensi QR</h3>
            <p>Lakukan absensi dengan QR Code</p>
        </div>
        <div class="card">
            <h3>Riwayat Kehadiran</h3>
            <p>Lihat kehadiran harian</p>
        </div>
    </div>
</div>

</body>
</html>



<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

// tambah siswa
if (isset($_POST['tambah'])) {
    $nis   = $_POST['nis'];
    $nama  = $_POST['nama'];
    $kelas = $_POST['kelas_id'];

    $stmt = $conn->prepare("
        INSERT INTO siswa (nis, nama, kelas_id, is_active)
        VALUES (?,?,?,1)
    ");
    $stmt->bind_param("ssi", $nis, $nama, $kelas);
    $stmt->execute();
    header("Location: siswa.php");
}

// hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM siswa WHERE siswa_id=$id");
    header("Location: siswa.php");
}

$siswa = $conn->query("
    SELECT s.*, k.nama_kelas
    FROM siswa s
    JOIN kelas k ON s.kelas_id = k.kelas_id
");
$kelas = $conn->query("SELECT * FROM kelas");
?>

<h3>Data Siswa</h3>

<form method="POST">
    <input type="text" name="nis" placeholder="NIS" required>
    <input type="text" name="nama" placeholder="Nama Siswa" required>
    <select name="kelas_id" required>
        <option value="">Pilih Kelas</option>
        <?php while ($k = $kelas->fetch_assoc()): ?>
            <option value="<?= $k['kelas_id'] ?>"><?= $k['nama_kelas'] ?></option>
        <?php endwhile; ?>
    </select>
    <button name="tambah">Tambah</button>
</form>

<table border="1" cellpadding="5">
<tr>
    <th>NIS</th><th>Nama</th><th>Kelas</th><th>Aksi</th>
</tr>
<?php while ($s = $siswa->fetch_assoc()): ?>
<tr>
    <td><?= $s['nis'] ?></td>
    <td><?= $s['nama'] ?></td>
    <td><?= $s['nama_kelas'] ?></td>
    <td>
        <a href="siswa_detail.php?id=<?= $s['siswa_id'] ?>">Detail</a> |
        <a href="?hapus=<?= $s['siswa_id'] ?>" onclick="return confirm('Hapus siswa?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
