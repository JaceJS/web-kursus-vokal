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
// sesi user
$user_id = $_SESSION['user_id'];

// Ambil daftar pengguna
$user_query = "SELECT id, nama, course, hari, jam, created_at FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);

// Jika data pengguna tidak ditemukan
if ($user_result->num_rows == 0) {
  echo "<p>Tidak ada data pengguna ditemukan.</p>";
  exit;
}
// Ambil data absensi berdasarkan user_id
$absen_query = "SELECT pertemuan_ke, tanggal, topik, instruktur, absen_date FROM absen WHERE user_id = $user_id ORDER BY pertemuan_ke ASC";
$absen_result = $conn->query($absen_query);


// Ambil data pengguna
$user_data = $user_result->fetch_assoc()


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
    .section {
      margin-bottom: 50px;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section h2 {
      font-size: 2em;
      margin-bottom: 20px;
      color: #444;
      text-align: center;
    }

    .absen-form,
    .jadwal-table {
      margin: 20px 0;
    }

    .absen-form label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
      color: #333;
    }

    .absen-form select,
    .absen-form button {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
    }

    .absen-form button {
      background-color: #5cb85c;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .absen-form button:hover {
      background-color: #4cae4c;
    }

    .success {
      color: green;
      text-align: center;
      margin-top: 20px;
      font-weight: bold;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 20px;
      font-weight: bold;
    }

    .jadwal-table,
    .absen-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .jadwal-table th,
    .jadwal-table td,
    .absen-table th,
    .absen-table td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }

    .jadwal-table th,
    .absen-table th {
      background-color: #333;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    .jadwal-table tr:nth-child(even),
    .absen-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .jadwal-table tr:hover,
    .absen-table tr:hover {
      background-color: #f1f1f1;
    }

    .jadwal-table td,
    .absen-table td {
      font-size: 16px;
      color: #555;
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="main-wrapper py-5">
    <div class="container">
      <!-- Bagian Absen -->
      <div class="section">

        <h2>Daftar Absensi</h2>
        <table class="absen-table">
          <thead>
            <tr>
              <th>Pertemuan</th>
              <th>Tanggal</th>
              <th>Topik</th>
              <th>Instruktur</th>
              <th>Waktu Absen</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($absen_result->num_rows > 0): ?>
              <?php while ($absen = $absen_result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($absen['pertemuan_ke']); ?></td>
                  <td><?php echo htmlspecialchars($absen['tanggal']); ?></td>
                  <td><?php echo htmlspecialchars($absen['topik']); ?></td>
                  <td><?php echo htmlspecialchars($absen['instruktur']); ?></td>
                  <td><?php echo htmlspecialchars($absen['absen_date']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">Belum ada data absensi.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

      </div>

      <!-- Bagian Jadwal -->
      <div class="section">
        <h2>Jadwal Kursus Anda</h2>
        <table class="jadwal-table">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Kursus</th>
              <th>Hari</th>
              <th>Jam</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo htmlspecialchars($user_data['nama']); ?></td>
              <td><?php echo htmlspecialchars($user_data['course']); ?></td>
              <td><?php echo htmlspecialchars($user_data['hari']); ?></td>
              <td><?php echo htmlspecialchars($user_data['jam']); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>
</body>

</html>

<?php
// Tutup koneksi setelah semua operasi selesai
$conn->close();
?>