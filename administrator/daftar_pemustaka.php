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
$query = "SELECT id_pemustaka, nama_pemustaka, email, nim_nip, profil_pemustaka
            FROM pemustaka";
$users = fetchData($query);

require_once '../components/header.php';
?> 

<section class="table-container">
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
                            <?= $u['profil_pemustaka'] ? '<img src="'.BASE_URL.'assets/img/'.$u['profil_pemustaka'].'" alt="profil">' : '👤'; ?>
                        </div>
                    </div>
                </td>

                <td><?= htmlspecialchars($u['nama_pemustaka']); ?></td>
                <td><?= htmlspecialchars($u['email']); ?></td>
                <td><?= htmlspecialchars($u['nim_nip']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once '../components/footer.php'; ?>
