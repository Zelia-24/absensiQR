<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

// CREATE
if (isset($_POST['add'])) {
    $menu_id = $_POST['menu_id'];
    $name    = $_POST['submenu_name'];
    $url     = $_POST['url'];

    $stmt = $conn->prepare(
        "INSERT INTO submenu (menu_id, submenu_name, url) VALUES (?,?,?)"
    );
    $stmt->bind_param("iss", $menu_id, $name, $url);
    $stmt->execute();
    header("Location: submenu.php");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM submenu WHERE submenu_id=$id");
    header("Location: submenu.php");
}

// READ
$menus   = $conn->query("SELECT * FROM menu");
$submenus = $conn->query("
    SELECT s.*, m.menu_name
    FROM submenu s
    JOIN menu m ON s.menu_id = m.menu_id
");
?>

<h3>Sub Menu Management</h3>

<form method="POST">
    <select name="menu_id" required>
        <option value="">Pilih Menu</option>
        <?php while ($m = $menus->fetch_assoc()): ?>
            <option value="<?= $m['menu_id'] ?>"><?= htmlspecialchars($m['menu_name']) ?></option>
        <?php endwhile; ?>
    </select>
    <input type="text" name="submenu_name" placeholder="Nama Sub Menu" required>
    <input type="text" name="url" placeholder="/absensi/data_absensi.php">
    <button name="add">Tambah</button>
</form>

<table border="1" cellpadding="5">
<tr><th>Menu</th><th>Sub Menu</th><th>URL</th><th>Aksi</th></tr>
<?php while ($s = $submenus->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($s['menu_name']) ?></td>
    <td><?= htmlspecialchars($s['submenu_name']) ?></td>
    <td><?= htmlspecialchars($s['url']) ?></td>
    <td>
        <a href="?delete=<?= $s['submenu_id'] ?>" onclick="return confirm('Hapus submenu?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
