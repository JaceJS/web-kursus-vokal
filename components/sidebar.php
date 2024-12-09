<?php
// Set locale untuk waktu ke Bahasa Indonesia
setlocale(LC_TIME, 'id_ID.UTF-8');

// Untuk mendukung PHP versi yang lebih baru
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Asia/Makassar');
}
?>

<div class="sidebar">
    <h2>
        <a href="adminpage.php" style="text-decoration: none; color: black">Admin Menu</a>
    </h2>
    <ul>
        <li><a href="adminpageabsen.php"><i class="fas fa-calendar-check"></i> Absen & Jadwal</a></li>
        <li><a href="adminpagedatapengguna.php"><i class="fas fa-users"></i> Data Pengguna (Aktif)</a></li>
        <li><a href="adminpagedatapenggunatidakaktif.php"><i class="fas fa-users"></i> Data Pengguna (Tidak Aktif)</a></li>
        <li><a href="adminpagelogout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<style>
    .sidebar {
        width: 250px;
        background-color: #333;
        color: white;
        height: 100%;
        padding: 20px;
        box-sizing: border-box;
        position: fixed;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 22px;
        color: white;
        background-color: #f0db4f;
        padding: 10px;
        border-radius: 8px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 20px 0;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        font-size: 18px;
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    .sidebar ul li a:hover {
        background-color: #555;
    }
</style>