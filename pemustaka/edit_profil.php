<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// set data
$data['title'] = 'Edit Profil';
$data['css'] = ['layout.css','profil.css','alert.css'];
$data['header'] ='Edit Profil';

// cek session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_pemustaka = $_SESSION['user_id'] ?? null;

// ambil profil lama
$profil = getProfilPemustaka($id_pemustaka);

// variabel form + error
$error_nama = $error_email = $error_identitas = $error_foto = "";
$nama = $email = $identitas = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Ambil input ---
    $nama      = test_input($_POST['nama_pemustaka'] ?? '');
    $email     = test_input($_POST['email_pemustaka'] ?? '');
    $identitas = test_input($_POST['nim_nip_pemustaka'] ?? '');

    // VALIDASI NAMA
    if (!wajib_isi($nama)) {
        $error_nama = "Nama harus diisi.";
    } elseif (!alfabet($nama)) {
        $error_nama = "Nama hanya boleh berisi huruf, spasi, koma, titik, apostrophe.";
    }

    // VALIDASI EMAIL
    if (!wajib_isi($email)) {
        $error_email = "Email harus diisi.";
    } elseif (!cek_format_email($email)) {
        $error_email = "Format email tidak valid.";
    }

    // VALIDASI NIM/NIP
    if (!wajib_isi($identitas)) {
        $error_identitas = "NIM / NIP harus diisi.";
    } elseif (!numerik($identitas)) {
        $error_identitas = "NIM / NIP hanya boleh angka.";
    } elseif (!cek_format_identitas($identitas)) {
        $error_identitas = "NIM harus 12 digit atau NIP harus 18 digit.";
    }

    // VALIDASI FOTO PROFIL (optional)
    if (isset($_FILES['profil']) && $_FILES['profil']['error'] === 0) {

        $allowed = ['jpg','jpeg','png','webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $fileName = $_FILES['profil']['name'];
        $fileTmp  = $_FILES['profil']['tmp_name'];
        $fileSize = $_FILES['profil']['size'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext,$allowed)) {
            $error_foto = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        }

        if ($fileSize > $maxSize) {
            $error_foto = "Ukuran foto maksimal 2MB.";
        }

        if (!getimagesize($fileTmp)) {
            $error_foto = "File yang diupload bukan gambar.";
        }
    }

    // JIKA SEMUA AMAN UPDATE
    if ($error_nama === "" && $error_email === "" && $error_identitas === "" && $error_foto === "") {

        $dataPost = [
            'nama_pemustaka'    => $nama,
            'email_pemustaka'   => $email,
            'nim_nip_pemustaka' => $identitas,
            'profil_lama'       => $profil['profil_pemustaka'] ?? null
        ];

        $isUpdated = updateProfilPemustaka($id_pemustaka, $dataPost, $_FILES['profil'] ?? null);

        if ($isUpdated) {
            $userBaru = getProfilPemustaka($id_pemustaka);

            // perbarui session
            $_SESSION['nama_user'] = $userBaru['nama_pemustaka'];
            $_SESSION['email']     = $userBaru['email'];
            $_SESSION['nim_nip']   = $userBaru['nim_nip'];
            $_SESSION['profil']    = $userBaru['profil_pemustaka'];

            header("Location: profil.php?success=profil berhasil di perbarui");
            exit;
        } else {
            $error_general = "Gagal memperbarui profil.";
        }
    }
}

require_once '../components/header.php';
?>

    <section class="content">
        <h2>Edit Profil</h2>

        <form method="POST" enctype="multipart/form-data" class="form-edit">

            <!-- NAMA -->
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama_pemustaka" 
                    value="<?= htmlspecialchars($nama ?: ($profil['nama_pemustaka'] ?? '')) ?>">
                <span class="form-error"><?= $error_nama ?></span>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email_pemustaka" 
                    value="<?= htmlspecialchars($email ?: ($profil['email'] ?? '')) ?>">
                <span class="form-error"><?= $error_email ?></span>
            </div>

            <!-- IDENTITAS -->
            <div class="form-group">
                <label>NIM / NIP</label>
                <input type="text" name="nim_nip_pemustaka" 
                    value="<?= htmlspecialchars($identitas ?: ($profil['nim_nip'] ?? '')) ?>">
                <span class="form-error"><?= $error_identitas ?></span>
            </div>

            <!-- FOTO -->
            <div class="form-group">
                <label>Foto Profil</label><br>
                <img src="<?= BASE_URL; ?>assets/upload/<?= $profil['profil_pemustaka'] ?? 'users.png'; ?>" class="img-rounded" alt="profil_<?= $profil['profil_pemustaka']; ?>">
                <input type="file" name="profil" accept="image/*">
                <span class="form-error"><?= $error_foto ?></span>
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>

    </section>

<?php require_once '../components/footer.php'; ?>
