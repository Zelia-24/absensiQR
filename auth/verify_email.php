<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../routes/auth.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $conn->prepare(
        "UPDATE users SET is_active=1 WHERE email=?"
    );
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "Email berhasil diverifikasi.<br>";
        echo "<a href='login.php'>Login</a>";
    } else {
        echo "Verifikasi gagal.";
    }
}
