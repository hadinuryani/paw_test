<?php 
session_start();
$data['title'] = 'document';
$data['css'] = ['layout.css','book.css'];
$data['header'] ='Categories';
// if(!($_SESSION['id_users'] && $_SESSION['nama_user'] && $_SESSION['pemustaka'])){
//     //lempar
// }

require_once '../config/config.php';
require_once '../config/function.php';



require_once '../components/header.php'

?>

<?php require_once '../components/footer.php' ?>