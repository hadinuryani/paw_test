<?php 
require_once 'config.php';

// database  source name
const DSN = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME ;

// opsi PDO
const OPTIONS =[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try{
    // database handler
    define("DBH",new PDO(DSN,DB_USER,DB_PASS,OPTIONS));
}catch(PDOException $err){
    echo "koneksi gagal : " . $err->getMessage();
}




