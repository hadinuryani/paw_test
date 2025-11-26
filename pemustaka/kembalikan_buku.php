<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

// pastikan hanya pemustaka yang boleh akses halaman ini
if(!($_SESSION['role'] == 'pemustaka' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

// ambil id peminjaman dari URL dan id user dari session
$id_peminjaman = $_GET['id'] ?? null;
$id_user  = $_SESSION['user_id'] ?? null;

// simple validasi biar tidak ada proses tanpa data
if (!$id_peminjaman || !$id_user) {
    die("Data tidak lengkap!");
}

// update status peminjaman menjadi returned + set tanggal kembali otomatis
$sql = "UPDATE peminjaman 
        SET status = 'returned', tanggal_kembali = CURRENT_TIMESTAMP()
        WHERE id_peminjaman = :id_peminjaman 
        AND id_pemustaka = :id_user";

// eksekusi query 
$result = runQuery($sql, [
    ':id_peminjaman' => $id_peminjaman,
    ':id_user'  => $id_user
]);

// feedback ke user berdasarkan hasil query
if ($result) {
    header('location: ' . BASE_URL . 'pemustaka/riwayat.php?success=buku telah di kembalikan');
    exit;
} else {
    header('location: ' . BASE_URL . 'pemustaka/riwayat.php?err=gagal mengembalikan buku');
}
