<?php
require_once "../config/database.php";

$id = (int)$_GET['id'];

$data = $conn->query("
    SELECT s.*, k.nama_kelas, j.nama_jurusan
    FROM siswa s
    JOIN kelas k ON s.kelas_id = k.kelas_id
    JOIN jurusan j ON k.jurusan_id = j.jurusan_id
    WHERE s.siswa_id = $id
")->fetch_assoc();

if (!$data) die("Data tidak ditemukan");
?>

<h3>Detail Siswa</h3>
<p>NIS : <?= $data['nis'] ?></p>
<p>Nama : <?= $data['nama'] ?></p>
<p>Kelas : <?= $data['nama_kelas'] ?></p>
<p>Jurusan : <?= $data['nama_jurusan'] ?></p>

<?php if ($data['qr_code']): ?>
    <img src="<?= $data['qr_code'] ?>" width="200">
<?php endif; ?>
