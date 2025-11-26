<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

//  Cek apakah user admin dan sudah login 
if(!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])){
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

//  Pastikan request berasal dari method POST 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // jika bukan POST, lempar kembali ke halaman kelola buku
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php');
    exit;
}

// ambil ID buku dari form
$id = $_POST['id_buku'];

// panggil fungsi deleteBook untuk menghapus data
if (deleteBook($id)) {
    // jika sukses, redirect dengan pesan sukses
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php?success=Buku berhasil dihapus');
} else {
    // jika gagal, redirect dengan pesan error
    header('location: ' . BASE_URL . 'administrator/kelola_buku.php?error=Gagal Menghapus buku');
}
exit;
