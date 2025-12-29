<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

// tambah
if (isset($_POST['tambah'])) {
    $jurusan = $_POST['jurusan_id'];
    $kelas   = $_POST['nama_kelas'];

    $stmt = $conn->prepare("INSERT INTO kelas (jurusan_id, nama_kelas) VALUES (?,?)");
    $stmt->bind_param("is", $jurusan, $kelas);
    $stmt->execute();
    header("Location: kelas.php");
}

// hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM kelas WHERE kelas_id=$id");
    header("Location: kelas.php");
}

$kelas = $conn->query("
    SELECT k.*, j.nama_jurusan
    FROM kelas k
    JOIN jurusan j ON k.jurusan_id = j.jurusan_id
");
$jurusan = $conn->query("SELECT * FROM jurusan");
?>

<h3>Data Kelas</h3>

<form method="POST">
    <select name="jurusan_id" required>
        <option value="">Pilih Jurusan</option>
        <?php while ($j = $jurusan->fetch_assoc()): ?>
            <option value="<?= $j['jurusan_id'] ?>"><?= $j['nama_jurusan'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="text" name="nama_kelas" placeholder="Nama Kelas" required>
    <button name="tambah">Tambah</button>
</form>

<table border="1" cellpadding="5">
<tr><th>Kelas</th><th>Jurusan</th><th>Aksi</th></tr>
<?php while ($k = $kelas->fetch_assoc()): ?>
<tr>
    <td><?= $k['nama_kelas'] ?></td>
    <td><?= $k['nama_jurusan'] ?></td>
    <td>
        <a href="?hapus=<?= $k['kelas_id'] ?>" onclick="return confirm('Hapus kelas?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
