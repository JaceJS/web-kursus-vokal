<nav class="navbar navbar-expand-lg navbar-light pb-0">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="gambar/logoheader.png" alt="KVS Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="d-flex flex-column ms-auto">
                <div class="social-icons text-end mb-2">
                    <a href="#">
                        <img src="gambar/logo_fb.png" alt="Facebook Logo" width="32">
                    </a>
                    <a href="#">
                        <img src="gambar/logo_ig.png" alt="Instagram Logo" width="32">
                    </a>
                </div>

                <ul class="navbar-nav fw-bold" style="font-size: 16px;">
                    <li class="nav-item">
                        <a class="nav-link pb-0 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">BERANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pb-0 <?php echo basename($_SERVER['PHP_SELF']) == 'kursus.php' ? 'active' : ''; ?>" href="kursus.php">KURSUS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pb-0 <?php echo basename($_SERVER['PHP_SELF']) == 'pendaftaran.php' ? 'active' : ''; ?>" href="pendaftaran.php">PENDAFTARAN</a>
                    </li>
                    <?php
                    if (isset($_SESSION['user_id'])):
                    ?>
                        <li class="nav-item">
                            <a class="nav-link pb-0 ' <?php echo basename($_SERVER['PHP_SELF']) == 'absendanjadwal.php' ? 'active' : '' ?>" href="absendanjadwal.php">ABSEN</a>
                        </li>
                    <?php
                    endif;
                    ?>
                    <li class="nav-item">
                        <a class="nav-link pb-0 <?php echo basename($_SERVER['PHP_SELF']) == 'galeri.php' ? 'active' : ''; ?>" href="galeri.php">GALERI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pb-0 <?php echo basename($_SERVER['PHP_SELF']) == 'kontak.php' ? 'active' : ''; ?>" href="kontak.php">KONTAK</a>
                    </li>
                    <li class="nav-item">
                        <?php
                        if (isset($_SESSION['user_id'])) {
                            echo '<a class="nav-link pb-0" href="logout.php">LOGOUT</a>';
                        } else {
                            echo '<a class="nav-link pb-0 ' . (basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '') . '" href="login.php">LOGIN</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>