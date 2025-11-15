<!-- Sidebar -->
<div class="sidebar">
    <div>
        <!-- Logo -->
        <div class="logo">
            <div>
                <img class="logo-icon" src="<?= BASE_URL .'assets/img/logo-light.png'; ?>" alt="logo">
            </div>
        </div>

        <?php 
        // Pastikan role sudah ada
        $role = $_SESSION['role'] ?? null;

        switch($role):
        
        /* =======================
           MENU UNTUK PEMUSTAKA
        ========================*/
        case 'pemustaka': ?>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="">
                <a href="<?= BASE_URL; ?>">Home</a>
            </div>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="">
                <a href="<?= BASE_URL; ?>pemustaka/riwayat.php">Riwayat Pinjam</a>
            </div>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="">
                <a href="<?= BASE_URL; ?>pemustaka/profil.php">Lihat Profil</a>
            </div>

        <?php 
        break;


        /* =====================
            MENU UNTUK ADMIN
        ======================*/
        case 'admin': ?>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="">
                <a href="<?= BASE_URL; ?>administrator/">Dashboard</a>
            </div>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="">
                <a href="<?= BASE_URL; ?>administrator/daftar_pemustaka.php">Daftar Pemustaka</a>
            </div>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="">
                <a href="<?= BASE_URL; ?>administrator/kelola_buku.php">Kelola Buku</a>
            </div>

            <div class="menu-item">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/status.png" alt="">
                <a href="<?= BASE_URL; ?>administrator/kelola_peminjaman.php">Update Status Peminjaman</a>
            </div>

        <?php 
        break;

        endswitch; 
        ?>

    </div>

    <!-- Logout -->
    <div class="logout">
        <div class="menu-item">
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/logout.png" alt="">
            <a href="<?= BASE_URL; ?>logout.php">Logout</a>
        </div>
    </div>
</div>
