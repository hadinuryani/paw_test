<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// set data untuk halaman (judul, css, dll)
$data['title'] = 'Riwayat Peminjaman';
$data['css'] = ['layout.css','table.css','badge.css','alert.css']; 
$data['header'] ='Riwayat Peminjaman';

// cek apakah user sudah login sebagai pemustaka
// kalau bukan pemustaka langsung dilempar ke login
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// ambil id pemustaka dari session login
$id_pemustaka = $_SESSION['user_id'];

// Query join peminjaman + buku
// Tujuannya: ngambil info lengkap peminjaman yang dilakukan user ini.
// Order by untuk menampilkan yang terbaru di atas.
$query = "SELECT p.id_peminjaman, 
               b.judul, 
               b.penulis, 
               p.tanggal_peminjaman, 
               p.tanggal_kembali, 
               p.status
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE p.id_pemustaka = :id_p
        ORDER BY p.tanggal_peminjaman DESC";

// ambil data riwayat peminjaman 
// parameter false supaya hasilnya berbentuk array biasa
$riwayat = fetchData($query,['id_p' => $id_pemustaka],false);

require_once '../components/header.php';
?>
    <div class="alert-wrapper">

        <!-- bagian alert ini cuma tampil kalau ada pesan dari URL -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✔️</span>
                <?= htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['err'])): ?>
            <div class="alert alert-error">
                <span class="alert-icon">❌</span>
                <?= htmlspecialchars($_GET['err']); ?>
            </div>
        <?php endif; ?>

    </div>

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

                <!-- kalau user sudah pernah meminjam buku -->
                <?php if ($riwayat): ?>

                    <?php foreach ($riwayat as $row): ?>
                        <tr>
                            <td><?= $row['judul']; ?></td>

                            <!-- tanggal pinjam -->
                            <td><?= $row['tanggal_peminjaman']; ?></td>

                            <!-- kalau tanggal kembali masih null ditampilkan "-" -->
                            <td><?= $row['tanggal_kembali'] ?: '-'; ?></td>

                            <td>
                                <!-- Badge status: logic warna berdasarkan status -->
                                <span class="badge 
                                    <?php 
                                        if ($row['status'] == 'pending') echo 'badge-yellow';
                                        else if ($row['status'] == 'borrow') echo 'badge-green';
                                        else if ($row['status'] == 'returned') echo 'badge-blue';
                                        else if ($row['status'] == 'rejected') echo 'badge-red';
                                    ?>">
                                    <?= ucfirst($row['status']); ?>
                                </span>
                            </td>

                            <td>
                                <!-- tombol kembalikan hanya muncul kalau status borrower -->
                                <!-- konsepnya: user cuma bisa mengembalikan buku yang statusnya 'borrow' -->
                                <?php if ($row['status'] == 'borrow'): ?>
                                    <a href="kembalikan_buku.php?id=<?= $row['id_peminjaman']; ?>" class="badge badge-blue">
                                        Kembalikan 
                                    </a>
                                <?php else: ?>
                                    <!-- selain itu (pending, returned, rejected) tidak ada aksi -->
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- fallback kalau sama sekali belum ada riwayat -->
                    <tr>
                        <td colspan="6" class="no-data">Belum ada riwayat peminjaman.</td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>
    </div>

<?php require_once '../components/footer.php'; ?>
