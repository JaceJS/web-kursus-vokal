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
    $phone = $conn->real_escape_string($_POST['phone']);

    if ($password !== $confirm_password) {
        $error = "Kata sandi dan konfirmasi kata sandi tidak cocok.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email is already registered in the users table
        $checkEmail = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($checkEmail->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $sql = "INSERT INTO users (email, password, nama, phone, status) 
                    VALUES ('$email', '$hashed_password', '$nama', '$phone', 'Aktif')";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
    <div class="main-wrapper">
        <?php include 'components/navbar.php'; ?>
    </div>

    <hr class="m-0">

    <div class="main-wrapper py-5">
        <div class="signup-form">
            <h1>Buat Akun Murid</h1>
            <form action="signup.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Konfirmasi Kata Sandi:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" required>

                <button class="mt-3" type="submit">Daftar</button>

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

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>