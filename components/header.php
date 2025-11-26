<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- load semua css yang di kirim  -->
    <?php foreach($data['css'] as $c):?>
        <link rel="stylesheet" href="<?= BASE_URL. 'assets/css/'. $c; ?>">
    <?php endforeach ?>
    <title><?= $data['title']; ?></title>
</head>
<body>
    
<main class="container">

    <!-- navbar -->
    <?php require_once BASE_PATH . 'components/navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="content-area">

            <!-- Header -->
            <div class="header">
                <?php if($_SESSION['role'] == 'pemustaka' && $data['title'] === 'Home Page'): ?>
                    <form class="search-bar" action="#" method="get">
                        <input type="text" name="q" placeholder="Search books, authors..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button title="cari" class="search-icon" type="submit">🔍</button>
                    </form>
                <?php elseif($_SESSION['role'] == 'pemustaka') : ?>
                    <div></div>
                <?php elseif($_SESSION['role'] == 'admin') : ?>
                        <h1 class="text-dashbord">DASHBOARD ADMIN</h1>
                <?php endif; ?>

                <!-- profil card -->
                <div class="user-section">
                    <div class="user-profile">
                        <!-- source users.png : https://www.flaticon.com/ -->
                        <img src="<?= BASE_URL; ?>assets/upload/<?= $_SESSION['profil'] ?? 'users.png'; ?>" class="user-avatar" alt="Foto Profil <?= $_SESSION['nama_user']; ?>">
                        <span><?= $_SESSION['nama_user']; ?></span>
                    </div>
                </div>

            </div>

            <!-- Categories -->
            <div class="section-header">
                <h2 class="section-title"><?= $data['header']; ?></h2>
            </div>
    