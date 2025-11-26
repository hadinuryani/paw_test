<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// set data tampilan halaman (judul, css, header)
$data['title'] = 'kelola peminjaman';
$data['css'] = ['layout.css','admin.css','table.css','alert.css','badge.css'];
$data['header'] ='Kelola Peminjaman';

// cek akses admin
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// update otomatis status peminjaman 
autoUpdateStatusPeminjaman();

// query untuk mengambil seluruh data peminjaman + relasi buku & pemustaka
$query = "SELECT
        p.id_peminjaman,
        p.tanggal_peminjaman,
        p.tanggal_kembali,
        p.status,
        b.judul,
        b.penulis,
        b.kategori,
        pm.nama_pemustaka
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN pemustaka pm ON p.id_pemustaka = pm.id_pemustaka
    ORDER BY p.id_peminjaman DESC";

// eksekusi query
$peminjaman = fetchData($query);

require_once '../components/header.php'
?> 

    <div class="alert-wrapper">
        <?php if (isset($_GET['success'])): ?>
            <!-- pesan sukses  -->
            <div class="alert alert-success">
                <span class="alert-icon">✔️</span>
                <?= $_GET['success']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <!-- pesan error jika aksi gagal -->
            <div class="alert alert-error">
                <span class="alert-icon">❌</span>
                <?= $_GET['error']; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabel Data Peminjaman Buku -->
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
                        <!-- info buku -->
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
                    <td><?= $p['nama_pemustaka']; ?></td>

                    <!-- badge status peminjaman -->
                    <td>
                        <?php if($p['status'] == 'pending'): ?>
                            <span class="badge badge-yellow">Pending</span>
                        <?php elseif($p['status'] == 'borrow'): ?>
                            <span class="badge badge-green">Dipinjam</span>
                        <?php elseif($p['status'] == 'rejected'): ?>
                            <span class="badge badge-red">Ditolak</span>
                        <?php else: ?>
                            <span class="badge badge-green">Returned</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <!-- tombol aksi sesuai status -->
                        <div class="action-buttons">

                            <?php if ($p['status'] === 'pending'): ?>

                                <!-- set status menjadi 'borrow' -->
                                <form action="update_status.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                    <input type="hidden" name="status" value="borrow">
                                    <button class="icon-btn icon-btn-approve" type="submit" title="Terima permintaan">✔️</button>
                                </form>

                                <!-- set status menjadi 'rejected' -->
                                <form action="update_status.php" method="POST" class="inline ml">
                                    <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="icon-btn icon-btn-reject" type="submit" title="Tolak permintaan">✖️</button>
                                </form>

                            <?php elseif ($p['status'] === 'borrow'): ?>

                                <!-- tandai sebagai returned -->
                                <form action="update_status.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                    <input type="hidden" name="status" value="returned">
                                    <button class="icon-btn icon-btn-return" type="submit" title="Tandai dikembalikan">↩️</button>
                                </form>

                            <?php elseif ($p['status'] === 'rejected'): ?>

                                <!-- jika ditolak tidak ada aksi -->
                                <span class="badge badge-red">-</span>

                            <?php else: ?>

                                <!-- jika sudah returned tidak ada aksi tambahan -->
                                <span class="badge badge-blue">-</span>

                            <?php endif; ?>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

<?php require_once '../components/footer.php' ?>
