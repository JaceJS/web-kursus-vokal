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

// Get the record to be edited

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $topik = $_POST['topik'];
    $instruktur = $_POST['instruktur'];

    $update_query = "UPDATE jadwal SET tanggal = '$tanggal', topik = '$topik', instruktur = '$instruktur' WHERE id = '$jadwal_id'";

    if ($conn->query($update_query) === TRUE) {
        header("Location: adminpagejadwal.php");
        exit;
    } else {
        $error_message = "Kesalahan: " . $conn->error;
    }
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
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("gambar/bannerbackground.png"); /* Gambar latar belakang */
        background-size: cover; /* Menyesuaikan gambar latar belakang agar menutupi seluruh area */
        background-position: center; /* Memposisikan gambar di tengah */
        background-attachment: fixed; /* Memastikan gambar tetap saat scroll */
        }
        .edit-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .edit-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .edit-container form {
            display: flex;
            flex-direction: column;
        }
        .edit-container input {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .edit-container button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-container button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit Jadwal</h1>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="adminpageeditjadwal.php?id=<?php echo $jadwal_id; ?>" method="post">
            <input type="date" name="tanggal" value="<?php echo $jadwal_data['tanggal']; ?>" required>
            <input type="text" name="topik" placeholder="Topik" required>
            <input type="text" name="instruktur" placeholder="Instruktur"  required>
            <button type="submit">Update Jadwal</button>
        </form>
    </div>
</body>
</html>
