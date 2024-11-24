<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kursus Vokal Terbaik</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .course-list {
      margin-top: 20px;
    }

    .course-item {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 20px;
      padding: 20px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .course-item h3 {
      margin-top: 0;
    }

    .course-item p {
      margin-bottom: 10px;
    }

    .course-item .price {
      font-weight: bold;
      color: #e74c3c;
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
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="container">
    <h1>Daftar Kursus Vokal Kami</h1>
    <div class="course-list">
      <div class="course-item">
        <h3>KURSUS VOKAL REGULER</h3>
        <p>WAKTU LATIHAN : 1 BULAN 4X (1 MINGGU 1X)</p>
        <p>JAM LATIHAN : 1 JAM/PERTEMUAN</p>
        <p class="price">Rp 400.000/BULAN</p>
      </div>

      <div class="course-item">
        <h3>KURSUS VOKAL PRIVATE</h3>
        <p>WAKTU LATIHAN : KHUSUS PERMINTAAN</p>
        <p>JAM LATIHAN : 1 JAM/PERTEMUAN</p>
        <p class="price">Rp 350.000/PERTEMUAN</p>
      </div>
    </div>
  </div>
  <footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>