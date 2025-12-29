<?php
require_once "../config/database.php";
if ($_SESSION['role_id'] != 1) die("Akses ditolak");

$id = $_GET['id'];

$conn->query("DELETE FROM users WHERE user_id=$id");

header("Location: user_list.php");
exit;
