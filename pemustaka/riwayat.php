<?php 
session_start();
$data['title'] = 'document';
$data['css'] = ['layout.css','book.css','admin.css']; // manggil admin css karena butuh style table
$data['header'] ='Categories';
// if(!($_SESSION['id_users'] && $_SESSION['nama_user'] && $_SESSION['pemustaka'])){
//     //lempar
// }

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
                <th>COVER</th>
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
            <!-- COVER -->
            <td>-</td>

            <!-- JUDUL -->
            <td><?= htmlspecialchars($row['judul']); ?></td>

            <!-- TANGGAL PINJAM -->
            <td><?= htmlspecialchars($row['tanggal_peminjaman']); ?></td>

            <!-- TANGGAL KEMBALI -->
            <td><?= $row['tanggal_kembali'] ?: '-'; ?></td>

            <!-- STATUS -->
            <td>
                <span class="status <?= strtolower($row['status']); ?>">
                    <?= htmlspecialchars($row['status']); ?>
                </span>
            </td>

            <!-- AKSI -->
            <td>
                <?php if ($row['status'] === 'borrow'): ?>
                    <a href="kembalikan.php?id=<?= $row['id_peminjaman']; ?>" class="btn-kembali">
                        Kembalikan
                    </a>
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