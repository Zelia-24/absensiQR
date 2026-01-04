<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$role = $_GET['role'] ?? '';
$error = "";

/* Validasi role */
$allowed_roles = ['siswa', 'guru', 'walikelas', 'admin'];
if (!in_array($role, $allowed_roles)) {
    die("Role tidak valid.");
}

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $role_post = $_POST['role'];

    $stmt = $conn->prepare("
        SELECT users.*, roless.role_nama
        FROM users 
        JOIN roless ON users.role_id = roless.role_id
        WHERE users.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        if ($user['is_active'] == 0) {
            $error = "Akun belum diaktifkan.";
        } elseif ($user['role_nama'] !== $role_post) {
            $error = "Role tidak sesuai dengan akun.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role_nama'];

            switch ($user['role_nama']) {
                case 'admin':
                    header("Location: ../admin/admin.php"); break;
                case 'guru':
                    header("Location: ../guru/guru.php"); break;
                case 'walikelas':
                    header("Location: ../walikelas/walikelas.php"); break;
                case 'siswa':
                    header("Location: ../siswa/siswa.php"); break;
            }
            exit;
        }
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login <?= ucfirst($role) ?> | SMP Pelita Bumi</title>
    <meta nama="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style_auth.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <div class="login-header">
            <img src="../public/images/logo.png" alt="Logo">
            <h2>Login <?= ucfirst($role) ?></h2>
            <p>SMP Pelita Bumi</p>
        </div>

        <?php if ($error): ?>
            <div class="alert"><?= $error ?></div>
        <?php endif; ?>

        <!-- FORM LOGIN -->
        <form method="POST">
            <input type="hidden" nama="role" value="<?= $role ?>">

            <div class="form-group">
                <label>Email</label>
                <input type="email" nama="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" nama="password" required>
            </div>

            <button type="submit" nama="login" class="btn-login">
                Login sebagai <?= ucfirst($role) ?>
            </button>

            <!-- REGISTRASI (PAKSA TAMPIL) -->
            <div style="
                margin-top:16px;
                text-align:center;
                font-size:14px;
                color:#000;
                display:block;
            ">
                Belum punya akun?
                <a href="registrasi.php?role=<?= $role ?>"
                   style="color:#e74c3c; font-weight:700; text-decoration:underline;">
                    Registrasi dulu
                </a>
            </div>
        </form>

        <div class="login-footer">
            <a href="../index.php">← Kembali ke dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
