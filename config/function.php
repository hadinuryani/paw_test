<?php
require_once 'config.php';
require_once 'database.php';


// Ambil data (bisa banyak / 1 row)
function fetchData(string $sql, array $params = [], bool $single = false) {
    $stmnt = DBH->prepare($sql);
    $stmnt->execute($params);
    return $single ? $stmnt->fetch(PDO::FETCH_ASSOC) : $stmnt->fetchAll(PDO::FETCH_ASSOC);
}

// cumak nge wraper fungsi fetch data sih biar ngak mbingungin
function fetchOne(string $sql, array $params = []) {
    return fetchData($sql, $params, true);
}

// Jalankan query INSERT / UPDATE / DELETE
function runQuery(string $sql, array $params = []) {
    try {
        $stmt = DBH->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        echo "Query gagal: " . $e->getMessage();
        return false;
    }
}

// Register pemustaka
function registerPemustaka(array $data){
    $sql = "INSERT INTO users 
            (nama_user, email, nim_nip, password, role)
            VALUES (:nama, :email, :nim_nip, :password, 'pemustaka')";
    return runQuery($sql, [
        ':nama'     => $data['nama_user'],
        ':email'    => $data['email'],
        ':nim_nip'  => $data['nim_nip'],
        ':password' => $data['password'],
    ]);
}

// Login 
function login(string $identity, string $password) {
    $sql = "SELECT *
            FROM users
            WHERE email = :id OR nim_nip = :id
            LIMIT 1";

    $user = fetchData($sql, [':id' => $identity], true);

    if (!$user) return false;

    return password_verify($password, $user['password']) ? $user : false;
}


/* CRUD BUKU
   ============================ */

// Ambil semua buku 
function getAllBooks() {
    $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
    return fetchData($sql);
}

// Ambil 1 buku berdasarkan ID 
function getBookById($id_buku) {
    $sql = "SELECT * FROM buku WHERE id_buku = :id";
    return fetchData($sql, [':id' => $id_buku], true);
}

// Tambah buku 
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

// Update buku
function updateBook($id_buku, $data) {
    $sql = "UPDATE buku SET
            judul = :judul,
            penulis = :penulis,
            penerbit = :penerbit,
            tahun_terbit = :tahun_terbit,
            kategori = :kategori
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

// Hapus buku
function deleteBook($id_buku) {
    $sql = "DELETE FROM buku WHERE id_buku = :id";
    return runQuery($sql, [':id' => $id_buku]);
}

function getAllPeminjaman() {
    $sql = "SELECT
                p.id_peminjaman,
                p.tanggal_peminjaman,
                p.tanggal_kembali,
                p.status,
                b.judul,
                b.penulis,
                b.kategori,
                u.nama_user
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            JOIN users u ON p.id_user = u.id_user
            ORDER BY p.id_peminjaman DESC";

    return fetchData($sql);
}

// lihat data pemustaka
function getAllUsers() {
    $sql = "SELECT id_user, nama_user, email, nim_nip, profil 
            FROM users 
            WHERE role = 'pemustaka'";
    return fetchData($sql);
}
// jumlah peminjaman aktif
function countActiveBorrow($id_user, $id_buku) {
    $sql = "SELECT COUNT(*) AS total 
            FROM peminjaman 
            WHERE id_user = :u 
              AND id_buku = :b 
              AND status IN ('pending', 'borrow')";
    return fetchData($sql, ['u' => $id_user, 'b' => $id_buku], true)['total'];
}
// Tambah peminjaman
function createBorrow($id_user, $id_buku) {
    $sql = "INSERT INTO peminjaman (id_user, id_buku, status, tanggal_peminjaman) 
            VALUES (:u, :b, 'pending', NOW())";

    return runQuery($sql, [
        'u' => $id_user,
        'b' => $id_buku
    ]);
}


/*  VALIDASI FORM
   ======================*/

// Untuk Membersihkan spasi, backslash, dan tag HTML
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Masukan Wajib Isi
function wajib_isi($data){
    return !empty($data);
}

// Untuk nama penulis, nama pemustaka
function alfabet($data) {
    return preg_match("/^[a-zA-Z\s',.]+$/", $data);
}

// Untuk judul buku, penerbit, kategori
function alfanumerik($data) {
    return preg_match("/^[a-zA-Z0-9\s,.:()'&\-\/]+$/", $data);
}

// Untuk NIM/NIP, Tahun, dll.
function numerik($data) {
    return preg_match("/^[0-9]+$/", $data);
}

// Panjang minimal untuk password 
function cek_panjang_minimal($data, $min_len) {
    return strlen($data) >= $min_len;
}

// Untuk tahun terbit (4), NIM (12), dan NIP (18)
function cek_panjang_tepat($data, $panjang_tepat) {
	return (preg_match("/^[0-9]+$/", $data) && strlen($data) == $panjang_tepat);
}

// Untuk email
function cek_format_email($data) {
    return filter_var($data, FILTER_VALIDATE_EMAIL);
}

 // Untuk cek apakah NIM (numerik AND panjang 12) ATAU NIP(numerik AND panjang 18)
function cek_format_identitas($data) {
    return cek_panjang_tepat($data, 12) || cek_panjang_tepat($data, 18);
}

// Untuk Konfirmasi Password
function cek_kesamaan_password($pass1, $pass2) {
    return $pass1 === $pass2;
}

function updateStatusPeminjaman(int $id_peminjaman, string $status) {
    // Jika user mengembalikan buku
    if ($status === 'returned') {
        $sql = "UPDATE peminjaman 
                SET status = :status, tanggal_kembali = CURDATE()
                WHERE id_peminjaman = :id";

    // Jika admin meng-approve (jadikan borrowed)
    } elseif ($status === 'borrowed') {
        $sql = "UPDATE peminjaman 
                SET status = :status, tanggal_peminjaman = CURDATE()
                WHERE id_peminjaman = :id";

    // pending atau rejected
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


// Ambil profil pemustaka
function getProfilPemustaka(int $id_pemustaka) {
    $sql = "SELECT nama_user, email, nim_nip, profil
            FROM users
            WHERE id_user = :id";
    return fetchData($sql, ['id'=>$id_pemustaka], true);
}

// Update profil pemustaka (dengan optional file)
function updateProfilPemustaka(int $id_pemustaka, array $data, ?array $file = null) {
    $fotoProfil = $data['profil_lama'] ?? null;

    if ($file && !empty($file['name'])) {
        $uploadDir = '../assets/img/';

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($file['name']); // nama file unik
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $fotoProfil = $fileName;
        }
    }
    $sql = "UPDATE users
            SET nama_user = :nama,
                email = :email,
                nim_nip = :nimnip,
                profil = :profil
            WHERE id_user = :id";

    return runQuery($sql, [
        ':nama'   => $data['nama_pemustaka'] ?? '',   
        ':email'  => $data['email_pemustaka'] ?? '',  
        ':nimnip' => $data['nim_nip_pemustaka'] ?? '',
        ':profil' => $fotoProfil, // hanya nama file
        ':id'     => $id_pemustaka
    ]);
}
// ambil status peminjaman
function getStatusPeminjaman($id) {
    $result = fetchOne(
        "SELECT status FROM peminjaman WHERE id_peminjaman = :id",
        ['id' => $id]
    );

    return $result['status'] ?? null;
}





