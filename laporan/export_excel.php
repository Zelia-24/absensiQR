<?php
require_once "../config/database.php";
require_once "../config/auth.php";
cekLogin();

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_absensi.xls");

$tanggal = $_GET['tanggal'];

echo "<table border='1'>";
echo "<tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Status</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
      </tr>";

$data = $conn->query("
    SELECT s.nis, s.nama, k.nama_kelas, a.status, a.jam_masuk, a.jam_keluar
    FROM absensi a
    JOIN siswa s ON a.siswa_id = s.siswa_id
    JOIN kelas k ON s.kelas_id = k.kelas_id
    WHERE a.tanggal = '$tanggal'
");

while ($row = $data->fetch_assoc()) {
    echo "<tr>
        <td>{$row['nis']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['nama_kelas']}</td>
        <td>{$row['status']}</td>
        <td>{$row['jam_masuk']}</td>
        <td>{$row['jam_keluar']}</td>
    </tr>";
}

echo "</table>";
exit;
