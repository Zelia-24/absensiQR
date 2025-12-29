<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

// CREATE
if (isset($_POST['add'])) {
    $name = $_POST['menu_name'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("INSERT INTO menu (menu_name, icon) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $icon);
    $stmt->execute();
    header("Location: menu.php");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM menu WHERE menu_id=$id");
    header("Location: menu.php");
}

// READ
$menus = $conn->query("SELECT * FROM menu");
?>

<h3>Menu Management</h3>

<form method="POST">
    <input type="text" name="menu_name" placeholder="Nama Menu" required>
    <input type="text" name="icon" placeholder="Icon (fa-home)">
    <button name="add">Tambah</button>
</form>

<table border="1" cellpadding="5">
<tr><th>Menu</th><th>Icon</th><th>Aksi</th></tr>
<?php while ($m = $menus->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($m['menu_name']) ?></td>
    <td><?= htmlspecialchars($m['icon']) ?></td>
    <td>
        <a href="?delete=<?= $m['menu_id'] ?>" onclick="return confirm('Hapus menu?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
