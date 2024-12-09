<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'utils/tanggal.php';

$tanggal_hari_ini = (new DateTime())->format('Y-m-d');
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

$jadwal_query = "
    SELECT u.id as user_id, u.nama, p.course, p.hari, p.jam, u.created_at, a.status, a.topik, a.instruktur
    FROM users u 
    LEFT JOIN absen a ON u.id = a.user_id AND a.tanggal = '$tanggal_hari_ini'
    LEFT JOIN pendaftaran p ON u.id = p.user_id
    WHERE p.hari = '$hari_dalam_bahasa' 
    AND u.status = 'Aktif' 
    AND DATE(u.created_at) <= '$tanggal_hari_ini'
    ORDER BY p.jam";
$jadwal_result = $conn->query($jadwal_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $nama = $_POST['nama'];
    $pertemuan_ke = $_POST['pertemuan_ke'];
    $topik = $_POST['topik'];
    $instruktur = $_POST['instruktur'];
    $course = $_POST['course'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'Hadir';

    $stmt = $conn->prepare("INSERT INTO absen (user_id, nama, pertemuan_ke, tanggal, topik, instruktur, status, course) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $user_id, $nama, $pertemuan_ke, $tanggal_hari_ini, $topik, $instruktur, $status, $course);
    $stmt->execute();
    $stmt->close();

    // Update status jika mencapai pertemuan ke-4
    if ($pertemuan_ke >= 4) {
        $update_status = $conn->prepare("UPDATE users SET status = 'Tidak Aktif' WHERE id = ?");
        $update_status->bind_param("i", $user_id);
        $update_status->execute();
        $update_status->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

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

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .hadir-btn {
            background-color: #4caf50;
        }

        .alpa-btn {
            background-color: #e74c3c;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        .button-container {
            display: flex;
            gap: 10px;
            /* Mengatur jarak antara tombol */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'components/sidebar.php'; ?>

    <div class="content">
        <h1>
            Jadwal Latihan Hari Ini: <?php echo formatTanggalIndo($tanggal_hari_ini); ?>
        </h1>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kursus</th>
                    <th>Pertemuan Ke</th>
                    <th>Jam</th>
                    <th>Topik</th>
                    <th>Instruktur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = new DateTime();

                if ($jadwal_result->num_rows > 0):
                    $has_schedule_today = false;
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
                            $existing_topik = isset($row['topik']) ? $row['topik'] : '';
                            $existing_instruktur = isset($row['instruktur']) ? $row['instruktur'] : '';
                            $status_kehadiran = isset($row['status']) ? $row['status'] : null;
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                <td><?php echo $pertemuan_ke; ?></td>
                                <td><?php echo htmlspecialchars($row['jam']); ?></td>

                                <!-- Form input untuk topik dan instruktur -->
                                <form method="POST" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                    <input type="hidden" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>">
                                    <input type="hidden" name="pertemuan_ke" value="<?php echo $pertemuan_ke; ?>">
                                    <input type="hidden" name="course" value="<?php echo htmlspecialchars($row['course']); ?>">

                                    <!-- Prefill topik and instruktur if already exists -->
                                    <td><input type="text" name="topik" value="<?php echo htmlspecialchars($existing_topik); ?>" placeholder="Topik latihan" <?php if ($status_kehadiran !== null) echo 'disabled'; ?>></td>
                                    <td><input type="text" name="instruktur" value="<?php echo htmlspecialchars($existing_instruktur); ?>" placeholder="Nama Instruktur" <?php if ($status_kehadiran !== null) echo 'disabled'; ?>></td>

                                    <td>
                                        <div class="button-container">
                                            <?php if ($status_kehadiran === null): ?>
                                                <button type="submit" name="status" value="Hadir" class="action-btn hadir-btn">Hadir</button>
                                                <button type="submit" name="status" value="Tidak Hadir" class="action-btn alpa-btn">Alpa</button>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($status_kehadiran); ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                    <?php
                        endif;
                    endwhile;
                    ?>

                    <?php if (!$has_schedule_today): ?>
                        <tr>
                            <td colspan="7">Tidak ada jadwal untuk hari ini.</td>
                        </tr>
                    <?php endif; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada jadwal untuk hari ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</body>

</html>