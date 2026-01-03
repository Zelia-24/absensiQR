<?php
session_start();
require_once "../config/database.php";

if (isset($_POST['daftar'])) {

    $role     = $_POST['role'];
    $nama     = $_POST['nama'] ?? '';
    $nis      = $_POST['nis'] ?? '';
    $nip      = $_POST['nip'] ?? '';
    $kelas    = $_POST['kelas'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ================= SISWA =================
    if ($role == "siswa") {
        $stmt = $conn->prepare("
            INSERT INTO siswa (nama, nis, kelas, password)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssss", $nama, $nis, $kelas, $password);

        if ($stmt->execute()) {
            $_SESSION['role'] = 'siswa';
            $_SESSION['id']   = $conn->insert_id;

            header("Location: ../dashboard/index.php");
            exit;
        }
    }

    // ================= GURU =================
    if ($role == "guru") {
        $stmt = $conn->prepare("
            INSERT INTO guru (nama, nip, password)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $nama, $nip, $password);

        if ($stmt->execute()) {
            $_SESSION['role'] = 'guru';
            $_SESSION['id']   = $conn->insert_id;

            header("Location: ../dashboard/index.php");
            exit;
        }
    }

    // ================= WALIKELAS =================
    if ($role == "walikelas") {
        $stmt = $conn->prepare("
            INSERT INTO walikelas (nama, kelas, password)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $nama, $kelas, $password);

        if ($stmt->execute()) {
            $_SESSION['role'] = 'walikelas';
            $_SESSION['id']   = $conn->insert_id;

            header("Location: ../dashboard/index.php");
            exit;
        }
    }

    // ================= ADMIN =================
    if ($role == "admin") {
        $stmt = $conn->prepare("
            INSERT INTO admin (username, password)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['role'] = 'admin';
            $_SESSION['id']   = $conn->insert_id;

            header("Location: ../dashboard/index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>

<h2>Registrasi Pengguna</h2>

<form method="post">

    <select name="role" required onchange="ubahForm(this.value)">
        <option value="">-- Pilih Role --</option>
        <option value="siswa">Siswa</option>
        <option value="guru">Guru</option>
        <option value="walikelas">Wali Kelas</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <div id="field_nama">
        <input type="text" name="nama" placeholder="Nama"><br>
    </div>

    <div id="field_nis">
        <input type="text" name="nis" placeholder="NIS"><br>
    </div>

    <div id="field_nip">
        <input type="text" name="nip" placeholder="NIP"><br>
    </div>

    <div id="field_kelas">
        <input type="text" name="kelas" placeholder="Kelas"><br>
    </div>

    <div id="field_username">
        <input type="text" name="username" placeholder="Username"><br>
    </div>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="daftar">Daftar</button>
</form>

<script>
function ubahForm(role) {
    const nama     = document.getElementById("field_nama");
    const nis      = document.getElementById("field_nis");
    const nip      = document.getElementById("field_nip");
    const kelas    = document.getElementById("field_kelas");
    const username = document.getElementById("field_username");

    nama.style.display =
    nis.style.display =
    nip.style.display =
    kelas.style.display =
    username.style.display = "none";

    if (role === "siswa") {
        nama.style.display = "block";
        nis.style.display = "block";
        kelas.style.display = "block";
    }

    if (role === "guru") {
        nama.style.display = "block";
        nip.style.display = "block";
    }

    if (role === "walikelas") {
        nama.style.display = "block";
        kelas.style.display = "block";
    }

    if (role === "admin") {
        username.style.display = "block";
    }
}
</script>

</body>
</html>
