<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// Set data halaman
$data['title'] = 'Lihat Pemustaka';
$data['css'] = ['layout.css','admin.css','table.css'];
$data['header'] ='Lihat Daftar Pemustaka';

// Cek session dan role admin
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// Ambil semua data pemustaka dari database
$query = "SELECT id_pemustaka, nama_pemustaka, email, nim_nip, profil_pemustaka
          FROM pemustaka";
$users = fetchData($query);


require_once '../components/header.php';
?> 

    <!-- Tabel daftar pemustaka -->
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
                    <!-- Tampilkan foto profil, jika tidak ada pakai default 'users.png' -->
                    <td>
                        <div class="profil-img">
                            <div class="profil">
                                <img src="<?= BASE_URL; ?>assets/upload/<?= $u['profil_pemustaka'] ? $u['profil_pemustaka'] : 'users.png' ?>" alt="profil pemustaka">
                            </div>
                        </div>
                    </td>

                    <!-- Nama, email, nim/nip -->
                    <td><?= htmlspecialchars($u['nama_pemustaka']); ?></td>
                    <td><?= htmlspecialchars($u['email']); ?></td>
                    <td><?= htmlspecialchars($u['nim_nip']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php require_once '../components/footer.php'; ?>
