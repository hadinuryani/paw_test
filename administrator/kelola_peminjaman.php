<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
$data['title'] = 'document';
$data['css'] = ['layout.css','admin.css','alert.css'];
$data['header'] ='Kelola Peminjaman';
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$peminjaman = getAllPeminjaman();
require_once '../components/header.php'
?> 
<div class="alert-wrapper">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✔️</span>
            <?= $_GET['success']; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <span class="alert-icon">❌</span>
            <?= $_GET['error']; ?>
        </div>
    <?php endif; ?>
</div>
<!-- Books Table -->
<section class="table-container">
    <table>
        <thead>
            <tr>
                <th>BOOK</th>
                <th>AUTHOR</th>
                <th>CATEGORY</th>
                <th>PEMINJAM</th> 
                <th>STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($peminjaman as $p): ?>
            <tr>
                <td>
                    <div class="book-info">
                        <div class="book-cover-small">📘</div>
                        <div class="book-details">
                            <h4><?= $p['judul']; ?></h4>
                            <p>Peminjaman: <?= $p['tanggal_peminjaman']; ?></p>
                        </div>
                    </div>
                </td>
                <td><?= $p['penulis']; ?></td>
                <td><?= $p['kategori']; ?></td>
                <td><?= $p['nama_user']; ?></td> <!-- Tambahan -->
                <td>
                    <?php if($p['status'] == 'pending'): ?>
                        <span class="badge badge-warning">Pending</span>
                    <?php elseif($p['status'] == 'borrow'): ?>
                        <span class="badge badge-success">Dipinjam</span>
                    <?php elseif($p['status'] == 'rejected'): ?>
                        <span class="badge badge-danger">Ditolak</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Returned</span>
                    <?php endif; ?>
                </td>


                <td>
                    <div class="action-buttons">
                        <?php if ($p['status'] === 'pending'): ?>
                            <!-- Terima / Approve -->
                            <form action="update_status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                <input type="hidden" name="status" value="borrow">
                                <button class="icon-btn icon-btn-approve" type="submit" title="Terima permintaan">✔️ Terima</button>
                            </form>

                            <!-- Tolak / Reject -->
                            <form action="update_status.php" method="POST" style="display:inline; margin-left:6px;">
                                <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button class="icon-btn icon-btn-reject" type="submit" title="Tolak permintaan">✖️ Tolak</button>
                            </form>

                        <?php elseif ($p['status'] === 'borrow'): ?>
                            <!-- Kembalikan buku -->
                            <form action="update_status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                <input type="hidden" name="status" value="returned">
                                <button class="icon-btn icon-btn-return" type="submit" title="Tandai dikembalikan">↩️ Kembalikan</button>
                            </form>

                        <?php elseif ($p['status'] === 'rejected'): ?>
                            <span class="badge badge-danger">Ditolak</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Selesai</span>
                        <?php endif; ?>
                    </div>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</section>


<?php require_once '../components/footer.php' ?>



