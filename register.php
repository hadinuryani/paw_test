<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';

if (isset($_POST['register'])) {
    $nama       = $_POST['nama'];
    $nim_nip    = $_POST['nim_nip'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // validasi password
    if ($password !== $confirm_password) {
    $error = 'Konfirmasi password tidak sesuai';
    } else {
    // cek apakah email atau nim/nip sudah ada
    $cek = fetchData(
        "SELECT id_user FROM users 
        WHERE email = :email OR nim_nip = :nim",
        [
        ':email' => $email,
        ':nim'   => $nim_nip
        ]
    );

        if(!empty($cek)) {
            $error = 'Email atau NIM/NIP sudah terdaftar';
        }else {
            // hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // insert data
            $insert = registerPemustaka([
            'nama_user' => $nama,
            'email'          => $email,
            'nim_nip'        => $nim_nip,
            'password'       => $password_hash
            ]);

            if ($insert) {
                header('location: ' . BASE_URL . 'login.php');
                // kasih alert 'Registrasi berhasil! Silakan login.';
            } else {
                // kasih alert 'Terjadi kesalahan saat registrasi.';
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

            <form action="#" method="post">
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-input" placeholder="masukan nama anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label">NIM/NIP</label>
                    <input type="text" name="nim_nip" class="form-input" placeholder="masukan nim/nip anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="masukan email anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Buat Password"  required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Confirmasi password" required>
                </div>

                <button type="submit" name="register" class="submit-btn">Create Account</button>
            </form>
        </div>
    </div>
</body>
</html>