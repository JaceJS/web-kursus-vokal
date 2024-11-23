<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminpagelogin.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $error_message = "";

    // Get user details (hari dan jam)
    $user_query = "SELECT hari, jam FROM users WHERE id = '$user_id'";
    $user_result = $conn->query($user_query);

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $hari = $user['hari'];
        $jam = $user['jam'];

        // Calculate the next date for the selected user's scheduled day
        $next_date = getNextDayDate($hari);

        // Insert new schedule into the database (without the 'topik' field)
        $insert_query = "INSERT INTO jadwal (user_id, tanggal) 
                         VALUES ('$user_id', '$next_date $jam')";

        if ($conn->query($insert_query) === TRUE) {
            header("Location: adminpagejadwal.php");
            exit;
        } else {
            $error_message = "Kesalahan: " . $conn->error;
        }
    } else {
        $error_message = "Pengguna tidak ditemukan.";
    }
}

$conn->close();

// Function to get the next date based on the specified day
function getNextDayDate($dayName)
{
    $daysOfWeek = [
        'Senin' => 1,
        'Selasa' => 2,
        'Rabu' => 3,
        'Kamis' => 4,
        'Jumat' => 5,
        'Sabtu' => 6,
        'Minggu' => 7
    ];

    $currentDay = date('N'); // Today as a number (1 = Monday, 7 = Sunday)
    $targetDay = $daysOfWeek[$dayName];

    // Calculate the difference in days and add 7 if the target day has passed
    $daysToAdd = ($targetDay - $currentDay + 7) % 7;
    if ($daysToAdd == 0) {
        $daysToAdd = 7; // If today is the target day, schedule it for next week
    }

    return date('Y-m-d', strtotime("+$daysToAdd days"));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Kursus</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("gambar/bannerbackground.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-container input,
        .form-container select,
        .form-container button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Tambah Jadwal</h1>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="adminpagetambahjadwal.php" method="post">
            <select name="user_id" required>
                <option value="">Pilih Pengguna...</option>
                <?php
                // Fetch users who don't have a schedule yet
                $conn = new mysqli($servername, $username, $password, $database);
                $users_query = "SELECT u.id, u.name, u.hari, u.jam 
                                FROM users u
                                LEFT JOIN jadwal j ON u.id = j.user_id
                                WHERE j.user_id IS NULL
                                AND u.hari IS NOT NULL AND u.jam IS NOT NULL";
                $users_result = $conn->query($users_query);

                if ($users_result->num_rows > 0) {
                    while ($user = $users_result->fetch_assoc()) {
                        echo "<option value='{$user['id']}'>
                                {$user['name']} - {$user['hari']} - {$user['jam']}
                              </option>";
                    }
                }
                $conn->close();
                ?>
            </select>
            <button type="submit">Tambah Jadwal</button>
        </form>
    </div>
</body>

</html>