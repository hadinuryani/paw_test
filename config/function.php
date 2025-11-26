<?php
require_once 'config.php';
require_once 'database.php';

// Ambil data dari database (bisa satu atau banyak)
function fetchData(string $sql, array $params = [], bool $single = false) {
    $stmnt = DBH->prepare($sql);
    $stmnt->execute($params);
    return $single ? $stmnt->fetch(PDO::FETCH_ASSOC) : $stmnt->fetchAll(PDO::FETCH_ASSOC);
}

// Wrapper biar gampang ambil satu data
function fetchOne(string $sql, array $params = []) {
    return fetchData($sql, $params, true);
}

// Eksekusi query INSERT / UPDATE / DELETE
function runQuery(string $sql, array $params = []) {
    try {
        $stmt = DBH->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        echo "Query gagal: " . $e->getMessage();
        return false;
    }
}

// Register pemustaka baru
function registerPemustaka(array $data){
    $sql = "INSERT INTO pemustaka 
            (nama_pemustaka, email, nim_nip, password_pemustaka)
            VALUES (:nama, :email, :nim_nip, :password)";
    return runQuery($sql, [
        ':nama'     => $data['nama_user'],
        ':email'    => $data['email'],
        ':nim_nip'  => $data['nim_nip'],
        ':password' => $data['password'],
    ]);
}


// Login pemustaka
function loginPemustaka(string $identity, string $password) {
    // cek apakah user login pakai email atau nim/nip
    $sql = "SELECT *
            FROM pemustaka
            WHERE email = :id OR nim_nip = :id
            LIMIT 1";

    $user = fetchData($sql, [':id' => $identity], true);

    if (!$user) return false;

    // verifikasi password
    return password_verify($password, $user['password_pemustaka']) ? $user : false;
}

// Login admin
function loginAdministrator(string $identity, string $password) {
    $sql = "SELECT *
            FROM administrator
            WHERE email_admin = :id 
            LIMIT 1";

    $user = fetchData($sql, [':id' => $identity], true);

    if (!$user) return false;

    return password_verify($password, $user['password_admin']) ? $user : false;
}


/* CRUD BUKU
============================ */

// Ambil daftar buku terbaru
function getAllBooks() {
    $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
    return fetchData($sql);
}

// Ambil detail satu buku
function getBookById($id_buku) {
    $sql = "SELECT * FROM buku WHERE id_buku = :id";
    return fetchData($sql, [':id' => $id_buku], true);
}

// Tambah buku baru
function addBook($data) {
    $sql = "INSERT INTO buku 
            (judul, penulis, penerbit, tahun_terbit, kategori)
            VALUES (:judul, :penulis, :penerbit, :tahun_terbit, :kategori)";
    return runQuery($sql, [
        ':judul'        => $data['judul'],
        ':penulis'      => $data['penulis'],
        ':penerbit'     => $data['penerbit'],
        ':tahun_terbit' => $data['tahun_terbit'],
        ':kategori'     => $data['kategori'],
    ]);
}

// Edit data buku
function updateBook($id_buku, $data) {
    $sql = "UPDATE buku 
            SET
            judul       = :judul,
            penulis     = :penulis,
            penerbit    = :penerbit,
            tahun_terbit= :tahun_terbit,
            kategori    = :kategori
            WHERE id_buku = :id";
    return runQuery($sql, [
        ':judul'        => $data['judul'],
        ':penulis'      => $data['penulis'],
        ':penerbit'     => $data['penerbit'],
        ':tahun_terbit' => $data['tahun_terbit'],
        ':kategori'     => $data['kategori'],
        ':id'           => $id_buku
    ]);
}

// Hapus buku by id
function deleteBook($id_buku) {
    $sql = "DELETE FROM buku WHERE id_buku = :id";
    return runQuery($sql, [':id' => $id_buku]);
}

// Hitung peminjaman aktif user untuk buku tertentu
function countActiveBorrow($id_user, $id_buku) {
    $sql = "SELECT COUNT(*) AS total 
            FROM peminjaman 
            WHERE id_pemustaka = :id_p AND id_buku = :id_b AND status IN ('pending', 'borrow')";
    return fetchData($sql, ['id_p' => $id_user, 'id_b' => $id_buku], true)['total'];
}

// Cek apakah buku masih dalam status pending
function isBookBorrowed($id_buku) {
    $sql = "SELECT COUNT(*) FROM peminjaman
            WHERE id_buku = :id_buku
            AND status = 'pending'"; 
    return fetchData($sql, ['id_buku' => $id_buku], true)['COUNT(*)'] > 0;
}


// Buat record peminjaman baru
function createBorrow($id_user, $id_buku, $tgl_kembali) {
    $sql = "INSERT INTO peminjaman (id_pemustaka, id_buku, status, tanggal_peminjaman, tanggal_kembali) 
            VALUES (:id_p, :id_b, 'pending', NOW(), :tgl_k)";

    return runQuery($sql, [
        'id_p' => $id_user,
        'id_b' => $id_buku,
        'tgl_k' => $tgl_kembali
    ]);
}

