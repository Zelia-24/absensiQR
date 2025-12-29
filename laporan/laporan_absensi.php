<?php
require_once "../config/auth.php";
cekLogin();
cekRole([1,2,3]); // Admin, Petugas, Guru
?>

<link rel="stylesheet" href="../public/css/style.css">

<div class="container">
    <h2>Laporan Absensi</h2>

    <form method="GET" action="export_excel.php">
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>Kelas</label>
        <select name="kelas">
            <option value="">Semua Kelas</option>
            <option value="X RPL 1">X RPL 1</option>
            <option value="XI RPL 2">XI RPL 2</option>
        </select>

        <button type="submit">📤 Export Excel</button>
    </form>

    <br>

    <form method="GET" action="export_csv.php">
        <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
        <button type="submit">📄 Export CSV</button>
    </form>
</div>
