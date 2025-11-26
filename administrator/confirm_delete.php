<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// Set data halaman
$data['title'] = 'konfirmasi';
$data['css'] = ['layout.css','admin.css','alert.css'];
$data['header'] ='Confirmasi Delete';

// Cek session dan role admin
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// Pastikan ada ID buku dari form POST
if (!isset($_POST['id_buku'])) {
    // jika tidak ada, lempar kembali ke halaman kelola buku
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php');
    exit;
}

$id_buku = $_POST['id_buku'];

// Ambil data buku berdasarkan ID
$sql = "SELECT * FROM buku WHERE id_buku = :id";
$b = fetchOne($sql, [':id' => $id_buku]);

// jika buku tidak ditemukan, hentikan script
if (!$b) {
    echo "Buku tidak ditemukan!";
    exit;
}

require_once '../components/header.php';
?>

    <!-- Konfirmasi penghapusan buku -->
    <div class="confirm-box">
        <h2>Konfirmasi Penghapusan</h2>
        <p>Apakah Anda yakin ingin menghapus buku:</p>

        <div class="book-info">
            <div><strong><?= $b['judul']; ?></strong></div>
            <span>Penulis: <?= $b['penulis']; ?></span>
            <span>Kategori: <?= $b['kategori']; ?></span>
        </div>

        <div class="confirm-actions">
            <!-- Form untuk submit penghapusan -->
            <form action="delete_buku.php" method="POST">
                <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </form>

            <!-- Tombol batal kembali ke kelola buku -->
            <a href="<?= BASE_URL; ?>administrator/kelola_buku.php" class="btn btn-secondary">Batal</a>
        </div>
    </div>

<?php require_once '../components/footer.php' ?>
