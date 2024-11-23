<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Inisialisasi array untuk slot yang sudah dibooking berdasarkan hari dan jenis kursus
$booked_slots_reguler = [];
$booked_slots_private = [];

// Ambil semua slot hari dan jam yang sudah diambil untuk kursus reguler
// Ambil semua slot hari dan jam yang sudah diambil untuk kursus reguler dengan status settled
$sql_reguler = "SELECT hari, jam FROM pendaftaran WHERE course = 'reguler' AND status = 'settled'";
$result_reguler = $conn->query($sql_reguler);
if ($result_reguler->num_rows > 0) {
    while ($row = $result_reguler->fetch_assoc()) {
        $booked_slots_reguler[$row['hari']][] = $row['jam'];
    }
}

// Ambil semua slot hari dan jam yang sudah diambil untuk kursus private dengan status settled
$sql_private = "SELECT hari, jam FROM pendaftaran WHERE course = 'private' AND status = 'settled'";
$result_private = $conn->query($sql_private);
if ($result_private->num_rows > 0) {
    while ($row = $result_private->fetch_assoc()) {
        $booked_slots_private[$row['hari']][] = $row['jam'];
    }
}


// Proses form pendaftaran
require_once dirname(__FILE__) . '/vendor/autoload.php';

// Set Midtrans server key
\Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
\Midtrans\Config::$isProduction = false; // Set true if production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$snapToken = null; // Initial null

session_start();

