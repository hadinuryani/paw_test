<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

$data['title'] = 'Tambah Buku';
$data['css'] = ['layout.css','admin.css'];
$data['header'] ='Tambah Buku';

if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

require_once '../components/header.php';

// proses submit form
$judul = $penulis = $penerbit = $tahun_terbit = $kategori = '';
$error_judul = $error_penulis = $error_penerbit = $error_tahun_terbit = $error_kategori = '';
$error_general = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // Validasi Judul
    if (!wajib_isi($_POST['judul'])) {
        $error_judul = "Judul buku wajib diisi.";
    } else {
        $judul = test_input($_POST['judul']);
        if (!alfanumerik($judul)) { 
            $error_judul = "Judul mengandung karakter yang tidak valid.";
        }
    }

    // Validasi Penulis
    if (!wajib_isi($_POST['penulis'])) {
        $error_penulis = "Nama penulis wajib diisi.";
    } else {
        $penulis = test_input($_POST['penulis']);
        if (!alfabet($penulis)) { 
            $error_penulis = "Nama penulis hanya boleh berisi huruf dan spasi.";
        }
    }

    // Validasi Penerbit
    if (!wajib_isi($_POST['penerbit'])) {
        $error_penerbit = "Penerbit wajib diisi.";
    } else {
        $penerbit = test_input($_POST['penerbit']);
        if (!alfanumerik($penerbit)) { 
            $error_penerbit = "Penerbit hanya boleh berisi huruf, angka, dan spasi.";
        }
    }

    // Validasi Tahun Terbit
    if (!wajib_isi($_POST['tahun_terbit'])) {
        $error_tahun_terbit = "Tahun terbit wajib diisi.";
    } else {
        $tahun_terbit = test_input($_POST['tahun_terbit']);
        if (!cek_panjang_tepat($tahun_terbit,4)) { 
            $error_tahun_terbit = "Tahun terbit harus 4 digit angka (ex: 2024).";
        }
    }

    // Validasi Kategori 
    if (!wajib_isi($_POST['kategori'])) {
        $error_kategori = "Kategori wajib dipilih.";
    } else {
        $kategori = test_input($_POST['kategori']);
    }

    // DATA UNTUK INSERT
    if (empty($error_judul) && empty($error_penulis) && empty($error_penerbit) && empty($error_tahun_terbit) && empty($error_kategori)) {

        $dataInsert = [
            'judul'        => $judul,
            'penulis'      => $penulis,
            'penerbit'     => $penerbit,
            'tahun_terbit' => $tahun_terbit,
            'kategori'     => $kategori
        ];

        if (addBook($dataInsert)) {
            header("Location: kelola_buku.php?success=buku $judul berhasil di tambahkan");
            exit;
        } else {
            $error_general = "Gagal menambahkan buku";
        }
    }
}
?>

<div class="form-container">

    <?php if(!empty($error_general)): ?>
        <div class="alert alert-danger"><?= $error_general ?></div>
    <?php endif; ?>

    <form action="#" method="post" class="form-edit">

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" class="form-input" value="<?= htmlspecialchars($judul) ?>">
            <span class="form-error"><?= $error_judul ?></span>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" class="form-input" value="<?= htmlspecialchars($penulis) ?>">
            <span class="form-error"><?= $error_penulis ?></span>
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" class="form-input" value="<?= htmlspecialchars($penerbit) ?>">
            <span class="form-error"><?= $error_penerbit ?></span>
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="text" name="tahun_terbit" class="form-input" value="<?= htmlspecialchars($tahun_terbit) ?>">
            <span class="form-error"><?= $error_tahun_terbit ?></span>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-input">
                <option value="">-- Pilih Kategori --</option>
                <option value="umum"   <?= ($kategori == 'umum') ? 'selected' : '' ?>>Umum</option>
                <option value="jurnal" <?= ($kategori == 'jurnal') ? 'selected' : '' ?>>Jurnal</option>
                <option value="skripsi"<?= ($kategori == 'skripsi') ? 'selected' : '' ?>>Skripsi</option>
            </select>
            <span class="form-error"><?= $error_kategori ?></span>
        </div>

        <button type="submit" name="submit" class="btn">Simpan Buku</button>
        <a href="kelola_buku.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

<?php require_once '../components/footer.php'; ?>
