<?php
session_start();

// Check jika admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Dapatkan tanggal hari ini dalam format Y-m-d
$tanggal_hari_ini = (new DateTime())->format('Y-m-d');

// Dapatkan hari ini dalam bahasa Indonesia
$hari_ini = date('l');
$hari_mapping = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];
$hari_dalam_bahasa = $hari_mapping[$hari_ini];

// Initialize the $tanggal_pertemuan variable with a default value
$tanggal_pertemuan = $tanggal_hari_ini;

// Ambil data pengguna yang sesuai dengan hari dan tanggal hari ini
$jadwal_query = "
    SELECT id, nama, course, hari, jam, created_at 
    FROM users 
    WHERE hari = '$hari_dalam_bahasa' AND DATE(created_at) <= '$tanggal_hari_ini'
    ORDER BY jam";
$jadwal_result = $conn->query($jadwal_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta nama="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus Vokal Terbaik</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
            background-color: #f0f0f0;
        }

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

        .add-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #3498db;
            margin-right: 5px;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <h2 style="color: black;"><a href="adminpage.php">Admin Menu</a></h2>
        <ul>
            <li><a href="adminpageabsen.php"><i class="fas fa-calendar-check"></i> Atur Absen</a></li>
            <li><a href="adminpagejadwal.php"><i class="fas fa-calendar-alt"></i> Atur Jadwal</a></li>
            <li><a href="adminpagedatapengguna.php"><i class="fas fa-users"></i> Data Pengguna</a></li>
            <li><a href="adminpageinformasi.php"><i class="fas fa-info-circle"></i> Informasi & Bukti Pembayaran</a></li>
            <li><a href="adminpagelogout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Jadwal Latihan Hari Ini: <?php echo htmlspecialchars($hari_dalam_bahasa); ?> - <?php echo htmlspecialchars($tanggal_pertemuan); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kursus</th>
                    <th>Pertemuan Ke</th>
                    <th>Jam</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = new DateTime(); // Tanggal hari ini

                if ($jadwal_result->num_rows > 0):
                    $has_schedule_today = false; // Flag untuk cek apakah ada jadwal hari ini
                ?>
                    <?php while ($row = $jadwal_result->fetch_assoc()):
                        $created_at = new DateTime($row['created_at']);
                        $hari_pertemuan = array_search($row['hari'], $hari_mapping); // Mapping hari
                        $start_date = (clone $created_at)->modify('next ' . $hari_pertemuan);
                        $interval = $start_date->diff($today);
                        $pertemuan_ke = floor($interval->days / 7) + 1;
                        $tanggal_pertemuan = (clone $start_date)->modify('+' . ($pertemuan_ke - 1) . ' weeks')->format('Y-m-d');

                        // Cek apakah tanggal pertemuan sama dengan hari ini
                        if ($tanggal_pertemuan === $today->format('Y-m-d')):
                            $has_schedule_today = true; // Set flag true jika ada jadwal hari ini
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                <td><?php echo $pertemuan_ke; ?></td>
                                <td><?php echo htmlspecialchars($row['jam']); ?></td>
                                <td><?php echo $tanggal_pertemuan; ?></td>
                            </tr>
                    <?php
                        endif;
                    endwhile;
                    ?>

                    <?php if (!$has_schedule_today): // Jika tidak ada jadwal hari ini 
                    ?>
                        <tr>
                            <td colspan="5">Tidak ada jadwal untuk hari ini.</td>
                        </tr>
                    <?php endif; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada jadwal untuk hari ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</body>

</html>