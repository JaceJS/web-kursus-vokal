<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Process registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $conn->real_escape_string($_POST['email']);
  $password = $conn->real_escape_string($_POST['password']);
  $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

  // Query to check if email exists in the pendaftaran table with a 'settled' status
  $checkRegistration = $conn->query(
    "SELECT name, course, hari, jam, order_id FROM pendaftaran 
         WHERE email='$email' AND status='settled'"
  );

  if ($checkRegistration->num_rows == 0) {
    // Email not registered or payment not completed
    $error = "Email tidak terdaftar atau belum melakukan pembayaran.";
  } elseif ($password !== $confirm_password) {
    // Password confirmation does not match
    $error = "Kata sandi dan konfirmasi kata sandi tidak cocok.";
  } else {
    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Fetch course details and order_id from pendaftaran table
    $row = $checkRegistration->fetch_assoc();
    $nama = $row['name'];
    $course = $row['course'];
    $hari = $row['hari'];
    $jam = $row['jam'];
    $order_id = $row['order_id'];

    // Check if email is already registered in the users table
    $checkEmail = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
      $error = "Email sudah terdaftar.";
    } else {
      // Insert the new user into the users table with status 'Aktif' and include order_id
      $sql = "INSERT INTO users (email, password, nama, course, hari, jam, status, order_id) 
                    VALUES ('$email', '$hashed_password', '$nama', '$course', '$hari', '$jam', 'Aktif', '$order_id')";
      if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit;
      } else {
        $error = "Kesalahan: " . $conn->error;
      }
    }
  }
}

$conn->close();
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
      /* Menetapkan gambar latar belakang */
      background-size: cover;
      /* Menyesuaikan gambar agar menutupi seluruh area */
      background-position: center;
      /* Memposisikan gambar di tengah */
      background-attachment: fixed;
      /* Memastikan gambar tetap di tempat saat scroll */
    }

    .container {
      width: 1200px;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      /* Mengatur tinggi minimal container */
    }

    .signup-form {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      background-color: rgba(255,
          255,
          255,
          0.9);
      /* Menambahkan transparansi sesuai dengan .login-form */
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .signup-form h1 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 2em;
    }

    .signup-form label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .signup-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .signup-form button {
      background-color: #333;
      color: #fff;
      border: none;
      padding: 15px;
      width: 100%;
      font-size: 1.2em;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .signup-form button:hover {
      background-color: #555;
    }

    .signup-form .message {
      text-align: center;
      margin-top: 20px;
    }

    .signup-form .message a {
      color: #333;
      text-decoration: none;
      font-weight: bold;
    }

    .signup-form .message a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 20px;
    }

    .success {
      color: green;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <header>
    <div class="header-container">
      <img src="gambar/logoheader.png" alt="Logo" class="logo" />
      <nav>
        <a href="index.html">Beranda</a>
        <a href="kursus.html">Kursus</a>
        <a href="pendaftaran.php">Pendaftaran</a>
        <a href="kontak.html">Kontak</a>
        <a href="login.php">Login</a>
      </nav>
    </div>
  </header>
  <div class="container">
    <div class="signup-form">
      <h1>Buat Akun Murid</h1>
      <form action="signup.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Kata Sandi:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Konfirmasi Kata Sandi:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Daftar</button>

        <?php if (isset($error)): ?>
          <div class="error"><?php echo $error; ?></div>
        <?php elseif (isset($success)): ?>
          <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="message">
          <p>Sudah punya akun? <a href="login.php">Masuk Sekarang</a></p>
        </div>
      </form>
    </div>
  </div>
  <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>
</body>

</html>