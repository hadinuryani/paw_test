<?php 
session_start();
$data['title'] = 'document';
$data['css'] = ['layout.css','book.css','card.css'];
$data['header'] ='Categories';

if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}

require_once '../config/config.php';
require_once '../config/function.php';

$id_pemustaka = $_SESSION['user_id'] ?? null;

$profil = getProfilPemustaka($id_pemustaka);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // pastikan $_POST key sesuai dengan nama input
    $dataPost = [
        'nama_pemustaka' => isset($_POST['nama_pemustaka']) ? trim($_POST['nama_pemustaka']) : '',
        'email_pemustaka' => isset($_POST['email_pemustaka']) ? trim($_POST['email_pemustaka']) : '',
        'nim_nip_pemustaka' => isset($_POST['nim_nip_pemustaka']) ? trim($_POST['nim_nip_pemustaka']) : '',
        'profil_lama' => $profil['profil'] ?? null
    ];

    $isUpdated = updateProfilPemustaka($id_pemustaka, $dataPost, $_FILES['profil'] ?? null);

    if ($isUpdated) {
        header("Location: profil.php?success=1");
        exit;
    } else {
        echo "<p style='color:red;'>Gagal memperbarui profil.</p>";
    }
}

require_once '../components/header.php';
?>

<section class="content">
    <h2>Edit Profil</h2>

    <?php if ($profil): ?>
    <form method="POST" enctype="multipart/form-data" class="form-edit">
        <div>
            <label>Nama</label>
            <input type="text" name="nama_pemustaka" value="<?= htmlspecialchars($profil['nama_user'] ?? ''); ?>" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email_pemustaka" value="<?= htmlspecialchars($profil['email'] ?? ''); ?>" required>
        </div>
        <div>
            <label>NIM/NIP</label>
            <input type="text" name="nim_nip_pemustaka" value="<?= htmlspecialchars($profil['nim_nip'] ?? ''); ?>" required>
        </div>
        <div>
            <label>Foto Profil</label><br>
            <img src="<?= BASE_URL; ?>assets/img/<?= $profil['profil'] ?? 'users.png'; ?>" 
                 alt="Foto Profil" width="100" style="border-radius:50%;margin-bottom:10px;">
            <input type="file" name="profil" accept="image/*">
        </div>
        <button type="submit" class="btn">Simpan Perubahan</button>
    </form>
    <?php else: ?>
        <p>Data profil tidak ditemukan.</p>
    <?php endif; ?>
</section>

<?php require_once '../components/footer.php'; ?>
