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
    
<div class="container">
    <!-- navbar -->
    <?php require_once BASE_PATH . 'components/navbar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="content-area">
            <!-- Header -->
            <div class="header">
                
                <div class="search-bar">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search your favourite books">
                </div>
                <div class="user-section">
                    <div class="user-profile">
                        <img src="<?= BASE_URL; ?>assets/img/users.png" class="user-avatar">
                        <span>namamu</span>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="section-header">
                <h2 class="section-title"><?= $data['header']; ?></h2>
            </div>
    