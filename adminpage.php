<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus Vokal Terbaik</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .content {
            flex-grow: 1;
            margin-left: 250px;
            padding: 40px;
            background-color: #f8f8f8;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .content h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            flex: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            color: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
        }

        .card-absen {
            background-color: #4caf50;
            /* Green */
        }

        .card-jadwal {
            background-color: #2196f3;
            /* Blue */
        }

        .card-informasi {
            background-color: #ff9800;
            /* Orange */
        }

        .card-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'components/sidebar.php'; ?>

    <div class="content">
        <h1>Selamat Datang di Dashboard Admin</h1>
        <p><b>Pilih menu di bawah untuk mengelola sistem</b></p>

        <div class="card-container">
            <div class="card card-absen" onclick="location.href='adminpageabsen.php'">
                <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
                <h3>Absen & Jadwal</h3>
                <p>Kelola dan pantau absensi peserta kursus.</p>
            </div>
            <!--<div class="card card-jadwal" onclick="location.href='adminpagejadwal.php'">-->
            <!--    <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>-->
            <!--    <h3>Lihat Jadwal</h3>-->
            <!--    <p>Lihat jadwal kursus hari ini.</p>-->
            <!--</div>-->
            <div class="card card-informasi" onclick="location.href='adminpageinformasi.php'">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h3>Data Pengguna</h3>
                <p>Lihat informasi peserta dan bukti pembayaran.</p>
            </div>
        </div>
    </div>
</body>

</html>