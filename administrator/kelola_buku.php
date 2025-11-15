<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
$data['title'] = 'document';
$data['css'] = ['layout.css','admin.css','alert.css'];
$data['header'] ='Kelola Peminjaman';
// ambil data buku
$books = getAllBooks();
$totalBooks = fetchOne("SELECT COUNT(*) AS total FROM buku");

require_once '../components/header.php'
?> 
<div class="alert-wrapper">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✔️</span>
            <?= htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <span class="alert-icon">❌</span>
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
</div>


<div class="table-header">
    <h3 class="table-title">All Books (<?= $totalBooks['total']; ?>)</h3>
    <a href="<?= BASE_URL; ?>administrator/tambah_buku.php" class="btn btn-secondary">
        <span>📤</span> Tambah
    </a>
</div>
            <!-- Books Table -->
            <div class="table-container">
                

                <table>
                    <thead>
                        <tr>
                            <th>BOOK</th>
                            <th>PENULIS</th>
                            <th>PENERBIT</th>
                            <th>KATEGORI</th>
                            <th>TAHUN TERBIT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $b): ?>
                        <tr>
                            <td>
                                <div class="book-info">
                                    📖
                                        <div class="book-details">
                                        <h4><?= $b['judul']; ?></h4>
                                    </div>
                                </div>
                            </td>
                            <td><?= $b['penulis']; ?></td>
                            <td><?= $b['penerbit']; ?></td>
                            <td><?= $b['tahun_terbit']; ?></td>
                            <td><?= $b['kategori']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_buku.php?id=<?= $b['id_buku'] ?>" class="icon-btn icon-btn-edit">✏️</a>
                                    <form action="confirm_delete.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_buku" value="<?= $b['id_buku']; ?>">
                                        <button type="submit" class="icon-btn icon-btn-delete">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>

                        
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <a href="#" class="page-btn">«</a>
                    <a href="#" class="page-btn active">1</a>
                    <a href="#" class="page-btn">2</a>
                    <a href="#" class="page-btn">3</a>
                    <a href="#" class="page-btn">4</a>
                    <a href="#" class="page-btn">5</a>
                    <a href="#" class="page-btn">...</a>
                    <a href="#" class="page-btn">124</a>
                    <a href="#" class="page-btn">»</a>
                </div>
            </div>


<?php require_once '../components/footer.php' ?>



