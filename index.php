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

    .card {
      border: 0;
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
                <h5 class="card-title">Studio Rekaman</h5>
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

      <!-- Card Lokasi -->
      <div class="row">
        <div class="col-md-8 col-sm-12">
          <div class="card" style="width: 100%; height: 100%;">
            <div class="card-body pb-0">
              <h3 class="card-title fw-bold" style="color: #2717f8;">Lokasi</h3>
              <p class="card-text">Kompleks Marina Plaza Gedung M walk lt2 B10 B11 B13, Manado, Indonesia</p>
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7976.906467201437!2d124.83742604135985!3d1.4988341439484099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3287756bab08cb2b%3A0xfa197c008d04af59!2sM%20Walk!5e0!3m2!1sen!2sid!4v1733074694929!5m2!1sen!2sid"
                width="600"
                height="200"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="card rounded-0" style="width: 100%; height: 100%; border: 1px solid #dcdcdc">
            <div class="card-body pb-0">
              <h3 class="card-title mb-5 fw-bold" style="color: #2717f8;">Kontak</h3>
              <p class="card-text">Tel. +62 813 4085 6153</p>
              <p class="card-text">
                Email: <a href="mailto:kursusvocalsimphony@gmail.com">kursusvocalsimphony@gmail.com</a>
              </p>
              <p class="card-text">Website: <a href="https://kvsmanado.my.id/" target="_blank"><u>https://kvsmanado.my.id</u></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>