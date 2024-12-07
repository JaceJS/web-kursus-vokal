<?php
session_start();

include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kursus Vokal Terbaik</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="main-wrapper" style="min-height: 60vh">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="gambar/slider/kursus_1.png" class="d-block w-100" alt="Slider Kursus 1">
          <div class="carousel-caption d-none d-md-block">
            <h1 class="fw-bold mb-4">KURSUS VOKAL REGULER</h1>
            <p>WAKTU LATIHAN : 1 BULAN 4X (1 MINGGU 1X)</p>
            <p>JAM LATIHAN : 1 JAM/PERTEMUAN</p>
            <p class="price fw-bold" style="color: #FDF867">Rp 400.000/BULAN</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="gambar/slider/kursus_2.png" class="d-block w-100" alt="Slider Kursus 2">
          <div class="carousel-caption d-none d-md-block">
            <h1 class="fw-bold mb-4">KURSUS VOKAL PRIVATE</h1>
            <p>WAKTU LATIHAN : KHUSUS PERMINTAAN</p>
            <p>JAM LATIHAN : 1 JAM/PERTEMUAN</p>
            <p class="price fw-bold" style="color: #FDF867">Rp 350.000/PERTEMUAN</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<style>
  .carousel-caption {
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    width: 100%;
    display: flex !important;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .carousel-caption p {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 500;
  }

  .caption-dark {
    background-color: rgba(0, 0, 0, 0.7);
  }

  .carousel-caption h5,
  .carousel-caption p {
    color: white;
  }

  @media (max-width: 767px) {
    .carousel-caption {
      padding: 10px;
      font-size: 14px;
      height: 100%;
    }

    .carousel-caption h1 {
      font-size: 16px;
    }

    .carousel-caption p {
      font-size: 12px;
    }

    .carousel-caption p.price {
      font-size: 14px;
    }
  }

  /* Responsif untuk tablet (layar lebih besar dari ponsel) */
  @media (min-width: 768px) and (max-width: 1024px) {
    .carousel-caption {
      padding: 15px;
      font-size: 16px;
      height: 100%;
    }

    .carousel-caption h1 {
      font-size: 18px;
    }

    .carousel-caption p {
      font-size: 14px;
    }

    .carousel-caption p.price {
      font-size: 16px;
    }
  }
</style>

</html>