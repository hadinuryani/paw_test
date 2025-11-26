<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';
// Cek jika user sudah login
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Jika admin lempar ke dashboard admin
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
$nama = $email = $nim_nip = $password = $confirm_password = '';
$error_nama = $error_email = $error_nim_nip = $error_password = $error_confirm_password = '';
$error_general = ''; 

// Jika tombol register ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // VALIDASI NAMA
    if (!wajib_isi($_POST['nama'])) {
        $error_nama = "Nama lengkap wajib diisi.";
    } else {
        $nama = test_input($_POST['nama']);
        if (!alfabet($nama)) {
            $error_nama = "Nama hanya boleh berisi huruf dan spasi.";
        }
    }
    // VALIDASI NIM/NIP
    if (!wajib_isi($_POST['nim_nip'])) {
        $error_nim_nip = "NIM/NIP wajib diisi.";
    } else {
        $nim_nip = test_input($_POST['nim_nip']);
        if (!cek_format_identitas($nim_nip)) {
            $error_nim_nip = "NIM/NIP tidak valid. Harus 12 digit (NIM) atau 18 digit (NIP).";
        }
    }

    // VALIDASI EMAIL
    if (!wajib_isi($_POST['email'])) {
        $error_email = "Email wajib diisi.";
    } else {
        $email = test_input($_POST['email']);
        if (!cek_format_email($email)) {
            $error_email = "Format email tidak valid.";
        }
    }

    // VALIDASI PASSWORD
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!wajib_isi($password)) {
        $error_password = "Password wajib diisi.";
    } else {
        if (!cek_panjang_minimal($password, 6)) {
            $error_password = "Password minimal harus 6 karakter.";
        }
    }

    // Validasi Konfirmasi Password
    if (!wajib_isi($confirm_password)) {
        $error_confirm_password = "Konfirmasi password wajib diisi.";
    } else {
        if (!cek_kesamaan_password($password, $confirm_password)) {
            $error_confirm_password = "Password dan konfirmasi tidak cocok.";
        }
    }

    // JIKA SEMUA VALIDASI LOLOS
    if (
        empty($error_nama) &&
        empty($error_email) &&
        empty($error_nim_nip) &&
        empty($error_password) &&
        empty($error_confirm_password)
    ) {
        // CEK DUPLIKASI EMAIL ATAU NIM
        $cekEmail = fetchOne(
            "SELECT id_pemustaka FROM pemustaka 
            WHERE email = :email LIMIT 1",
            [':email' => $email]
        );
        $cekNIM = fetchOne(
            "SELECT id_pemustaka FROM pemustaka 
            WHERE nim_nip = :nim LIMIT 1",
            [':nim'   => $nim_nip ]
        );

        if ($cekEmail) {
            $error_general = "Email sudah terdaftar.";
        }elseif($cekNIM){
            $error_general = "NIM/NIP sudah terdaftar";
        } else {
            // HASH PASSWORD
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // DATA UNTUK INSERT
            $dataInsert = [
                'nama_user' => $nama,
                'email'     => $email,
                'nim_nip'   => $nim_nip,
                'password'  => $password_hash
            ];

            // INSERT DATA
            if (registerPemustaka($dataInsert)) {
                header('Location: ' . BASE_URL . 'login.php?success=berhasil melakukan registrasi silankan login');
                exit;
            } else {
                $error_general = "Registrasi gagal, terjadi kesalahan sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/auth.css">
</head>
<body>
    <!-- register page -->
    <div class="auth-container">

        <!-- bagian kanan -->
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

        <!-- bagian kiri -->
        <div class="auth-right">
            <div class="auth-header">
                <h1 class="auth-title">Register</h1>
                <p class="auth-subtitle">Sudah Punya akun ? <a href="<?= BASE_URL; ?>login.php">Login</a></p>
            </div>

            <!-- tampilkan error jika ada -->
            <?php if(!empty($error_general)): ?>
                <div class="form-error"><?= $error_general ?></div>
            <?php endif; ?>

            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-input" placeholder="masukan nama anda" value="<?= htmlspecialchars($nama) ?>">
                    <span class="form-error"><?= $error_nama ?></span>
                </div>

                <div class="form-group">
                    <label for="nim_nip" class="form-label">NIM/NIP</label>
                    <input type="text" name="nim_nip" id="nim_nip" class="form-input" placeholder="masukan nim/nip anda" value="<?= htmlspecialchars($nim_nip) ?>">
                    <span class="form-error"><?= $error_nim_nip ?></span>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-input" placeholder="masukan email anda" value="<?= htmlspecialchars($email) ?>">
                    <span class="form-error"><?= $error_email ?></span>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Buat Password (minimal 6 karakter)" >
                    <span class="form-error"><?= $error_password ?></span>
                </div>

                <div class="form-group">
                    <label for="password2" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" id="password2" class="form-input" placeholder="Konfirmasi password">
                    <span class="form-error"><?= $error_confirm_password ?></span>
                </div>

                <button type="submit" class="submit-btn">Buat Akun</button>
            </form>
        </div>
    </div>

</body>
</html>