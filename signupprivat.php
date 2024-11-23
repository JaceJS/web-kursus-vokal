<?php
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

// Proses pendaftaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Kata sandi dan konfirmasi kata sandi tidak cocok.";
    } else {
        // Enkripsi kata sandi
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah email sudah terdaftar
        $checkEmail = $conn->query("SELECT id FROM userprivat WHERE email='$email'");
        if ($checkEmail->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Insert ke database
            $sql = "INSERT INTO userprivat (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                // Redirect ke loginprivat.php setelah berhasil signup
                header("Location: loginprivat.php");
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
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      .container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
      }
      .signup-form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
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
          <a href="loginprivat.php">Login</a>
        </nav>
      </div>
    </header>
    <div class="container">
      <div class="signup-form">
        <h1>Buat Akun Murid</h1>
        <form action="signupprivat.php" method="post">
          <label for="name">Nama Lengkap:</label>
          <input type="text" id="name" name="name" required />

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required />

          <label for="password">Kata Sandi:</label>
          <input type="password" id="password" name="password" required />

          <label for="confirm_password">Konfirmasi Kata Sandi:</label>
          <input type="password" id="confirm_password" name="confirm_password" required />

          <button type="submit">Daftar</button>

          <?php if (isset($error)): ?>
          <div class="error"><?php echo $error; ?></div>
          <?php elseif (isset($success)): ?>
          <div class="success"><?php echo $success; ?></div>
          <?php endif; ?>

          <div class="message">
            <p>Sudah punya akun? <a href="loginprivat.php">Masuk Sekarang</a></p>
          </div>
        </form>
      </div>
    </div>
    <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>
  </body>
</html>
