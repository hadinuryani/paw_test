<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'document';
$data['css'] = ['layout.css','book.css'];
$data['header'] ='Categories';
// set session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_user = $_SESSION['user_id'];
$id_buku = $_GET['id'] ?? null;

if (!$id_buku) {
    die("Buku tidak ditemukan.");
}

// Ambil detail buku
$buku = fetchData("SELECT * FROM buku WHERE id_buku = :id", ['id' => $id_buku], true);
if (!$buku) {
    die("Buku tidak ditemukan.");
}

$msg =$err = "";

// Jika tombol PINJAM ditekan
if (isset($_POST['pinjam'])) {

    // Hitung peminjaman aktif (pending + borrow)
    $count = countActiveBorrow($id_user, $id_buku);

    if ($count >= 1) {
        $err = "Anda sudah meminjam buku ini dan belum mengembalikannya.";
    } else {
        if (createBorrow($id_user, $id_buku)) {
            $msg = "Peminjaman berhasil! Menunggu persetujuan admin.";
        } else {
            $err = "Gagal meminjam buku.";
        }
    }
}

require_once '../components/header.php';
?>

<section class="book-detail">
    <!--cover -->
    <div class="book-cover-detail">
        <div class="title">
            <div style="font-size: 18px; font-weight: 700; margin-bottom: 10px;">
                <?= $buku['judul']; ?>
            </div>
            <div style="font-size: 12px;">
                <?= $buku['penulis']; ?>
            </div>
        </div>
    </div>

    <div class="detail-info">
        <h2><?= $buku['judul']; ?></h2>
        <p><strong>Penulis:</strong> <?= $buku['penulis']; ?></p>
        <p><strong>Kategori:</strong> <?= $buku['kategori']; ?></p>
        <p><strong>Tahun Terbit:</strong> <?= $buku['tahun_terbit']; ?></p>
        <!-- pesan saat meminjam buku -->
        <?php if ($msg): ?>
            <div class="alert-success"><?= $msg; ?></div>
        <?php endif; ?>

        <?php if ($err): ?>
            <div class="alert-error"><?= $err; ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <button type="submit" name="pinjam" class="btn btn-primary">Pinjam Buku</button>
        </form>
    </div>

</section>

<?php require_once '../components/footer.php'; ?>
