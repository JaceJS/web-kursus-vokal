<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kursus Vokal Terbaik</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url("gambar/bannerbackground.png");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      header {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 10px 20px;
      }
      nav {
        display: flex;
        justify-content: center;
        gap: 20px;
      }
      nav a {
        color: #333;
        text-decoration: none;
        font-weight: bold;
        padding: 0 15px;
        font-size: 18px;
      }
      nav a:hover {
        text-decoration: underline;
      }
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        text-align: center; /* Center content in the container */
      }
      .contact-info {
        margin-bottom: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 8px;
        text-align: left; /* Align text in contact info */
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
        max-width: 150px; /* Set a maximum width for the logo */
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
    <header>
      <div class="header-container">
        <img src="gambar/logoheader.png" alt="Logo" class="logo" />
        <nav>
          <a href="index.html">Beranda</a>
          <a href="kursus.html">Kursus</a>
          <a href="pendaftaran.php">Pendaftaran</a>
          <a href="kontak.html">Kontak</a>
          <a href="login.php">Login</a>
        </nav>
      </div>
    </header>
    <div class="container">
      <div class="contact-info">
        <h2>Kontak Kami</h2>
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
        <!--<footer>&copy; 2024 Kursus Vokal Terbaik. Semua hak dilindungi.</footer>-->
  </body>
</html>
