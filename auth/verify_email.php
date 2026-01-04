<?php
require_once __DIR__ . "/../config/database.php";

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Token tidak valid");
}

$stmt = $conn->prepare(
    "UPDATE users 
     SET is_active = 1, verify_token = NULL 
     WHERE verify_token = ?"
);
$stmt->bind_param("s", $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Akun berhasil diverifikasi. <a href='../auth/login.php'>Login</a>";
} else {
    echo "Token tidak valid atau akun sudah aktif.";
}
