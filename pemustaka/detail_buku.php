<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php'; 

// set data
$data['title'] = 'Detail Buku';
$data['css'] = ['layout.css','book.css','admin.css']; // panggil admin.css untuk pewarisan sytle form
$data['header'] ='Detail Buku';

// cek session user
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])) {
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_user = $_SESSION['user_id'];
$id_buku = $_GET['id'] ?? null;

if (!$id_buku) { die("Buku tidak ditemukan."); }

// ambil detail buku
$buku = fetchData("SELECT * FROM buku WHERE id_buku = :id", ['id' => $id_buku], true);
if (!$buku) { die("Buku tidak ditemukan."); }

$msg = "";
$err = "";

// FLAG untuk menampilkan input tanggal
$show_tanggal = false;

if (isset($_POST['pinjam'])) {

    $tgl_kembali = $_POST['tgl_kembali'] ?? '';

    // Jika tombol pinjam di-klik pertama kali tanpa tanggal tampilkan input
    if (empty($tgl_kembali)) {
        $show_tanggal = true;
        $err = "Silakan isi tanggal rencana pengembalian buku.";
    }

    // Jika user sudah isi tanggal lakukan validasi
    if (!empty($tgl_kembali)) {

        $tgl_kembali = test_input($tgl_kembali);

        // 1. wajib isi
        if (!wajib_isi($tgl_kembali)) {
            $err = "Tanggal kembali wajib diisi.";
        }

        // 2. format tanggal (Y-m-d)
        if (!$err) {
            $cek_format = DateTime::createFromFormat('Y-m-d', $tgl_kembali);
            if (!$cek_format) {
                $err = "Format tanggal tidak valid.";
            }
        }

        // 3. tanggal harus lebih besar dari hari ini
        if (!$err) {
            if ($tgl_kembali <= date("Y-m-d")) {
                $err = "Tanggal kembali harus lebih besar dari hari ini.";
            }
        }

        // Jika semua validasi lolos cek database
        if (!$err) {

            // cek apakah user ini sudah meminjam buku
            $count = countActiveBorrow($id_user, $id_buku);
            if ($count >= 1) {
                $err = "Anda sudah meminjam buku ini harap dikembalikan dulu.";
            } else {

                // cek apakah sedang dipinjam user lain
                $dipinjamUserLain = isBookBorrowed($id_buku);

                if ($dipinjamUserLain) {
                    $err = "Buku sedang dipinjam pemustaka lain.";
                } else {

                    // proses insert
                    if (createBorrow($id_user, $id_buku, $tgl_kembali)) {
                        $msg = "Peminjaman berhasil! Menunggu persetujuan admin.";
                    } else {
                        $err = "Gagal meminjam buku.";
                    }
                }
            }
        }

        // Jika ada error, tampilkan kembali input tanggal
        if ($err) {
            $show_tanggal = true;
        }
    }
}

require_once '../components/header.php';
?>

    <section class="book-detail">

        <div class="book-cover-detail">
            <div class="title">
                <div><?= $buku['judul']; ?></div>
                <div><?= $buku['penulis']; ?></div>
            </div>
        </div>

        <div class="detail-info">

            <h2><?= $buku['judul']; ?></h2>

            <div class="text-info">
                <p><strong>Penulis:</strong> <?= $buku['penulis']; ?></p>
                <p><strong>Jenis Kategori:</strong> <?= $buku['kategori']; ?></p>
                <p><strong>Tahun Terbit Buku</strong> <?= $buku['tahun_terbit']; ?></p>
            </div>

            <!-- FORM PINJAM -->
            <form action="#" method="POST" class="form-edit">

                <?php if ($show_tanggal): ?>
                    <label for="tgl_kembali">Tanggal Kembali</label>
                    <input
                        type="date"
                        name="tgl_kembali"
                        id="tgl_kembali"
                        value="<?= $_POST['tgl_kembali'] ?? '' ?>"
                    >
                <?php endif; ?>

                <?php if ($msg): ?>
                    <div class="alert-success"><?= $msg; ?></div>
                <?php endif; ?>

                <?php if ($err): ?>
                    <div class="alert-error"><?= $err; ?></div>
                <?php endif; ?>

                <button type="submit" name="pinjam" class="btn-pinjam">Pinjam Buku</button>
            </form>

        </div>

    </section>

<?php require_once '../components/footer.php'; ?>
