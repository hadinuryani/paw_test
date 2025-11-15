<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

$data['title'] = 'Tambah Buku';
$data['css'] = ['layout.css','admin.css',];
$data['header'] ='Tambah Buku';
require_once '../components/header.php';

// proses submit form
if (isset($_POST['submit'])) {
    $judul        = $_POST['judul'];
    $penulis      = $_POST['penulis'];
    $penerbit     = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori     = $_POST['kategori'];


    // DATA UNTUK INSERT
    $dataInsert = [
        'judul'        => $judul,
        'penulis'      => $penulis,
        'penerbit'     => $penerbit,
        'tahun_terbit' => $tahun_terbit,
        'kategori'     => $kategori
    ];

    if (addBook($dataInsert)) {
        header("Location: kelola_buku.php?success=1");
        exit;
    } else {
        $error = "Gagal menambahkan buku";
    }
}

?>

<div class="form-container">
    <h2 class="form-title">Tambah Buku</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="#" method="post">

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="date" name="tahun_terbit" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-input" required>
                <option value="umum">Umum</option>
                <option value="jurnal">Jurnal</option>
                <option value="skripsi">Skripsi</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            Simpan Buku
        </button>

        <a href="kelola_buku.php" class="btn btn-secondary">Kembali</a>
    </form>

    
</div>

<?php require_once '../components/footer.php'; ?>
