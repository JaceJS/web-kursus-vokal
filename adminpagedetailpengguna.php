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

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
} else {
    echo "Invalid user ID.";
    exit;
}

// Query to fetch the user's details based on the ID
$user_query = "SELECT u.id, u.nama, u.email, p.course, p.hari, p.jam, p.order_id, created_at 
                FROM users u
                JOIN pendaftaran p ON u.id = p.user_id 
                WHERE u.id = $user_id";
$user_result = $conn->query($user_query);

// Check if the user exists
if ($user_result->num_rows == 0) {
    echo "User not found.";
    exit;
}

// Fetch the user data
$user_data = $user_result->fetch_assoc();

// Fetch payment details from the `pendaftaran` table based on `order_id`
$order_id = $user_data['order_id'];
if ($order_id) {
    $payment_query = "SELECT order_id, status, payment_type, store, va_number, bank FROM pendaftaran WHERE order_id = '$order_id'";
    $payment_result = $conn->query($payment_query);
    $payment_data = ($payment_result->num_rows > 0) ? $payment_result->fetch_assoc() : [];
} else {
    $payment_data = [];
}

// Query to fetch attendance data based on user_id
$absen_query = "SELECT id, pertemuan_ke, tanggal, topik, instruktur, status, absen_date FROM absen WHERE user_id = $user_id";
$absen_result = $conn->query($absen_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna</title>
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

        .content p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a.edit-button {
            color: white;
            background-color: #4CAF50;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        a.edit-button:hover {
            background-color: #45a049;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <h1>Detail Pengguna</h1>
        <div class="d-flex justify-content-between">
            <div>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($user_data['nama']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
                <p><strong>Kursus:</strong> <?php echo htmlspecialchars($user_data['course']); ?></p>
                <p><strong>Hari:</strong> <?php echo htmlspecialchars($user_data['hari']); ?></p>
                <p><strong>Jam:</strong> <?php echo htmlspecialchars($user_data['jam']); ?></p>
                <p><strong>Tanggal Pendaftaran:</strong> <?php echo htmlspecialchars($user_data['created_at']); ?></p>
            </div>
            <div>
                <a href="adminpage_print_pengguna.php?user_id=<?php echo $user_data['id']; ?>" class="btn btn-primary" target="_blank">Print PDF</a>
                <!-- <form action="adminpage_print_pengguna.php" method="get">
                    <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                    <button type="submit" class="btn btn-danger">Print PDF</button>
                </form> -->
            </div>
        </div>

        <!-- Payment Details -->
        <h2>Detail Pembayaran</h2>
        <?php if ($payment_data): ?>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($payment_data['order_id']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($payment_data['status']); ?></p>
            <p><strong>Payment Type:</strong> <?php echo htmlspecialchars($payment_data['payment_type']); ?></p>
            <?php if ($payment_data['payment_type'] == 'cstore'): ?>
                <p><strong>Store:</strong> <?php echo htmlspecialchars($payment_data['store']); ?></p>
            <?php elseif ($payment_data['payment_type'] == 'bank_transfer'): ?>
                <p><strong>Bank:</strong> <?php echo htmlspecialchars($payment_data['bank']); ?></p>
                <p><strong>VA Number:</strong> <?php echo htmlspecialchars($payment_data['va_number']); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>Detail pembayaran tidak tersedia.</p>
        <?php endif; ?>

        <!-- Tabel Absen Pengguna -->
        <h2>Data Absen</h2>
        <table>
            <tr>
                <th>Pertemuan Ke</th>
                <th>Tanggal</th>
                <th>Topik</th>
                <th>Instruktur</th>
                <th>Status</th>
                <th>Tanggal Absen</th>
                <th>Edit</th>
            </tr>
            <?php
            if ($absen_result->num_rows > 0) {
                while ($absen = $absen_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($absen['pertemuan_ke']) . "</td>";
                    echo "<td>" . htmlspecialchars($absen['tanggal']) . "</td>";
                    echo "<td>" . htmlspecialchars($absen['topik']) . "</td>";
                    echo "<td>" . htmlspecialchars($absen['instruktur']) . "</td>";
                    echo "<td>" . htmlspecialchars($absen['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($absen['absen_date']) . "</td>";
                    echo "<td><a class='edit-button' href='edit_absen.php?id=" . htmlspecialchars($absen['id']) . "'>Edit</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data absen untuk pengguna ini.</td></tr>";
            }
            ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>