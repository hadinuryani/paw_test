<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';

// Inisialisasi variabel
$identity = $password = '';
$error_identity = $error_password = $error_general = '';

// proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $identity = $_POST['identity'];
    $password = $_POST['password'];

    // Validasi (Email/NIM/NIP)
    if (!wajib_isi($identity)) {
        $error_identity = "Email atau NIM/NIP wajib diisi.";
    }

    // Validasi Password
    if (!wajib_isi($password)) {
        $error_password = "Password wajib diisi.";
    }

    // PROSES LOGIN (Hanya jika validasi dasar lolos)
    if (empty($error_identity) && empty($error_password)) {
        $identity_clean = test_input($identity); 
        $user = login($identity_clean, $password);

        if ($user === false) {
            // Jika login gagal (data tidak cocok)
            $error_general = "Identitas atau password salah.";
        } else {
            // Jika login berhasil
            // Cek role (Logika Anda di sini sudah benar)
            if($user['role']  == 'pemustaka'){
                $_SESSION['user_id']   = $user['id_user'];
                $_SESSION['nama_user'] = $user['nama_user'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['profil']    = $user['profil'];
                header('location: ' . BASE_URL . 'index.php');
                exit;
            } elseif($user['role'] == 'admin'){
                $_SESSION['user_id']   = $user['id_user'];
                $_SESSION['nama_user'] = $user['nama_user'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['profil']    = $user['profil'];
                header('location: ' . BASE_URL . 'administrator/index.php');
                exit;
            }
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
                <p class="auth-subtitle">Belum punya akun? <a href="<?= BASE_URL; ?>register.php">Sign up</a></p>
            </div>

             <?php if(!empty($error_general)): ?>
            <div class="alert alert-danger"><?= $error_general ?></div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label class="form-label">Identitas (Email atau NIM/NIP)</label>
                    <input type="text" class="form-input" name="identity" placeholder="Masukkan email atau NIM/NIP" value="<?= htmlspecialchars($identity) ?>">
                    <span class="form-error"><?= $error_identity ?></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password">
                    <span class="form-error"><?= $error_password ?></span>
                </div>
                <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>