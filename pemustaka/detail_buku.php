<?php
session_start();
require_once 'config/config.php';
require_once 'config/function.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemustaka') {
    header("Location: login.php");
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

$msg = "";
$err = "";

// Jika tombol PINJAM ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Hitung peminjaman aktif (pending + borrow)
    $count = countActiveBorrow($id_user, $id_buku);

    if ($count >= 2) {
        $err = "Anda sudah meminjam buku ini 2 kali dan belum mengembalikannya.";
    } else {
        if (createBorrow($id_user, $id_buku)) {
            $msg = "Peminjaman berhasil! Menunggu persetujuan admin.";
        } else {
            $err = "Gagal meminjam buku.";
        }
    }
}

require_once 'components/header.php';
?>

<div class="book-detail">

    <h2><?= htmlspecialchars($buku['judul']); ?></h2>
    <p><strong>Penulis:</strong> <?= htmlspecialchars($buku['penulis']); ?></p>
    <p><strong>Kategori:</strong> <?= htmlspecialchars($buku['kategori']); ?></p>
    <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($buku['tahun_terbit']); ?></p>

    <hr>

    <?php if ($msg): ?>
        <div class="alert-success"><?= $msg; ?></div>
    <?php endif; ?>

    <?php if ($err): ?>
        <div class="alert-error"><?= $err; ?></div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" class="btn btn-primary">PINJAM BUKU</button>
    </form>

</div>

<?php require_once 'components/footer.php'; ?>
