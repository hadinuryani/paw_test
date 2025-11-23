<?php
session_start();
require_once 'config/config.php';
require_once 'config/function.php';
// Cek jika user sudah login
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Jika admin  lempar ke dashboard admin
    if ($_SESSION['role'] == 'admin') {
        header("Location: " . BASE_URL . "administrator/index.php");
        exit;
    }
    // Jika pemustaka lempar ke dashboard user
    if ($_SESSION['role'] == 'pemustaka') {
        header("Location: " . BASE_URL . "index.php");
        exit;
    }
}

// Inisialisasi variabel
$identity = $password = '';
$error_identity = $error_password = $error_general = '';

// proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identity = $_POST['identity'];
    $password = $_POST['password'];

    // Validasi identity
    if (!wajib_isi($identity)) {
        $error_identity = "Email atau NIM/NIP wajib diisi.";
    } else {
        $identity_clean = test_input($identity);

        if (cek_format_email($identity_clean)) {
            $tipe_identity = "email";
        } else if (cek_format_identitas($identity_clean)) {
            $tipe_identity = "nim_nip";
        } else {
            $error_identity = "Format email atau NIM/NIP tidak valid.";
        }
    }


    // Validasi password
    if (!wajib_isi($password)) {
        $error_password = "Password wajib diisi.";
    } elseif (!cek_panjang_minimal($password, 6)) {
        $error_password = "Password minimal 6 karakter.";
    }


    // PROSES LOGIN (Hanya jika validasi dasar lolos)
    if (empty($error_identity) && empty($error_password)) {
        $identity_clean = test_input($identity);

        if ($user = loginPemustaka($identity_clean, $password)) {
            $_SESSION['user_id']   = $user['id_pemustaka'];
            $_SESSION['nama_user'] = $user['nama_pemustaka'];
            $_SESSION['profil']    = $user['profil_pemustaka'];
            $_SESSION['role'] = 'pemustaka';
            header('location: ' . BASE_URL . 'index.php');
            exit;
        }
        if ($user = loginAdministrator($identity_clean, $password)) {
            $_SESSION['user_id']   = $user['id_admin'];
            $_SESSION['nama_user'] = $user['nama_admin'];
            $_SESSION['profil']    = $user['profil_admin'];
            $_SESSION['role'] = 'admin';
            header('location: ' . BASE_URL . 'administrator/index.php');
            exit;
        } else {
            $error_general = "Identitas atau password salah.";
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
                <p class="auth-subtitle">Belum punya akun? <a href="<?= BASE_URL; ?>register.php">Register</a></p>
            </div>

            <?php if (!empty($error_general)): ?>
                <div class="form-error"><?= $error_general ?></div>
            <?php endif; ?>

            <form action="#" method="post">
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
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>
    </div>
</body>

</html>