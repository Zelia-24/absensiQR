<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 2) die("Akses ditolak");

// Ambil data jam (1 record saja)
$data = $conn->query("SELECT * FROM jam_absen LIMIT 1")->fetch_assoc();

// Simpan / Update
if (isset($_POST['simpan'])) {
    $masuk = $_POST['jam_masuk'];
    $keluar = $_POST['jam_keluar'];
    $batas = $_POST['batas_terlambat'];

    if ($data) {
        $stmt = $conn->prepare("
            UPDATE jam_absen 
            SET jam_masuk=?, jam_keluar=?, batas_terlambat=?
            WHERE jam_id=?
        ");
        $stmt->bind_param("sssi", $masuk, $keluar, $batas, $data['jam_id']);
    } else {
        $stmt = $conn->prepare("
            INSERT INTO jam_absen (jam_masuk, jam_keluar, batas_terlambat)
            VALUES (?,?,?)
        ");
        $stmt->bind_param("sss", $masuk, $keluar, $batas);
    }

    $stmt->execute();
    header("Location: jam_absen.php");
}
?>

<h3>Pengaturan Jam Absensi</h3>

<form method="POST">
    Jam Masuk<br>
    <input type="time" name="jam_masuk" value="<?= $data['jam_masuk'] ?? '' ?>" required><br><br>

    Jam Keluar<br>
    <input type="time" name="jam_keluar" value="<?= $data['jam_keluar'] ?? '' ?>" required><br><br>

    Batas Terlambat<br>
    <input type="time" name="batas_terlambat" value="<?= $data['batas_terlambat'] ?? '' ?>" required><br><br>

    <button name="simpan">Simpan</button>
</form>
