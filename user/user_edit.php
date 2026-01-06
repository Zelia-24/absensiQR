<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE user_id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $name  = $_POST['nama'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();

    header("Location: user_list.php");
}
?>

<form method="POST">
    <input type="text" name="nama" value="<?= $user['name'] ?>" required><br>
    <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
    <button name="update">Update</button>
</form>
