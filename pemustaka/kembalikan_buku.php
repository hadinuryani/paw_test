<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// cek session
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}
// Ambil data dari form
$id_peminjaman = $_GET['id'] ?? null;
$id_user  = $_SESSION['user_id'] ?? null;
if (!$id_peminjaman || !$id_user) {
    die("Data tidak lengkap!");
}

// Query untuk ubah status jadi 'returned'
$sql = "UPDATE peminjaman 
        SET status = 'returned', tanggal_kembali = CURDATE()
        WHERE id_peminjaman = :id_peminjaman 
        AND id_user = :id_user";

$result = runQuery($sql, [
    ':id_peminjaman' => $id_peminjaman,
    ':id_user'  => $id_user
]);
if ($result) {
    header('location: ' . BASE_URL . 'pemustaka/riwayat.php');
    exit;
} else {
    header('location: ' . BASE_URL . 'pemustaka/riwayat.php?pesan=gagal');
}
