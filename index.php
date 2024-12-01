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
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Perhatian!</strong> Anda belum mendaftar kursus. Silahkan melakukan pendaftaran kursus di Menu Pendaftaran.
          </div>
      <?php
        }
      }
      ?>

      <div class="hero">
        <img src="gambar/bannerberanda.png" alt="Gambar Kursus Vokal" />
      </div>

      <!-- Card Section: Tentang Kami -->
      <section>
        <h2>Tentang Kami</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <div class="col">
            <div class="card about-card">
              <img src="gambar/tentang1.jpg" class="card-img-top" alt="Instruktur Berpengalaman">
              <div class="card-body">
                <h5 class="card-title">Pengajar Ahli</h5>
                <p class="card-text">Pengajar vokal yang sudah berpengalaman dan siap membantu Anda berkembang.</p>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card about-card">
              <img src="gambar/tentang2.jpg" class="card-img-top" alt="Metode Pembelajaran">
              <div class="card-body">
                <h5 class="card-title">Metode Belajar Menyenangkan</h5>
                <p class="card-text">Belajar vokal dengan cara yang mudah dan menyenangkan. Tidak ada tekanan, hanya kesenangan.</p>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card about-card">
              <img src="gambar/tentang3.jpg" class="card-img-top" alt="Fasilitas Terbaik">
              <div class="card-body">
                <h5 class="card-title">Fasilitas Nyaman</h5>
                <p class="card-text">Dilengkapi dengan studio dan alat rekaman yang nyaman, kami siap mendukung proses belajar Anda.</p>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Card Section: Fasilitas Kami -->
      <section>
        <h2>Fasilitas Kami</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <div class="col">
            <div class="card">
              <img src="gambar/fasilitas1.jpg" class="card-img-top" alt="Kelas Pribadi">
              <div class="card-body">
                <h5 class="card-title">Kelas Pribadi & Kelompok</h5>
                <p class="card-text">Kelas yang didesain khusus untuk kebutuhan individu dengan perhatian penuh dari instruktur.</p>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card">
              <img src="gambar/fasilitas2.jpg" class="card-img-top" alt="Studio Rekaman">
              <div class="card-body">
                <h5 class="card-title">Studio Rekaman Profesional</h5>
                <p class="card-text">Dilengkapi dengan perangkat rekaman terbaik untuk hasil suara yang optimal.</p>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card">
              <img src="gambar/fasilitas3.jpg" class="card-img-top" alt="Materi Pembelajaran">
              <div class="card-body">
                <h5 class="card-title">Materi Pembelajaran Lengkap</h5>
                <p class="card-text">Materi yang disusun secara terstruktur untuk memudahkan pemahaman setiap peserta.</p>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card">
              <img src="gambar/fasilitas4.jpg" class="card-img-top" alt="Sesi Latihan Tambahan">
              <div class="card-body">
                <h5 class="card-title">Sesi Latihan Tambahan</h5>
                <p class="card-text">Kami menyediakan sesi latihan untuk meningkatkan kemampuan vokal Anda lebih jauh.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>