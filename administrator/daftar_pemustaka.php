<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'Lihat Pemustaka';
$data['css'] = ['layout.css','admin.css'];
$data['header'] ='Lihat Daftar Pemustaka';
// cek session
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

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
                        <div class="profil">
                            <?= $u['profil'] ? '<img src="'.BASE_URL.'assets/img/'.$u['profil'].'" alt="profil">' : '👤'; ?>
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

</div>

<?php require_once '../components/footer.php'; ?>
