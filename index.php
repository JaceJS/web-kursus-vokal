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

    .profile-card {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .border-card {
      border: 3px solid rgba(255, 255, 255, 0.8);
    }

    .daftar-hover:hover {
      background-color: #2c3e50 !important;
      color: #fff !important;
      transition: background-color 0.3s ease !important;
    }

    .profile-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-right: 20px;
    }

    .profile-card .info {
      display: flex;
      flex-direction: column;
    }

    .profile-card h5 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
      color: #2717f8;
    }

    .profile-card p {
      margin: 5px 0;
      font-size: 14px;
      color: #2717f8;
    }

    .profile-card .btn {
      font-size: 14px;
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
        <!-- Slider -->
        <div class="row">
          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="gambar/slider/beranda_1.png" class="d-block w-100" alt="Slider Vokal 1">
              </div>
              <div class="carousel-item">
                <img src="gambar/slider/beranda_2.png" class="d-block w-100" alt="Slider Vokal 2">
              </div>
              <div class="carousel-item">
                <img src="gambar/slider/beranda_3.png" class="d-block w-100" alt="Slider Vokal 3">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

        <!-- Card Section: Kategori Kursus, Daftar Sekarang, Profil Pelatih -->
        <div class="row d-flex align-items-stretch w-100 mx-auto p-4" style="background-color: #FDF867;">
          <a class="col" href="kursus.php" style="text-decoration: none;">
            <div class="border-card h-100 text-start p-3" style="color: #2717f8">
              <h5 class="fw-bold">Kategori Kursus</h5>
              <ul class="mb-0">
                <li>Kelas Reguler</li>
                <li>Kelas Private</li>
              </ul>
            </div>
          </a>

          <div class="col d-flex">
            <a href="pendaftaran.php" class="daftar-hover d-flex justify-content-center align-items-center w-100 h-100 text-center text-white" style="background-color: #2717f8; text-decoration:none;  font-size: 1.5rem; font-weight: bold; text-transform: uppercase;">
              <span>DAFTAR SEKARANG!!!</span>
            </a>
          </div>

          <div class="col">
            <div class="profile-card border-card p-3">
              <img src="gambar/pelatih.jpg" alt="Profile Image">
              <div class="info">
                <h5>Roos Atimang</h5>
                <p>Pelatih Vokal</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Section: Tentang Kami -->
      <section class="card-section px-0 px-md-5">
        <section>
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
              <div class="card rounded-0 h-100">
                <div class="card-body px-0 py-0" style="background-color: #2717f8; color: #FDF867;">
                  <img width="290" height="150" style="object-fit: cover;" src="gambar/galeri/gambar11.jpg" class="card-img-top" alt="Instruktur Berpengalaman">
                  <div class="px-3 py-3">
                    <h5 class="card-title fw-bold">Pengajar Ahli</h5>
                    <p class="card-text">Pengajar vokal yang sudah berpengalaman dan siap membantu Anda berkembang.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card h-100">
                <div class="card-body px-0 py-0" style="background-color: #2717f8; color: #FDF867;">
                  <iframe style="width: 100%;" width="290" height="150" src="https://www.youtube.com/embed/uXXkuy2ltlI?si=3OUVNyPRq_DojQYn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                  <div class="px-3 py-3">
                    <h5 class="card-title fw-bold">Glory Vane Sumakud <span class="fs-6 fw-bold">("Sisa Rasa")</span></h5>
                    <p class="card-text">Juara 1 Menyanyi Solo FLS2N Sulut SMP 2023.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card h-100">
                <div class="card-body px-0 py-0" style="background-color: #2717f8; color: #FDF867;">
                  <iframe style="width: 100%;" width="290" height="150" src="https://www.youtube.com/embed/DX3lgcR8lhY?si=l-mvv4_C36xra065" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                  <div class="px-3 py-3">
                    <h5 class="card-title fw-bold">Glory Vane Sumakud <span class="fs-6 fw-bold">("Hanya Rindu")</span></h5>
                    <p class="card-text">Juara 2 Menyanyi Solo FLS2N Sulut SMP 2024.</p>
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
                <a href="galeri.php">
                  <img width="290" height="150" style="object-fit: cover; object-position: 50% 20%;" src=" gambar/galeri/gambar6.jpg" class="card-img-top" alt="Kelas Pribadi">
                </a>
                <div class="card-body">
                  <h5 class="card-title">Kelas Pribadi & Kelompok</h5>
                  <p class="card-text">Kelas yang didesain khusus untuk kebutuhan individu dengan perhatian penuh dari instruktur.</p>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card">
                <a href="galeri.php">
                  <img width="290" height="150" style="object-fit: cover; object-position: 50% 20%;" src="gambar/galeri/gambar12.jpg" class="card-img-top" alt="Studio Rekaman">
                </a>
                <div class="card-body">
                  <h5 class="card-title">Pengajaran Berbasis Praktik</h5>
                  <p class="card-text">Pembelajaran langsung melalui latihan vokal untuk meningkatkan keterampilan secara efektif.</p>
                </div>
              </div>
            </div>

            <div class="col">
              <div class="card">
                <a href="galeri.php">
                  <img width="290" height="150" style="object-fit: cover; object-position: 50% 45%;" src="gambar/galeri/gambar9.jpg" class="card-img-top" alt="Sesi Latihan Tambahan">
                </a>
                <div class="card-body">
                  <h5 class="card-title">Pelatihan Vokal untuk Kompetisi</h5>
                  <p class="card-text">Pelatihan vokal untuk mempersiapkan siswa meraih kemenangan di kompetisi.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Card Lokasi & Kontak -->
        <div class="row">
          <div class="col-md-8 col-sm-12 mb-4 mb-md-0">
            <div class="card" style="width: 100%; height: 100%;">
              <div class="card-body pb-0">
                <h3 class="card-title fw-bold" style="color: #2717f8;">Lokasi</h3>
                <p class="card-text">Kompleks Marina Plaza Gedung M walk lt2 B10 B11 B13, Manado, Indonesia</p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7976.906467201437!2d124.83742604135985!3d1.4988341439484099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3287756bab08cb2b%3A0xfa197c008d04af59!2sM%20Walk!5e0!3m2!1sen!2sid!4v1733074694929!5m2!1sen!2sid"
                  width="600"
                  height="200"
                  style="border:0; width: 100%;"
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
                <a href="kontak.php" style="text-decoration: none;">
                  <h3 class="card-title fw-bold" style="color: #2717f8;">Kontak</h3>
                </a>
                <p class="card-text">Tel. +62 813 4085 6153</p>
                <p class="card-text">
                  Email: <a href="mailto:kursusvocalsimphony@gmail.com">kursusvocalsimphony@gmail.com</a>
                </p>
                <p class="card-text">Website: <a href="https://kvsmanado.my.id/" target="_blank"><u>https://kvsmanado.my.id</u></a>
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