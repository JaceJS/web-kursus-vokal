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

// Check if the attendance ID is provided
if (isset($_GET['id'])) {
    $absen_id = intval($_GET['id']);
} else {
    echo "Invalid attendance ID.";
    exit;
}

// Fetch attendance and user data based on the attendance ID
$absen_query = "SELECT user_id, pertemuan_ke, tanggal, topik, instruktur, status, absen_date FROM absen WHERE id = $absen_id";
$absen_result = $conn->query($absen_query);

// Check if the record exists
if ($absen_result->num_rows == 0) {
    echo "Attendance record not found.";
    exit;
}

// Fetch the attendance and user data
$absen_data = $absen_result->fetch_assoc();
$user_id = $absen_data['user_id']; // Set the $user_id variable

// Update the record if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pertemuan_ke = intval($_POST['pertemuan_ke']);
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $topik = $conn->real_escape_string($_POST['topik']);
    $instruktur = $conn->real_escape_string($_POST['instruktur']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update query, excluding absen_date
    $update_query = "UPDATE absen SET pertemuan_ke = $pertemuan_ke, tanggal = '$tanggal', topik = '$topik', instruktur = '$instruktur', status = '$status' WHERE id = $absen_id";

    if ($conn->query($update_query) === TRUE) {
        header("Location: adminpagedetailpengguna.php?id=" . $user_id); // Redirect to the correct page with user_id
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absen</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Absen</h2>
        <form method="post">
            <div class="form-group">
                <label for="pertemuan_ke">Pertemuan Ke</label>
                <input type="number" name="pertemuan_ke" id="pertemuan_ke" value="<?php echo htmlspecialchars($absen_data['pertemuan_ke']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="<?php echo htmlspecialchars($absen_data['tanggal']); ?>" required>
            </div>
            <div class="form-group">
                <label for="topik">Topik</label>
                <input type="text" name="topik" id="topik" value="<?php echo htmlspecialchars($absen_data['topik']); ?>" required>
            </div>
            <div class="form-group">
                <label for="instruktur">Instruktur</label>
                <input type="text" name="instruktur" id="instruktur" value="<?php echo htmlspecialchars($absen_data['instruktur']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="Hadir" <?php if ($absen_data['status'] === 'Hadir') echo 'selected'; ?>>Hadir</option>
                    <option value="Tidak Hadir" <?php if ($absen_data['status'] === 'Tidak Hadir') echo 'selected'; ?>>Tidak Hadir</option>
                </select>
            </div>

            <button type="submit" class="submit-button">Update</button>
        </form>
    </div>
</body>

</html>