<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

$data['title'] = 'Manage Books';
$data['css'] = ['layout.css','admin.css'];
$data['header'] = 'Manage Books';

require_once '../components/header.php';

// Ambil total buku
$totalBooks = fetchOne("SELECT COUNT(*) AS total FROM buku");


// Ambil pending review, jika tidak ada kolom status, buat 0 dulu
$pending = fetchOne("SELECT COUNT(*) AS p FROM peminjaman WHERE status = 'pending'");


// Ambil semua buku untuk tabel
$books = fetchData("SELECT * FROM buku ORDER BY id_buku DESC");

?>

<!-- Stats Cards -->
<div class="stats-grid">

    <!-- Total Books -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Books</span>
            <span class="stat-icon">📚</span>
        </div>
        <div class="stat-value"><?= $totalBooks['total']; ?></div>
        <div class="stat-change">Data realtime dari database</div>
    </div>

    <!-- Pending Review -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Pending Review</span>
            <span class="stat-icon">⏳</span>
        </div>
        <div class="stat-value"><?= $pending['p']; ?></div>
        <div class="stat-change">Jumlah buku yang sedang menunggu review</div>
    </div>
</div>

<!-- Books Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>JUDUL</th>
                <th>PENULIS</th>
                <th>KATEGORI</th>
                <th>TAHUN TERBIT</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($books as $b): ?>
            <tr>
                <td>
                    <div class="book-info">
                        <div class="book-cover-small">📖</div>
                        <div class="book-details">
                            <h4><?= $b['judul']; ?></h4>
                            <p>Published: <?= $b['tahun_terbit']; ?></p>
                        </div>
                    </div>
                </td>

                <td><?= $b['penulis']; ?></td>
                <td><?= $b['kategori']; ?></td>
                <td><?= $b['tahun_terbit']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination (Belum dibuat dinamich) -->
    <div class="pagination">
        <a href="#" class="page-btn">«</a>
        <a href="#" class="page-btn active">1</a>
        <a href="#" class="page-btn">2</a>
        <a href="#" class="page-btn">3</a>
        <a href="#" class="page-btn">»</a>
    </div>
</div>

<?php require_once '../components/footer.php' ?>
