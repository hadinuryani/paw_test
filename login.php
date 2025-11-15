<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';

if (isset($_POST['login'])) {
    // lakukan validasi dulu baru login;
    $identity = trim($_POST['identity']);
    $password = $_POST['password'];
    $user = login($identity, $password);
    if ($user === false) {
        $error = "Identitas atau password salah";
    } else {
        // cek role
        if($user['role']  == 'pemustaka'){
            // Set session
            $_SESSION['user_id']  = $user['id_user'];
            $_SESSION['nama_user'] = $user['nama_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profil'] = $user['profil'];
            // Redirect ke homepage pemustaka
            header('location: ' . BASE_URL . 'index.php');
            exit;
        }elseif($user['role'] == 'admin'){
            // Set session
            $_SESSION['user_id']  = $user['id_user'];
            $_SESSION['nama_user'] = $user['nama_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profil'] = $user['profil'];
            // Redirect ke dashboard admin
            header('location: ' . BASE_URL . 'administrator/index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/auth.css">
</head>
<body>
    <!-- Login Page -->
    <div class="auth-container">
        <div class="auth-left">
            <div class="logo-section">
                <div class="logo-icon-big">📚</div>
                <div class="logo-text">UnivLib</div>
                <div class="logo-tagline">
                    Satu Akses, Sejuta Pengetahuan
                </div>
            </div>
            <div class="book-illustration">📖</div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h1 class="auth-title">Welcome Back!</h1>
                <p class="auth-subtitle">Don't have an account? <a href="<?= BASE_URL; ?>register.php">Sign up</a></p>
            </div>

            <form action="#" method="post">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="text" class="form-input" name="identity" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>