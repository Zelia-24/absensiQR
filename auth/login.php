<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$role = $_GET['role'] ?? '';
$error = "";

/* Validasi role */
$allowed_roles = ['siswa', 'guru', 'wali', 'admin'];
if (!in_array($role, $allowed_roles)) {
    die("Role tidak valid.");
}

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $role_post = $_POST['role'];

    $stmt = $conn->prepare("
        SELECT users.*, roles.role_name 
        FROM users 
        JOIN roles ON users.role_id = roles.role_id
        WHERE users.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        if ($user['is_active'] == 0) {
            $error = "Akun belum diaktifkan.";
        } elseif ($user['role_name'] !== $role_post) {
            $error = "Role tidak sesuai dengan akun.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['nama'];
            $_SESSION['role'] = $user['role_name'];

            /* Redirect sesuai role */
            switch ($user['role_name']) {
                case 'admin':
                    header("Location: ../admin/admin.php");
                    break;
                case 'guru':
                    header("Location: ../guru/guru.php");
                    break;
                case 'wali':
                    header("Location: ../wali/wali.php");
                    break;
                case 'siswa':
                    header("Location: ../siswa/siswa.php");
                    break;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <form method="POST">
            <input type="hidden" name="role" value="<?= $role ?>">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="login" class="btn-login">
                Login sebagai <?= ucfirst($role) ?>
            </button>
        </form>

        <div class="login-footer">
            <a href="../index.php">← Kembali ke Portal</a>
        </div>

    </div>
</div>

</body>
</html>
