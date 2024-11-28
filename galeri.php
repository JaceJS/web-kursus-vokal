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

    <div class="main-wrapper py-5" style="height: 50vh;">
        <h1>Galeri Kursus Vokal Kami</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri1.jpg" class="card-img-top" alt="Galeri 1">
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri2.jpg" class="card-img-top" alt="Galeri 2">
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri3.jpg" class="card-img-top" alt="Galeri 3">
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri4.jpg" class="card-img-top" alt="Galeri 4">
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri5.jpg" class="card-img-top" alt="Galeri 5">
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="gambar/galeri6.jpg" class="card-img-top" alt="Galeri 6">
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>