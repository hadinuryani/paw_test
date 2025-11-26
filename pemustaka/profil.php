<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// set data untuk halaman profil
$data['title'] = 'Profil';
$data['css'] = ['layout.css','profil.css','alert.css'];
$data['header'] ='Profil';

// Cek apakah user benar-benar login sebagai pemustaka.
// Kalau tidak, user langsung diarahkan ke login.
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_user = $_SESSION['user_id'];

// Query ambil data profil pengguna.
$sql = "SELECT nama_pemustaka, email, nim_nip, profil_pemustaka
        FROM pemustaka
        WHERE id_pemustaka = :id";

// ambil data profil pengguna.
$profil = fetchOne($sql, ['id' => $id_user]);

require_once '../components/header.php'
?>
    <div class="alert-wrapper">
        <!-- Alert ini hanya muncul kalau update profil sukses -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✔️</span>
                <?= htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Card profil -->
    <section class="card">
        <h2>Profil Pemustaka</h2>

        <div class="profile-card">

            <!-- Kalau user belum upload foto, fallback ke users.png -->
            <img src="<?= BASE_URL; ?>assets/img/<?= $profil['profil_pemustaka'] ? $profil['profil_pemustaka'] :'users.png'; ?>" 
                alt="Foto Profil" class="profile-photo">

            <div class="profile-info">
                <h3><?= $profil['nama_pemustaka']; ?></h3>
                <p><i class="fa fa-envelope"></i> <?= $profil['email']; ?></p>
                <p><i class="fa fa-id-card"></i> <?= $profil['nim_nip']; ?></p>

                <!-- Link ke halaman edit profil -->
                <a href="edit_profil.php" class="btn-edit">Edit Profil</a>
            </div>
        </div>

    </section>
    
<?php require_once '../components/footer.php' ?>
