<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/timezone.php";
require_once __DIR__ . "/auth_helper.php"; 

cekLogin();

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    /* ================= DASHBOARD ================= */
    case 'dashboard':
        require_once __DIR__ . "../views/dashboard.php";
        break;

    /* ================= ABSENSI ================= */
    case 'camera':
        require_once __DIR__ . "../absensi/camera.php";
        break;

    case 'scan':
        require_once __DIR__ . "../absensi/scan_qr.php";
        break;

    case 'absen-masuk':
        require_once __DIR__ . "../absensi/absen_masuk.php";
        break;

    case 'absen-keluar':
        require_once __DIR__ . "../absensi/absen_keluar.php";
        break;

    /* ================= SISWA ================= */
    case 'siswa':
        require_once __DIR__ . "../siswa/siswa.php";
        break;

    case 'siswa-detail':
        require_once __DIR__ . "../siswa/siswa_detail.php";
        break;

    case 'siswa-kelas':
        require_once __DIR__ . "../siswa/siswa_perkelas.php";
        break;

    case 'kelas':
        cekRole([1,2]);
        require_once __DIR__ . "../siswa/kelas.php";
        break;

    case 'jurusan':
        cekRole([1,2]);
        require_once __DIR__ . "../siswa/jurusan.php";
        break;

    /* ================= IZIN ================= */
    case 'izin':
        require_once __DIR__ . "../izin/izin_list.php";
        break;

    case 'izin-detail':
        require_once __DIR__ . "../izin/izin_detail.php";
        break;

    case 'izin-upload':
        cekRole([4]);
        require_once __DIR__ . "../izin/upload_surat.php";
        break;

    /* ================= USER ================= */
    case 'users':
        cekRole([1]);
        require_once __DIR__ . "../user/user_list.php";
        break;

    /* ================= MENU ================= */
    case 'menu':
        cekRole([1]);
        require_once __DIR__ . "../menu/menu.php";
        break;

    case 'submenu':
        cekRole([1]);
        require_once __DIR__ . "../menu/submenu.php";
        break;

    case 'access':
        cekRole([1]);
        require_once __DIR__ . "../menu/access_control.php";
        break;

    /* ================= LOGOUT ================= */
    case 'logout':
        require_once __DIR__ . "../auth/logout.php";
        break;

    /* ================= DEFAULT ================= */
    default:
        echo "<h3>404 - Halaman tidak ditemukan</h3>";
        break;
}
