<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

// SIMPAN AKSES
if (isset($_POST['save'])) {
    $role_id = $_POST['role_id'];
    $subs    = $_POST['submenu'] ?? [];

    // reset akses role
    $conn->query("DELETE FROM menu_access WHERE role_id=$role_id");

    // insert baru
    foreach ($subs as $sid) {
        $stmt = $conn->prepare(
            "INSERT INTO menu_access (role_id, submenu_id) VALUES (?,?)"
        );
        $stmt->bind_param("ii", $role_id, $sid);
        $stmt->execute();
    }
    echo "Akses diperbarui";
}

// DATA
$roles = $conn->query("SELECT * FROM roles");
$subs  = $conn->query("
    SELECT s.submenu_id, s.submenu_name, m.menu_name
    FROM submenu s
    JOIN menu m ON s.menu_id = m.menu_id
");

$selected_role = $_POST['role_id'] ?? null;
$allowed = [];
if ($selected_role) {
    $res = $conn->query(
        "SELECT submenu_id FROM menu_access WHERE role_id=$selected_role"
    );
    while ($r = $res->fetch_assoc()) {
        $allowed[] = $r['submenu_id'];
    }
}
?>

<h3>Menu Access Control</h3>

<form method="POST">
    <select name="role_id" onchange="this.form.submit()" required>
        <option value="">Pilih Role</option>
        <?php while ($r = $roles->fetch_assoc()): ?>
            <option value="<?= $r['role_id'] ?>"
                <?= ($selected_role == $r['role_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['role_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <?php if ($selected_role): ?>
        <hr>
        <?php while ($s = $subs->fetch_assoc()): ?>
            <label>
                <input type="checkbox" name="submenu[]"
                       value="<?= $s['submenu_id'] ?>"
                       <?= in_array($s['submenu_id'], $allowed) ? 'checked' : '' ?>>
                <?= htmlspecialchars($s['menu_name']) ?> → <?= htmlspecialchars($s['submenu_name']) ?>
            </label><br>
        <?php endwhile; ?>

        <br>
        <button name="save">Simpan Akses</button>
    <?php endif; ?>
</form>
