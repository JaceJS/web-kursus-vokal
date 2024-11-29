<?php

session_start();
include 'koneksi.php';

$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mengambil dan membersihkan input
  $email = $conn->real_escape_string($_POST['email']);
  $password = $_POST['password'];

  // Cek pengguna di database
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verifikasi kata sandi
    if (password_verify($password, $user['password'])) {
      session_start();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      header("Location: absendanjadwal.php");
      exit;
    } else {
      $error = "Kata sandi salah.";
    }
  } else {
    $error = "Email tidak ditemukan.";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .login-form {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: rgba(255,
          255,
          255,
          0.9);
      /* Latar belakang formulir dengan transparansi */
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-form h1 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 2em;
    }

    .login-form label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .login-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .login-form .masuk-button {
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

    .login-form button:hover {
      background-color: #555;
    }

    .login-form .message {
      text-align: center;
      margin-top: 10px;
    }

    .login-form .message a {
      color: #333;
      text-decoration: none;
      font-weight: bold;
    }

    .login-form .message a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="main-wrapper py-5">
    <div class="login-form">
      <h1>Login</h1>
      <?php
      if (isset($_SESSION['error_daftar_kursus'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo $_SESSION['error_daftar_kursus']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_daftar_kursus']); // Menghapus session error setelah ditampilkan 
        ?>
      <?php
      endif;
      ?>
      <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Kata Sandi:</label>
        <input type="password" id="password" name="password" required />

        <button class="masuk-button" type="submit">Masuk</button>

        <?php
        if ($error) {
          echo "<p style='color: red; text-align: center;'>$error</p>";
        }
        ?>
        <div class="message">
          <p>
            Belum punya akun murid? <a href="signup.php">Buat akun sekarang</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>