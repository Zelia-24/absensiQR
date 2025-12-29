<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE user_id=$id")->fetch_assoc();
$roles = $conn->query("SELECT * FROM roles");

if (isset($_POST['update'])) {
    $role = $_POST['role_id'];

    $stmt = $conn->prepare("UPDATE users SET role_id=? WHERE user_id=?");
    $stmt->bind_param("ii", $role, $id);
    $stmt->execute();

    header("Location: user_list.php");
}
?>

<form method="POST">
    <select name="role_id">
        <?php while ($r = $roles->fetch_assoc()): ?>
            <option value="<?= $r['role_id'] ?>" <?= $r['role_id'] == $user['role_id'] ? 'selected' : '' ?>>
                <?= $r['role_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button name="update">Ubah Role</button>
</form>
