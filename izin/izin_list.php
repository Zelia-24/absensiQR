<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 3) die("Akses ditolak");

$data = $conn->query("
    SELECT i.izin_id, s.nama, s.nis, i.tanggal, i.status
    FROM izin i
    JOIN siswa s ON i.siswa_id = s.siswa_id
    ORDER BY i.tanggal DESC
");
?>

<h3>Daftar Izin Siswa</h3>

<table border="1" cellpadding="5">
<tr>
    <th>NIS</th>
    <th>Nama</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while ($i = $data->fetch_assoc()): ?>
<tr>
    <td><?= $i['nis'] ?></td>
    <td><?= $i['nama'] ?></td>
    <td><?= $i['tanggal'] ?></td>
    <td><?= $i['status'] ?></td>
    <td>
        <a href="izin_detail.php?id=<?= $i['izin_id'] ?>">Detail</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
