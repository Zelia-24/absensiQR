<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

$result = $conn->query("
    SELECT u.user_id, u.name, u.email, r.role_name, u.is_active
    FROM users u
    JOIN roles r ON u.role_id = r.role_id
");
?>

<h3>Data User</h3>
<table border="1" cellpadding="5">
<tr>
    <th>Nama</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while ($u = $result->fetch_assoc()): ?>
<tr>
    <td><?= $u['name'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['role_name'] ?></td>
    <td><?= $u['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
    <td>
        <a href="user_edit.php?id=<?= $u['user_id'] ?>">Edit</a> |
        <a href="user_role.php?id=<?= $u['user_id'] ?>">Role</a> |
        <a href="user_status.php?id=<?= $u['user_id'] ?>">Status</a> |
        <a href="user_delete.php?id=<?= $u['user_id'] ?>" onclick="return confirm('Hapus user?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
