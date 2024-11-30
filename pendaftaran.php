<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

ob_clean();

// Inisialisasi array untuk slot yang sudah dibooking berdasarkan hari dan jenis kursus
$booked_slots_reguler = [];
$booked_slots_private = [];

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

require_once dirname(__FILE__) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();

// Set Midtrans server key
\Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
\Midtrans\Config::$isProduction = false; // Set true if production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$snapToken = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses Login
    if (isset($_POST['account_option']) && $_POST['account_option'] == 'login') {
        $email = $_POST['email-login'];
        $password = $_POST['password-login'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nama'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_status'] = $user['status'];
            $_SESSION['success_account'] = "Login berhasil.";
            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil!'
            ]);
        } else {
            $_SESSION['error_account'] = "Email atau password salah!";
            echo json_encode([
                'status' => 'error',
                'message' => 'Email atau password salah!'
            ]);
        }
        exit;
    }

    // Proses Registrasi
    if (isset($_POST['account_option']) && $_POST['account_option'] == 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $phone = $_POST['phone'];

        if ($password !== $confirm_password) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password dan konfirmasi password tidak cocok!'
            ]);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email sudah terdaftar!'
            ]);
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nama, email, password, phone, status) VALUES (?, ?, ?, ?, 'Tidak Aktif')");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_status'] = 'Tidak Aktif';
            echo json_encode([
                'status' => 'success',
                'message' => 'Registrasi berhasil!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mendaftar, coba lagi.'
            ]);
        }
        exit;
    }

    // Proses Pendaftaran Kursus
    if (isset($_POST['account_option']) && $_POST['account_option'] == 'course') {
        if (!$_SESSION['user_id']) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Silakan login terlebih dahulu!'
            ]);
            exit;
        }

        $course = $_POST['course'];
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];
        $message = $_POST['message'];
        $order_id = 'ORDER-' . time();
        $status = 'pending';

        $stmt = $conn->prepare("INSERT INTO pendaftaran (user_id, course, hari, jam, message, order_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION['user_id'], $course, $hari, $jam, $message, $order_id, $status);

        if ($stmt->execute()) {
            // Harga kursus
            // $course_price = ($course == 'reguler') ? 400000 : 350000;
            $course_price = ($course == 'reguler') ? 20 : 10;

            // Membuat detail transaksi untuk Midtrans
            $transaction_details = array(
                'order_id' => $order_id,
                'gross_amount' => $course_price
            );

            // Customer details
            $customer_details = array(
                'first_name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'phone' => $_SESSION['user_phone']
            );

            // Item detail untuk kursus
            $item_details = array(
                array(
                    'id' => 'a01',
                    'price' => $course_price,
                    'quantity' => 1,
                    'name' => $course == 'reguler' ? 'Kursus Vokal Reguler' : 'Kursus Vokal Private'
                )
            );

            // Siapkan transaksi untuk Midtrans
            $transaction = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            );

            try {
                // Memanggil Snap API untuk mendapatkan token
                $snapToken = \Midtrans\Snap::getSnapToken($transaction);

                // Kirim token kembali ke client
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pendaftaran kursus berhasil! Proceed to payment.',
                    'snapToken' => $snapToken
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menghubungi Midtrans: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mendaftar kursus, coba lagi.'
            ]);
        }
        exit;
    }

    // // Sanitize input form
    // $course = $conn->real_escape_string($_POST['course']);
    // $hari = $conn->real_escape_string($_POST['hari']);
    // $jam = $conn->real_escape_string($_POST['jam']);
    // $message = $conn->real_escape_string($_POST['message']);

    // // Create a temporary entry with status 'pending' or save in session
    // $_SESSION['registration_data'] = [
    //     'name' => $name,
    //     'email' => $email,
    //     'phone' => $phone,
    //     'course' => $course,
    //     'hari' => $hari,
    //     'jam' => $jam,
    //     'message' => $message
    // ];
    // $order_id = 'ORDER-' . time(); // Unique order ID
    // // Simpan data pendaftaran dengan status pending ke database
    // $sql = "INSERT INTO pendaftaran (name, email, phone, course, hari, jam, message, order_id, status) 
    //             VALUES ('$name', '$email', '$phone', '$course', '$hari', '$jam', '$message', '$order_id', 'pending')";
    // if (!$conn->query($sql)) {
    //     $error_message = "Database error: " . $conn->error;
    // }
    // $course_price = ($course == 'reguler') ? 400000 : 350000;
    // $transaction_details = array(
    //     'order_id' => $order_id,
    //     'gross_amount' => $course_price
    // );
    // $item_details = array(
    //     array(
    //         'id' => 'a01',
    //         'price' => $course_price,
    //         'quantity' => 1,
    //         'name' => $course == 'reguler' ? 'Kursus Vokal Reguler' : 'Kursus Vokal Private'
    //     )
    // );
    // $customer_details = array(
    //     'first_name' => $name,
    //     'email' => $email,
    //     'phone' => $phone
    // );
    // $transaction = array(
    //     'transaction_details' => $transaction_details,
    //     'customer_details' => $customer_details,
    //     'item_details' => $item_details,
    // );
    // try {
    //     $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    // } catch (Exception $e) {
    //     $error_message = "Midtrans error: " . $e->getMessage();
    // }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .registration-form {
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

        .registration-form .daftar-button {

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
    </style>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OMcK_7hgLXcWo96E"></script>
</head>

<body>
    <div class="main-wrapper">
        <?php include 'components/navbar.php'; ?>
    </div>

    <hr class="m-0">

    <div class="main-wrapper py-5">
        <div class="d-flex flex-column justify-content-around">
            <h1 class="mb-4">Pendaftaran Kursus Vokal</h1>
            <div class="d-flex justify-content-between flex-wrap">
                <!-- Account Form -->
                <form class="flex-grow-1 registration-form mt-5 mt-md-0" style="max-width: 450px;">
                    <?php if (isset($_SESSION['success_account'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success_account']; ?>
                        </div>
                        <?php unset($_SESSION['success_account']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error_account'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error_account']; ?>
                        </div>
                        <?php unset($_SESSION['error_account']); ?>
                    <?php endif; ?>


                    <form action="pendaftaran.php" method="post" id="form-registrasi">
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <div class="row mb-0 mb-md-3">
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center justify-content-center w-100">
                                        <input class="mb-0 me-2" style="width: 20px; height: 20px; cursor: pointer;" type="radio" id="login" name="account_option" value="login" onclick="toggleAccountForm()" checked>
                                        <label class="mb-0" for="login">Sudah punya akun</label>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center w-100">
                                        <input class="mb-0 me-2" style="width: 20px; height: 20px; cursor: pointer;" type="radio" id="register" name="account_option" value="register" onclick="toggleAccountForm()">
                                        <label class="mb-0" for="register">Daftar akun baru</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 ">
                                    <!-- Dropdown untuk Sudah Punya Akun -->
                                    <div id="login-form" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="email-login" class="form-label">Email:</label>
                                                    <input type="email" class="form-control" id="email-login" name="email-login" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password-login" class="form-label">Password:</label>
                                                    <input type="password" class="form-control" id="password-login" name="password-login" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="login-btn">Masuk</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dropdown untuk Registrasi Akun Baru -->
                                    <div id="register-form" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="name-signup" class="form-label">Nama Lengkap:</label>
                                                    <input type="text" class="form-control" id="name-signup" name="name-signup" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email-signup" class="form-label">Email:</label>
                                                    <input type="email" class="form-control" id="email-signup" name="email-signup" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password-signup" class="form-label">Password:</label>
                                                    <input type="password" class="form-control" id="password-signup" name="password-signup" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
                                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone-signup">Nomor Telepon:</label>
                                                    <input type="text" id="phone-signup" name="phone-signup" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="register-btn">Daftar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['user_name']; ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon:</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $_SESSION['user_phone']; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </form>

                    <!-- Choose Course -->
                    <div class="flex-grow-1 registration-form" style="max-width: 450px;">
                        <?php if (isset($error_message)): ?>
                            <div class="error-message"><?php echo $error_message; ?></div>
                        <?php endif;

                        if (isset($_SESSION['error_daftar_kursus'])): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php echo $_SESSION['error_daftar_kursus']; ?>
                            </div>
                            <?php unset($_SESSION['error_daftar_kursus']);
                            ?>
                        <?php
                        endif;
                        ?>

                        <form action="pendaftaran.php" method="post">
                            <div class="row">
                                <label for="course">Pilih Kursus:</label>
                                <select id="course" name="course" onchange="updateHariOptions()">
                                    <option value="">Pilih kursus...</option>
                                    <option value="reguler">KURSUS VOKAL REGULER</option>
                                    <option value="private">KURSUS VOKAL PRIVATE</option>
                                </select>

                                <label for="hari">Pilih Hari:</label>
                                <select id="hari" name="hari" onchange="updateJamOptions()">
                                    <option value="">Pilih hari...</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>

                                <label for="jam">Pilih Jam:</label>
                                <select id="jam" name="jam">
                                    <option value="">Pilih jam...</option>
                                </select>
                                <label for="message">Pesan Tambahan:</label>
                                <textarea id="message" name="message" rows="4"></textarea>
                            </div>
                        </form>
                    </div>
            </div>
            <div class="registration-form mt-5" style="max-height: 100px; width: 100%;">
                <button class="daftar-button" id="daftar-kursus-btn" type="submit">Daftar Sekarang</button>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data booked_slots diambil dari PHP untuk reguler dan private
        var bookedSlotsReguler = <?php echo json_encode($booked_slots_reguler); ?>;
        var bookedSlotsPrivate = <?php echo json_encode($booked_slots_private); ?>;
        var bookedSlots = {};
        window.onload = function() {
            var hariSelect = document.getElementById('hari');
            var jamSelect = document.getElementById('jam');

            // Disable hari dan jam pada awalnya
            hariSelect.disabled = true;
            jamSelect.disabled = true;
        };

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
    <script src="script/script.js"></script>
</body>

</html>