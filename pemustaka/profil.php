<?php 
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// set data
$data['title'] = 'Profil';
$data['css'] = ['layout.css','card.css'];
$data['header'] ='Categories';
// cek session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_user = $_SESSION['user_id'];

// Query ambil profil
$sql = "SELECT nama_pemustaka, email, nim_nip, profil_pemustaka
        FROM pemustaka
        WHERE id_pemustaka = :id";

$result = fetchData($sql, ['id' => $id_user]);

$profil = $result ? $result[0] : null;

require_once '../components/header.php'

?>
<section class="card">
    <h2>Profil Pemustaka</h2>
    <?php if ($profil): ?>
        <div class="profile-card">
            <img src="<?= BASE_URL; ?>assets/img/<?= $profil['profil_pemustaka'] ? $profil['profil_pemustaka'] :'users.png'; ?>" 
                alt="Foto Profil" class="profile-photo">

            <div class="profile-info">
                <h3><?= $profil['nama_pemustaka']; ?></h3>
                <p><i class="fa fa-envelope"></i> <?= $profil['email']; ?></p>
                <p><i class="fa fa-id-card"></i> <?= $profil['nim_nip']; ?></p>

                <a href="edit_profil.php" class="btn-edit">Edit Profil</a>
            </div>
        </div>

    <?php else: ?>
        <p>Data profil tidak ditemukan.</p>
    <?php endif; ?>

</section>
<?php require_once '../components/footer.php' ?>