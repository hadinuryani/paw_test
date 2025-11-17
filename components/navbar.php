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
        // cek role
        $role = $_SESSION['role'] ?? null;

        switch($role):
        
        /* MENU UNTUK PEMUSTAKA
        ========================*/
        case 'pemustaka': ?>
            <a class="menu-item" href="<?= BASE_URL; ?>">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="">
                <span>Home</span>
            </a>

            <a class="menu-item" href="<?= BASE_URL; ?>pemustaka/riwayat.php">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="">
                <span>Riwayat Pinjam</span>
            </a>

            <a class="menu-item" href="<?= BASE_URL; ?>pemustaka/profil.php">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="">
                <span>Lihat Profil</span>
            </a>

        <?php 
        break;

        /* MENU UNTUK ADMIN
        ======================*/
        case 'admin': ?>

           <a class="menu-item" href="<?= BASE_URL; ?>administrator/">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="">
                <span>Dashboard</span>
            </a>

            <a class="menu-item" href="<?= BASE_URL; ?>administrator/daftar_pemustaka.php">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="">
                <span>Daftar Pemustaka</span>
            </a>

            <a class="menu-item" href="<?= BASE_URL; ?>administrator/kelola_buku.php">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="">
                <span>Kelola Buku</span>
            </a>

            <a class="menu-item" href="<?= BASE_URL; ?>administrator/kelola_peminjaman.php">
                <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/status.png" alt="">
                <span>Update Status Peminjaman</span>
            </a>

        <?php 
        break;

        endswitch; 
        ?>

    </div>

    <!-- Logout -->
    <div>
        <a class="menu-item" href="<?= BASE_URL; ?>logout.php">
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/logout.png" alt="">
            <span>Logout</span>
        </a>

    </div>
    
</div>
