<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

// Parameter koneksi database
$servername = "localhost";
$database = "websitekursus";
$username = "root";
$password = "";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Delete record
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM pendaftaran WHERE id = '$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Data berhasil dihapus.";
    } else {
        $error_message = "Kesalahan: " . $conn->error;
    }
}

// Fetch pendaftaran data
$pendaftaran_query = "SELECT * FROM pendaftaran";
$pendaftaran_result = $conn->query($pendaftaran_query);

$pembayaran_query = "SELECT a.id, u.name AS nama, a.payment_method, a.payment_proof
                  FROM pembayaran a 
                  JOIN users u ON a.id";
$pembayaran_result = $conn->query($pembayaran_query);

$conn->close();
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
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
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
            <li><a href="adminpageinformasi.php"><i class="fas fa-info-circle"></i> Informasi & Bukti Pembayaran</a></li>
            <li><a href="adminpagelogout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Informasi Pendaftaran</h1>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Jenis Kursus</th>
                    
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $pendaftaran_result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                       
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="action-btn delete-btn">Hapus</a>
                        </td>
                        
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1>Informasi Bukti Pembayaran</h1>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $pembayaran_result->fetch_assoc()):
                ?>
                    <tr>
                        
                        <td><img src="pembayaran/<?php echo $row['payment_proof']; ?>" alt="Foto Dokumentasi" style="width: 100px; height: 100px;"></td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="action-btn delete-btn">Hapus</a>
                        </td>
                        
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>