// update status otomatis saat tanggal peminjaman sudah lewat
function autoUpdateStatusPeminjaman() {
    $today = date('Y-m-d');

    $rows = fetchData("SELECT id_peminjaman, tanggal_kembali, status FROM peminjaman");

    foreach ($rows as $row) {
        $id  = $row['id_peminjaman'];
        $due = $row['tanggal_kembali'];
        $st  = $row['status'];

        $newStatus = null;

        if ($st === 'borrow' && $today >= $due) {
            $newStatus = 'returned';
        }
        if ($st === 'pending' && $today > $due) {
            $newStatus = 'rejected';
        }

        if ($newStatus !== null) {
            runQuery(
                "UPDATE peminjaman SET status = :st WHERE id_peminjaman = :id",
                [':st' => $newStatus, ':id' => $id]
            );
        }
    }
}

// Update status peminjaman 
function updateStatusPeminjaman(int $id_peminjaman, string $status) {

    // status returned → set tanggal kembali dengan waktu lengkap
    if ($status === 'returned') {
        $sql = "UPDATE peminjaman 
                SET status = :status, tanggal_kembali = CURDATE()
                WHERE id_peminjaman = :id";

    // status borrow → set tanggal pinjam 
    } elseif ($status === 'borrow') {
        $sql = "UPDATE peminjaman 
                SET status = :status, tanggal_peminjaman = CURDATE()
                WHERE id_peminjaman = :id";

    // status lain cukup update status aja
    } else {
        $sql = "UPDATE peminjaman 
                SET status = :status
                WHERE id_peminjaman = :id";
    }

    return runQuery($sql, [
        ':status' => $status,
        ':id'     => $id_peminjaman
    ]);
}


// Ambil profil user
function getProfilPemustaka(int $id_pemustaka) {
    $sql = "SELECT nama_pemustaka, email, nim_nip, profil_pemustaka
            FROM pemustaka
            WHERE id_pemustaka = :id";
    return fetchData($sql, ['id'=>$id_pemustaka], true);
}

// Update profil (nama, email, foto)
function updateProfilPemustaka(int $id_pemustaka, array $data, ?array $file = null) {
    // default pakai foto lama
    $fotoProfil = $data['profil_lama'] ?? null;

    // kalau upload file baru
    if ($file && !empty($file['name'])) {
        $uploadDir = '../assets/upload/';

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // agar nama file unik
        $fileName = time() . '_' . basename($file['name']); 
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $fotoProfil = $fileName;
        }
    }

    $sql = "UPDATE pemustaka
            SET nama_pemustaka = :nama,
                email = :email,
                nim_nip = :nimnip,
                profil_pemustaka = :profil
            WHERE id_pemustaka = :id";

    return runQuery($sql, [
        ':nama'   => $data['nama_pemustaka'] ?? '',   
        ':email'  => $data['email_pemustaka'] ?? '',  
        ':nimnip' => $data['nim_nip_pemustaka'] ?? '',
        ':profil' => $fotoProfil,
        ':id'     => $id_pemustaka
    ]);
}

// Ambil status peminjaman berdasarkan ID
function getStatusPeminjaman($id) {
    $result = fetchOne(
        "SELECT status FROM peminjaman WHERE id_peminjaman = :id",
        ['id' => $id]
    );

    return $result['status'] ?? null;
}


/*  VALIDASI FORM
=====================*/

// Bersihkan input dari karakter aneh
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek input wajib
function wajib_isi($data){
    return !empty($data);
}

// Validasi nama (huruf saja)
function alfabet($data) {
    return preg_match("/^[a-zA-Z\s',.]+$/", $data);
}

// Validasi judul / penerbit / kategori
function alfanumerik($data) {
    return preg_match("/^[a-zA-Z0-9\s,.:()'&\-\/]+$/", $data);
}

// Validasi angka
function numerik($data) {
    return preg_match("/^[0-9]+$/", $data);
}

// Minimal panjang karakter
function cek_panjang_minimal($data, $min_len) {
    return strlen($data) >= $min_len;
}

// Panjang angka tertentu
function cek_panjang_tepat($data, $panjang_tepat) {
	return (preg_match("/^[0-9]+$/", $data) && strlen($data) == $panjang_tepat);
}

// Validasi email
function cek_format_email($data) {
    return filter_var($data, FILTER_VALIDATE_EMAIL);
}

// Cek apakah identitas NIM (12 digit) atau NIP (18 digit)
function cek_format_identitas($data) {
    return cek_panjang_tepat($data, 12) || cek_panjang_tepat($data, 18);
}

// Cek apakah password konfirmasi sama
function cek_kesamaan_password($pass1, $pass2) {
    return $pass1 === $pass2;
}
