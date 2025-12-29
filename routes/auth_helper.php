<?php
// routes/auth_helper.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?auth&page=login");
        exit;
    }
}

function cekRole(array $roles) {
    if (!in_array($_SESSION['role_id'] ?? null, $roles)) {
        die("Akses ditolak");
    }
}
