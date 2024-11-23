<?php
// Mulai sesi
session_start();

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

// Set zona waktu ke WITA
date_default_timezone_set('Asia/Makassar');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginprivat.php");
    exit;
}

// Proses absensi
if (isset($_POST['absen'])) {
    $user_id = $_POST['nama'];
    $topik = $_POST['topik'];
    $instruktur = $_POST['instruktur'];
    $absen_date = date('Y-m-d H:i:s');
    $foto = $_FILES['foto']['name'];

    // Upload gambar
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar atau bukan
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if($check !== false) {
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    } else {
        $absen_error = "File bukan gambar.";
    }

    // Insert data absen
    $absen_query = "INSERT INTO absensiprivat (nama, topik, instruktur, absen_date, foto) VALUES ('$nama', '$topik', '$instruktur', '$absen_date', '$foto')";
    if ($conn->query($absen_query) === TRUE) {
        $_SESSION['absen_success'] = "Absensi berhasil!";
        header("Location: absendanjadwal2.php"); // Redirect untuk mencegah pengulangan pengiriman formulir
        exit;
    } else {
        $absen_error = "Kesalahan: " . $conn->error;
    }
}

// Ambil daftar pengguna
$user_query = "SELECT id, name AS full_name FROM userprivat";
$user_result = $conn->query($user_query);

// Ambil daftar absensi
$absen_result = $conn->query("SELECT a.id, u.name AS full_name, a.topik, a.instruktur, a.absen_date, a.foto FROM absensiprivat a JOIN userprivat u ON a.nama = u.id ORDER BY a.absen_date DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus Vokal Terbaik</title>
    <link rel="stylesheet" href="style.css">
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url("gambar/bannerbackground.png");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #333;
      }
      header {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 10px 20px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      nav {
        display: flex;
        gap: 20px;
      }
      nav a {
        color: #333;
        text-decoration: none;
        font-weight: bold;
        padding: 0 15px;
        font-size: 18px;
      }
      nav a:hover {
        text-decoration: underline;
      }
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        margin-top: 150px; /* Adjust to avoid overlap with the fixed header */
      }
      .section {
        margin-bottom: 50px;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }
      .section h2 {
        font-size: 2em;
        margin-bottom: 20px;
        color: #444;
        text-align: center;
      }
      .absen-form {
        margin: 20px 0;
      }
      .absen-form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #333;
      }
      .absen-form input[type="text"],
      .absen-form select,
      .absen-form input[type="file"],
      .absen-form button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
      }
      .absen-form button {
        background-color: #5cb85c;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }
      .absen-form button:hover {
        background-color: #4cae4c;
      }
      .success {
        color: green;
        text-align: center;
        margin-top: 20px;
        font-weight: bold;
      }
      .error {
        color: red;
        text-align: center;
        margin-top: 20px;
        font-weight: bold;
      }
      .absen-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }
      .absen-table th,
      .absen-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: justify;
        vertical-align: middle;
      }
      .absen-table th {
        background-color: #333;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.1em;
      }
      .absen-table td {
        font-size: 16px;
        color: #555;
      }
      .absen-table img {
        width: 150px; /* Perbesar gambar */
        height: 150px; /* Perbesar gambar */
        object-fit: cover;
        border-radius: 4px;
      }
      footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 15px 0;
        position: fixed;
        width: 100%;
        bottom: 0;
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
      }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="gambar/logoheader.png" alt="Logo" class="logo">
            <nav>
                <a href="index3.html">Beranda</a>
                <a href="kursus3.html">Kursus</a>
                <a href="kontak3.html">Kontak</a>
                <a href="absendanjadwal2.php">Absen</a>
                <a href="loginprivat.php">Logout</a>
            </nav>
        </div>
    </header>
    <div class="container">
        <!-- Bagian Absen -->
        <div class="section">
            <h2>Absensi</h2>
            <form action="absendanjadwal2.php" method="post" class="absen-form" enctype="multipart/form-data">
            <label for="nama">Nama:</label>
             <input type="text" id="nama" name="nama" required>  

              

                <label for="topik"><br>Topik:</label>
                <input type="text" id="topik" name="topik" required>

                <label for="instruktur"><br>Instruktur:</label>
                <input type="text" id="instruktur" name="instruktur" required>

                <label for="foto"><br>Upload Foto Dokumentasi (jpg/png):</label>
                <input type="file" id="foto" name="foto" accept=".jpg, .jpeg, .png" required>

                <button type="submit" name="absen">Absen</button>

                <?php if (isset($_SESSION['absen_success'])): ?>
                    <div class="success"><?php echo $_SESSION['absen_success']; unset($_SESSION['absen_success']); ?></div>
                <?php elseif (isset($absen_error)): ?>
                    <div class="error"><?php echo $absen_error; ?></div>
                <?php endif; ?>
            </form>

            
        </div>
    </div>
    <footer>
        &copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.
    </footer>
</body>
</html>
