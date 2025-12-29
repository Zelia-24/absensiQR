<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 3) die("Akses ditolak");

$id = (int)$_GET['id'];

$data = $conn->query("
    SELECT i.*, s.nama, s.nis
    FROM izin i
    JOIN siswa s ON i.siswa_id = s.siswa_id
    WHERE i.izin_id=$id
")->fetch_assoc();

if (!$data) die("Data tidak ditemukan");
?>

<h3>Detail Izin</h3>

<p>NIS : <?= $data['nis'] ?></p>
<p>Nama : <?= $data['nama'] ?></p>
<p>Tanggal : <?= $data['tanggal'] ?></p>
<p>Alasan : <?= $data['alasan'] ?></p>
<p>Status : <?= $data['status'] ?></p>

<?php if ($data['surat']): ?>
    <a href="../storage/izin/<?= $data['surat'] ?>" target="_blank">
        Lihat Surat
    </a>
<?php endif; ?>

<?php if ($data['status'] == 'Pending'): ?>
<hr>
<a href="izin_approve.php?id=<?= $data['izin_id'] ?>">✅ Setujui</a> |
<a href="izin_reject.php?id=<?= $data['izin_id'] ?>">❌ Tolak</a>
<?php endif; ?>
