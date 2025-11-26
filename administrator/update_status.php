<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// cek session
if (!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])) {
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// ambil data dari form
$id_peminjaman = $_POST['id'] ?? null;
$new_status = $_POST['status'] ?? null;

// jaga-jaga kalo ada data yang kosong
if (!$id_peminjaman || !$new_status) {
    die("Data tidak lengkap!");
}

// ambil status lama untuk dicek
$current = getStatusPeminjaman($id_peminjaman); 

// jiks id nya tidak di temukan
if (!$current) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Data tidak ditemukan!');
    exit;
}

// flag buat ngecek status valid atau tidak
$valid = false;

// atur transisi status yang boleh
if ($current === 'pending' && in_array($new_status, ['borrow', 'rejected'])) {
    $valid = true;
}
elseif ($current === 'borrow' && $new_status === 'returned') {
    $valid = true;
}

// kalo transisinya maksa dan ga sesuai rules
if (!$valid) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Transisi status tidak valid!');
    exit;
}

// update status ke database
if (updateStatusPeminjaman((int)$id_peminjaman, $new_status)) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?success=Status peminjaman berhasil diperbarui!');
    exit;
} else {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Gagal memperbarui status!');
    exit;
}
?>
