<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';
// cek session
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php');
    exit;
}

$id = $_POST['id_buku'];

if (deleteBook($id)) {
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php?success=Buku berhasil dihapus');
} else {
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php?error=Gagal Menghapus buku');
}
exit;
