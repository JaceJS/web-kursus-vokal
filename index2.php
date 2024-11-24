<?php
// Mulai sesi
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Set zona waktu ke WITA
date_default_timezone_set('Asia/Makassar');
// Sesi user
$user_id = $_SESSION['user_id'];

// Query untuk mengambil informasi pengguna
$sql = "SELECT status, nama FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  header("Location: logout.php");
  exit;
}

$user = $result->fetch_assoc();
$status = $user['status'];
$nama = $user['nama'];

$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kursus Vokal Terbaik</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url("gambar/bannerbackground.png");
      /* Gambar latar belakang */
      background-size: cover;
      /* Menyesuaikan gambar latar belakang agar menutupi seluruh area */
      background-position: center;
      /* Memposisikan gambar di tengah */
      background-attachment: fixed;
      /* Memastikan gambar tetap saat scroll */
    }

    header {
      background-color: rgba(255,
          255,
          255,
          0.8);
      /* Latar belakang header dengan transparansi */
      padding: 10px 20px;
    }

    .header-container {
      display: flex;
      align-items: center;
      /* Menyelaraskan item secara vertikal di tengah */
      justify-content: space-between;
      /* Memastikan logo dan menu berada di ujung yang berbeda */
      max-width: 1200px;
      /* Lebar maksimum container */
      margin: 0 auto;
      /* Tengah-tengah container */
    }

    .logo {
      height: 120px;
      /* Menetapkan tinggi logo */
      width: auto;
      /* Menjaga rasio aspek logo */
      margin-right: 30px;
      /* Jarak antara logo dan menu */
    }

    nav {
      display: flex;
      align-items: center;
      /* Menyelaraskan item menu secara vertikal di tengah */
    }

    nav a {
      text-decoration: none;
      color: #333;
      /* Warna teks menu */
      padding: 0 15px;
      /* Jarak horizontal antar item menu */
      font-size: 18px;
      /* Ukuran font menu */
      line-height: 120px;
      /* Menyelaraskan teks menu dengan tinggi logo */
      transition: color 0.3s;
      /* Efek transisi saat hover */
    }

    nav a:hover {
      color: #007bff;
      /* Warna teks saat hover */
    }

    .container {
      background-color: rgba(255,
          255,
          255,
          0.9);
      /* Warna latar belakang putih dengan transparansi */
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      /* Menambahkan sudut melengkung pada kontainer */
    }

    .hero {
      text-align: center;
      margin-bottom: 20px;
    }

    .hero img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .hero h1 {
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.2em;
      color: #555;
    }

    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      width: 100%;
      bottom: 0;
    }

    .notification {
      padding: 15px;
      background-color: #f44336;
      /* Merah */
      color: white;
      margin-bottom: 20px;
      border-radius: 4px;
      position: relative;
    }

    .notification.success {
      background-color: #4CAF50;
    }

    /* Hijau */
    .notification.info {
      background-color: #2196F3;
    }

    /* Biru */
    .notification.warning {
      background-color: #ff9800;
    }

    /* Oranye */
    .notification .closebtn {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <header>
    <div class="header-container">
      <img src="gambar/logoheader.png" alt="Logo" class="logo" />
      <nav>
        <a href="index2.php">Beranda</a>
        <a href="kursus2.html">Kursus</a>
        <a href="kontak2.html">Kontak</a>
        <a href="absendanjadwal.php">Absen</a>
        <a href="login.php">Logout</a>
      </nav>
    </div>
  </header>

  <div class="container">
    <?php if ($status === 'Tidak Aktif'): ?>
      <div class="notification">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong>Perhatian!</strong> Akun Anda sudah tidak aktif. Silakan hubungi admin untuk informasi lebih lanjut.
      </div>
    <?php endif; ?>

    <div class="hero">
      <img src="gambar/bannerberanda.png" alt="Gambar Kursus Vokal" />
      <h1>SELAMAT DATANG DI KURSUS VOCAL SIMPHONY</h1>
      <p>
        Kembangkan bakat vokal Anda dengan bimbingan dari instruktur
        profesional dan kursus yang terstruktur.
      </p>
    </div>
    <section>
      <h2>Tentang Kami</h2>
      <p>
        Di Kursus Vokal Terbaik, kami menawarkan pelatihan vokal yang
        dirancang untuk membantu Anda mencapai potensi penuh Anda. Kami
        menyediakan berbagai kursus mulai dari tingkat pemula hingga tingkat
        lanjut, yang disampaikan oleh instruktur berpengalaman.
      </p>
    </section>
    <section>
      <h2>Fasilitas Kami</h2>
      <ul>
        <li>Kelas pribadi dan kelompok</li>
        <li>Studio rekaman profesional</li>
        <li>Materi pembelajaran lengkap</li>
        <li>Sesi latihan tambahan</li>
      </ul>
    </section>
  </div>
  <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>

  <script>
    // Script untuk menutup pemberitahuan
    var close = document.getElementsByClassName("closebtn");
    for (var i = 0; i < close.length; i++) {
      close[i].onclick = function() {
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function() {
          div.style.display = "none";
        }, 600);
      }
    }
  </script>
</body>

</html>