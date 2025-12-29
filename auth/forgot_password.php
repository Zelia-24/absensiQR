<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../routes/auth.php";

if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $newPass = password_hash("123456", PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "UPDATE users SET password=? WHERE email=?"
    );
    $stmt->bind_param("ss", $newPass, $email);

    if ($stmt->execute()) {
        echo "Password direset ke: <b>123456</b>";
    } else {
        echo "Reset gagal.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" required placeholder="Email"><br>
    <button name="reset">Reset Password</button>
</form>
