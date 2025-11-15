<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

$data['title'] = 'Edit Buku';
$data['css'] = ['layout.css','admin.css'];
$data['header'] = 'edit';

require_once '../components/header.php';

/* =========================
   Ambil ID buku
========================= */
if (!isset($_GET['id'])) {
    header("Location: kelola_buku.php");
    exit;
}

$id_buku = $_GET['id'];
$buku = getBookById($id_buku);

if (!$buku) {
    echo "<p>Buku tidak ditemukan!</p>";
    require_once '../components/footer.php';
    exit;
}

/* =========================
   Proses update
========================= */
if (isset($_POST['submit'])) {

    $dataUpdate = [
        'judul'        => $_POST['judul'],
        'penulis'      => $_POST['penulis'],
        'penerbit'     => $_POST['penerbit'],
        'tahun_terbit' => $_POST['tahun_terbit'],
        'kategori'     => $_POST['kategori'],
    ];

    if (updateBook($id_buku, $dataUpdate)) {
        header("Location: kelola_buku.php?update_success=1");
        exit;
    } else {
        $error = "Gagal memperbarui buku.";
    }
}

?>

<div class="form-container">
    <h2 class="form-title">Edit Buku</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="#" method="post">

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" class="form-input" 
                   value="<?= htmlspecialchars($buku['judul']) ?>" required>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" class="form-input" 
                   value="<?= htmlspecialchars($buku['penulis']) ?>" required>
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" class="form-input" 
                   value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="date" name="tahun_terbit" class="form-input"
                   value="<?= $buku['tahun_terbit'] ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-input" required>
                <option value="umum"     <?= $buku['kategori']=='umum'?'selected':'' ?>>Umum</option>
                <option value="jurnal"   <?= $buku['kategori']=='jurnal'?'selected':'' ?>>Jurnal</option>
                <option value="skripsi"  <?= $buku['kategori']=='skripsi'?'selected':'' ?>>Skripsi</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Update Buku</button>
        <a href="kelola_buku.php" class="btn btn-secondary">Kembali</a>

    </form>
</div>

<?php require_once '../components/footer.php'; ?>
