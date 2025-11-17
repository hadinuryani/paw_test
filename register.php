<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';

// Inisialisasi semua variabel & variabel error
$nama = $email = $nim_nip = $password = $confirm_password = '';
$error_nama = $error_email = $error_nim_nip = $error_password = $error_confirm_password = '';
$error_general = ''; 

// Proses jika form di submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Validasi Nama
    if (!wajib_isi($_POST['nama'])) {
        $error_nama = "Nama lengkap wajib diisi.";
    } else {
        $nama = test_input($_POST['nama']); 
        if (!alfabet($nama)) { 
            $error_nama = "Nama hanya boleh berisi huruf dan spasi.";
        }
    }

    // Validasi Email
    if (!wajib_isi($_POST['email'])) {
        $error_email = "Email wajib diisi.";
    } else {
        $email = test_input($_POST['email']);
        if (!cek_format_email($email)) { 
            $error_email = "Format email tidak valid.";
        } else {
            $sql_cek_email = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
            if (fetchOne($sql_cek_email, [':email' => $email])) {
                $error_email = "Email ini sudah terdaftar.";
            }
        }
    }

    // Validasi NIM/NIP
    if (!wajib_isi($_POST['nim_nip'])) {
        $error_nim_nip = "NIM/NIP wajib diisi.";
    } else {
        $nim_nip = test_input($_POST['nim_nip']);
        if (!cek_format_identitas($nim_nip)) { 
            $error_nim_nip = "NIM/NIP tidak valid. Harus 12 digit (NIM) atau 18 digit (NIP).";
        } else {
            $sql_cek_nim = "SELECT id_user FROM users WHERE nim_nip = :nim LIMIT 1";
            if (fetchOne($sql_cek_nim, [':nim' => $nim_nip])) {
                $error_nim_nip = "NIM/NIP ini sudah terdaftar.";
            }
        }
    }

    // Validasi password minimal panjang
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 
    if (!wajib_isi($password)) {
        $error_password = "Password wajib diisi.";
    } else {
        if (!cek_panjang_minimal($password, 6)) { // <== Menggunakan cek_panjang_minimal()
            $error_password = "Password minimal harus 6 karakter.";
        }
    }

    // Validasi kesamaan password
    if (!wajib_isi($confirm_password)) {
        $error_confirm_password = "Konfirmasi password wajib diisi.";
    } else {
        if (!cek_kesamaan_password($password, $confirm_password)) { 
            $error_confirm_password = "Password dan konfirmasi tidak cocok.";
        }
    }

    // DATA UNTUK INSERT
    if (empty($error_nama) && empty($error_email) && empty($error_nim_nip) && empty($error_password) && empty($error_confirm_password)) {
        // Encrypt password 
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $dataInsert = [
            'nama_user' => $nama,
            'email'     => $email,
            'nim_nip'   => $nim_nip,
            'password'  => $password_hash
        ];

        if (registerPemustaka($dataInsert)) {
            header('location: ' . BASE_URL . 'login.php?status=sukses_registrasi');
            exit;
        } else { 
            $error_general = "Registrasi gagal, terjadi masalah pada sistem.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/auth.css">

</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="logo-icon-big">📚</div>
            <div class="logo-section">
                <div class="logo-text">UnivLib</div>
                <div class="logo-tagline">
                    Satu Akses, Sejuta Pengetahuan
                </div>
            </div>
            <div class="book-illustration">📖</div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h1 class="auth-title">Register</h1>
                <p class="auth-subtitle">Sudah Punya akun ? <a href="<?= BASE_URL; ?>login.php">Sign in</a></p>
            </div>

            <?php if(!empty($error_general)): ?>
            <div class="alert alert-danger"><?= $error_general ?></div>
            <?php endif; ?>

            <form action="register.php" method="post">
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-input" placeholder="masukan nama anda" value="<?= htmlspecialchars($nama) ?>">
                    <span class="form-error"><?= $error_nama ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">NIM/NIP</label>
                    <input type="text" name="nim_nip" class="form-input" placeholder="masukan nim/nip anda" value="<?= htmlspecialchars($nim_nip) ?>">
                    <span class="form-error"><?= $error_nim_nip ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-input" placeholder="masukan email anda"value="<?= htmlspecialchars($email) ?>">
                    <span class="form-error"><?= $error_email ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Buat Password (minimal 6 karakter)" >
                    <span class="form-error"><?= $error_password ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Konfirmasi password">
                    <span class="form-error"><?= $error_confirm_password ?></span>
                </div>

                <button type="submit" name="register" class="submit-btn">Create Account</button>
            </form>
        </div>
    </div>
</body>
</html>