<?php 
session_start();
$data['title'] = 'document';
$data['css'] = ['layout.css','book.css'];
$data['header'] ='Categories';

if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}

require_once 'config/config.php';
require_once 'config/function.php';

// filter data
$params = [];
$where = [];
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$kat = $_GET['kategori'] ?? null;

if ($q !== '') {
    $where[] = "(judul LIKE :q OR penulis LIKE :q)";
    $params['q'] = "%$q%";
}

if ($kat) {
    $where[] = "kategori = :kat";
    $params['kat'] = $kat;
}

$sql = "SELECT * FROM buku";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id_buku DESC LIMIT 10";

$books = fetchData($sql, $params);

require_once 'components/header.php';
?>

<div class="categories">
    <a class="category-btn <?= !$kat ? 'active' : '' ?>" href="index.php">All</a>
    <a class="category-btn <?= $kat == 'umum' ? 'active' : '' ?>" href="?kategori=umum">Umum</a>
    <a class="category-btn <?= $kat == 'jurnal' ? 'active' : '' ?>" href="?kategori=jurnal">Jurnal</a>
    <a class="category-btn <?= $kat == 'skripsi' ? 'active' : '' ?>" href="?kategori=skripsi">Skripsi</a>
</div>

<?php if (empty($books)): ?>
        <p class="buku-empty">Tidak ada buku ditemukan.</p>
    <?php endif; ?>
<div class="books-grid">

    

    <?php foreach ($books as $b): ?>
    <div class="book-card">

       
        <div class="book-cover">
            <div class="title" style="padding: 20px; text-align: center; color: #1f2937;">
                <div style="font-size: 18px; font-weight: 700; margin-bottom: 10px;">
                    <?= htmlspecialchars($b['judul']); ?>
                </div>
                <div style="font-size: 12px;">
                    <?= htmlspecialchars($b['penulis']); ?>
                </div>
            </div>
        </div>

        <!-- Nama + tombol detail -->
        <div class="btn-book">
            <div>
                <div class="book-title"><?= htmlspecialchars($b['judul']); ?></div>
                <div class="book-author"><?= htmlspecialchars($b['penulis']); ?></div>
            </div>

            <!-- Tombol Lihat Detail -->
            <a href="<?= BASE_URL; ?>pemustaka/detail_buku.php?id=<?= $b['id_buku'] ?>">
                <img src="<?= BASE_URL; ?>assets/img/show.png" alt="">
            </a>
        </div>

    </div>
    <?php endforeach; ?>

</div>

<?php require_once 'components/footer.php'; ?>
