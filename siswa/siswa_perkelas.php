<?php
require_once "../config/database.php";

$kelas = $conn->query("SELECT * FROM kelas");
$selected = $_GET['kelas_id'] ?? null;

$data = null;
if ($selected) {
    $data = $conn->query("
        SELECT * FROM siswa WHERE kelas_id=$selected
    ");
}
?>

<h3>Data Siswa Per Kelas</h3>

<form method="GET">
    <select name="kelas_id" onchange="this.form.submit()">
        <option value="">Pilih Kelas</option>
        <?php while ($k = $kelas->fetch_assoc()): ?>
            <option value="<?= $k['kelas_id'] ?>" <?= $selected == $k['kelas_id'] ? 'selected' : '' ?>>
                <?= $k['nama_kelas'] ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>

<?php if ($data): ?>
<table border="1" cellpadding="5">
<tr><th>NIS</th><th>Nama</th></tr>
<?php while ($s = $data->fetch_assoc()): ?>
<tr>
    <td><?= $s['nis'] ?></td>
    <td><?= $s['nama'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>
