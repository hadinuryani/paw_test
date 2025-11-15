<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
$data['title'] = 'document';
$data['css'] = ['layout.css','admin.css'];
$data['header'] ='Kelola Peminjaman';

$peminjaman = getAllPeminjaman();

require_once '../components/header.php'
?> 
            
            <!-- Books Table -->
            <div class="table-container">
                

                <table>
                    <thead>
                        <tr>
                            <th>BOOK</th>
                            <th>AUTHOR</th>
                            <th>CATEGORY</th>
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
                                        <h4><?= htmlspecialchars($p['judul']); ?></h4>
                                        <p>Peminjaman: <?= $p['tanggal_peminjaman']; ?></p>
                                    </div>
                                </div>
                            </td>

                            <td><?= htmlspecialchars($p['penulis']); ?></td>

                            <td><?= htmlspecialchars($p['kategori']); ?></td>

                            <td>
                                <?php if($p['status'] == 'pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif($p['status'] == 'borrow'): ?>
                                    <span class="badge badge-success">Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Returned</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <?php if ($p['status'] === 'pending'): ?>
                                        <!-- ADMIN Approve -->
                                        <form action="update_status.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                            <input type="hidden" name="status" value="borrow">
                                            <button class="icon-btn icon-btn-edit">✔️ Approve</button>
                                        </form>
                                    <?php elseif ($p['status'] === 'borrow'): ?>
                                        <!-- Peminjam Kembalikan -->
                                        <form action="update_status.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $p['id_peminjaman']; ?>">
                                            <input type="hidden" name="status" value="returned">
                                            <button class="icon-btn icon-btn-view">↩️ Kembalikan</button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Returned = tidak ada aksi -->
                                        <span class="badge badge-secondary">Selesai</span>
                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
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



