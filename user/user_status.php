<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

$id = $_GET['id'];

$user = $conn->query("SELECT is_active FROM users WHERE user_id=$id")->fetch_assoc();
$newStatus = $user['is_active'] ? 0 : 1;

$conn->query("UPDATE users SET is_active=$newStatus WHERE user_id=$id");

header("Location: user_list.php");
exit;
