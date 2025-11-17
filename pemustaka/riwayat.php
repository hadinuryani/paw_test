<?php 
session_start();
$data['title'] = 'document';
$data['css'] = ['layout.css','admin.css']; // manggil admin css karena butuh style table
$data['header'] ='Categories';

if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

require_once '../config/config.php';
require_once '../config/function.php';

$id_pemustaka = $_SESSION['user_id'];

$sql = "SELECT p.id_peminjaman, 
               b.judul, 
               b.penulis, 
               p.tanggal_peminjaman, 
               p.tanggal_kembali, 
               p.status
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE p.id_user = ?
        ORDER BY p.tanggal_peminjaman DESC";

$stmt = DBH->prepare($sql);
$stmt->execute([$id_pemustaka]);
$riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once '../components/header.php';
?>


<!-- Books Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>JUDUL</th>
                <th>TANGGAL PINJAM</th>
                <th>TANGGAL KEMBALI</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($riwayat): ?>
            <?php foreach ($riwayat as $row): ?>
                <tr>
                    <td><?= $row['judul']; ?></td>
                    <td><?= $row['tanggal_peminjaman']; ?></td>
                    <td><?= $row['tanggal_kembali'] ?: '-'; ?></td>
                    <td>
                        <span class="badge  <?= $row['status'] == 'pending' ? 'badge-danger' : ($row['status'] == 'returned' ? 'badge-success' : 'badge-warning'); ?>">
                            <?= $row['status']; ?>
                        </span>
                    </td>
                    <!-- AKSI -->
                    <td>
                        <?php if ($row['status'] === 'borrow'): ?>
                            <a href="kembalikan_buku.php?id=<?= $row['id_peminjaman']; ?>" class="menu-item">↩️</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="no-data">Belum ada riwayat peminjaman.</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>

</div>

<?php require_once '../components/footer.php' ?>