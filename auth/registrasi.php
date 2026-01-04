<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$error = "";
$success = "";

if (isset($_POST['registrasi'])) {

    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $role     = $_POST['role'] ?? '';

    // khusus siswa
    $nis        = trim($_POST['nis'] ?? '');
    $kelas_nama = trim($_POST['kelas'] ?? '');
    $jurusan_id = $_POST['jurusan_id'] ?? null;

    if (!$role) {
        $error = "Role wajib dipilih";
    } else {
        $conn->begin_transaction();
        try {

            /* ===== ROLE ===== */
            $r = $conn->prepare("SELECT role_id FROM roless WHERE role_nama=? LIMIT 1");
            $r->bind_param("s", $role);
            $r->execute();
            $roleRow = $r->get_result()->fetch_assoc();
            if (!$roleRow) throw new Exception("Role tidak valid");
            $role_id = $roleRow['role_id'];

            /* ===== USERS ===== */
            $u = $conn->prepare(
                "INSERT INTO users (nama,email,password,role_id,is_active)
                 VALUES (?,?,?,?,1)"
            );
            $u->bind_param("sssi", $nama, $email, $password, $role_id);
            $u->execute();
            $user_id = $conn->insert_id;

            /* ===== SISWA ===== */
            if ($role === 'siswa') {

                if (!$nis || !$kelas_nama || empty($jurusan_id)) {
                    throw new Exception("NIS, Kelas, dan Jurusan wajib diisi");
                }

                $jurusan_id = (int)$jurusan_id;

                // KELAS
                $c = $conn->prepare("SELECT kelas_id FROM kelas WHERE nama_kelas=? LIMIT 1");
                $c->bind_param("s", $kelas_nama);
                $c->execute();
                $kelas = $c->get_result()->fetch_assoc();

                if ($kelas) {
                    $kelas_id = $kelas['kelas_id'];
                } else {
                    $ci = $conn->prepare("INSERT INTO kelas (nama_kelas, jurusan_id) VALUES (?,?)"
                    );
                    $ci->bind_param("si", $kelas_nama, $jurusan_id);
                    $ci->execute();
                    $kelas_id = $conn->insert_id;
                }

                // INSERT SISWA
                $qr_code = md5($nis . time());

                $s = $conn->prepare(
                    "INSERT INTO siswa
                    (nis,nama,kelas_id,jurusan_id,user_id,qr_code,is_active)
                    VALUES (?,?,?,?,?, ?,1)"
                );
                $s->bind_param(
                    "ssiiis",
                    $nis,
                    $nama,
                    $kelas_id,
                    $jurusan_id,
                    $user_id,
                    $qr_code
                );
                $s->execute();
            }

            /* ===== GURU ===== */
            elseif ($role === 'guru') {
                $g = $conn->prepare(
                    "INSERT INTO guru (nama,user_id,is_active)
                     VALUES (?, ?,1)"
                );
                $g->bind_param("si", $nama, $user_id);
                $g->execute();
            }

            /* ===== WALIKELAS ===== */
            elseif ($role === 'walikelas') {
                $w = $conn->prepare(
                    "INSERT INTO walikelas (nama,user_id,is_active)
                     VALUES (?, ?,1)"
                );
                $w->bind_param("si", $nama, $user_id);
                $w->execute();
            }

            $conn->commit();
            $success = "Registrasi berhasil";

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
<title>Registrasi</title>
<style>
.form-group{margin-bottom:10px}
.siswa-only{display:none}
</style>
</head>
<body>

<h2>Registrasi</h2>

<?php if($error): ?><p style="color:red"><?= $error ?></p><?php endif ?>
<?php if($success): ?><p style="color:green"><?= $success ?></p><?php endif ?>

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
    <select name="role" required>
        <option value="">-- pilih --</option>
        <option value="siswa">Siswa</option>
        <option value="guru">Guru</option>
        <option value="walikelas">Wali Kelas</option>
    </select>
</div>

<div class="siswa-only">

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
        <option value="">-- pilih jurusan --</option>
        <?php
        $q = mysqli_query($conn,"SELECT jurusan_id,nama_jurusan FROM jurusan");
        while($j=mysqli_fetch_assoc($q)){
            echo "<option value='{$j['jurusan_id']}'>{$j['nama_jurusan']}</option>";
        }
        ?>
    </select>
</div>

</div>

<button type="submit" name="registrasi">Daftar</button>
</form>

<!-- ===== JS FIX FINAL ===== -->
<script>
const role = document.querySelector('[name=role]');
const box = document.querySelector('.siswa-only');
const nis = document.querySelector('[name=nis]');
const kelas = document.querySelector('[name=kelas]');
const jurusan = document.querySelector('[name=jurusan_id]');

function toggle(){
    if(role.value === 'siswa'){
        box.style.display = 'block';
        nis.required = true;
        kelas.required = true;
        jurusan.required = true;
    }else{
        box.style.display = 'none';
        nis.required = false;
        kelas.required = false;
        jurusan.required = false;
    }
}
role.addEventListener('change',toggle);
toggle();
</script>

</body>
</html>