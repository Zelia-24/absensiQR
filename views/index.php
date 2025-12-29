<?php
require_once "/config/database.php";
require_once "/routes/auth.php";
cekLogin();
?>

<link rel="stylesheet" href="/public/css/style.css">

<div class="container">
    <h2>Dashboard Absensi</h2>
    <p>Selamat datang, <b><?= $_SESSION['name'] ?></b></p>

    <!-- STATISTIK RINGKAS -->
    <div style="display:flex; gap:20px; margin-top:20px;">
        <div class="card">
            <h3>Total Siswa</h3>
            <p class="big">320</p>
        </div>
        <div class="card">
            <h3>Hadir Hari Ini</h3>
            <p class="big">280</p>
        </div>
        <div class="card">
            <h3>Izin</h3>
            <p class="big">25</p>
        </div>
        <div class="card">
            <h3>Alpa</h3>
            <p class="big">15</p>
        </div>
    </div>

    <!-- INFO HARI & JAM -->
    <div style="margin-top:30px;">
        <h3>Informasi Hari Ini</h3>
        <table>
            <tr>
                <td>Tanggal</td>
                <td><?= date("d M Y") ?></td>
            </tr>
            <tr>
                <td>Hari</td>
                <td><?= date("l") ?></td>
            </tr>
            <tr>
                <td>Jam Server</td>
                <td><?= date("H:i:s") ?></td>
            </tr>
            <tr>
                <td>Status Sistem</td>
                <td><span style="color:green;">Aktif</span></td>
            </tr>
        </table>
    </div>

    <!-- AKTIVITAS TERAKHIR (STATIK) -->
    <div style="margin-top:30px;">
        <h3>Aktivitas Absensi Terakhir</h3>
        <table>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
            <tr>
                <td>20231201</td>
                <td>Ahmad Fauzi</td>
                <td>XII RPL 1</td>
                <td>Hadir</td>
                <td>07:05</td>
            </tr>
            <tr>
                <td>20231202</td>
                <td>Siti Aisyah</td>
                <td>XII RPL 2</td>
                <td>Izin</td>
                <td>-</td>
            </tr>
            <tr>
                <td>20231203</td>
                <td>Budi Santoso</td>
                <td>XI TKJ 1</td>
                <td>Alpa</td>
                <td>-</td>
            </tr>
        </table>
    </div>
</div>

<style>
.card {
    flex: 1;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    text-align: center;
}
.card .big {
    font-size: 28px;
    font-weight: bold;
    color: #2e86de;
}
</style>
