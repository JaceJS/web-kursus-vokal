<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Makassar');

include 'koneksi.php';
include 'utils/tanggal.php';

$filter_option = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where_clause = "WHERE u.status = 'Tidak Aktif'";

if ($filter_option === 'today') {
    $where_clause .= " AND DATE(p.created_at) = CURDATE()";
} elseif ($filter_option === 'this_week') {
    $where_clause .= " AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter_option === 'this_month') {
    $where_clause .= " AND MONTH(p.created_at) = MONTH(CURDATE()) AND YEAR(p.created_at) = YEAR(CURDATE())";
} elseif ($filter_option === 'this_year') {
    $where_clause .= " AND YEAR(p.created_at) = YEAR(CURDATE())";
}

// Query to fetch all users from the `users` table
$users_query = "SELECT u.id, u.nama, u.email, p.course, p.hari, p.jam, p.created_at, u.status
FROM users u
JOIN pendaftaran p ON p.user_id = u.id
$where_clause
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>
    <?php include 'components/sidebar.php'; ?>

    <div class="content">
        <h1>Data Pengguna <span class="btn btn-danger" style="cursor: auto;">TIDAK AKTIF</span></h1>

        <form method="GET" action="adminpagedatapenggunatidakaktif.php" class="mb-4 text-center">
            <label for="filter">Filter Data:</label>
            <select name="filter" id="filter" class="form-select w-auto d-inline">
                <option value="all" <?= $filter_option === 'all' ? 'selected' : '' ?>>Semua</option>
                <option value="today" <?= $filter_option === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                <option value="this_week" <?= $filter_option === 'this_week' ? 'selected' : '' ?>>Minggu Ini</option>
                <option value="this_month" <?= $filter_option === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                <option value="this_year" <?= $filter_option === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
            </select>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>

        <table id="usersTable" class="display">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Kursus</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Tanggal Pendaftaran</th>
                    <th>Status</th>
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
                            <td>
                                <?php
                                echo formatTanggalIndo($row['created_at']);
                                ?>
                            </td>
                            <td>
                                <?php
                                $status_class = ($row['status'] == 'Aktif') ? 'btn-success' : 'btn-danger';
                                $status_text = htmlspecialchars($row['status']);
                                ?>
                                <button class="btn <?php echo $status_class; ?>" disabled><?php echo $status_text; ?></button>
                            </td>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        function goToDetail(userId) {
            window.location.href = 'adminpagedetailpengguna.php?id=' + userId;
        }

        $(document).ready(function() {
            $('#usersTable').DataTable();
        });
    </script>
</body>

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

</html>