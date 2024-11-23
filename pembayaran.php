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

// Proses pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $payment_proof = $_FILES['payment_proof']['name'];

    // Tentukan folder tujuan untuk menyimpan bukti pembayaran
    $target_dir = "pembayaran/";
    $target_file = $target_dir . basename($payment_proof);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar atau bukan
    $check = getimagesize($_FILES["payment_proof"]["tmp_name"]);
    if($check !== false) {
        // Pindahkan file yang diunggah ke folder pembayaran
        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            // Simpan informasi pembayaran ke database
            $sql = "INSERT INTO pembayaran (payment_method, payment_proof) 
                    VALUES ('$payment_method', '$payment_proof')";
            if ($conn->query($sql) === TRUE) {
                // Redirect ke halaman login setelah pembayaran berhasil
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Kesalahan: " . $conn->error;
            }
        } else {
            $error_message = "Gagal mengunggah bukti pembayaran.";
        }
    } else {
        $error_message = "File bukan gambar.";
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
      }
      header {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 10px 20px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
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
        width: 1200px;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding-top: 100px;
        margin-top: 80px;
      }
      .payment-form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      .payment-form h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 2em;
      }
      .payment-form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
      }
      .payment-form input,
      .payment-form select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      .payment-form .radio-group {
        margin-bottom: 20px;
      }
      .payment-form .radio-group label {
        display: inline-block;
        margin-right: 15px;
      }
      .payment-form button {
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
      .payment-form button:hover {
        background-color: #555;
      }
      .payment-form .info {
        margin-top: 20px;
        text-align: center;
      }
      .payment-form .info p {
        margin-bottom: 10px;
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
            <img src="gambar/logoheader.png" alt="Logo" class="logo">
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
        <div class="payment-form">
            <h1>Halaman Pembayaran</h1>
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form action="pembayaran.php" method="post" enctype="multipart/form-data">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="payment_method" value="dana" required>
                        <img src="gambar/logo_dana.png" alt="DANA" style="height: 50px;">
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="debit_card" required>
                        <img src="gambar/logo_bri.png" alt="BRI" style="height: 50px;">
                    </label>
                </div>

                <div id="dana-details" style="display: none">
                    <p><strong>Informasi Penerima DANA</strong></p>
                    <p>Nomor DANA: 085242071305</p>
                    <p>Atas Nama: Morreno Ponto</p>
                </div>

                <div id="debit-details" style="display: none">
                    <p><strong>Informasi Penerima BRI</strong></p>
                    <p>Bank: BRI</p>
                    <p>Nomor Rekening: 517901036576538</p>
                    <p>Atas Nama: Morreno Ponto</p>
                </div>

                <label for="payment_proof"><br>Unggah Bukti Pembayaran (JPG/PNG):</label>
                <input type="file" id="payment_proof" name="payment_proof" accept=".jpg, .jpeg, .png" required>

                <button type="submit">Bayar Sekarang</button>

                <div class="info">
                    <p>Metode pembayaran yang tersedia adalah DANA dan Rekening BRI.</p>
                </div>
            </form>
        </div>
    </div>
    <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>

    <script>
      // Menampilkan detail pembayaran berdasarkan metode yang dipilih
      document
        .querySelectorAll('input[name="payment_method"]')
        .forEach(function (elem) {
          elem.addEventListener("change", function () {
            var paymentMethod = this.value;
            if (paymentMethod === "dana") {
              document.getElementById("dana-details").style.display = "block";
              document.getElementById("debit-details").style.display = "none";
            } else if (paymentMethod === "debit_card") {
              document.getElementById("dana-details").style.display = "none";
              document.getElementById("debit-details").style.display = "block";
            }
          });
        });
    </script>
</body>
</html>
