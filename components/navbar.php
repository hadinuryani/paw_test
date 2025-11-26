<!-- Sidebar -->
<div class="sidebar">
    <div>
        <!-- Logo -->
        <div class="logo">
            <div>
                <!-- dibuat di figma -->
                <img class="logo-icon" src="<?= BASE_URL .'assets/img/logo-light.png'; ?>" alt="logo">
            </div>
        </div>

        <?php 
        // cek role
        $role = $_SESSION['role'] ?? null;
        $current = basename($_SERVER['PHP_SELF']);

        switch($role):
        
        /* MENU UNTUK PEMUSTAKA
        ========================*/
        case 'pemustaka': ?>
            <a class="menu-item <?= $current == 'index.php' ? 'active' : '' ?>" 
                href="<?= BASE_URL; ?>">
                    <!-- https://www.flaticon.com/ -->
                    <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="icon">
                    <span>Home</span>
                </a>

                <a class="menu-item <?= $current == 'riwayat.php' ? 'active' : '' ?>" 
                href="<?= BASE_URL; ?>pemustaka/riwayat.php">
                    <!-- https://www.flaticon.com/ -->
                    <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="icon">
                    <span>Riwayat Pinjam</span>
                </a>

                <a class="menu-item <?= $current == 'profil.php' ? 'active' : '' ?>" 
                href="<?= BASE_URL; ?>pemustaka/profil.php">
                    <!-- https://www.flaticon.com/ -->
                    <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="icon">
                    <span>Lihat Profil</span>
                </a>


        <?php 
        break;

        /* MENU UNTUK ADMIN
        ======================*/
        case 'admin': ?>

         <a class="menu-item <?= $current == 'index.php' ? 'active' : '' ?>" 
        href="<?= BASE_URL; ?>administrator/">
            <!-- https://www.flaticon.com/ -->
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/home.png" alt="icon">
            <span>Dashboard</span>
        </a>

        <a class="menu-item <?= $current == 'daftar_pemustaka.php' ? 'active' : '' ?>" 
        href="<?= BASE_URL; ?>administrator/daftar_pemustaka.php">
            <!-- https://www.flaticon.com/ -->
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/profil.png" alt="icon">
            <span>Daftar Pemustaka</span>
        </a>

        <a class="menu-item <?= $current == 'kelola_buku.php' ? 'active' : '' ?>" 
        href="<?= BASE_URL; ?>administrator/kelola_buku.php">
            <!-- https://www.flaticon.com/ -->
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/riwayat.png" alt="icon">
            <span>Kelola Buku</span>
        </a>

        <a class="menu-item <?= $current == 'kelola_peminjaman.php' ? 'active' : '' ?>" 
        href="<?= BASE_URL; ?>administrator/kelola_peminjaman.php">
            <!-- https://www.flaticon.com/ -->
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/status.png" alt="icon">
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
            <!-- https://www.flaticon.com/ -->
            <img class="menu-icon" src="<?= BASE_URL; ?>assets/img/logout.png" alt="icon">
            <span>Logout</span>
        </a>

    </div>
    
</div>
