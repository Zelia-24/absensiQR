<?php
require_once "../config/database.php";

if ($_SESSION['role_id'] != 4) die("Akses ditolak");

$siswa = $conn->query("
    SELECT siswa_id FROM siswa WHERE user_id={$_SESSION['user_id']}
")->fetch_assoc();

if (!$siswa) die("Akun belum dikaitkan dengan NIS");

if (isset($_POST['kirim'])) {
    $tgl = $_POST['tanggal'];
    $alasan = $_POST['alasan'];

    $file = $_FILES['surat'];
    $namaFile = time() . "_" . $file['name'];
    move_uploaded_file($file['tmp_name'], "../storage/izin/$namaFile");

    $stmt = $conn->prepare("
        INSERT INTO izin (siswa_id, tanggal, alasan, surat)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param("isss", $siswa['siswa_id'], $tgl, $alasan, $namaFile);
    $stmt->execute();

    echo "Izin berhasil dikirim";
}
?>

<h3>Pengajuan Izin</h3>

<form method="POST" enctype="multipart/form-data">
    Tanggal Izin<br>
    <input type="date" name="tanggal" required><br><br>

    Alasan<br>
    <textarea name="alasan" required></textarea><br><br>

    Upload Surat<br>
    <input type="file" name="surat" required><br><br>

    <button name="kirim">Kirim Izin</button>
</form>
