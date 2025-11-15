<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'Confirmasi';
$data['css'] = ['layout.css','admin.css','alert.css'];
$data['header'] ='Confirmasi Delete';
// cek session
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}
if (!isset($_POST['id_buku'])) {
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php');
    exit;
}

$id_buku = $_POST['id_buku'];

// Ambil data buku
$sql = "SELECT * FROM buku WHERE id_buku = :id";
$b = fetchOne($sql, [':id' => $id_buku]);

if (!$b) {
    echo "Buku tidak ditemukan!";
    exit;
}
require_once '../components/header.php';
?>

<div class="confirm-box">
    <h2>Konfirmasi Penghapusan</h2>
    <p>Apakah Anda yakin ingin menghapus buku:</p>

    <div class="book-info">
        <div><strong><?= $b['judul']; ?></strong></div>
        <span>Penulis: <?= $b['penulis']; ?></span>
        <span>Kategori: <?= $b['kategori']; ?></span>
        
    </div>

    <div class="confirm-actions">
        <form action="delete_buku.php" method="POST">
            <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>

        <a href="<?= BASE_URL; ?>administrator/kelola_buku.php" class="btn btn-secondary">Batal</a>
    </div>
</div>

<?php require_once '../components/footer.php' ?>