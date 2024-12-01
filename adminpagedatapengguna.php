<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Query to fetch all users from the `users` table
$users_query = "SELECT id, nama, email, course, hari, jam, created_at 
                FROM users
                WHERE status = 'Aktif'
                ORDER BY created_at DESC";
$users_result = $conn->query($users_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
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

        tbody tr {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Redirect to detail page when clicking a row
        function goToDetail(userId) {
            window.location.href = 'adminpagedetailpengguna.php?id=' + userId;
        }
    </script>
</head>

<body>
    <div class="sidebar">
        <h2 style="color: black;"><a href="adminpage.php">Admin Menu</a></h2>
        <ul>
            <li><a href="adminpageabsen.php"><i class="fas fa-calendar-check"></i> Absen & Jadwal</a></li>
            <!--<li><a href="adminpagejadwal.php"><i class="fas fa-calendar-alt"></i> Atur Jadwal</a></li>-->
            <li><a href="adminpagedatapengguna.php"><i class="fas fa-users"></i> Data Pengguna</a></li>
            <!--<li><a href="adminpageinformasi.php"><i class="fas fa-info-circle"></i> Informasi & Bukti Pembayaran</a></li>-->
            <li><a href="adminpagelogout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Data Pengguna</h1>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Kursus</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Tanggal Pendaftaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users_result->num_rows > 0): ?>
                    <?php while ($row = $users_result->fetch_assoc()): ?>
                        <tr onclick="goToDetail(<?php echo $row['id']; ?>)">
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo htmlspecialchars($row['hari']); ?></td>
                            <td><?php echo htmlspecialchars($row['jam']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada data pengguna ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>