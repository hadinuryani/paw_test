<?php
session_start();
require_once '../config/config.php';
require_once '../config/function.php';

if (!($_SESSION['role'] == 'admin' && $_SESSION['nama_user'])) {
    header('location: ' . BASE_URL . 'login.php');
    exit;
}

$id_peminjaman = $_POST['id'] ?? null;
$new_status = $_POST['status'] ?? null;

if (!$id_peminjaman || !$new_status) {
    die("Data tidak lengkap!");
}

// Ambil status saat ini
$current = getStatusPeminjaman($id_peminjaman); // ← bikin di function.php

if (!$current) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Data tidak ditemukan!');
    exit;
}


$valid = false;

if ($current === 'pending' && in_array($new_status, ['borrow', 'rejected'])) {
    $valid = true;
}
elseif ($current === 'borrow' && $new_status === 'returned') {
    $valid = true;
}

if (!$valid) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Transisi status tidak valid!');
    exit;
}

// UPDATE STATUS
if (updateStatusPeminjaman((int)$id_peminjaman, $new_status)) {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?success=Status peminjaman berhasil diperbarui!');
    exit;
} else {
    header('location: ' . BASE_URL . 'administrator/kelola_peminjaman.php?error=Gagal memperbarui status!');
    exit;
}
?>
