<?php 
session_start();
require_once 'config/config.php';
require_once 'config/function.php';
// set data
$data['title'] = 'Home Page';
$data['css'] = ['layout.css','book.css'];
$data['header'] ='Categories';
// cek session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}

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

$query = "SELECT * FROM buku";
if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$query .= " ORDER BY id_buku DESC";

// ambil data buku
$books = fetchData($query, $params);

require_once 'components/header.php';
?>
<!-- kategori -->
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
            <!-- cover (boleh berisi ilustrasi / teks singkat) -->
            <div class="book-cover">
                <div class="title">
                <!-- teks ringkas di cover, mis. singkatan atau subtitle -->
                <div class="cover-title"><?= $b['judul']; ?></div>
                <div class="cover-sub"><?= $b['penulis']; ?></div>
                </div>
            </div>

            <!-- info yang utama (judul lengkap + penulis) -->
            <div class="book-info">
                <div class="book-title"><?= $b['judul']; ?></div>
                <div class="book-author"><?= $b['penulis']; ?></div>
            </div>

            <!-- tombol detail (SVG) -->
            <div class="btn-book">
                <a title="Lihat Detail Buku" class="detail-link" href="<?= BASE_URL; ?>pemustaka/detail_buku.php?id=<?= $b['id_buku']; ?>" aria-label="Lihat detail">
                    <svg  viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M4 4v15.5" />
                        <path d="M6.5 2H20v15H6.5A2.5 2.5 0 0 1 4 14.5V4z" />
                        <circle cx="17.5" cy="17.5" r="3" />
                        <line x1="20" y1="20" x2="22" y2="22" />
                    </svg>
                </a>
            </div>
        </div>

    <?php endforeach; ?>

</div>

<?php require_once 'components/footer.php'; ?>
