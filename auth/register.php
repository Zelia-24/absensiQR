<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../routes/auth.php";


if (isset($_POST['register'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = 2; // default: Wali Kelas / Petugas
    $token = md5($email . time());

    $stmt = $conn->prepare(
        "INSERT INTO users (role_id, name, email, password, is_active)
         VALUES (?, ?, ?, ?, 0)"
    );
    $stmt->bind_param("isss", $role, $name, $email, $pass);

    if ($stmt->execute()) {
        $link = "http://localhost/absensi/auth/verify_email.php?email=$email";

        // simulasi kirim email
        echo "Registrasi berhasil.<br>
              Klik link verifikasi:<br>
              <a href='$link'>$link</a>";
    } else {
        echo "Registrasi gagal.";
    }
}
?>

<form method="POST">
    <input type="text" name="name" required placeholder="Nama"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button name="register">Register</button>
</form>
