<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <?php if($_SESSION['role'] == 'pemustaka'): ?>
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Search your favourite books">
                    </div>
                <?php else : ?>
                        <h1 class="text-dashbord">DASBORD ADMIN</h1>
                <?php endif; ?>
                <div class="user-section">
                    <div class="user-profile">
                        <img src="<?= BASE_URL; ?>assets/img/<?= $_SESSION['profil'] ?? 'users.png'; ?>" class="user-avatar">
                        <span><?= $_SESSION['nama_user']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="section-header">
                <h2 class="section-title"><?= $data['header']; ?></h2>
            </div>
    