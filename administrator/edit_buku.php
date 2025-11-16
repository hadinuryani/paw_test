<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'Edit Buku';
$data['css'] = ['layout.css','admin.css'];
$data['header'] = 'Edit Buku';
// cek session
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

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

require_once '../components/header.php';
?>

<div class="form-container">

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="#" method="post" class="form-edit">

    <div>
        <label>Judul Buku</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>
    </div>

    <div>
        <label>Penulis</label>
        <input type="text" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" required>
    </div>

    <div>
        <label>Penerbit</label>
        <input type="text" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
    </div>

    <div>
        <label>Tahun Terbit</label>
        <input type="text" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>" required>
    </div>

    <div>
        <label>Kategori</label>
        <select name="kategori" required>
            <option value="umum"     <?= $buku['kategori']=='umum'?'selected':'' ?>>Umum</option>
            <option value="jurnal"   <?= $buku['kategori']=='jurnal'?'selected':'' ?>>Jurnal</option>
            <option value="skripsi"  <?= $buku['kategori']=='skripsi'?'selected':'' ?>>Skripsi</option>
        </select>
    </div>

    <button type="submit" name="submit" class="btn">Update Buku</button>
    <a href="kelola_buku.php" class="btn btn-secondary">Kembali</a>

</form>

</div>

<?php require_once '../components/footer.php'; ?>
