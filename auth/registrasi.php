<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $nama       = trim($_POST['nama'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $role_nama  = $_POST['role'] ?? null;
    $kelas_nama = trim($_POST['kelas'] ?? '');
    $nis        = trim($_POST['nis'] ?? '');
    $jurusan_nama = trim($_POST['jurusan'] ?? '');

    if (!$role_nama) {
        $error = "Silakan pilih role.";
    } else {
        $conn->begin_transaction();
        try {
            // Ambil role_id
            $r = $conn->prepare("SELECT role_id FROM roless WHERE role_nama = ? LIMIT 1");
            $r->bind_param("s", $role_nama);
            $r->execute();
            $role = $r->get_result()->fetch_assoc();
            if (!$role) throw new Exception("Role $role_nama tidak ditemukan di tabel roless");
            $role_id = $role['role_id'];

            // Insert user
            $u = $conn->prepare("INSERT INTO users (nama, email, password, role_id, is_active) VALUES (?, ?, ?, ?, 1)");
            $u->bind_param("sssi", $nama, $email, $password, $role_id);
            $u->execute();
            $user_id = $conn->insert_id;

            if ($role_nama === 'siswa') {
                if (!$nis || !$kelas_nama || !$jurusan_nama) 
                    throw new Exception("NIS, Kelas, dan Jurusan wajib diisi untuk siswa");

                // Ambil atau insert jurusan
                $j = $conn->prepare("SELECT jurusan_id FROM jurusan WHERE nama_jurusan = ? LIMIT 1");
                $j->bind_param("s", $jurusan_nama);
                $j->execute();
                $res = $j->get_result()->fetch_assoc();
                if ($res) $jurusan_id = $res['jurusan_id'];
                else {
                    $ji = $conn->prepare("INSERT INTO jurusan (nama_jurusan) VALUES (?)");
                    $ji->bind_param("s", $jurusan_nama);
                    $ji->execute();
                    $jurusan_id = $conn->insert_id;
                }

                // Ambil atau insert kelas
                $c = $conn->prepare("SELECT kelas_id FROM kelas WHERE nama_kelas = ?");
                $c->bind_param("s", $kelas_nama);
                $c->execute();
                $kelas = $c->get_result()->fetch_assoc();
                if ($kelas) $kelas_id = $kelas['kelas_id'];
                else {
                    $ci = $conn->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
                    $ci->bind_param("s", $kelas_nama);
                    $ci->execute();
                    $kelas_id = $conn->insert_id;
                }

                // Insert siswa
                $qr_code = md5($nis . time());
                $s = $conn->prepare("INSERT INTO siswa (nis, nama, kelas_id, user_id, qr_code, is_active, jurusan_id) VALUES (?, ?, ?, ?, ?, 1, ?)");
                $s->bind_param("ssissi", $nis, $nama, $kelas_id, $user_id, $qr_code, $jurusan_id);
                $s->execute();

            } elseif ($role_nama === 'guru') {
                $g = $conn->prepare("INSERT INTO guru (nama, user_id, is_active) VALUES (?, ?, 1)");
                $g->bind_param("si", $nama, $user_id);
                $g->execute();

            } elseif ($role_nama === 'walikelas') {
                $w = $conn->prepare("INSERT INTO walikelas (nama, user_id, is_active) VALUES (?, ?, 1)");
                $w->bind_param("si", $nama, $user_id);
                $w->execute();
            }

            $conn->commit();
            $success = "Registrasi berhasil untuk role $role_nama. Silakan login.";

        } catch (Exception $e) {
            $conn->rollback();
            $error = "Gagal registrasi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi User</title>
<link rel="stylesheet" href="../public/css/style_auth.css">
<style>
    .form-group { margin-bottom:10px; }
    .siswa-only { display:none; }
    .alert { padding:10px; margin-bottom:10px; border-radius:5px; }
    .alert.error { background:#ffeaea; color:#d32f2f; }
    .alert.success { background:#eaffea; color:#2e7d32; }
    .btn-login { display:inline-block; padding:10px 20px; background:#1976d2; color:#fff; text-decoration:none; border-radius:5px; margin-top:10px; }
    .btn-login:hover { background:#0d47a1; }
</style>
</head>
<body>

<div class="login-container">
<div class="login-card">

<div class="login-header">
    <h2>Registrasi User</h2>
    <p>Buat akun baru</p>
</div>

<?php if ($error): ?>
<div class="alert error"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="siswa" <?= (($_POST['role'] ?? '') === 'siswa') ? 'selected' : '' ?>>Siswa</option>
            <option value="guru" <?= (($_POST['role'] ?? '') === 'guru') ? 'selected' : '' ?>>Guru</option>
            <option value="walikelas" <?= (($_POST['role'] ?? '') === 'walikelas') ? 'selected' : '' ?>>Wali Kelas</option>
        </select>
    </div>

    <div class="siswa-only">
        <div class="form-group">
            <label>NIS</label>
            <input type="text" name="nis" value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" value="<?= htmlspecialchars($_POST['kelas'] ?? '') ?>" placeholder="Contoh: X RPL 1">
        </div>

        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="jurusan" value="<?= htmlspecialchars($_POST['jurusan'] ?? '') ?>" placeholder="Contoh: RPL, TKJ">
        </div>
    </div>

<!-- tombol submit form -->
 <div style="margin-top:15px; text-align:center;">
<button type="submit" name="register" class="btn-login">Daftar</button>
</form>
</div>

<!-- Tombol Login terpisah, bukan di dalam form -->
<div style="margin-top:15px; text-align:center;">
    <a href="login.php" class="btn-login">Login</a>
</div>


</div>
</div>

<script>
const roleSelect = document.querySelector('select[name="role"]');
const siswaFields = document.querySelectorAll('.siswa-only');

function toggleSiswaFields() {
    if(roleSelect.value === 'siswa') {
        siswaFields.forEach(f => f.style.display = 'block');
    } else {
        siswaFields.forEach(f => f.style.display = 'none');
    }
}

roleSelect.addEventListener('change', toggleSiswaFields);
toggleSiswaFields();
</script>

</body>
</html>