// Proses form pendaftaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $course = $conn->real_escape_string($_POST['course']);
    $hari = $conn->real_escape_string($_POST['hari']);
    $jam = $conn->real_escape_string($_POST['jam']);
    $message = $conn->real_escape_string($_POST['message']);

    // Create a temporary entry with status 'pending' or save in session
    $_SESSION['registration_data'] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'course' => $course,
        'hari' => $hari,
        'jam' => $jam,
        'message' => $message
    ];

    $order_id = 'ORDER-' . time(); // Unique order ID

    // Simpan data pendaftaran dengan status pending ke database
    $sql = "INSERT INTO pendaftaran (name, email, phone, course, hari, jam, message, order_id, status) 
            VALUES ('$name', '$email', '$phone', '$course', '$hari', '$jam', '$message', '$order_id', 'pending')";
    if (!$conn->query($sql)) {
        $error_message = "Database error: " . $conn->error;
    }

    // Tentukan harga berdasarkan jenis kursus
    $course_price = ($course == 'reguler') ? 400000 : 350000;

    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $course_price
    );

    $item_details = array(
        array(
            'id' => 'a01',
            'price' => $course_price,
            'quantity' => 1,
            'name' => $course == 'reguler' ? 'Kursus Vokal Reguler' : 'Kursus Vokal Private'
        )
    );

    $customer_details = array(
        'first_name' => $name,
        'email' => $email,
        'phone' => $phone
    );

    $transaction = array(
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
    );

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    } catch (Exception $e) {
        $error_message = "Midtrans error: " . $e->getMessage();
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
        /* CSS sama seperti sebelumnya */
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .registration-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .registration-form h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2em;
        }

        .registration-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .registration-form input,
        .registration-form select,
        .registration-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .registration-form button {
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

        .registration-form button:hover {
            background-color: #555;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
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

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OMcK_7hgLXcWo96E"></script>
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
        <div class="registration-form">
            <h1>Pendaftaran Kursus Vokal</h1>
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form action="pendaftaran.php" method="post">
                <label for="name">Nama Lengkap:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Nomor Telepon:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="course">Pilih Kursus:</label>
                <select id="course" name="course" required onchange="updateHariOptions()">
                    <option value="">Pilih kursus...</option>
                    <option value="reguler">KURSUS VOKAL REGULER</option>
                    <option value="private">KURSUS VOKAL PRIVATE</option>
                </select>

                <label for="hari">Pilih Hari:</label>
                <select id="hari" name="hari" required onchange="updateJamOptions()">
                    <option value="">Pilih hari...</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>

                <label for="jam">Pilih Jam:</label>
                <select id="jam" name="jam" required>
                    <option value="">Pilih jam...</option>
                </select>

                <label for="message">Pesan Tambahan:</label>
                <textarea id="message" name="message" rows="4"></textarea>

                <button type="submit">Daftar Sekarang</button>
            </form>
            <?php if ($snapToken): ?>
                <script type="text/javascript">
                    document.addEventListener('DOMContentLoaded', function() {
                        // Initiate Snap payment pop-up when form is submitted
                        snap.pay('<?php echo $snapToken; ?>', {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil");
                                window.location.href = "https://kvsmanado.my.id/signup.php"; // Redirect ke halaman signup
                            },
                            onPending: function(result) {
                                alert("Pembayaran tertunda");
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal");
                            },
                            onClose: function() {
                                alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                            }
                        });
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
    <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>
    <script>
        // Data booked_slots diambil dari PHP untuk reguler dan private
        var bookedSlotsReguler = <?php echo json_encode($booked_slots_reguler); ?>;
        var bookedSlotsPrivate = <?php echo json_encode($booked_slots_private); ?>;

        var bookedSlots = {}; // Variabel sementara untuk menampung slot berdasarkan pilihan kursus

        // Disable hari dan jam pada awalnya
        window.onload = function() {
            var hariSelect = document.getElementById('hari');
            var jamSelect = document.getElementById('jam');

            // Disable hari dan jam pada awalnya
            hariSelect.disabled = true;
            jamSelect.disabled = true;
        };

        // Fungsi untuk mengaktifkan hari setelah kursus dipilih
        function updateHariOptions() {
            var course = document.getElementById('course').value;
            var hariSelect = document.getElementById('hari');
            var jamSelect = document.getElementById('jam');

            // Pilih slot yang sesuai dengan kursus
            if (course === "reguler") {
                bookedSlots = bookedSlotsReguler; // Set bookedSlots dengan data reguler
            } else if (course === "private") {
                bookedSlots = bookedSlotsPrivate; // Set bookedSlots dengan data private
            } else {
                bookedSlots = {}; // Kosongkan jika tidak ada kursus yang dipilih
            }

            // Kosongkan dropdown hari dan jam saat kursus diubah
            hariSelect.value = ""; // Reset pilihan hari
            jamSelect.innerHTML = '<option value="">Pilih jam...</option>'; // Kosongkan dropdown jam
            jamSelect.disabled = true; // Disable pilihan jam sampai hari dipilih

            if (course !== "") {
                hariSelect.disabled = false; // Enable pilihan hari
            } else {
                hariSelect.disabled = true; // Disable pilihan hari
                jamSelect.disabled = true; // Disable pilihan jam juga
            }
        }

        // Fungsi untuk menampilkan jam yang tersedia berdasarkan hari yang dipilih
        function updateJamOptions() {
            var hari = document.getElementById('hari').value;
            var jamSelect = document.getElementById('jam');
            var jamOptions = ["12:00-13:00", "13:00-14:00", "14:00-15:00",
                "15:00-16:00", "16:00-17:00", "17:00-18:00",
                "18:00-19:00"
            ];

            if (hari !== "") {
                jamSelect.disabled = false; // Enable pilihan jam setelah hari dipilih
            } else {
                jamSelect.disabled = true; // Disable pilihan jam jika hari tidak dipilih
                return;
            }

            // Kosongkan pilihan jam
            jamSelect.innerHTML = '<option value="">Pilih jam...</option>';

            // Loop untuk menambahkan opsi jam
            jamOptions.forEach(function(jam) {
                var option = document.createElement('option');
                option.value = jam;
                option.text = jam;

                // Cek apakah jam ini sudah dibooking untuk hari yang dipilih
                if (bookedSlots[hari] && bookedSlots[hari].includes(jam)) {
                    option.disabled = true;
                    option.text += " (Sudah dibooking)";
                }

                // Tambahkan opsi ke dropdown
                jamSelect.appendChild(option);
            });
        }
    </script>

</body>

</html>