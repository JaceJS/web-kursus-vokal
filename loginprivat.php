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

// Variabel untuk menyimpan pesan kesalahan
$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil dan membersihkan input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Cek pengguna di database
    $sql = "SELECT * FROM userprivat WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi kata sandi
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index3.html"); // Redirect ke halaman index3.html
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
        header {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
        }
        nav {
            display: flex;
            justify-content: center;
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
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
        .login-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
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
        .login-form button {
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
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
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
        <div class="login-form">
            <h1>Login</h1>
            <form action="loginprivat.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required />

                <button type="submit">Masuk</button>

                <?php
                // Menampilkan pesan error jika ada
                if ($error) {
                    echo "<p style='color: red; text-align: center;'>$error</p>";
                }
                ?>

                <div class="message">
                    <p>
                        Belum punya akun murid? <a href="signupprivat.php">Buat akun sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>
</body>
</html>
