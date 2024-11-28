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
  <style>
    .contact-info {
      margin-bottom: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      border-radius: 8px;
      text-align: left;
      /* Align text in contact info */
    }

    .contact-info h2 {
      margin-bottom: 15px;
      font-size: 2em;
    }

    .contact-info p {
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .contact-form {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .contact-form h1 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 2em;
    }

    .contact-form label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .contact-form button {
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

    .contact-form button:hover {
      background-color: #555;
    }

    .center-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 20px;
    }

    .center-content img {
      max-width: 150px;
      margin-bottom: 10px;
    }

    .center-content button {
      background-color: #333;
      color: #fff;
      border: none;
      padding: 15px 30px;
      font-size: 1.2em;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .center-content button:hover {
      background-color: #555;
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <?php include 'components/navbar.php'; ?>
  </div>

  <hr class="m-0">

  <div class="main-wrapper py-5">
    <div class="contact-info">
      <h1 class="mb-4">Kontak Kami</h1>
      <p>
        Jika Anda memiliki pertanyaan atau memerlukan informasi lebih lanjut,
        jangan ragu untuk menghubungi kami melalui informasi di bawah ini:
      </p>
      <p>
        <strong>Alamat:</strong> Kompleks Marina Plaza Gedung M walk lt2 B10
        B11 B13, Manado, Indonesia
      </p>
      <p><strong>Telepon:</strong> +62 813 4085 6153</p>
      <p><strong>Email:</strong> info@kursusvokal.com</p>
    </div>

    <!-- Centered Logo Bantuan and Button -->
    <div class="center-content">
      <p><strong>INGIN BERTANYA SESUATU ?</strong></p>
      <img src="gambar/logo_bantuan.jpg" alt="Logo Bantuan" />
      <a href="https://api.whatsapp.com/send?phone=6281340856153" target="_blank">
        <button class="ho-button ml-5"><i class="ion ion-logo-whatsapp"></i>&nbsp;&nbsp;Hubungi Kami</button>
      </a>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>