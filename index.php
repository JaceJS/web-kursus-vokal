<?php
session_start();

include 'koneksi.php';

date_default_timezone_set('Asia/Makassar');

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT status, nama FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    header("Location: logout.php");
    exit;
  }

  $user = $result->fetch_assoc();
  $status = $user['status'];
  $nama = $user['nama'];

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kursus Vokal Terbaik</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero {
      text-align: center;
      margin-bottom: 20px;
    }

    .hero img {
      max-width: 100%;
      height: auto;
      margin-bottom: 20px;
    }

    .hero h1 {
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.2em;
      color: #555;
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="main-wrapper">
    <div class="container">
      <?php
      if (isset($_SESSION['user_id'])) {
        if ($status === 'Tidak Aktif') {
      ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Perhatian!</strong> Akun Anda sudah tidak aktif. Silakan hubungi admin untuk informasi lebih lanjut.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php
        }
      }
      ?>

      <div class="hero">
        <img src="gambar/bannerberanda.png" alt="Gambar Kursus Vokal" />
        <h1>SELAMAT DATANG DI KURSUS VOCAL SIMPHONY</h1>
        <p>
          Kembangkan bakat vokal Anda dengan bimbingan dari instruktur
          profesional dan kursus yang terstruktur.
        </p>
      </div>
      <section>
        <h2>Tentang Kami</h2>
        <p>
          Di Kursus Vokal Terbaik, kami menawarkan pelatihan vokal yang
          dirancang untuk membantu Anda mencapai potensi penuh Anda. Kami
          menyediakan berbagai kursus mulai dari tingkat pemula hingga tingkat
          lanjut, yang disampaikan oleh instruktur berpengalaman.
        </p>
      </section>
      <section>
        <h2>Fasilitas Kami</h2>
        <ul>
          <li>Kelas pribadi dan kelompok</li>
          <li>Studio rekaman profesional</li>
          <li>Materi pembelajaran lengkap</li>
          <li>Sesi latihan tambahan</li>
        </ul>
      </section>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>