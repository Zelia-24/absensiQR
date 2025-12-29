<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] > 3) die("Akses ditolak");

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
    UPDATE izin 
    SET status='Disetujui', approved_by=?
    WHERE izin_id=?
");
$stmt->bind_param("ii", $_SESSION['user_id'], $id);
$stmt->execute();

header("Location: izin_list.php");
exit;
