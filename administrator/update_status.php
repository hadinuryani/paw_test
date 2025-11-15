<?php
require_once '../config/config.php';
require_once '../config/function.php';
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}

$id_peminjaman = $_POST['id'] ?? null;
$action = $_POST['status'] ?? null;

if (!$id_peminjaman || !$action) {
    die("Data tidak lengkap!");
}

if (updateStatusPeminjaman((int)$id_peminjaman, $action)) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?success=Status peminjaman berhasil diperbarui!');
    exit;
} else {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Status peminjaman gagal diperbarui!');
    exit;
  
}
?>
