<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'document';
$data['css'] = ['layout.css','admin.css']; 
$data['header'] ='Categories';
// cek session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}



$id_pemustaka = $_SESSION['user_id'];

$query = "SELECT p.id_peminjaman, 
               b.judul, 
               b.penulis, 
               p.tanggal_peminjaman, 
               p.tanggal_kembali, 
               p.status
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE p.id_pemustaka = ?
        ORDER BY p.tanggal_peminjaman DESC";
// ambil data riwayat
$riwayat = fetchData($query,[$id_pemustaka],false);

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

                    <!-- BADGE STATUS -->
                    <td>
                        <span class="badge 
                            <?php 
                                if ($row['status'] == 'pending') echo 'badge-warning';
                                else if ($row['status'] == 'borrow') echo 'badge-primary';
                                else if ($row['status'] == 'returned') echo 'badge-success';
                                else if ($row['status'] == 'rejected') echo 'badge-danger';
                            ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td>
                        <?php if ($row['status'] == 'borrow'): ?>
                            <a href="kembalikan_buku.php?id=<?= $row['id_peminjaman']; ?>" class="menu-item">
                                Kembalikan Buku
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
</div>

<?php require_once '../components/footer.php'; ?>
