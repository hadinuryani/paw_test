<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

$data['title'] = 'Registrasi Pemustaka';
$data['css'] = ['layout.css','admin.css'];
$data['header'] ='Kelola Pemustaka';

$users = getAllUsers();
require_once '../components/header.php';


?> 

<div class="table-container">

    <table>
        <thead>
            <tr>
                <th>PROFIL</th>
                <th>NAMA USER</th>
                <th>EMAIL</th>
                <th>NIM/NIP</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div class="book-info">
                        <div class="book-cover-small">
                            <?= $u['profil'] ? '<img src="'.BASE_URL.'uploads/'.$u['profil'].'" alt="profil">' : '👤'; ?>
                        </div>
                    </div>
                </td>

                <td><?= htmlspecialchars($u['nama_user']); ?></td>
                <td><?= htmlspecialchars($u['email']); ?></td>
                <td><?= htmlspecialchars($u['nim_nip']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination (dummy, nanti bisa dibuat dinamis) -->
    <div class="pagination">
        <a href="#" class="page-btn">«</a>
        <a href="#" class="page-btn active">1</a>
        <a href="#" class="page-btn">2</a>
        <a href="#" class="page-btn">3</a>
        <a href="#" class="page-btn">»</a>
    </div>

</div>

<?php require_once '../components/footer.php'; ?>
