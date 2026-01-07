<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$error = "";
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if (isset($_POST['login'])) {

    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT users.*, roless.role_nama
        FROM users
        JOIN roless ON users.role_id = roless.role_id
        WHERE users.email=?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role_nama'];

        switch ($user['role_nama']) {
            case 'admin': header("Location: ../admin/admin.php"); break;
            case 'guru': header("Location: ../guru/guru.php"); break;
            case 'walikelas': header("Location: ../walikelas/walikelas.php"); break;
            case 'siswa': header("Location: ../siswa/siswa.php"); break;
        }
        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body{
    background:linear-gradient(135deg,#e3f2fd,#bbdefb);
    font-family:Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    background:#fff;
    width:380px;
    padding:26px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}
h2{
    text-align:center;
    color:#1565c0;
    margin-bottom:18px;
}
.form-group{
    margin-bottom:14px;
}
label{
    display:block;
    margin-bottom:4px;
    font-weight:600;
    color:#0d47a1;
}
input{
    width:100%;
    padding:9px;
    border-radius:7px;
    border:1px solid #90caf9;
}
input:focus{
    outline:none;
    border-color:#1565c0;
}
button{
    width:100%;
    padding:11px;
    background:#1565c0;
    color:white;
    border:none;
    border-radius:7px;
    font-size:16px;
    cursor:pointer;
    margin-top:6px;
}
button:hover{
    background:#0d47a1;
}
.register-text{
    text-align:center;
    font-size:14px;
    margin:10px 0 12px;
}
.register-text a{
    color:#1565c0;
    font-weight:bold;
    text-decoration:none;
}
.register-text a:hover{
    text-decoration:underline;
}
.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:10px;
    text-align:center;
    border-radius:6px;
    margin-bottom:12px;
}
.error{
    color:#c62828;
    text-align:center;
    margin-bottom:12px;
}
</style>
</head>
<body>

<div class="card">
    <h2>Login</h2>

    <?php if($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <!-- NAMA (UI SAJA) -->
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama lengkap">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <!-- REGISTER DI BAWAH PASSWORD -->
        <div class="register-text">
            Belum punya akun?
            <a href="registrasi.php">Registrasi</a>
        </div>

        <button name="login">Login</button>
         <div class="register-text">
            <a href="/absensiQR/index.php">Kembali ke Dashboard</a>
        </div>
        
    </form>
</div>

</body>
</html>
