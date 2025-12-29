<?php
// routes/auth.php

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        require_once __DIR__ . "/../auth/login.php";
        break;

    case 'register':
        require_once __DIR__ . "/../auth/register.php";
        break;

    case 'forgot':
        require_once __DIR__ . "/../auth/forgot_password.php";
        break;

    case 'verify':
        require_once __DIR__ . "/../auth/verify_email.php";
        break;

    case 'logout':
        require_once __DIR__ . "/../auth/logout.php";
        break;

    default:
        echo "Halaman auth tidak ditemukan";
}
