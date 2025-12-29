<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

// tambah
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $stmt = $conn->prepare("INSERT INTO jurusan (nama_jurusan) VALUES (?)");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    header("Location: jurusan.php");
}

// hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM jurusan WHERE jurusan_id=$id");
    header("Location: jurusan.php");
}

$data = $conn->query("SELECT * FROM jurusan");
?>

<h3>Data Jurusan</h3>

<form method="POST">
    <input type="text" name="nama" placeholder="Nama Jurusan" required>
    <button name="tambah">Tambah</button>
</form>

<table border="1" cellpadding="5">
<tr><th>Jurusan</th><th>Aksi</th></tr>
<?php while ($j = $data->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($j['nama_jurusan']) ?></td>
    <td>
        <a href="?hapus=<?= $j['jurusan_id'] ?>" onclick="return confirm('Hapus jurusan?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
