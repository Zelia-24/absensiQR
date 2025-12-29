<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

$libur = $conn->query("SELECT * FROM hari_libur");
$events = [];

while ($l = $libur->fetch_assoc()) {
    $events[$l['tanggal']] = $l['keterangan'];
}
?>

<h3>Kalender Hari Libur</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Tanggal</th>
    <th>Keterangan</th>
</tr>

<?php foreach ($events as $tgl => $ket): ?>
<tr style="background:#fdd;">
    <td><?= $tgl ?></td>
    <td><?= htmlspecialchars($ket) ?></td>
</tr>
<?php endforeach; ?>
</table>
