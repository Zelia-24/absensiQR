<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$error = "";

if (isset($_POST['registrasi'])) {

    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['roless'] ?? '';

    $nis        = $_POST['nis'] ?? '';
    $kelas_nama = $_POST['kelas'] ?? '';
    $jurusan_id = $_POST['jurusan_id'] ?? null;

    try {
        $conn->begin_transaction();

        // ===== CEK ROLE =====
        $r = $conn->prepare("SELECT role_id FROM roless WHERE role_nama=?");
        $r->bind_param("s", $role);
        $r->execute();
        $roleRow = $r->get_result()->fetch_assoc();
        if (!$roleRow) throw new Exception("Role tidak valid");

        $role_id = $roleRow['role_id'];

        // ===== INSERT USERS =====
        $u = $conn->prepare("INSERT INTO users (nama,email,password,role_id,is_active) VALUES (?,?,?,?,1)");
        $u->bind_param("sssi", $nama, $email, $password, $role_id);
        $u->execute();
        $user_id = $conn->insert_id;

        // ===== SISWA =====
        if ($role === 'siswa') {

            if (!$nis || !$kelas_nama || !$jurusan_id) {
                throw new Exception("Data siswa belum lengkap");
            }

            // Cek kelas
            $c = $conn->prepare("SELECT kelas_id FROM kelas WHERE nama_kelas=?");
            $c->bind_param("s", $kelas_nama);
            $c->execute();
            $kelas = $c->get_result()->fetch_assoc();

            if ($kelas) {
                $kelas_id = $kelas['kelas_id'];
            } else {
                $ci = $conn->prepare("INSERT INTO kelas (nama_kelas,jurusan_id) VALUES (?,?)");
                $ci->bind_param("si", $kelas_nama, $jurusan_id);
                $ci->execute();
                $kelas_id = $conn->insert_id;
            }

            $qr_code = md5($nis . time());

            $s = $conn->prepare("INSERT INTO siswa (nis,nama,kelas_id,jurusan_id,user_id,qr_code,is_active) VALUES (?,?,?,?,?, ?,1)");
            $s->bind_param("ssiiis", $nis, $nama, $kelas_id, $jurusan_id, $user_id, $qr_code);
            $s->execute();
        }

        // ===== GURU =====
        if ($role === 'guru') {
            $g = $conn->prepare("INSERT INTO guru (nama,user_id,is_active) VALUES (?, ?,1)");
            $g->bind_param("si", $nama, $user_id);
            $g->execute();
        }

        // ===== WALIKELAS =====
        if ($role === 'walikelas') {
            $w = $conn->prepare("INSERT INTO walikelas (nama,user_id,is_active) VALUES (?, ?,1)");
            $w->bind_param("si", $nama, $user_id);
            $w->execute();
        }

        // ===== ADMIN =====
        if ($role === 'admin') {
            $a = $conn->prepare("INSERT INTO admin (nama,user_id,is_active) VALUES (?, ?,1)");
            $a->bind_param("si", $nama, $user_id);
            $a->execute();
        }

        $conn->commit();

        $_SESSION['success'] = "Registrasi berhasil, silakan login";
        header("Location: login.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi</title>
<style>
body{
    background:linear-gradient(135deg,#e3f2fd,#bbdefb);
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    background:#fff;
    width:420px;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}
h2{text-align:center;color:#1565c0}
.form-group{margin-bottom:12px}
label{font-weight:600;color:#0d47a1}
input,select{
    width:100%;
    padding:8px;
    border-radius:6px;
    border:1px solid #90caf9;
}
button{
    width:100%;
    padding:10px;
    background:#1565c0;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
}
.kembali-text{
    text-align:center;
    font-size:14px;
    margin:10px 0 12px;
}
.kembali-text a{
    color:#1565c0;
    font-weight:bold;
    text-decoration:none;
}
.kembali-text a:hover{
    text-decoration:underline;
}
.siswa-only{display:none;margin-top:10px}
.error{text-align:center;color:#c62828}
</style>
</head>
<body>

<div class="card">
<h2>Registrasi</h2>

<?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Nama</label>
<input type="text" name="nama" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<div class="form-group">
<label>Role</label>
<select name="roless" id="role" required>
<option value="">-- pilih --</option>
<option value="siswa">Siswa</option>
<option value="guru">Guru</option>
<option value="walikelas">Wali Kelas</option>
<option value="admin">Admin</option>
</select>
</div>

<!-- Form khusus siswa -->
<div class="siswa-only" id="siswaBox">
<div class="form-group">
<label>NIS</label>
<input type="text" name="nis">
</div>

<div class="form-group">
<label>Kelas</label>
<input type="text" name="kelas">
</div>

<div class="form-group">
<label>Jurusan</label>
<select name="jurusan_id">
<option value="">-- pilih --</option>
<?php
$q=mysqli_query($conn,"SELECT * FROM jurusan");
while($j=mysqli_fetch_assoc($q)){
    echo "<option value='{$j['jurusan_id']}'>{$j['nama_jurusan']}</option>";
}
?>
</select>
</div>
</div>

<button name="registrasi">Daftar</button>
<div class="kembali-text">
            <a href="/absensiQR/index.php">Kembali ke Dashboard</a>
        </div>
</form>
</div>

<script>
const role=document.getElementById('role');
const box=document.getElementById('siswaBox');
role.addEventListener('change',()=>{
    box.style.display = role.value==='siswa' ? 'block':'none';
});
</script>

</body>
</html>
