<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

// Tambah libur
if (isset($_POST['tambah'])) {
    $tgl = $_POST['tanggal'];
    $ket = $_POST['keterangan'];

    $stmt = $conn->prepare("
        INSERT INTO hari_libur (tanggal, keterangan)
        VALUES (?,?)
    ");
    $stmt->bind_param("ss", $tgl, $ket);
    $stmt->execute();
    header("Location: hari_libur.php");
}

// Hapus libur
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM hari_libur WHERE libur_id=$id");
    header("Location: hari_libur.php");
}

$libur = $conn->query("
    SELECT * FROM hari_libur ORDER BY tanggal DESC
");
?>

<h3>Data Hari Libur</h3>

<form method="POST">
    Tanggal<br>
    <input type="date" name="tanggal" required><br>
    Keterangan<br>
    <input type="text" name="keterangan" required><br><br>
    <button name="tambah">Tambah Libur</button>
</form>

<br>

<table border="1" cellpadding="5">
<tr>
    <th>Tanggal</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>
<?php while ($l = $libur->fetch_assoc()): ?>
<tr>
    <td><?= $l['tanggal'] ?></td>
    <td><?= htmlspecialchars($l['keterangan']) ?></td>
    <td>
        <a href="?hapus=<?= $l['libur_id'] ?>" onclick="return confirm('Hapus hari libur?')">
            Hapus
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>
